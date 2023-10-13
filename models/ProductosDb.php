<?php
require_once PROJECT_ROOT_PATH . "/entities/Producto.php";

class ProductosDb extends Database
{
  public $class = Producto::class;
  public $idName = "id_producto";
  public $tableName = "productos";

  public function obtenerProducto($id)
  {
    $query = $this->prepareQuery("select-one");
    $params = $this->prepareParams(null, "select-one", $id);

    return $this->executeQuery($query, $params, "select-one");
  }

  public function listarProductos()
  {
    $query = $this->prepareQuery("select");

    return $this->executeQuery($query, null, "select");
  }

  public function listarPorGrupo($grupos)
  {
    $grupos = explode(",", $grupos);

    $query = "SELECT * FROM productos WHERE id_grupo IN (";
    $query .= implode(",", array_fill(0, count($grupos), "?"));
    $query .= ")";

    $params = array_map(function ($key, $value) {
      return array("nombre" => $key + 1, "valor" => $value, "tipo" => PDO::PARAM_INT);
    }, array_keys($grupos), $grupos);

    return $this->executeQuery($query, $params, "select");
  }

  public function buscarPorNombre($nombreProducto)
  {
    $query = "SELECT p.id_producto, p.nombre_producto, p.codigo, p.precio_venta_01, p.precio_venta_02, p.precio_venta_03 
    FROM productos p WHERE p.nombre_producto = :nombre_producto";

    $params = array(
      array("nombre" => "nombre_producto", "valor" => $nombreProducto, "tipo" => PDO::PARAM_STR)
    );

    return $this->executeQuery($query, $params);
  }

  public function listarHospedajes()
  {
    $query = "SELECT id_producto, nombre_producto FROM $this->tableName WHERE tipo = 'SVH'";

    return $this->executeQuery($query, null);
  }

  public function buscarConDocDetallesPorNombreProducto($nombresProducto)
  {
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
    foreach ($nombresProducto as $key => $nombreProducto) {
      $query .= "pr.nombre_producto LIKE :nombre_producto_$key";
      if (next($nombresProducto)) {
        $query .= " OR ";
      }
    }

    $query .= " GROUP BY pr.id_producto, tp.nombre_tipo_de_producto, pr.nombre_producto, pr.costo_unitario, pr.tipo_de_unidad";

    $params = array_map(function ($key, $value) {
      return array("nombre" => "nombre_producto_$key", "valor" => "%$value%", "tipo" => PDO::PARAM_STR);
    }, array_keys($nombresProducto), $nombresProducto);

    return $this->executeQuery($query, $params);
  }

  public function buscarConDocDetallesPorTipoProducto($tipoProducto)
  {
    $query = "SELECT tp.nombre_tipo_de_producto AS tipo_producto, pr.nombre_producto, pr.costo_unitario, pr.tipo_de_unidad,
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
    GROUP BY pr.id_producto, tp.nombre_tipo_de_producto, pr.nombre_producto, pr.costo_unitario, pr.tipo_de_unidad";
    $params = array(
      array("nombre" => "id_tipo_de_producto", "valor" => $tipoProducto, "tipo" => PDO::PARAM_INT)
    );

    return $this->executeQuery($query, $params);
  }

  public function crearProducto(Producto $producto)
  {
    $productoArray = $this->prepareData((array) $producto, "insert");
    $query = $this->prepareQuery("insert", $productoArray);
    $params = $this->prepareParams($productoArray);

    return $this->executeQuery($query, $params, "insert");
  }

  public function actualizarProducto($id, Producto $producto)
  {
    $productoArray = $this->prepareData((array) $producto);
    $query = $this->prepareQuery("update", $productoArray);
    $params = $this->prepareParams($productoArray, "update", $id);

    return $this->executeQuery($query, $params, "update");
  }

  public function eliminarProducto($id)
  {
    $query = $this->prepareQuery("delete");
    $params = $this->prepareParams(null, "delete", $id);

    return $this->executeQuery($query, $params, "delete");
  }
}
?>