<?php
require_once PROJECT_ROOT_PATH . "/entities/Pais.php";

class PaisesDb extends Database
{
  public $class = Pais::class;
  public $idName = "id_pais";
  public $tableName = "pais";

  public function obtenerPais($id)
  {
    $query = $this->prepareQuery("select-one");
    $params = $this->prepareParams(null, "select-one", $id);

    return $this->executeQuery($query, $params, "select-one");
  }

  public function listarPaises()
  {
    $query = $this->prepareQuery("select");

    return $this->executeQuery($query, null, "select");
  }
}
?>