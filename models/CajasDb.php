<?php
require_once PROJECT_ROOT_PATH . "/entities/Caja.php";

class CajasDb extends Database
{
  public $class = Caja::class;
  public $idName = "nro_de_caja";
  public $tableName = "cajas";

  public function obtenerCaja($id)
  {
    $query = $this->prepareQuery("select-one");
    $params = $this->prepareParams(null, "select-one", $id);

    return $this->executeQuery($query, $params, "select-one");
  }

  public function listarCajas()
  {
    $query = $this->prepareQuery("select");

    return $this->executeQuery($query, null, "select");
  }

  public function crearCaja(Caja $caja)
  {
    $cajaArray = $this->prepareData((array) $caja, "insert");
    $query = $this->prepareQuery("insert", $cajaArray);
    $params = $this->prepareParams($cajaArray);

    return $this->executeQuery($query, $params, "insert");
  }

  public function actualizarCaja($id, Caja $caja)
  {
    $cajaArray = $this->prepareData((array) $caja);
    $query = $this->prepareQuery("update", $cajaArray);
    $params = $this->prepareParams($cajaArray, "update", $id);

    return $this->executeQuery($query, $params, "update");
  }

  public function eliminarCaja($id)
  {
    $query = $this->prepareQuery("delete");
    $params = $this->prepareParams(null, "delete", $id);

    return $this->executeQuery($query, $params, "delete");
  }
}
?>