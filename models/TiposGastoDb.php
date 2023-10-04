<?php
require_once PROJECT_ROOT_PATH . "/entities/TipoGasto.php";

class TiposGastoDb extends Database
{
  public $class = TipoGasto::class;
  public $idName = "id_tipo_de_gasto";
  public $tableName = "tipo_de_gasto";

  public function obtenerTipoGasto($id)
  {
    $query = $this->prepareQuery("select-one");
    $params = $this->prepareParams(null, "select-one", $id);

    return $this->executeQuery($query, $params, "select-one");
  }

  public function listarTiposGasto()
  {
    $query = $this->prepareQuery("select");

    return $this->executeQuery($query, null, "select");
  }

  public function crearTipoGasto(TipoGasto $tipoGasto)
  {
    $tipoGastoArray = $this->prepareData((array) $tipoGasto, "insert");
    $query = $this->prepareQuery("insert", $tipoGastoArray);
    $params = $this->prepareParams($tipoGastoArray);

    return $this->executeQuery($query, $params, "insert");
  }

  public function actualizarTipoGasto($id, TipoGasto $tipoGasto)
  {
    $tipoGastoArray = $this->prepareData((array) $tipoGasto);
    $query = $this->prepareQuery("update", $tipoGastoArray);
    $params = $this->prepareParams($tipoGastoArray, "update", $id);

    return $this->executeQuery($query, $params, "update");
  }

  public function eliminarTipoGasto($id)
  {
    $query = $this->prepareQuery("delete");
    $params = $this->prepareParams(null, "delete", $id);

    return $this->executeQuery($query, $params, "delete");
  }
}
?>