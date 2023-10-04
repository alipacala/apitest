<?php
require_once __DIR__ . "/../../inc/bootstrap.php";
require_once PROJECT_ROOT_PATH . "/controllers/BaseController.php";

require_once PROJECT_ROOT_PATH . "/models/HabitacionesDb.php";

class HabitacionesController extends BaseController
{
  public function get()
  {
    $params = $this->getParams();
    $deHotelArenasSpa = isset($params['de_hotel_arenas_spa']);

    $habitacionesDb = new HabitacionesDb();

    if ($deHotelArenasSpa) {
      $result = $habitacionesDb->listarHabitacionesDeHotelArenasSpa();
      $this->sendResponse($result, 200);
      
      return;
    }

    $result = $habitacionesDb->listarHabitaciones();

    $this->sendResponse($result, 200);
  }

  public function getOne($id)
  {
    $habitacionesDb = new HabitacionesDb();
    $habitacion = $habitacionesDb->obtenerHabitacion($id);

    $response = $habitacion ? $habitacion : ["mensaje" => "Habitacion no encontrada"];
    $code = $habitacion ? 200 : 404;

    $this->sendResponse($response, $code);
  }

  public function create()
  {
    $habitacionDelBody = $this->getBody();
    $habitacion = $this->mapJsonToClass($habitacionDelBody, Habitacion::class);

    $habitacionesDb = new HabitacionesDb();
    $id = $habitacionesDb->crearHabitacion($habitacion);

    $response = $id ? [
      "mensaje" => "Habitacion creada correctamente",
      "resultado" => array_merge([$habitacionesDb->idName => intval($id)], (array) $habitacionDelBody)
    ] : ["mensaje" => "Error al crear la Habitacion"];
    $code = $id ? 201 : 400;

    $this->sendResponse($response, $code);
  }

  public function update($id)
  {
    $habitacionDelBody = $this->getBody();
    $habitacion = $this->mapJsonToClass($habitacionDelBody, Habitacion::class);

    $habitacionesDb = new HabitacionesDb();

    $prevHabitacion = $habitacionesDb->obtenerHabitacion($id);
    unset($prevHabitacion->id_habitacion);

    // comprobar que la habitacion exista
    if (!$prevHabitacion) {
      $this->sendResponse(["mensaje" => "Habitacion no encontrada"], 404);
      return;
    }

    // si los datos son iguales, no se hace nada
    if ($this->compararObjetoActualizar($habitacion, $prevHabitacion)) {
      $this->sendResponse(["mensaje" => "No se realizaron cambios"], 200);
      return;
    }

    $result = $habitacionesDb->actualizarHabitacion($id, $habitacion);

    $response = $result ? [
      "mensaje" => "Habitacion actualizada correctamente",
      "resultado" => $habitacionesDb->obtenerHabitacion($id)
    ] : ["mensaje" => "Error al actualizar la Habitacion"];
    $code = $result ? 200 : 400;

    $this->sendResponse($response, $code);
  }

  public function delete($id)
  {
    $habitacionesDb = new HabitacionesDb();
    $prevHabitacion = $habitacionesDb->obtenerHabitacion($id);

    // comprobar que la habitacion exista
    if (!$prevHabitacion) {
      $this->sendResponse(["mensaje" => "Habitacion no encontrada"], 404);
      return;
    }

    $result = $habitacionesDb->eliminarHabitacion($id);

    $response = $result ? [
      "mensaje" => "Habitacion eliminada correctamente",
      "resultado" => $prevHabitacion
    ] : ["mensaje" => "Error al eliminar la Habitacion"];
    $code = $result ? 200 : 400;

    $this->sendResponse($response, $code);
  }
}

try {
  $controller = new HabitacionesController();
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