<?php
require_once PROJECT_ROOT_PATH."/entities/Producto.php";

class ProductosDb extends Database {
  public $class = Producto::class;
  public $idName = "id_producto";
  public $tableName = "productos";

  public function obtenerProducto($id) {
    $query = $this->prepareQuery("select-one");
    $params = $this->prepareParams(null, "select-one", $id);

    return $this->executeQuery($query, $params, "select-one");
  }

  public function listarProductos() {
    $query = $this->prepareQuery("select");

    return $this->executeQuery($query, null, "select");
  }

  public function listarPorGrupo($grupos) {
    $grupos = explode(",", $grupos);

    $query = "SELECT * FROM productos WHERE id_grupo IN (";
    $query .= implode(",", array_fill(0, count($grupos), "?"));
    $query .= ")";

    $params = array_map(function ($key, $value) {
      return array("nombre" => $key + 1, "valor" => $value, "tipo" => PDO::PARAM_INT);
    }, array_keys($grupos), $grupos);

    return $this->executeQuery($query, $params, "select");
  }

  public function listarConCentralesCostos($idCentralCostos = null, $codigoProducto = null, $soloPedido = false) {
    $query = "SELECT
    p.id_producto, p.nombre_producto, p.codigo, p.costo_unitario, p.tipo_de_unidad, cc.id_central_de_costos, cc.nombre_del_costo, p.stock_min_temporada_baja, p.stock_max_temporada_baja, p.stock_min_temporada_alta, p.stock_max_temporada_alta,

    SUM(CASE 
        WHEN dd.tipo_movimiento = 'IN' THEN dd.cantidad
        WHEN dd.tipo_movimiento = 'SA' THEN -dd.cantidad
        ELSE 0
      END) AS stock,

    p.tipo_de_unidad,
    p.cantidad_pedido

    FROM productos p
    LEFT JOIN centraldecostos cc ON p.id_central_de_costos = cc.id_central_de_costos
    LEFT JOIN documento_detalle dd ON dd.id_producto = p.id_producto

    WHERE (
      p.tipo = 'PRD'
      AND (
        (:solo_pedido IS NOT NULL AND p.cantidad_pedido > 0)
        OR (:id_central_costos1 IS NOT NULL AND p.id_central_de_costos = :id_central_costos2)
        OR (:codigo_producto1 IS NOT NULL AND (
          CASE 
          WHEN length(:codigo_producto2) <= 3 THEN p.codigo
          ELSE p.nombre_producto
          END LIKE :codigo_producto3
        ))
      )
    )

    GROUP BY p.id_producto, p.nombre_producto, p.codigo, p.costo_unitario, p.tipo_de_unidad, cc.id_central_de_costos, cc.nombre_del_costo, p.stock_min_temporada_baja, p.stock_max_temporada_baja, p.stock_min_temporada_alta, p.stock_max_temporada_alta
    ORDER BY cc.nombre_del_costo, p.nombre_producto ASC";
    $params = array(
      array("nombre" => "id_central_costos1", "valor" => $idCentralCostos, "tipo" => PDO::PARAM_INT),
      array("nombre" => "id_central_costos2", "valor" => $idCentralCostos, "tipo" => PDO::PARAM_INT),
      array("nombre" => "codigo_producto1", "valor" => $codigoProducto, "tipo" => PDO::PARAM_STR),
      array("nombre" => "codigo_producto2", "valor" => $codigoProducto, "tipo" => PDO::PARAM_STR),
      array("nombre" => "codigo_producto3", "valor" => "%$codigoProducto%", "tipo" => PDO::PARAM_STR),
      array("nombre" => "solo_pedido", "valor" => $soloPedido, "tipo" => PDO::PARAM_STR)
    );

    return $this->executeQuery($query, $params);
  }

  public function buscarConPrecios($idProducto) {
    $query = "SELECT p.id_producto, p.nombre_producto, p.codigo, p.precio_venta_01, p.precio_venta_02, p.precio_venta_03 
    FROM productos p WHERE p.id_producto = :id_producto";

    $params = array(
      array("nombre" => "id_producto", "valor" => $idProducto, "tipo" => PDO::PARAM_STR)
    );

    return $this->executeQuery($query, $params, "select-one");
  }

