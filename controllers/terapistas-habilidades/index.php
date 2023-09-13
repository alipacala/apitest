<?php
require_once __DIR__ . "/../../inc/bootstrap.php";
require_once PROJECT_ROOT_PATH . "/controllers/BaseController.php";

require_once PROJECT_ROOT_PATH . "/models/TerapistasHabilidadesDb.php";
require_once PROJECT_ROOT_PATH . "/entities/TerapistaHabilidad.php";

class TerapistasHabilidadesController extends BaseController
{
  public function get()
  {
    $params = $this->getParams();
    $conHabilidades = $params['con_habilidades'] == '';

    $terapistasHabilidadesDb = new TerapistasHabilidadesDb();
    $result = $terapistasHabilidadesDb->listarTerapistasHabilidades($conHabilidades);

    $this->sendResponse($result, 200);
  }

  public function getOne($id)
  {
    $terapistasHabilidadesDb = new TerapistasHabilidadesDb();
    $terapistaHabilidad = $terapistasHabilidadesDb->obtenerTerapistaHabilidad($id);

    $response = $terapistaHabilidad ? $terapistaHabilidad : ["mensaje" => "Terapista Habilidad no encontrada"];
    $code = $terapistaHabilidad ? 200 : 404;

    $this->sendResponse($response, $code);
  }

  public function create()
  {
    $terapistaHabilidadDelBody = $this->getBody();
    $terapistaHabilidad = $this->mapJsonToClass($terapistaHabilidadDelBody, TerapistaHabilidad::class);

    $terapistasHabilidadesDb = new TerapistasHabilidadesDb();
    $id = $terapistasHabilidadesDb->crearTerapistaHabilidad($terapistaHabilidad);

    $response = $id ? [
      "mensaje" => "Terapista Habilidad creada correctamente",
      "resultado" => array_merge([$terapistasHabilidadesDb->idName => intval($id)], (array) $terapistaHabilidadDelBody)
    ] : ["mensaje" => "Error al crear la TerapistaHabilidad"];
    $code = $id ? 201 : 400;

    $this->sendResponse($response, $code);
  }

  public function update($id)
  {
    $terapistaHabilidadDelBody = $this->getBody();
    $terapistaHabilidad = $this->mapJsonToClass($terapistaHabilidadDelBody, TerapistaHabilidad::class);

    $terapistasHabilidadesDb = new TerapistasHabilidadesDb();

    $prevTerapistaHabilidad = $terapistasHabilidadesDb->obtenerTerapistaHabilidad($id);
    unset($prevTerapistaHabilidad->id_terapista);

    // comprobar que la terapista exista
    if (!$prevTerapistaHabilidad) {
      $this->sendResponse(["mensaje" => "Terapista Habilidad no encontrada"], 404);
      return;
    }

    // si los datos son iguales, no se hace nada
    if ($prevTerapistaHabilidad == $terapistaHabilidad) {
      $this->sendResponse(["mensaje" => "No se realizaron cambios"], 200);
      return;
    }

    $result = $terapistasHabilidadesDb->actualizarTerapistaHabilidad($id, $terapistaHabilidad);

    $response = $result ? [
      "mensaje" => "Terapista Habilidad actualizada correctamente",
      "resultado" => $terapistasHabilidadesDb->obtenerTerapistaHabilidad($id)
    ] : ["mensaje" => "Error al actualizar la Terapista Habilidad"];
    $code = $result ? 200 : 400;

    $this->sendResponse($response, $code);
  }

  public function delete($id)
  {
    $terapistasHabilidadesDb = new TerapistasHabilidadesDb();
    $prevTerapistaHabilidad = $terapistasHabilidadesDb->obtenerTerapistaHabilidad($id);

    // comprobar que la terapista exista
    if (!$prevTerapistaHabilidad) {
      $this->sendResponse(["mensaje" => "Terapista Habilidad no encontrada"], 404);
      return;
    }

    $result = $terapistasHabilidadesDb->eliminarTerapistaHabilidad($id);

    $response = $result ? [
      "mensaje" => "Terapista Habilidad eliminada correctamente",
      "resultado" => $prevTerapistaHabilidad
    ] : ["mensaje" => "Error al eliminar la Terapista Habilidad"];
    $code = $result ? 200 : 400;

    $this->sendResponse($response, $code);
  }
}

try {
  $controller = new TerapistasHabilidadesController();
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