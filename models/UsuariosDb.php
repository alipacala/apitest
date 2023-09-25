<?php
require_once PROJECT_ROOT_PATH . "/entities/Usuario.php";

class UsuariosDb extends Database
{
  public $class = Usuario::class;
  public $idName = "id_usuario";
  public $tableName = "usuarios";

  public function obtenerNombreUsuario($id)
  {
    $query = "SELECT usuario FROM usuarios WHERE id_usuario = :id";
    $params = array(
      ["nombre" => "id", "valor" => $id, "tipo" => PDO::PARAM_INT]
    );

    return $this->executeQuery($query, $params);
  }

  public function login($usuario, $clave)
  {
    $query = "SELECT COUNT(*) AS logueado
     FROM usuarios us
     WHERE us.usuario = :usuario AND us.clave = :clave";
    $params = array(
      ["nombre" => "usuario", "valor" => $usuario, "tipo" => PDO::PARAM_STR],
      ["nombre" => "clave", "valor" => $clave, "tipo" => PDO::PARAM_STR]
    );

    return $this->executeQuery($query, $params);
  }
}
?>