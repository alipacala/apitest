<?php
require_once __DIR__ . "/../../inc/bootstrap.php";
require_once PROJECT_ROOT_PATH . "/controllers/BaseController.php";

require_once PROJECT_ROOT_PATH . "/models/RoomingDb.php";

class RoomingController extends BaseController
{
  public function get()
  {
    $params = $this->getParams();
    $nroRegistroMaestro = $params['nro_registro_maestro'] ?? null;
    $idCheckin = $params['id_checkin'] ?? null;

    $conDatos = isset($params['con-datos']);
    $fecha = $params['fecha'] ?? null;

    $roomingDb = new RoomingDb();

    $result = null;
    
    if ($conDatos) {
      $result = $roomingDb->listarRoomingConDatos($fecha);
    }
    if ($nroRegistroMaestro) {
      $result = $roomingDb->buscarPorNroRegistroMaestro($nroRegistroMaestro);
    }
    if ($idCheckin) {
      $result = $roomingDb->buscarPorIdCheckin($idCheckin);
    }
    if (count($params) === 0) {
      $result = $roomingDb->listarRooming();
    }

    $this->sendResponse($result, 200);
  }

  public function getOne($id)
  {
    $roomingDb = new RoomingDb();
    $rooming = $roomingDb->obtenerRooming($id);

    $response = $rooming ? $rooming : ["mensaje" => "Rooming no encontrada"];
    $code = $rooming ? 200 : 404;

    $this->sendResponse($response, $code);
  }

  public function create()
  {
    $roomingDelBody = $this->getBody();
    $rooming = new Rooming();
    $this->mapJsonToObj($roomingDelBody, $rooming);

    $roomingDb = new RoomingDb();
    $id = $roomingDb->crearRooming($rooming);

    $response = $id ? [
      "mensaje" => "Rooming creada correctamente",
      "resultado" => array_merge([$roomingDb->idName => intval($id)], (array) $roomingDelBody)
    ] : ["mensaje" => "Error al crear la Rooming"];
    $code = $id ? 201 : 400;

    $this->sendResponse($response, $code);
  }

  public function update($id)
  {
    $roomingDelBody = $this->getBody();
    $rooming = new Rooming();
    $this->mapJsonToObj($roomingDelBody, $rooming);

    $roomingDb = new RoomingDb();

    $prevRooming = $roomingDb->obtenerRooming($id);
    unset($prevRooming->id_rooming);

    // comprobar que la rooming exista
    if (!$prevRooming) {
      $this->sendResponse(["mensaje" => "Rooming no encontrada"], 404);
      return;
    }

    // si los datos son iguales, no se hace nada
    if ($this->compararObjetoActualizar($rooming, $prevRooming)) {
      $this->sendResponse(["mensaje" => "No se realizaron cambios"], 200);
      return;
    }

    $result = $roomingDb->actualizarRooming($id, $rooming);

    $response = $result ? [
      "mensaje" => "Rooming actualizada correctamente",
      "resultado" => $roomingDb->obtenerRooming($id)
    ] : ["mensaje" => "Error al actualizar la Rooming"];
    $code = $result ? 200 : 400;

    $this->sendResponse($response, $code);
  }

  public function delete($id)
  {
    $roomingDb = new RoomingDb();
    $prevRooming = $roomingDb->obtenerRooming($id);

    // comprobar que la rooming exista
    if (!$prevRooming) {
      $this->sendResponse(["mensaje" => "Rooming no encontrada"], 404);
      return;
    }

    $result = $roomingDb->eliminarRooming($id);

    $response = $result ? [
      "mensaje" => "Rooming eliminada correctamente",
      "resultado" => $prevRooming
    ] : ["mensaje" => "Error al eliminar la Rooming"];
    $code = $result ? 200 : 400;

    $this->sendResponse($response, $code);
  }
}

try {
  $controller = new RoomingController();
  $controller->route();
} catch (Exception $e) {
  $controller->sendResponse($controller->errorResponse($e), 500);
}
?>