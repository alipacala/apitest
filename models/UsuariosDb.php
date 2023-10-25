<?php
require_once PROJECT_ROOT_PATH . "/entities/Usuario.php";

class UsuariosDb extends Database
{
  public $class = Usuario::class;
  public $idName = "id_usuario";
  public $tableName = "usuarios";

  public function listarUsuarios()
  {
    $query = $this->prepareQuery("select");
    return $this->executeQuery($query, null, "select");
  }

  public function obtenerNombreUsuario($id)
  {
    $query = "SELECT usuario FROM usuarios WHERE id_usuario = :id";
    $params = array(
      ["nombre" => "id", "valor" => $id, "tipo" => PDO::PARAM_INT]
    );

    return $this->executeQuery($query, $params);
  }

  public function obtenerUsuario($id)
  {
    $query = $this->prepareQuery("select-one", null);
    $params = array(
      ["nombre" => "id", "valor" => $id, "tipo" => PDO::PARAM_INT]
    );

    return $this->executeQuery($query, $params, "select-one");
  }

  public function listarConPersonas()
  {
    $query = "SELECT u.id_usuario, u.nro_doc, p.apellidos, p.nombres, u.cargo, u.usuario, u.activo, u.fecha_cese
    FROM usuarios u
    INNER JOIN personanaturaljuridica p ON p.id_persona = u.id_persona";

    return $this->executeQuery($query, null, "select");
  }

  public function listarActivosConPersonas()
  {
    $query = "SELECT u.id_usuario, p.apellidos, p.nombres
    FROM usuarios u
    INNER JOIN personanaturaljuridica p ON p.id_persona = u.id_persona
    WHERE u.activo = 1";

    return $this->executeQuery($query, null, "select");
  }

  public function buscarPorNroDoc($nroDoc) {
    
  }

  public function loginAdministrador($usuario, $clave)
  {
    $query = "SELECT COUNT(*) AS logueado
     FROM usuarios us
     WHERE us.usuario = :usuario AND us.clave = :clave AND us.id_tipo_de_usuario = 11";
    $params = array(
      ["nombre" => "usuario", "valor" => $usuario, "tipo" => PDO::PARAM_STR],
      ["nombre" => "clave", "valor" => $clave, "tipo" => PDO::PARAM_STR]
    );

    return $this->executeQuery($query, $params);
  }
  
  public function crearUsuario(Usuario $usuario)
  {
    $usuarioArray = $this->prepareData((array) $usuario, "insert");
    $query = $this->prepareQuery("insert", $usuarioArray);
    $params = $this->prepareParams($usuarioArray);

    return $this->executeQuery($query, $params, "insert");
  }

  public function actualizarUsuario($id, Usuario $usuario)
  {
    $usuarioArray = $this->prepareData((array) $usuario);
    $query = $this->prepareQuery("update", $usuarioArray);
    $params = $this->prepareParams($usuarioArray, "update", $id);

    return $this->executeQuery($query, $params, "update");
  }

}
?>