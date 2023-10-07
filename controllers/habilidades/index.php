<?php
require_once __DIR__ . "/../../inc/bootstrap.php";
require_once PROJECT_ROOT_PATH . "/controllers/BaseController.php";

require_once PROJECT_ROOT_PATH . "/models/HabilidadesProfesionalesDb.php";

class HabilidadesProfesionalesController extends BaseController
{
  public function get()
  {
    $habilidadesProfesionalesDb = new HabilidadesProfesionalesDb();
    $result = $habilidadesProfesionalesDb->listarHabilidadesProfesionales();

    $this->sendResponse($result, 200);
  }

  public function getOne($id)
  {
    $habilidadesProfesionalesDb = new HabilidadesProfesionalesDb();
    $habilidadProfesional = $habilidadesProfesionalesDb->obtenerHabilidadProfesional($id);

    $response = $habilidadProfesional ? $habilidadProfesional : ["mensaje" => "Habilidad Profesional no encontrada"];
    $code = $habilidadProfesional ? 200 : 404;

    $this->sendResponse($response, $code);
  }

  public function create()
  {
    $habilidadProfesionalDelBody = $this->getBody();
    $habilidadProfesional = new HabilidadProfesional();
    $this->mapJsonToObj($habilidadProfesionalDelBody, $habilidadProfesional);

    $habilidadesProfesionalesDb = new HabilidadesProfesionalesDb();
    $id = $habilidadesProfesionalesDb->crearHabilidadProfesional($habilidadProfesional);

    $response = $id ? [
      "mensaje" => "Habilidad Profesional creada correctamente",
      "resultado" => array_merge([$habilidadesProfesionalesDb->idName => intval($id)], (array) $habilidadProfesionalDelBody)
    ] : ["mensaje" => "Error al crear la Habilidad Profesional"];
    $code = $id ? 201 : 400;

    $this->sendResponse($response, $code);
  }

  public function update($id)
  {
    $habilidadProfesionalDelBody = $this->getBody();
    $habilidadProfesional = new HabilidadProfesional();
    $this->mapJsonToObj($habilidadProfesionalDelBody, $habilidadProfesional);

    $habilidadesProfesionalesDb = new HabilidadesProfesionalesDb();

    $prevHabilidadProfesional = $habilidadesProfesionalesDb->obtenerHabilidadProfesional($id);
    unset($prevHabilidadProfesional->id_habilidad);

    // comprobar que la habilidad profesional exista
    if (!$prevHabilidadProfesional) {
      $this->sendResponse(["mensaje" => "Habilidad Profesional no encontrada"], 404);
      return;
    }

    // si los datos son iguales, no se hace nada
    if ($this->compararObjetoActualizar($habilidadProfesional, $prevHabilidadProfesional)) {
      $this->sendResponse(["mensaje" => "No se realizaron cambios"], 200);
      return;
    }

    $result = $habilidadesProfesionalesDb->actualizarHabilidadProfesional($id, $habilidadProfesional);

    $response = $result ? [
      "mensaje" => "Habilidad Profesional actualizada correctamente",
      "resultado" => $habilidadesProfesionalesDb->obtenerHabilidadProfesional($id)
    ] : ["mensaje" => "Error al actualizar la Habilidad Profesional"];
    $code = $result ? 200 : 400;

    $this->sendResponse($response, $code);
  }

  public function delete($id)
  {
    $habilidadesProfesionalesDb = new HabilidadesProfesionalesDb();
    $prevHabilidadProfesional = $habilidadesProfesionalesDb->obtenerHabilidadProfesional($id);

    // comprobar que la habilidad profesional exista
    if (!$prevHabilidadProfesional) {
      $this->sendResponse(["mensaje" => "Habilidad Profesional no encontrada"], 404);
      return;
    }

    $result = $habilidadesProfesionalesDb->eliminarHabilidadProfesional($id);

    $response = $result ? [
      "mensaje" => "Habilidad Profesional eliminada correctamente",
      "resultado" => $prevHabilidadProfesional
    ] : ["mensaje" => "Error al eliminar la Habilidad Profesional"];
    $code = $result ? 200 : 400;

    $this->sendResponse($response, $code);
  }
}

try {
  $controller = new HabilidadesProfesionalesController();
  $controller->route();
} catch (Exception $e) {
  $controller->sendResponse($controller->errorResponse($e), 500);
}
?>