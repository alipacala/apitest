<?php
require_once PROJECT_ROOT_PATH . "/entities/Impresora.php";

class ImpresorasDb extends Database
{
  public $class = Impresora::class;
  public $idName = "id_impresora";
  public $tableName = "impresoras";

  public function obtenerImpresora($id)
  {
    $query = $this->prepareQuery("select-one");
    $params = $this->prepareParams(null, "select-one", $id);

    return $this->executeQuery($query, $params, "select-one");
  }

  public function listarImpresoras()
  {
    $query = $this->prepareQuery("select");

    return $this->executeQuery($query, null, "select");
  }

  public function crearImpresora(Impresora $impresora)
  {
    $impresoraArray = $this->prepareData((array) $impresora, "insert");
    $query = $this->prepareQuery("insert", $impresoraArray);
    $params = $this->prepareParams($impresoraArray);

    return $this->executeQuery($query, $params, "insert");
  }

  public function actualizarImpresora($id, Impresora $impresora)
  {
    $impresoraArray = $this->prepareData((array) $impresora);
    $query = $this->prepareQuery("update", $impresoraArray);
    $params = $this->prepareParams($impresoraArray, "update", $id);

    return $this->executeQuery($query, $params, "update");
  }

  public function eliminarImpresora($id)
  {
    $query = $this->prepareQuery("delete");
    $params = $this->prepareParams(null, "delete", $id);

    return $this->executeQuery($query, $params, "delete");
  }
}
?>