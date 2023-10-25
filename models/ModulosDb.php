<?php
require_once PROJECT_ROOT_PATH . "/entities/Modulo.php";

class ModulosDb extends Database
{
  public $class = Modulo::class;
  public $idName = "id_modulo";
  public $tableName = "modulos";

  public function obtenerModulo($id)
  {
    $query = $this->prepareQuery("select-one");
    $params = $this->prepareParams(null, "select-one", $id);

    return $this->executeQuery($query, $params, "select-one");
  }

  public function listarModulos()
  {
    $query = $this->prepareQuery("select");

    return $this->executeQuery($query, null, "select");
  }

  public function crearModulo(Modulo $modulo)
  {
    $moduloArray = $this->prepareData((array) $modulo, "insert");
    $query = $this->prepareQuery("insert", $moduloArray);
    $params = $this->prepareParams($moduloArray);

    return $this->executeQuery($query, $params, "insert");
  }

  public function actualizarModulo($id, Modulo $modulo)
  {
    $moduloArray = $this->prepareData((array) $modulo);
    $query = $this->prepareQuery("update", $moduloArray);
    $params = $this->prepareParams($moduloArray, "update", $id);

    return $this->executeQuery($query, $params, "update");
  }

  public function eliminarModulo($id)
  {
    $query = $this->prepareQuery("delete");
    $params = $this->prepareParams(null, "delete", $id);

    return $this->executeQuery($query, $params, "delete");
  }
}
?>