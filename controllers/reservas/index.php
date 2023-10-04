<?php
require_once __DIR__ . "/../../inc/bootstrap.php";
require_once PROJECT_ROOT_PATH . "/controllers/BaseController.php";

require_once PROJECT_ROOT_PATH . "/models/ReservasDb.php";

class ReservasController extends BaseController
{
  public function get()
  {
    $params = $this->getParams();
    $conCantidadHabitaciones = isset($params['con_cantidad_habitaciones']);

    $reservasDb = new ReservasDb();

    if ($conCantidadHabitaciones) {
      $result = $reservasDb->listarConCantidadHabitaciones();
      $this->sendResponse($result, 200);
      return;
    }

    $result = $reservasDb->listarReservas();

    $this->sendResponse($result, 200);
  }

  public function getOne($id)
  {
    $reservasDb = new ReservasDb();
    $reserva = $reservasDb->obtenerReserva($id);

    $response = $reserva ? $reserva : ["mensaje" => "Reserva no encontrada"];
    $code = $reserva ? 200 : 404;

    $this->sendResponse($response, $code);
  }

  public function create()
  {
    $reservaDelBody = $this->getBody();
    $reserva = $this->mapJsonToClass($reservaDelBody, Reserva::class);

    // TODO: aqui me quede

    $reservasDb = new ReservasDb();
    $id = $reservasDb->crearReserva($reserva);

    $response = $id ? [
      "mensaje" => "Reserva creada correctamente",
      "resultado" => array_merge([$reservasDb->idName => intval($id)], (array) $reservaDelBody)
    ] : ["mensaje" => "Error al crear la Reserva"];
    $code = $id ? 201 : 400;

    $this->sendResponse($response, $code);
  }

  public function update($id)
  {
    $reservaDelBody = $this->getBody();
    $reserva = $this->mapJsonToClass($reservaDelBody, Reserva::class);

    $reservasDb = new ReservasDb();

    $prevReserva = $reservasDb->obtenerReserva($id);
    unset($prevReserva->id_reserva);

    // comprobar que la reserva exista
    if (!$prevReserva) {
      $this->sendResponse(["mensaje" => "Reserva no encontrada"], 404);
      return;
    }

    // si los datos son iguales, no se hace nada
    if ($this->compararObjetoActualizar($reserva, $prevReserva)) {
      $this->sendResponse(["mensaje" => "No se realizaron cambios"], 200);
      return;
    }

    $result = $reservasDb->actualizarReserva($id, $reserva);

    $response = $result ? [
      "mensaje" => "Reserva actualizada correctamente",
      "resultado" => $reservasDb->obtenerReserva($id)
    ] : ["mensaje" => "Error al actualizar la Reserva"];
    $code = $result ? 200 : 400;

    $this->sendResponse($response, $code);
  }

  public function delete($id)
  {
    $reservasDb = new ReservasDb();
    $prevReserva = $reservasDb->obtenerReserva($id);

    // comprobar que la reserva exista
    if (!$prevReserva) {
      $this->sendResponse(["mensaje" => "Reserva no encontrada"], 404);
      return;
    }

    $result = $reservasDb->eliminarReserva($id);

    $response = $result ? [
      "mensaje" => "Reserva eliminada correctamente",
      "resultado" => $prevReserva
    ] : ["mensaje" => "Error al eliminar la Reserva"];
    $code = $result ? 200 : 400;

    $this->sendResponse($response, $code);
  }
}

try {
  $controller = new ReservasController();
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