  public function listarHospedajesConPrecios() {
    $query = "SELECT id_producto, nombre_producto, precio_venta_01, precio_venta_02, precio_venta_03 
     FROM $this->tableName WHERE tipo = 'SVH'";

    return $this->executeQuery($query, null);
  }

  public function listarServiciosTerapistas() {
    $query = "SELECT id_producto, nombre_producto, codigo_habilidad
     FROM $this->tableName WHERE tipo = 'SRV' AND requiere_programacion = 1";

    return $this->executeQuery($query);
  }

  public function listarServiciosDeTerapista($idProfesional) {
    $query = "SELECT pr.id_producto AS id, pr.nombre_producto AS nombre, pr.precio_venta_01 AS precio
      FROM productos pr
      INNER JOIN habilidadesprofesionales hp ON hp.codigo_habilidad = pr.codigo_habilidad
      INNER JOIN terapistashabilidades th ON th.id_habilidad = hp.id_habilidad
      INNER JOIN terapistas ter ON ter.id_persona = th.id_persona
      WHERE pr.tipo = 'SRV' AND pr.requiere_programacion = 1 AND ter.id_profesional = :id_profesional;";
    $params = array(
      array("nombre" => "id_profesional", "valor" => $idProfesional, "tipo" => PDO::PARAM_INT)
    );

    return $this->executeQuery($query, $params);
  }

  public function buscarConDocDetallesPorNombreProducto($nombresProducto) {
    $query = "SELECT pr.id_producto, tp.nombre_tipo_de_producto AS tipo_producto, pr.nombre_producto, pr.costo_unitario, pr.tipo_de_unidad,
      SUM(CASE 
        WHEN dd.tipo_movimiento = 'IN' THEN dd.cantidad
        WHEN dd.tipo_movimiento = 'SA' THEN -dd.cantidad
        ELSE 0
      END) AS stock,
      SUM(CASE 
        WHEN dd.tipo_movimiento = 'IN' THEN dd.precio_total
        WHEN dd.tipo_movimiento = 'SA' THEN -dd.precio_total
        ELSE 0
      END) AS costo_total 
    FROM $this->tableName pr
    INNER JOIN documento_detalle dd ON dd.id_producto = pr.id_producto
    INNER JOIN tipodeproductos tp ON tp.id_tipo_producto = pr.id_tipo_de_producto
    WHERE ";

    // buscar por nombre de producto
    foreach($nombresProducto as $key => $nombreProducto) {
      $query .= "pr.nombre_producto LIKE :nombre_producto_$key";
      if(next($nombresProducto)) {
        $query .= " OR ";
      }
    }

    $query .= " GROUP BY pr.id_producto, tp.nombre_tipo_de_producto, pr.nombre_producto, pr.costo_unitario, pr.tipo_de_unidad
                ORDER BY pr.nombre_producto ASC";

    $params = array_map(function ($key, $value) {
      return array("nombre" => "nombre_producto_$key", "valor" => "%$value%", "tipo" => PDO::PARAM_STR);
    }, array_keys($nombresProducto), $nombresProducto);

    return $this->executeQuery($query, $params);
  }

  public function buscarConDocDetallesPorTipoProducto($tipoProducto) {
    $query = "SELECT pr.id_producto, tp.nombre_tipo_de_producto AS tipo_producto, pr.nombre_producto, pr.costo_unitario, pr.tipo_de_unidad,
      SUM(CASE 
        WHEN dd.tipo_movimiento = 'IN' THEN dd.cantidad
        WHEN dd.tipo_movimiento = 'SA' THEN -dd.cantidad
        ELSE 0
      END) AS stock,
      SUM(CASE 
        WHEN dd.tipo_movimiento = 'IN' THEN dd.precio_total
        WHEN dd.tipo_movimiento = 'SA' THEN -dd.precio_total
        ELSE 0
      END) AS costo_total 
    FROM $this->tableName pr
    INNER JOIN documento_detalle dd ON dd.id_producto = pr.id_producto
    INNER JOIN tipodeproductos tp ON tp.id_tipo_producto = pr.id_tipo_de_producto
    WHERE pr.id_tipo_de_producto = :id_tipo_de_producto
      AND pr.tipo != 'SVH' AND pr.tipo != 'SRV' AND pr.tipo != 'PAQ'
    GROUP BY pr.id_producto, tp.nombre_tipo_de_producto, pr.nombre_producto, pr.costo_unitario, pr.tipo_de_unidad
    ORDER BY pr.nombre_producto ASC";
    $params = array(
      array("nombre" => "id_tipo_de_producto", "valor" => $tipoProducto, "tipo" => PDO::PARAM_INT)
    );

    return $this->executeQuery($query, $params);
  }

