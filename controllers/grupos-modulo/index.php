<?php
require_once __DIR__ . "/../../inc/bootstrap.php";
require_once PROJECT_ROOT_PATH . "/controllers/BaseController.php";

require_once PROJECT_ROOT_PATH . "/models/GruposModuloDb.php";

class GruposModuloController extends BaseController
{
  public function get()
  {
    $gruposModuloDb = new GruposModuloDb();
    $result = $gruposModuloDb->listarGruposModulo();

    $this->sendResponse($result, 200);
  }

  public function getOne($id)
  {
    $gruposModuloDb = new GruposModuloDb();
    $grupoModulo = $gruposModuloDb->obtenerGrupoModulo($id);

    $response = $grupoModulo ? $grupoModulo : ["mensaje" => "Grupo Modulo no encontrado"];
    $code = $grupoModulo ? 200 : 404;

    $this->sendResponse($response, $code);
  }

  public function create()
  {
    $grupoModuloDelBody = $this->getBody();
    $grupoModulo = $this->mapJsonToClass($grupoModuloDelBody, GrupoModulo::class);

    $gruposModuloDb = new GruposModuloDb();
    $id = $gruposModuloDb->crearGrupoModulo($grupoModulo);

    $response = $id ? [
      "mensaje" => "Grupo Modulo creado correctamente",
      "resultado" => array_merge([$gruposModuloDb->idName => intval($id)], (array) $grupoModuloDelBody)
    ] : ["mensaje" => "Error al crear el Grupo Modulo"];
    $code = $id ? 201 : 400;

    $this->sendResponse($response, $code);
  }

  public function update($id)
  {
    $grupoModuloDelBody = $this->getBody();
    $grupoModulo = $this->mapJsonToClass($grupoModuloDelBody, GrupoModulo::class);

    $gruposModuloDb = new GruposModuloDb();

    $prevGrupoModulo = $gruposModuloDb->obtenerGrupoModulo($id);
    unset($prevGrupoModulo->id_grupoModulo);

    // comprobar que la grupoModulo exista
    if (!$prevGrupoModulo) {
      $this->sendResponse(["mensaje" => "Grupo Modulo no encontrado"], 404);
      return;
    }

    // si los datos son iguales, no se hace nada
    if ($this->compararObjetoActualizar($grupoModulo, $prevGrupoModulo)) {
      $this->sendResponse(["mensaje" => "No se realizaron cambios"], 200);
      return;
    }

    $result = $gruposModuloDb->actualizarGrupoModulo($id, $grupoModulo);

    $response = $result ? [
      "mensaje" => "Grupo Modulo actualizado correctamente",
      "resultado" => $gruposModuloDb->obtenerGrupoModulo($id)
    ] : ["mensaje" => "Error al actualizar el Grupo Modulo"];
    $code = $result ? 200 : 400;

    $this->sendResponse($response, $code);
  }

  public function delete($id)
  {
    $gruposModuloDb = new GruposModuloDb();
    $prevGrupoModulo = $gruposModuloDb->obtenerGrupoModulo($id);

    // comprobar que la grupoModulo exista
    if (!$prevGrupoModulo) {
      $this->sendResponse(["mensaje" => "Grupo Modulo no encontrado"], 404);
      return;
    }

    $result = $gruposModuloDb->eliminarGrupoModulo($id);

    $response = $result ? [
      "mensaje" => "Grupo Modulo eliminado correctamente",
      "resultado" => $prevGrupoModulo
    ] : ["mensaje" => "Error al eliminar el Grupo Modulo"];
    $code = $result ? 200 : 400;

    $this->sendResponse($response, $code);
  }
}

try {
  $controller = new GruposModuloController();
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