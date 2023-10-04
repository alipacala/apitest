<?php
require_once __DIR__ . "/../../inc/bootstrap.php";
require_once PROJECT_ROOT_PATH . "/controllers/BaseController.php";

require_once PROJECT_ROOT_PATH . "/models/AcompanantesDb.php";

class AcompanantesController extends BaseController
{
  public function get()
  {
    $params = $this->getParams();
    $nroRegistroMaestro = $params['nro_registro_maestro'] ?? null;

    $acompanantesDb = new AcompanantesDb();

    if ($nroRegistroMaestro) {
      $result = $acompanantesDb->buscarAcompanantePorNroRegistroMaestro($nroRegistroMaestro);
      $this->sendResponse($result, 200);
      return;
    }

    $result = $acompanantesDb->listarAcompanantes();

    $this->sendResponse($result, 200);
  }

  public function getOne($id)
  {
    $acompanantesDb = new AcompanantesDb();
    $acompanante = $acompanantesDb->obtenerAcompanante($id);

    $response = $acompanante ? $acompanante : ["mensaje" => "Acompañante no encontrado"];
    $code = $acompanante ? 200 : 404;

    $this->sendResponse($response, $code);
  }

  public function create()
  {
    $acompananteDelBody = $this->getBody();
    $acompanante = $this->mapJsonToClass($acompananteDelBody, Acompanante::class);

    $acompanantesDb = new AcompanantesDb();
    $id = $acompanantesDb->crearAcompanante($acompanante);

    $response = $id ? [
      "mensaje" => "Acompañante creado correctamente",
      "resultado" => array_merge([$acompanantesDb->idName => intval($id)], (array) $acompananteDelBody)
    ] : ["mensaje" => "Error al crear el Acompañante"];
    $code = $id ? 201 : 400;

    $this->sendResponse($response, $code);
  }

  public function update($id)
  {
    $acompananteDelBody = $this->getBody();
    $acompanante = $this->mapJsonToClass($acompananteDelBody, Acompanante::class);

    $acompanantesDb = new AcompanantesDb();

    $prevAcompanante = $acompanantesDb->obtenerAcompanante($id);
    unset($prevAcompanante->id_acompanante);

    // comprobar que el acompañante exista
    if (!$prevAcompanante) {
      $this->sendResponse(["mensaje" => "Acompañante no encontrado"], 404);
      return;
    }

    // si los datos son iguales, no se hace nada
    if ($this->compararObjetoActualizar($acompanante, $prevAcompanante)) {
      $this->sendResponse(["mensaje" => "No se realizaron cambios"], 200);
      return;
    }

    $result = $acompanantesDb->actualizarAcompanante($id, $acompanante);

    $response = $result ? [
      "mensaje" => "Acompañante actualizado correctamente",
      "resultado" => $acompanantesDb->obtenerAcompanante($id)
    ] : ["mensaje" => "Error al actualizar el Acompañante"];
    $code = $result ? 200 : 400;

    $this->sendResponse($response, $code);
  }

  public function delete($id)
  {
    $acompanantesDb = new AcompanantesDb();
    $prevAcompanante = $acompanantesDb->obtenerAcompanante($id);

    // comprobar que el acompañante exista
    if (!$prevAcompanante) {
      $this->sendResponse(["mensaje" => "Acompanante no encontrada"], 404);
      return;
    }

    $result = $acompanantesDb->eliminarAcompanante($id);

    $response = $result ? [
      "mensaje" => "Acompanante eliminada correctamente",
      "resultado" => $prevAcompanante
    ] : ["mensaje" => "Error al eliminar la Acompanante"];
    $code = $result ? 200 : 400;

    $this->sendResponse($response, $code);
  }
}

try {
  $controller = new AcompanantesController();
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