  public function listarInventario($unidadNegocio, $tipo, $grupo, $fechaInicio, $fechaFin) {
    $query = "SELECT
                sm.id_producto,
                sm.nombre_producto,
                sm.tipo_de_unidad,
                sm.id_tipo_de_producto,
                sm.id_grupo,
                sm.id_unidad_de_negocio,
                sm.costo_unitario,
                tp.nombre_tipo_de_producto AS tipo_producto,
                gr.nombre_grupo,
                COALESCE(ANT, 0) AS ANT,
                COALESCE(INGRESO, 0) AS INGRESO,
                COALESCE(SALIDAS_OTROS, 0) AS SALIDAS_OTROS,
                COALESCE(SALIDAS_VENTAS, 0) AS SALIDAS_VENTAS,
                COALESCE(ANT, 0) + COALESCE(INGRESO, 0) - COALESCE(SALIDAS_OTROS, 0) - COALESCE(SALIDAS_VENTAS, 0) AS EXISTENCIA,
                sm.costo_unitario * (COALESCE(ANT, 0) + COALESCE(INGRESO, 0) - COALESCE(SALIDAS_OTROS, 0) - COALESCE(SALIDAS_VENTAS, 0)) AS VALOR_TOTAL
            FROM (
                    SELECT
                        pr.id_producto,
                        pr.id_tipo_de_producto,
                        pr.id_grupo,
                        dm.id_unidad_de_negocio,
                        pr.nombre_producto,
                        pr.tipo_de_unidad,
                        pr.costo_unitario,
                        SUM(
                            CASE
                                WHEN dd.fecha < :fecha_inicio1 THEN CASE
                                    WHEN dd.tipo_movimiento = 'IN' THEN dd.cantidad
                                    WHEN dd.tipo_movimiento = 'SA' THEN - dd.cantidad
                                    ELSE 0
                                END
                                ELSE 0
                            END
                        ) AS ANT,
                        SUM(
                            CASE
                                WHEN (
                                    CAST(dd.fecha AS DATE) >= :fecha_inicio2 AND CAST(dd.fecha AS DATE) <= :fecha_fin1
                                )
                                AND dd.tipo_movimiento = 'IN' THEN dd.cantidad
                                ELSE 0
                            END
                        ) AS INGRESO,
                        SUM(
                            CASE
                                WHEN (
                                  CAST(dd.fecha AS DATE) >= :fecha_inicio3 AND CAST(dd.fecha AS DATE) <= :fecha_fin2
                                )
                                AND dd.tipo_movimiento = 'SA'
                                AND dm.nro_de_comanda IS NULL THEN dd.cantidad
                                ELSE 0
                            END
                        ) AS SALIDAS_OTROS,
                        SUM(
                            CASE
                                WHEN (
                                  CAST(dd.fecha AS DATE) >= :fecha_inicio4 AND CAST(dd.fecha AS DATE) <= :fecha_fin3
                                )
                                AND dd.tipo_movimiento = 'SA'
                                AND dm.nro_de_comanda IS NOT NULL THEN dd.cantidad
                                ELSE 0
                            END
                        ) AS SALIDAS_VENTAS
                    FROM productos pr
                        INNER JOIN documento_detalle dd ON pr.id_producto = dd.id_producto
                        LEFT JOIN documento_movimiento dm ON dd.id_documento_movimiento = dm.id_documento_movimiento
                    AND pr.tipo = 'PRD'
                    GROUP BY
                        pr.id_producto,
                        pr.id_tipo_de_producto,
                        pr.id_grupo,
                        dm.id_unidad_de_negocio,
                        pr.nombre_producto,
                        pr.tipo_de_unidad,
                        pr.costo_unitario
                ) sm
                INNER JOIN tipodeproductos tp ON sm.id_tipo_de_producto = tp.id_tipo_producto
                INNER JOIN gruposdelacarta gr ON sm.id_grupo = gr.id_grupo
            WHERE sm.id_unidad_de_negocio = :id_unidad_negocio1
                AND (
                    :id_tipo_producto1 IS NULL
                    OR sm.id_tipo_de_producto IS NULL
                    OR sm.id_tipo_de_producto = :id_tipo_producto2
                )
                AND (
                    :codigo_grupo1 IS NULL
                    OR gr.codigo_subgrupo IS NULL
                    OR gr.codigo_subgrupo = :codigo_grupo2
                )";

    $params = array(
      ["nombre" => "fecha_inicio1", "valor" => $fechaInicio, "tipo" => PDO::PARAM_STR],
      ["nombre" => "fecha_inicio2", "valor" => $fechaInicio, "tipo" => PDO::PARAM_STR],
      ["nombre" => "fecha_inicio3", "valor" => $fechaInicio, "tipo" => PDO::PARAM_STR],
      ["nombre" => "fecha_inicio4", "valor" => $fechaInicio, "tipo" => PDO::PARAM_STR],
      ["nombre" => "fecha_fin1", "valor" => $fechaFin, "tipo" => PDO::PARAM_STR],
      ["nombre" => "fecha_fin2", "valor" => $fechaFin, "tipo" => PDO::PARAM_STR],
      ["nombre" => "fecha_fin3", "valor" => $fechaFin, "tipo" => PDO::PARAM_STR],
      ["nombre" => "id_unidad_negocio1", "valor" => $unidadNegocio, "tipo" => PDO::PARAM_INT],
      ["nombre" => "id_tipo_producto1", "valor" => $tipo, "tipo" => PDO::PARAM_INT],
      ["nombre" => "id_tipo_producto2", "valor" => $tipo, "tipo" => PDO::PARAM_INT],
      ["nombre" => "codigo_grupo1", "valor" => $grupo, "tipo" => PDO::PARAM_STR],
      ["nombre" => "codigo_grupo2", "valor" => $grupo, "tipo" => PDO::PARAM_STR]
    );

    return $this->executeQuery($query, $params);
  }

