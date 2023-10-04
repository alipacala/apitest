<?php
require_once __DIR__ . "/../../inc/bootstrap.php";
require_once PROJECT_ROOT_PATH . "/controllers/BaseController.php";

require_once PROJECT_ROOT_PATH . "/models/TiposDeUsuarioDb.php";

class TiposDeUsuarioController extends BaseController
{
  public function get()
  {
    $tiposDeUsuarioDb = new TiposDeUsuarioDb();
    $result = $tiposDeUsuarioDb->listarTiposDeUsuario();

    $this->sendResponse($result, 200);
  }

  public function getOne($id)
  {
    $tiposDeUsuarioDb = new TiposDeUsuarioDb();
    $tipoDeUsuario = $tiposDeUsuarioDb->obtenerTipoDeUsuario($id);

    $response = $tipoDeUsuario ? $tipoDeUsuario : ["mensaje" => "Tipo de Usuario no encontrada"];
    $code = $tipoDeUsuario ? 200 : 404;

    $this->sendResponse($response, $code);
  }

  public function create()
  {
    $tipoDeUsuarioDelBody = $this->getBody();
    $tipoDeUsuario = $this->mapJsonToClass($tipoDeUsuarioDelBody, TipoDeUsuario::class);

    $tiposDeUsuarioDb = new TiposDeUsuarioDb();
    $id = $tiposDeUsuarioDb->crearTipoDeUsuario($tipoDeUsuario);

    $response = $id ? [
      "mensaje" => "Tipo de Usuario creada correctamente",
      "resultado" => array_merge([$tiposDeUsuarioDb->idName => intval($id)], (array) $tipoDeUsuarioDelBody)
    ] : ["mensaje" => "Error al crear la Tipo de Usuario"];
    $code = $id ? 201 : 400;

    $this->sendResponse($response, $code);
  }

  public function update($id)
  {
    $tipoDeUsuarioDelBody = $this->getBody();
    $tipoDeUsuario = $this->mapJsonToClass($tipoDeUsuarioDelBody, TipoDeUsuario::class);

    $tiposDeUsuarioDb = new TiposDeUsuarioDb();

    $prevTipoDeUsuario = $tiposDeUsuarioDb->obtenerTipoDeUsuario($id);
    unset($prevTipoDeUsuario->id_tipoDeUsuario);

    // comprobar que la tipoDeUsuario exista
    if (!$prevTipoDeUsuario) {
      $this->sendResponse(["mensaje" => "Tipo de Usuario no encontrada"], 404);
      return;
    }

    // si los datos son iguales, no se hace nada
    if ($this->compararObjetoActualizar($tipoDeUsuario, $prevTipoDeUsuario)) {
      $this->sendResponse(["mensaje" => "No se realizaron cambios"], 200);
      return;
    }

    $result = $tiposDeUsuarioDb->actualizarTipoDeUsuario($id, $tipoDeUsuario);

    $response = $result ? [
      "mensaje" => "Tipo de Usuario actualizada correctamente",
      "resultado" => $tiposDeUsuarioDb->obtenerTipoDeUsuario($id)
    ] : ["mensaje" => "Error al actualizar la Tipo de Usuario"];
    $code = $result ? 200 : 400;

    $this->sendResponse($response, $code);
  }

  public function delete($id)
  {
    $tiposDeUsuarioDb = new TiposDeUsuarioDb();
    $prevTipoDeUsuario = $tiposDeUsuarioDb->obtenerTipoDeUsuario($id);

    // comprobar que la tipoDeUsuario exista
    if (!$prevTipoDeUsuario) {
      $this->sendResponse(["mensaje" => "Tipo de Usuario no encontrada"], 404);
      return;
    }

    $result = $tiposDeUsuarioDb->eliminarTipoDeUsuario($id);

    $response = $result ? [
      "mensaje" => "Tipo de Usuario eliminada correctamente",
      "resultado" => $prevTipoDeUsuario
    ] : ["mensaje" => "Error al eliminar la Tipo de Usuario"];
    $code = $result ? 200 : 400;

    $this->sendResponse($response, $code);
  }
}

try {
  $controller = new TiposDeUsuarioController();
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