<?php
require_once __DIR__ . "/../../inc/bootstrap.php";
require_once PROJECT_ROOT_PATH . "/controllers/BaseController.php";

require_once PROJECT_ROOT_PATH . "/models/ReservasHabitacionesDb.php";

class ReservasHabitacionesController extends BaseController
{
  public function get()
  {
    $params = $this->getParams();
    $nroDocumento = $params['nro_reserva'] ?? null;
    $nroHabitacion = $params['nro_habitacion'] ?? null;

    if ($nroDocumento) {
      $reservasHabitacionesDb = new ReservasHabitacionesDb();
      $result = $reservasHabitacionesDb->buscarPorNroReserva($nroDocumento);

      if (!$result || count($result) === 0) {
        $this->sendResponse(["mensaje" => "No se encontraron reservas con el código proporcionado."], 404);
        return;
      }
    }

    if ($nroHabitacion) {
      $reservasHabitacionesDb = new ReservasHabitacionesDb();
      $result = $reservasHabitacionesDb->buscarPorNroHabitacion($nroHabitacion);

      if (!$result || count($result) === 0) {
        $this->sendResponse(["mensaje" => "No se encontraron reservas con el código proporcionado."], 404);
        return;
      }
    }

    $reservasHabitacionesDb = new ReservasHabitacionesDb();
    $result = $reservasHabitacionesDb->listarReservasHabitaciones();

    $this->sendResponse($result, 200);
  }

  public function getOne($id)
  {
    $reservasHabitacionesDb = new ReservasHabitacionesDb();
    $reservaHabitacion = $reservasHabitacionesDb->obtenerReservaHabitacion($id);

    $response = $reservaHabitacion ? $reservaHabitacion : ["mensaje" => "Reserva Habitacion no encontrada"];
    $code = $reservaHabitacion ? 200 : 404;

    $this->sendResponse($response, $code);
  }

  public function create()
  {
    $reservaHabitacionDelBody = $this->getBody();
    $reservaHabitacion = $this->mapJsonToClass($reservaHabitacionDelBody, ReservaHabitacion::class);

    $reservasHabitacionesDb = new ReservasHabitacionesDb();
    $id = $reservasHabitacionesDb->crearReservaHabitacion($reservaHabitacion);

    $response = $id ? [
      "mensaje" => "Reserva Habitacion creada correctamente",
      "resultado" => array_merge([$reservasHabitacionesDb->idName => intval($id)], (array) $reservaHabitacionDelBody)
    ] : ["mensaje" => "Error al crear la ReservaHabitacion"];
    $code = $id ? 201 : 400;

    $this->sendResponse($response, $code);
  }

  public function update($id)
  {
    $reservaHabitacionDelBody = $this->getBody();
    $reservaHabitacion = $this->mapJsonToClass($reservaHabitacionDelBody, ReservaHabitacion::class);

    $reservasHabitacionesDb = new ReservasHabitacionesDb();

    $prevReservaHabitacion = $reservasHabitacionesDb->obtenerReservaHabitacion($id);
    unset($prevReservaHabitacion->id_reservaHabitacion);

    // comprobar que la reservaHabitacion exista
    if (!$prevReservaHabitacion) {
      $this->sendResponse(["mensaje" => "Reserva Habitacion no encontrada"], 404);
      return;
    }

    // si los datos son iguales, no se hace nada
    if ($this->compararObjetoActualizar($reservaHabitacion, $prevReservaHabitacion)) {
      $this->sendResponse(["mensaje" => "No se realizaron cambios"], 200);
      return;
    }

    $result = $reservasHabitacionesDb->actualizarReservaHabitacion($id, $reservaHabitacion);

    $response = $result ? [
      "mensaje" => "Reserva Habitacion actualizada correctamente",
      "resultado" => $reservasHabitacionesDb->obtenerReservaHabitacion($id)
    ] : ["mensaje" => "Error al actualizar la Reserva Habitacion"];
    $code = $result ? 200 : 400;

    $this->sendResponse($response, $code);
  }

  public function delete($id)
  {
    $reservasHabitacionesDb = new ReservasHabitacionesDb();
    $prevReservaHabitacion = $reservasHabitacionesDb->obtenerReservaHabitacion($id);

    // comprobar que la reservaHabitacion exista
    if (!$prevReservaHabitacion) {
      $this->sendResponse(["mensaje" => "Reserva Habitacion no encontrada"], 404);
      return;
    }

    $result = $reservasHabitacionesDb->eliminarReservaHabitacion($id);

    $response = $result ? [
      "mensaje" => "Reserva Habitacion eliminada correctamente",
      "resultado" => $prevReservaHabitacion
    ] : ["mensaje" => "Error al eliminar la ReservaHabitacion"];
    $code = $result ? 200 : 400;

    $this->sendResponse($response, $code);
  }
}

try {
  $controller = new ReservasHabitacionesController();
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