<?php
require_once PROJECT_ROOT_PATH . "/entities/DocumentoDetalle.php";

class DocumentosDetallesDb extends Database
{
  public $class = DocumentoDetalle::class;
  public $idName = "id_documentos_detalle";
  public $tableName = "documento_detalle";

  public function obtenerDocumentoDetalle($id)
  {
    $query = $this->prepareQuery("select-one");
    $params = $this->prepareParams(null, "select-one", $id);

    return $this->executeQuery($query, $params, "select-one");
  }

  public function listarDocumentosDetalles()
  {
    $query = $this->prepareQuery("select");

    return $this->executeQuery($query, null, "select");
  }

  public function buscarPorNroRegistroMaestro($nroRegistroMaestro)
  {
    $query = "SELECT * FROM $this->tableName WHERE nro_registro_maestro = :nro_registro_maestro AND nivel_descargo != 2";
    $params = array(["nombre" => "nro_registro_maestro", "valor" => $nroRegistroMaestro, "tipo" => PDO::PARAM_STR]);

    return $this->executeQuery($query, $params, "select");
  }

  public function buscarPorNroComprobanteVenta($nroComprobanteVenta)
  {
    $query = "SELECT * FROM $this->tableName WHERE nro_comprobante = :nro_comprobante_venta";
    $params = array(["nombre" => "nro_comprobante_venta", "valor" => $nroComprobanteVenta, "tipo" => PDO::PARAM_STR]);

    return $this->executeQuery($query, $params, "select");
  }

  public function generarKardex($idProducto, $fechaInicio, $fechaFin)
  {
    $query = "WITH dd_antes_fecha AS (
      SELECT
          tipo_movimiento,
          cantidad,
          tipo_de_unidad,
          precio_unitario,
          fecha
        FROM documento_detalle
        WHERE id_producto = :id_producto1 AND fecha < :fecha_inicio1
    ),
    
    dd_entre_fechas AS (
      SELECT
          dd.fecha,
          CASE WHEN dd.tipo_movimiento = 'IN' THEN dd.nro_comprobante ELSE dm.nro_de_comanda END AS nro_doc,
          CASE WHEN dd.tipo_movimiento = 'IN' THEN pe1.apellidos ELSE pe2.apellidos END AS apellidos,
          CASE WHEN dd.tipo_movimiento = 'IN' THEN pe1.nombres ELSE pe2.nombres END AS nombres,
          dd.tipo_de_unidad,
          dd.precio_unitario,
          CASE WHEN dd.tipo_movimiento = 'IN' THEN dd.cantidad ELSE 0 END AS ingreso,
          CASE WHEN dd.tipo_movimiento = 'SA' THEN dd.cantidad ELSE 0 END AS salida,
        
          dd.tipo_movimiento
        
        FROM documento_detalle dd
        LEFT JOIN comprobante_detalle cd ON dd.id_documentos_detalle = cd.id_documentos_detalle
        LEFT JOIN comprobante_ventas cv ON cd.id_comprobante_ventas = cv.id_comprobante_ventas
        LEFT JOIN personanaturaljuridica pe1 ON cv.nro_documento_cliente = pe1.nro_documento
        LEFT JOIN documento_movimiento dm ON dd.id_documento_movimiento = dm.id_documento_movimiento
        LEFT JOIN personanaturaljuridica pe2 ON dm.nro_documento = pe2.nro_documento
        
        WHERE dd.id_producto = :id_producto2 AND CAST(dd.fecha AS DATE) BETWEEN :fecha_inicio2 AND :fecha_fin
    ),
    
    monto_antes_fecha AS (
                    SELECT
                      ultimo_dd.tipo_de_unidad,
                      ultimo_dd.precio_unitario,
                      SUM(CASE WHEN tipo_movimiento = 'IN' THEN cantidad ELSE -cantidad END) AS antes_fecha
                    FROM dd_antes_fecha
                    JOIN (
                        SELECT tipo_de_unidad, precio_unitario
                        FROM dd_antes_fecha
                        ORDER BY fecha DESC
                        LIMIT 1
                    ) AS ultimo_dd
                  ),
    
    monto_antes_y_dd_prev AS (
        SELECT
          NULL AS tipo_movimiento,
            NULL AS fecha,
            NULL AS nro_doc,
            NULL AS apellidos,
            NULL AS nombres,
            maf.tipo_de_unidad,
            maf.precio_unitario,
            CASE WHEN antes_fecha >= 0 THEN antes_fecha ELSE 0 END AS ingreso,
            CASE WHEN antes_fecha < 0 THEN -antes_fecha ELSE 0 END AS salida
        FROM monto_antes_fecha maf
    
        UNION ALL
    
        SELECT
          tipo_movimiento,
            fecha,
            nro_doc,
            apellidos,
            nombres,
            tipo_de_unidad,
            precio_unitario,
            ingreso,
            salida
        FROM dd_entre_fechas
    )
    
