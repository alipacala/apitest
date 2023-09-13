<?php
require_once PROJECT_ROOT_PATH . "/entities/TipoDeProductos.php";

class TiposDeProductosDb extends Database
{
  public $class = TipoDeProductos::class;
  public $idName = "id_tipo_producto";
  public $tableName = "tipodeproductos";

  public function obtenerTipoDeProductos($id)
  {
    $query = $this->prepareQuery("select-one");
    $params = $this->prepareParams(null, "select-one", $id);

    return $this->executeQuery($query, $params, "select-one");
  }

  public function listarTiposDeProductos()
  {
    $query = $this->prepareQuery("select");

    return $this->executeQuery($query, null, "select");
  }

  public function crearTipoDeProductos(TipoDeProductos $tipoDeProductos)
  {
    $tipoDeProductosArray = $this->prepareData((array) $tipoDeProductos, "insert");
    $query = $this->prepareQuery("insert", $tipoDeProductosArray);
    $params = $this->prepareParams($tipoDeProductosArray);

    return $this->executeQuery($query, $params, "insert");
  }

  public function actualizarTipoDeProductos($id, TipoDeProductos $tipoDeProductos)
  {
    $tipoDeProductosArray = $this->prepareData((array) $tipoDeProductos);
    $query = $this->prepareQuery("update", $tipoDeProductosArray);
    $params = $this->prepareParams($tipoDeProductosArray, "update", $id);

    return $this->executeQuery($query, $params, "update");
  }

  public function eliminarTipoDeProductos($id)
  {
    $query = $this->prepareQuery("delete");
    $params = $this->prepareParams(null, "delete", $id);

    return $this->executeQuery($query, $params, "delete");
  }
}
?>