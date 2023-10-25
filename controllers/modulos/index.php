<?php
require_once __DIR__ . "/../../inc/bootstrap.php";
require_once PROJECT_ROOT_PATH . "/controllers/BaseController.php";

require_once PROJECT_ROOT_PATH . "/models/ModulosDb.php";

class ModulosController extends BaseController
{
  public function get()
  {
    $modulosDb = new ModulosDb();
    $result = $modulosDb->listarModulos();

    $this->sendResponse($result, 200);
  }

  public function getOne($id)
  {
    $modulosDb = new ModulosDb();
    $modulo = $modulosDb->obtenerModulo($id);

    $response = $modulo ? $modulo : ["mensaje" => "Modulo no encontrado"];
    $code = $modulo ? 200 : 404;

    $this->sendResponse($response, $code);
  }

  public function create()
  {
    $moduloDelBody = $this->getBody();
    $modulo = new Modulo();
    $this->mapJsonToObj($moduloDelBody, $modulo);

    $modulosDb = new ModulosDb();
    $id = $modulosDb->crearModulo($modulo);

    $response = $id ? [
      "mensaje" => "Modulo creado correctamente",
      "resultado" => array_merge([$modulosDb->idName => intval($id)], (array) $moduloDelBody)
    ] : ["mensaje" => "Error al crear el Modulo"];
    $code = $id ? 201 : 400;

    $this->sendResponse($response, $code);
  }

  public function update($id)
  {
    $moduloDelBody = $this->getBody();
    $modulo = new Modulo();
    $this->mapJsonToObj($moduloDelBody, $modulo);

    $modulosDb = new ModulosDb();

    $prevModulo = $modulosDb->obtenerModulo($id);
    unset($prevModulo->id_modulo);

    // comprobar que la modulo exista
    if (!$prevModulo) {
      $this->sendResponse(["mensaje" => "Modulo no encontrado"], 404);
      return;
    }

    // si los datos son iguales, no se hace nada
    if ($this->compararObjetoActualizar($modulo, $prevModulo)) {
      $this->sendResponse(["mensaje" => "No se realizaron cambios"], 200);
      return;
    }

    $result = $modulosDb->actualizarModulo($id, $modulo);

    $response = $result ? [
      "mensaje" => "Modulo actualizado correctamente",
      "resultado" => $modulosDb->obtenerModulo($id)
    ] : ["mensaje" => "Error al actualizar el Modulo"];
    $code = $result ? 200 : 400;

    $this->sendResponse($response, $code);
  }

  public function delete($id)
  {
    $modulosDb = new ModulosDb();
    $prevModulo = $modulosDb->obtenerModulo($id);

    // comprobar que la modulo exista
    if (!$prevModulo) {
      $this->sendResponse(["mensaje" => "Modulo no encontrado"], 404);
      return;
    }

    $result = $modulosDb->eliminarModulo($id);

    $response = $result ? [
      "mensaje" => "Modulo eliminado correctamente",
      "resultado" => $prevModulo
    ] : ["mensaje" => "Error al eliminar el Modulo"];
    $code = $result ? 200 : 400;

    $this->sendResponse($response, $code);
  }
}

try {
  $controller = new ModulosController();
  $controller->route();
} catch (Exception $e) {
  $controller->sendResponse($controller->errorResponse($e), 500);
}
?>