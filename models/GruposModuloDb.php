<?php
require_once PROJECT_ROOT_PATH . "/entities/GrupoModulo.php";

class GruposModuloDb extends Database
{
  public $class = GrupoModulo::class;
  public $idName = "id_grupo_modulo";
  public $tableName = "grupo_modulo";

  public function obtenerGrupoModulo($id)
  {
    $query = $this->prepareQuery("select-one");
    $params = $this->prepareParams(null, "select-one", $id);

    return $this->executeQuery($query, $params, "select-one");
  }

  public function listarGruposModulo()
  {
    $query = $this->prepareQuery("select");

    return $this->executeQuery($query, null, "select");
  }

  public function crearGrupoModulo(GrupoModulo $grupoModulo)
  {
    $grupoModuloArray = $this->prepareData((array) $grupoModulo, "insert");
    $query = $this->prepareQuery("insert", $grupoModuloArray);
    $params = $this->prepareParams($grupoModuloArray);

    return $this->executeQuery($query, $params, "insert");
  }

  public function actualizarGrupoModulo($id, GrupoModulo $grupoModulo)
  {
    $grupoModuloArray = $this->prepareData((array) $grupoModulo);
    $query = $this->prepareQuery("update", $grupoModuloArray);
    $params = $this->prepareParams($grupoModuloArray, "update", $id);

    return $this->executeQuery($query, $params, "update");
  }

  public function eliminarGrupoModulo($id)
  {
    $query = $this->prepareQuery("delete");
    $params = $this->prepareParams(null, "delete", $id);

    return $this->executeQuery($query, $params, "delete");
  }
}
?>