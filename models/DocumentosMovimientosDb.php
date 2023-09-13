<?php
require_once PROJECT_ROOT_PATH . "/entities/DocumentoMovimiento.php";

class DocumentosMovimientosDb extends Database
{
  public $class = DocumentoMovimiento::class;
  public $idName = "id_documento_movimiento";
  public $tableName = "documento_movimiento";

  public function obtenerDocumentoMovimiento($id)
  {
    $query = $this->prepareQuery("select-one");
    $params = $this->prepareParams(null, "select-one", $id);

    return $this->executeQuery($query, $params, "select-one");
  }

  public function listarDocumentosMovimientos()
  {
    $query = $this->prepareQuery("select");

    return $this->executeQuery($query, null, "select");
  }

  public function crearDocumentoMovimiento(DocumentoMovimiento $grupoDeLaCarta)
  {
    $documentoMovimientoArray = $this->prepareData((array) $grupoDeLaCarta, "insert");
    $query = $this->prepareQuery("insert", $documentoMovimientoArray);
    $params = $this->prepareParams($documentoMovimientoArray);

    return $this->executeQuery($query, $params, "insert");
  }

  public function actualizarDocumentoMovimiento($id, DocumentoMovimiento $grupoDeLaCarta)
  {
    $documentoMovimientoArray = $this->prepareData((array) $grupoDeLaCarta);
    $query = $this->prepareQuery("update", $documentoMovimientoArray);
    $params = $this->prepareParams($documentoMovimientoArray, "update", $id);

    return $this->executeQuery($query, $params, "update");
  }

  public function eliminarDocumentoMovimiento($id)
  {
    $query = $this->prepareQuery("delete");
    $params = $this->prepareParams(null, "delete", $id);

    return $this->executeQuery($query, $params, "delete");
  }
}
?>