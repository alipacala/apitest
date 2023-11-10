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
    $limite = $params['limite'] ?? null;

    $personasDb = new PersonasDb();

    if ($dni) {
      $result = $personasDb->buscarPorNroDocumento($dni);
    }
    if ($limite) {
      $result = $personasDb->listarPersonas($limite);
    }
    if (count($params) === 0) {
      $result = $personasDb->listarPersonas();
    }

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
    $persona = new Persona();
    $this->mapJsonToObj($personaDelBody, $persona);

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
    $persona = new Persona();
    $this->mapJsonToObj($personaDelBody, $persona);

    $personasDb = new PersonasDb();

    $prevPersona = $personasDb->obtenerPersona($id);
    unset($prevPersona->id_persona);

    // comprobar que la persona exista
    if (!$prevPersona) {
      $this->sendResponse(["mensaje" => "Persona no encontrada"], 404);
      return;
    }

    // si los datos son iguales, no se hace nada
    if ($this->compararObjetoActualizar($persona, $prevPersona)) {
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
  $controller->sendResponse($controller->errorResponse($e), 500);
}
?>