    SELECT
      *, SUM(ingreso - salida) OVER (ORDER BY fecha, tipo_movimiento, EXTRACT(HOUR FROM fecha)) AS existencias
    FROM monto_antes_y_dd_prev";

    $params = array(
      ["nombre" => "id_producto1", "valor" => $idProducto, "tipo" => PDO::PARAM_INT],
      ["nombre" => "id_producto2", "valor" => $idProducto, "tipo" => PDO::PARAM_INT],
      ["nombre" => "fecha_inicio1", "valor" => $fechaInicio, "tipo" => PDO::PARAM_STR],
      ["nombre" => "fecha_inicio2", "valor" => $fechaInicio, "tipo" => PDO::PARAM_STR],
      ["nombre" => "fecha_fin", "valor" => $fechaFin, "tipo" => PDO::PARAM_STR]
    );

    return $this->executeQuery($query, $params);
  }

  public function crearDocumentoDetalle(DocumentoDetalle $documentoDetalle)
  {
    $documentoDetalleArray = $this->prepareData((array) $documentoDetalle, "insert");
    $query = $this->prepareQuery("insert", $documentoDetalleArray);
    $params = $this->prepareParams($documentoDetalleArray);

    return $this->executeQuery($query, $params, "insert");
  }

  public function actualizarDocumentoDetalle($id, DocumentoDetalle $documentoDetalle)
  {
    $documentoDetalleArray = $this->prepareData((array) $documentoDetalle);
    $query = $this->prepareQuery("update", $documentoDetalleArray);
    $params = $this->prepareParams($documentoDetalleArray, "update", $id);

    return $this->executeQuery($query, $params, "update");
  }

  public function actualizarConSubproductos($id, $nroComprobante)
  {
    $query = "UPDATE $this->tableName SET nro_comprobante = :nro_comprobante WHERE $this->idName = :id OR id_item = :id_item";
    $params = array(
      ["nombre" => "nro_comprobante", "valor" => $nroComprobante, "tipo" => PDO::PARAM_STR],
      ["nombre" => "id", "valor" => $id, "tipo" => PDO::PARAM_INT],
      ["nombre" => "id_item", "valor" => $id, "tipo" => PDO::PARAM_INT],
    );

    return $this->executeQuery($query, $params, "update");
  }

  public function actualizarDocumentoMovimiento($id, $idDocumentoMovimiento)
  {
    $query = "UPDATE $this->tableName SET id_documento_movimiento = :id_documento_movimiento WHERE $this->idName = :id";
    $params = array(
      ["nombre" => "id_documento_movimiento", "valor" => $idDocumentoMovimiento, "tipo" => PDO::PARAM_INT],
      ["nombre" => "id", "valor" => $id, "tipo" => PDO::PARAM_INT]
    );

    return $this->executeQuery($query, $params, "update");
  }

  public function actualizarIdItem($id, $idItem)
  {
    $query = "UPDATE $this->tableName SET id_item = :id_item WHERE $this->idName = :id";
    $params = array(
      ["nombre" => "id_item", "valor" => $idItem, "tipo" => PDO::PARAM_INT],
      ["nombre" => "id", "valor" => $id, "tipo" => PDO::PARAM_INT]
    );

    return $this->executeQuery($query, $params, "update");
  }

  public function deshacerPagoDocumentosDetalles($id)
  {
    $query = "UPDATE $this->tableName
      SET nro_comprobante = NULL, id_recibo_de_pago = NULL 
      WHERE nro_comprobante = (SELECT nro_comprobante FROM comprobante_ventas WHERE id_comprobante_ventas = :id_comprobante_ventas LIMIT 1)";
    $params = array(["nombre" => "id_comprobante_ventas", "valor" => $id, "tipo" => PDO::PARAM_INT]);

    return $this->executeQuery($query, $params, "update");
  }

  public function eliminarDocumentoDetalle($id)
  {
    $query = $this->prepareQuery("delete");
    $params = $this->prepareParams(null, "delete", $id);

    return $this->executeQuery($query, $params, "delete");
  }

  public function eliminarPorNroComprobante($nroComprobante)
  {
    $query = "DELETE FROM $this->tableName WHERE nro_comprobante = :nro_comprobante";
    $params = array(["nombre" => "nro_comprobante", "valor" => $nroComprobante, "tipo" => PDO::PARAM_STR]);

    return $this->executeQuery($query, $params, "delete");
  }

  public function anularDocumentoDetalle($id)
  {
    $query = "UPDATE $this->tableName
    SET anulado = 1
    WHERE $this->idName = :id1
    OR id_item = :id2
    OR id_item IN (SELECT id_documentos_detalle
    FROM documento_detalle
    WHERE id_item = :id3)";
    $params = array(
      ["nombre" => "id1", "valor" => $id, "tipo" => PDO::PARAM_INT],
      ["nombre" => "id2", "valor" => $id, "tipo" => PDO::PARAM_INT],
      ["nombre" => "id3", "valor" => $id, "tipo" => PDO::PARAM_INT]
    );

    return $this->executeQuery($query, $params, "update");
  }
}
?>