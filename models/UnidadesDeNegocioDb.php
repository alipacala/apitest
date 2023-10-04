<?php
require_once PROJECT_ROOT_PATH . "/entities/UnidadDeNegocio.php";

class UnidadesDeNegocioDb extends Database
{
  public $class = UnidadDeNegocio::class;
  public $idName = "id_unidad_de_negocio";
  public $tableName = "unidaddenegocio";

  public function obtenerUnidadDeNegocio($id)
  {
    $query = $this->prepareQuery("select-one");
    $params = $this->prepareParams(null, "select-one", $id);

    return $this->executeQuery($query, $params, "select-one");
  }

  public function listarUnidadesDeNegocio()
  {
    $query = $this->prepareQuery("select");

    return $this->executeQuery($query, null, "select");
  }

  public function crearUnidadDeNegocio(UnidadDeNegocio $usuarioModulo)
  {
    $usuarioModuloArray = $this->prepareData((array) $usuarioModulo, "insert");
    $query = $this->prepareQuery("insert", $usuarioModuloArray);
    $params = $this->prepareParams($usuarioModuloArray);

    return $this->executeQuery($query, $params, "insert");
  }

  public function actualizarUnidadDeNegocio($id, UnidadDeNegocio $usuarioModulo)
  {
    $usuarioModuloArray = $this->prepareData((array) $usuarioModulo);
    $query = $this->prepareQuery("update", $usuarioModuloArray);
    $params = $this->prepareParams($usuarioModuloArray, "update", $id);

    return $this->executeQuery($query, $params, "update");
  }

  public function eliminarUnidadDeNegocio($id)
  {
    $query = $this->prepareQuery("delete");
    $params = $this->prepareParams(null, "delete", $id);

    return $this->executeQuery($query, $params, "delete");
  }
}
?>