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
    
    $reservasHabitacionesDb = new ReservasHabitacionesDb();

    if ($nroDocumento) {
      $result = $reservasHabitacionesDb->buscarPorNroReserva($nroDocumento);

      if (!$result || count($result) === 0) {
        $this->sendResponse(["mensaje" => "No se encontraron reservas con el código proporcionado."], 404);
        return;
      }
    }
    if ($nroHabitacion) {
      $result = $reservasHabitacionesDb->buscarPorNroHabitacion($nroHabitacion);
    }
    if (count($params) === 0) {
      $result = $reservasHabitacionesDb->listarReservasHabitaciones();
    }

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
    $reservaHabitacion = new ReservaHabitacion();
    $this->mapJsonToObj($reservaHabitacionDelBody, $reservaHabitacion);

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
    $reservaHabitacion = new ReservaHabitacion();
    $this->mapJsonToObj($reservaHabitacionDelBody, $reservaHabitacion);

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

  public function deleteCustom($id, $action)
  {
    if ($action == 'reserva') {
      $body = $this->getBody();
      $idsReservaHabitaciones = $body["id"];

      $reservasHabitacionesDb = new ReservasHabitacionesDb();

      $reservaHabitacionesEliminadas = [];
      foreach ($idsReservaHabitaciones as $id) {
        $prevReservaHabitacion = $reservasHabitacionesDb->obtenerReservaHabitacion($id);

        // comprobar que la reservaHabitacion exista
        if (!$prevReservaHabitacion) {
          $this->sendResponse(["mensaje" => "Reserva Habitacion no encontrada"], 404);
          return;
        }

        $reservasHabitacionesDb->eliminarReservaHabitacion($id);
        $reservaHabitacionesEliminadas[] = $prevReservaHabitacion;
      }

      $this->sendResponse([
        "mensaje" => "Reserva Habitaciones eliminadas correctamente",
        "resultado" => $reservaHabitacionesEliminadas
      ], 200);

    } else {
      $this->sendResponse(["mensaje" => "Acción no encontrada"], 404);
    }
  }
}

try {
  $controller = new ReservasHabitacionesController();
  $controller->route();
} catch (Exception $e) {
  $controller->sendResponse($controller->errorResponse($e), 500);
}
?>