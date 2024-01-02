<?php
require_once __DIR__."/../../inc/bootstrap.php";
require_once PROJECT_ROOT_PATH."/controllers/BaseController.php";

require_once PROJECT_ROOT_PATH."/models/UsuariosDb.php";
require_once PROJECT_ROOT_PATH."/models/UsuariosModulosDb.php";

class UsuariosController extends BaseController {
  public function get() {
    $params = $this->getParams();
    $conPersonas = isset($params['con-personas']);
    $activos = isset($params['activos']);

    $nroDoc = $params['nro_doc'] ?? null;

    $usuariosDb = new UsuariosDb();

    if($conPersonas) {
      $result = $usuariosDb->listarConPersonas();
    }
    if($activos) {
      $result = $usuariosDb->listarActivosConPersonas();
    }
    if($nroDoc) {
      $result = $usuariosDb->buscarPorNroDoc($nroDoc);
    }
    if(count($params) === 0) {
      $result = $usuariosDb->listarUsuarios();
    }

    $this->sendResponse($result, 200);
  }

  public function getOne($id) {
    $usuariosDb = new UsuariosDb();
    $usuario = $usuariosDb->obtenerUsuario($id);

    $response = $usuario ? $usuario : ["mensaje" => "Usuario no encontrado"];
    $code = $usuario ? 200 : 404;

    $this->sendResponse($response, $code);
  }

  public function create() {
    $usuarioDelBody = $this->getBody();

    $permisos = $usuarioDelBody["permisos"];
    unset($usuarioDelBody["permisos"]);

    $usuario = new Usuario();
    $this->mapJsonToObj($usuarioDelBody, $usuario);

    $usuariosDb = new UsuariosDb();

    $idUsuario = null;
    try {
      $usuariosDb->empezarTransaccion();
      $idUsuario = $usuariosDb->crearUsuario($usuario);

      foreach($permisos as &$permiso) {
        $permiso["id_usuario"] = $idUsuario;
        $permiso["cese_fecha_hora"] = null;

        $usuarioModulo = new UsuarioModulo();
        $this->mapJsonToObj($permiso, $usuarioModulo);
        $usuariosModulosDb = new UsuariosModulosDb();
        $permiso->id_usuario_modulo = $usuariosModulosDb->crearUsuarioModulo($usuarioModulo);
      }

      $usuariosDb->terminarTransaccion();
    } catch (Exception $e) {
      $usuariosDb->cancelarTransaccion();
      $newException = new Exception("Error al crear el usuario o sus permisos", 0, $e);
      throw $newException;
    }

    $response = $idUsuario ? [
      "mensaje" => "Usuario creada correctamente",
      "resultado" => array_merge([$usuariosDb->idName => intval($idUsuario)], (array)$usuarioDelBody, ["permisos" => $permisos])
    ] : ["mensaje" => "Error al crear la Usuario"];
    $code = $idUsuario ? 201 : 400;

    $this->sendResponse($response, $code);
  }

  public function createCustom($action) {
    switch($action) {
      case "login-terapeutas":
        $usuarioDelBody = $this->getBody();

        $usuario = $usuarioDelBody->usuario;
        $contrasena = $usuarioDelBody->contrasena;

        $usuariosDb = new UsuariosDb();
        $result = $usuariosDb->loginTerapeuta($usuario, $contrasena);

        $response = $result ? [
          "mensaje" => "Terapeuta logueado correctamente",
          "resultado" => $result[0]
        ] : ["mensaje" => "Usuario o contraseña incorrectos"];
        $code = $result ? 200 : 400;

        $this->sendResponse($response, $code);
        break;

        case "login-colaboradores":
          $usuarioDelBody = $this->getBody();
  
          $usuario = $usuarioDelBody->usuario;
          $contrasena = $usuarioDelBody->contrasena;
  
          $usuariosDb = new UsuariosDb();
          $result = $usuariosDb->loginColaborador($usuario, $contrasena);
  
          $response = $result ? [
            "mensaje" => "Colaborador logueado correctamente",
            "resultado" => $result[0]
          ] : ["mensaje" => "Usuario o contraseña incorrectos"];
          $code = $result ? 200 : 400;
  
          $this->sendResponse($response, $code);
          break;
      default:
        $this->sendResponse(["mensaje" => "Acción no encontrada"], 404);
        break;
    }
  }

  public function update($id) {
    $usuarioDelBody = $this->getBody();
    $usuario = new Usuario();
    $this->mapJsonToObj($usuarioDelBody, $usuario);

    $usuariosDb = new UsuariosDb();

    $prevUsuario = $usuariosDb->obtenerUsuario($id);
    unset($prevUsuario->id_usuario);

    // comprobar que la usuario exista
    if(!$prevUsuario) {
      $this->sendResponse(["mensaje" => "Usuario no encontrada"], 404);
      return;
    }

    // si los datos son iguales, no se hace nada
    if($this->compararObjetoActualizar($usuario, $prevUsuario)) {
      $this->sendResponse(["mensaje" => "No se realizaron cambios"], 200);
      return;
    }

    $result = $usuariosDb->actualizarUsuario($id, $usuario);

    $response = $result ? [
      "mensaje" => "Usuario actualizada correctamente",
      "resultado" => $usuariosDb->obtenerUsuario($id)
    ] : ["mensaje" => "Error al actualizar la Usuario"];
    $code = $result ? 200 : 400;

    $this->sendResponse($response, $code);
  }
}

try {
  $controller = new UsuariosController();
  $controller->route();
} catch (Exception $e) {
  $controller->sendResponse($controller->errorResponse($e), 500);
}
?>