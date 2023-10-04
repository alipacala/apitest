<?php
require_once PROJECT_ROOT_PATH . "/entities/UsuarioModulo.php";

class UsuariosModulosDb extends Database
{
  public $class = UsuarioModulo::class;
  public $idName = "id_usuario_modulo";
  public $tableName = "usuariosmodulos";

  public function obtenerUsuarioModulo($id)
  {
    $query = $this->prepareQuery("select-one");
    $params = $this->prepareParams(null, "select-one", $id);

    return $this->executeQuery($query, $params, "select-one");
  }

  public function listarUsuariosModulos()
  {
    $query = $this->prepareQuery("select");

    return $this->executeQuery($query, null, "select");
  }

  public function listarConUsuariosYModulos() {
    $query = "SELECT u.id_usuario_modulo, us.usuario, m.nombre_modulo, u.tiene_acceso, u.acceso_consulta, u.acceso_modificacion, u.acceso_creacion, u.cese_fecha_hora
    FROM usuariosmodulos u
    INNER JOIN usuarios us ON us.id_usuario = u.id_usuario
    INNER JOIN modulos m ON m.id_modulo = u.id_modulo";
    
    return $this->executeQuery($query, null, "select");
  }

  public function crearUsuarioModulo(UsuarioModulo $usuarioModulo)
  {
    $usuarioModuloArray = $this->prepareData((array) $usuarioModulo, "insert");
    $query = $this->prepareQuery("insert", $usuarioModuloArray);
    $params = $this->prepareParams($usuarioModuloArray);

    return $this->executeQuery($query, $params, "insert");
  }

  public function actualizarUsuarioModulo($id, UsuarioModulo $usuarioModulo)
  {
    $usuarioModuloArray = $this->prepareData((array) $usuarioModulo);
    $query = $this->prepareQuery("update", $usuarioModuloArray);
    $params = $this->prepareParams($usuarioModuloArray, "update", $id);

    return $this->executeQuery($query, $params, "update");
  }

  public function eliminarUsuarioModulo($id)
  {
    $query = $this->prepareQuery("delete");
    $params = $this->prepareParams(null, "delete", $id);

    return $this->executeQuery($query, $params, "delete");
  }
}
?>