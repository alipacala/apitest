<?php
require_once PROJECT_ROOT_PATH . "/entities/TipoDeUsuario.php";

class TiposDeUsuarioDb extends Database
{
  public $class = TipoDeUsuario::class;
  public $idName = "id_tipo_de_usuario";
  public $tableName = "tipodeusuario";

  public function obtenerTipoDeUsuario($id)
  {
    $query = $this->prepareQuery("select-one");
    $params = $this->prepareParams(null, "select-one", $id);

    return $this->executeQuery($query, $params, "select-one");
  }

  public function listarTiposDeUsuario()
  {
    $query = $this->prepareQuery("select");

    return $this->executeQuery($query, null, "select");
  }

  public function crearTipoDeUsuario(TipoDeUsuario $tipoDeUsuario)
  {
    $tipoDeUsuarioArray = $this->prepareData((array) $tipoDeUsuario, "insert");
    $query = $this->prepareQuery("insert", $tipoDeUsuarioArray);
    $params = $this->prepareParams($tipoDeUsuarioArray);

    return $this->executeQuery($query, $params, "insert");
  }

  public function actualizarTipoDeUsuario($id, TipoDeUsuario $tipoDeUsuario)
  {
    $tipoDeUsuarioArray = $this->prepareData((array) $tipoDeUsuario);
    $query = $this->prepareQuery("update", $tipoDeUsuarioArray);
    $params = $this->prepareParams($tipoDeUsuarioArray, "update", $id);

    return $this->executeQuery($query, $params, "update");
  }

  public function eliminarTipoDeUsuario($id)
  {
    $query = $this->prepareQuery("delete");
    $params = $this->prepareParams(null, "delete", $id);

    return $this->executeQuery($query, $params, "delete");
  }
}
?>