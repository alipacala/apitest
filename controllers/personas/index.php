<?php
require_once __DIR__ . "/../../inc/bootstrap.php";
require_once PROJECT_ROOT_PATH . "/controllers/BaseController.php";

require_once PROJECT_ROOT_PATH . "/models/PersonasDb.php";

class PersonasController extends BaseController
{
  public function get()
  {
    $params = $this->getParams();
    $dni = $params['dni'] ?? null;

    $personasDb = new PersonasDb();
    $result = $personasDb->listarPersonas($dni);

    $this->sendResponse($result, 200);
  }

  public function getOne($id)
  {
    $personasDb = new PersonasDb();
    $persona = $personasDb->obtenerPersona($id);

    $response = $persona ? $persona : ["mensaje" => "Persona no encontrada"];
    $code = $persona ? 200 : 404;

    $this->sendResponse($response, $code);
  }

  public function create()
  {
    $personaDelBody = $this->getBody();
    $persona = $this->mapJsonToClass($personaDelBody, Persona::class);

    $personasDb = new PersonasDb();
    $id = $personasDb->crearPersona($persona);

    $response = $id ? [
      "mensaje" => "Persona creada correctamente",
      "resultado" => array_merge([$personasDb->idName => intval($id)], (array) $personaDelBody)
    ] : ["mensaje" => "Error al crear la Persona"];
    $code = $id ? 201 : 400;

    $this->sendResponse($response, $code);
  }

  public function update($id)
  {
    $personaDelBody = $this->getBody();
    $persona = $this->mapJsonToClass($personaDelBody, Persona::class);

    $personasDb = new PersonasDb();

    $prevPersona = $personasDb->obtenerPersona($id);
    unset($prevPersona->id_persona);

    // comprobar que la persona exista
    if (!$prevPersona) {
      $this->sendResponse(["mensaje" => "Persona no encontrada"], 404);
      return;
    }

    // si los datos son iguales, no se hace nada
    if ($prevPersona == $persona) {
      $this->sendResponse(["mensaje" => "No se realizaron cambios"], 200);
      return;
    }

    $result = $personasDb->actualizarPersona($id, $persona);

    $response = $result ? [
      "mensaje" => "Persona actualizada correctamente",
      "resultado" => $personasDb->obtenerPersona($id)
    ] : ["mensaje" => "Error al actualizar la Persona"];
    $code = $result ? 200 : 400;

    $this->sendResponse($response, $code);
  }

  public function delete($id)
  {
    $personasDb = new PersonasDb();
    $prevPersona = $personasDb->obtenerPersona($id);

    // comprobar que la persona exista
    if (!$prevPersona) {
      $this->sendResponse(["mensaje" => "Persona no encontrada"], 404);
      return;
    }

    $result = $personasDb->eliminarPersona($id);

    $response = $result ? [
      "mensaje" => "Persona eliminada correctamente",
      "resultado" => $prevPersona
    ] : ["mensaje" => "Error al eliminar la Persona"];
    $code = $result ? 200 : 400;

    $this->sendResponse($response, $code);
  }
}

try {
  $controller = new PersonasController();
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