  public function listarSoloProductos() {
    $query = "SELECT * FROM $this->tableName
      WHERE (tipo = 'PRD' OR tipo = 'PAQ' OR tipo = 'RST')
      AND (id_tipo_de_producto = 12 OR id_tipo_de_producto = 13)";

    return $this->executeQuery($query);
  }

  public function crearProducto(Producto $producto) {
    $productoArray = $this->prepareData((array)$producto, "insert");
    $query = $this->prepareQuery("insert", $productoArray);
    $params = $this->prepareParams($productoArray);

    return $this->executeQuery($query, $params, "insert");
  }

  public function actualizarProducto($id, Producto $producto) {
    $productoArray = $this->prepareData((array)$producto);
    $query = $this->prepareQuery("update", $productoArray);
    $params = $this->prepareParams($productoArray, "update", $id);

    return $this->executeQuery($query, $params, "update");
  }

  public function hayProductosConPedido() {
    $query = "SELECT COUNT(*) AS cantidad FROM $this->tableName WHERE cantidad_pedido > 0";

    return $this->executeQuery($query);
  }

  public function limpiarPedido() {
    $query = "UPDATE $this->tableName SET cantidad_pedido = 0 WHERE cantidad_pedido > 0";

    return $this->executeQuery($query, null, "update");
  }

  public function eliminarProducto($id) {
    $query = $this->prepareQuery("delete");
    $params = $this->prepareParams(null, "delete", $id);

    return $this->executeQuery($query, $params, "delete");
  }
}
?>