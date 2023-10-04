<?php
require_once __DIR__ . "/../../inc/bootstrap.php";
require_once PROJECT_ROOT_PATH . "/controllers/BaseController.php";

require_once PROJECT_ROOT_PATH . "/models/UsuariosModulosDb.php";

class UsuariosModulosController extends BaseController
{
  public function get()
  {
    $usuariosModulosDb = new UsuariosModulosDb();
    $result = $usuariosModulosDb->listarUsuariosModulos();

    $this->sendResponse($result, 200);
  }

  public function getOne($id)
  {
    $usuariosModulosDb = new UsuariosModulosDb();
    $usuarioModulo = $usuariosModulosDb->obtenerUsuarioModulo($id);

    $response = $usuarioModulo ? $usuarioModulo : ["mensaje" => "Usuario Modulo no encontrada"];
    $code = $usuarioModulo ? 200 : 404;

    $this->sendResponse($response, $code);
  }

  public function create()
  {
    $usuarioModuloDelBody = $this->getBody();
    $usuarioModulo = $this->mapJsonToClass($usuarioModuloDelBody, UsuarioModulo::class);

    $usuarioModulo->apertura_fecha_hora = date("Y-m-d H:i:s");
    $usuarioModulo->cese_fecha_hora = date("Y-m-d H:i:s"); // TODO: está bien esto?

    $usuariosModulosDb = new UsuariosModulosDb();
    $id = $usuariosModulosDb->crearUsuarioModulo($usuarioModulo);

    $response = $id ? [
      "mensaje" => "Usuario Modulo creada correctamente",
      "resultado" => array_merge([$usuariosModulosDb->idName => intval($id)], (array) $usuarioModuloDelBody)
    ] : ["mensaje" => "Error al crear la Usuario Modulo"];
    $code = $id ? 201 : 400;

    $this->sendResponse($response, $code);
  }

  public function update($id)
  {
    $usuarioModuloDelBody = $this->getBody();
    $usuarioModulo = $this->mapJsonToClass($usuarioModuloDelBody, UsuarioModulo::class);

    $usuariosModulosDb = new UsuariosModulosDb();

    $prevUsuarioModulo = $usuariosModulosDb->obtenerUsuarioModulo($id);
    unset($prevUsuarioModulo->id_usuarioModulo);

    // comprobar que la usuarioModulo exista
    if (!$prevUsuarioModulo) {
      $this->sendResponse(["mensaje" => "Usuario Modulo no encontrada"], 404);
      return;
    }

    // si los datos son iguales, no se hace nada
    if ($this->compararObjetoActualizar($usuarioModulo, $prevUsuarioModulo)) {
      $this->sendResponse(["mensaje" => "No se realizaron cambios"], 200);
      return;
    }

    $result = $usuariosModulosDb->actualizarUsuarioModulo($id, $usuarioModulo);

    $response = $result ? [
      "mensaje" => "Usuario Modulo actualizada correctamente",
      "resultado" => $usuariosModulosDb->obtenerUsuarioModulo($id)
    ] : ["mensaje" => "Error al actualizar la Usuario Modulo"];
    $code = $result ? 200 : 400;

    $this->sendResponse($response, $code);
  }

  public function delete($id)
  {
    $usuariosModulosDb = new UsuariosModulosDb();
    $prevUsuarioModulo = $usuariosModulosDb->obtenerUsuarioModulo($id);

    // comprobar que la usuarioModulo exista
    if (!$prevUsuarioModulo) {
      $this->sendResponse(["mensaje" => "Usuario Modulo no encontrada"], 404);
      return;
    }

    $result = $usuariosModulosDb->eliminarUsuarioModulo($id);

    $response = $result ? [
      "mensaje" => "Usuario Modulo eliminada correctamente",
      "resultado" => $prevUsuarioModulo
    ] : ["mensaje" => "Error al eliminar la Usuario Modulo"];
    $code = $result ? 200 : 400;

    $this->sendResponse($response, $code);
  }
}

try {
  $controller = new UsuariosModulosController();
  $controller->route();
} catch (Exception $e) {
  $controller->sendResponse([
    "mensaje" => $e->getMessage(),
    "archivo" => $e->getPrevious()?->getFile() ?? $e->getFile(),
    "linea" => $e->getPrevious()?->getLine() ?? $e->getLine(),
    "trace" => $e->getPrevious()?->getTrace() ?? $e->getTrace()
  ], 500);
}
?>