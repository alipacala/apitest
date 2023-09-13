<?php
require_once PROJECT_ROOT_PATH . "/entities/CentralDeCostos.php";

class CentralesDeCostosDb extends Database
{
  public $class = CentralDeCostos::class;
  public $idName = "id_central_de_costos";
  public $tableName = "centraldecostos";

  public function obtenerCentralDeCostos($id)
  {
    $query = $this->prepareQuery("select-one");
    $params = $this->prepareParams(null, "select-one", $id);

    return $this->executeQuery($query, $params, "select-one");
  }

  public function listarCentralesDeCostos()
  {
    $query = $this->prepareQuery("select");

    return $this->executeQuery($query, null, "select");
  }

  public function crearCentralDeCostos(CentralDeCostos $centralDeCostos)
  {
    $centralDeCostosArray = $this->prepareData((array) $centralDeCostos, "insert");
    $query = $this->prepareQuery("insert", $centralDeCostosArray);
    $params = $this->prepareParams($centralDeCostosArray);

    return $this->executeQuery($query, $params, "insert");
  }

  public function actualizarCentralDeCostos($id, CentralDeCostos $centralDeCostos)
  {
    $centralDeCostosArray = $this->prepareData((array) $centralDeCostos);
    $query = $this->prepareQuery("update", $centralDeCostosArray);
    $params = $this->prepareParams($centralDeCostosArray, "update", $id);

    return $this->executeQuery($query, $params, "update");
  }

  public function eliminarCentralDeCostos($id)
  {
    $query = $this->prepareQuery("delete");
    $params = $this->prepareParams(null, "delete", $id);

    return $this->executeQuery($query, $params, "delete");
  }
}
?>