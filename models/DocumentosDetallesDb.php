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

  public function buscarDocumentosDetallesPorNroRegistroMaestro($nroRegistroMaestro)
  {
    $query = "SELECT * FROM $this->tableName WHERE nro_registro_maestro = :nro_registro_maestro AND nivel_descargo != 2";
    $params = array(["nombre" => "nro_registro_maestro", "valor" => $nroRegistroMaestro, "tipo" => PDO::PARAM_STR]);

    return $this->executeQuery($query, $params, "select");
  }

  public function buscarDocumentosDetallesPorNroComprobanteVenta($nroComprobanteVenta)
  {
    $query = "SELECT * FROM $this->tableName WHERE nro_comprobante = :nro_comprobante_venta";
    $params = array(["nombre" => "nro_comprobante_venta", "valor" => $nroComprobanteVenta, "tipo" => PDO::PARAM_STR]);

    return $this->executeQuery($query, $params, "select");
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