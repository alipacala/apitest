<?php
require_once __DIR__ . "/../../inc/bootstrap.php";
require_once PROJECT_ROOT_PATH . "/controllers/BaseController.php";

require_once PROJECT_ROOT_PATH . "/models/ReservasDb.php";
require_once PROJECT_ROOT_PATH . "/models/ConfigDb.php";
require_once PROJECT_ROOT_PATH . "/models/ReservasHabitacionesDb.php";

class ReservasController extends BaseController
{
  public function get()
  {
    $params = $this->getParams();
    $conCantidadHabitaciones = isset($params['con_cantidad_habitaciones']);

    $reservasDb = new ReservasDb();

    if ($conCantidadHabitaciones) {
      $result = $reservasDb->listarConCantidadHabitaciones();
    }
    if (count($params) === 0) {
      $result = $reservasDb->listarReservas();
    }

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
    $body = $this->getBody();

    $reserva = new Reserva();
    $this->mapJsonToObj($body->reserva, $reserva);

    $habitaciones = $body->habitaciones;

    $reserva->id_unidad_de_negocio = 3;
    $reserva->fecha_pago = date("Y-m-d");
    $reserva->estado_pago = 0;

    // calcular el monto total
    $reserva->monto_total = 0;
    foreach ($habitaciones as $habitacion) {
      $habitacion->precio_total = $habitacion->precio_unitario * $habitacion->nro_noches;
      $reserva->monto_total += $habitacion->precio_total;
    }

    $reserva->adelanto = $reserva->monto_total * $reserva->porcentaje_pago / 100;
    $reserva->nro_personas = $reserva->nro_adultos + $reserva->nro_ninos + $reserva->nro_infantes;

    $configDb = new ConfigDb();

    $reserva->nro_reserva = $configDb->obtenerCodigoOGenerar("RESERVA");

    $reservasDb = new ReservasDb();

    try {
      $reservasDb->empezarTransaccion();

      $idReserva = $reservasDb->crearReserva($reserva);

      if (!$idReserva) {
        $this->sendResponse(["mensaje" => "Error al crear la Reserva"], 400);
        return;
      }

      // crear habitaciones
      $reservasHabitacionesDb = new ReservasHabitacionesDb();

      foreach ($habitaciones as $habitacion) {
        $reservaHabitacion = new ReservaHabitacion();
        $this->mapJsonToObj($habitacion, $reservaHabitacion);

        $reservaHabitacion->id_unidad_de_negocio = $reserva->id_unidad_de_negocio;
        $reservaHabitacion->nro_reserva = $reserva->nro_reserva;
        $reservaHabitacion->fecha_ingreso = $reserva->fecha_llegada;
        $reservaHabitacion->fecha_salida = $reserva->fecha_salida;

        $reservasHabitacionesDb->crearReservaHabitacion($reservaHabitacion);
      }

      $codigo = "RE" . date("y");
      $seActualizoConfig = $configDb->actualizarNumeroCorrelativo($codigo);

      if ($idReserva && $seActualizoConfig) {
        $this->sendResponse([
          "mensaje" => "Reserva creada correctamente",
          "resultado" => $reservasDb->obtenerReserva($idReserva)
        ], 201);
      }

      $reservasDb->terminarTransaccion();
    } catch (Exception $e) {
      $reservasDb->cancelarTransaccion();
      throw $e;
    }
  }

  public function update($id)
  {
    $reservaDelBody = $this->getBody();
    $reserva = new Reserva();
    $this->mapJsonToObj($reservaDelBody, $reserva);

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

  public function updatePartial($id, $action = null)
  {
    if ($action == "fechas-observaciones") {
      // el id es 0, por lo cual hay que buscar por nro_reserva
      $reservaDelBody = $this->getBody();
      $reserva = new Reserva();

      $this->mapJsonToObj($reservaDelBody, $reserva);

      // comprobar que la reserva tenga los datos necesarios
      $camposRequeridos = ["fecha_llegada", "fecha_salida", "observaciones_hospedaje", "observaciones_pago", "nro_reserva"];
      $camposFaltantes = $this->comprobarCamposRequeridos($camposRequeridos, $reserva);

      if (count($camposFaltantes) > 0) {
        $this->sendResponse(["mensaje" => "Faltan los siguientes campos: " . implode(", ", $camposFaltantes)], 400);
        return;
      }

      $reservasDb = new ReservasDb();

      $prevReserva = $reservasDb->buscarPorNroReserva($reserva->nro_reserva);

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

      $resultReservaActualizada = $reservasDb->actualizarReserva($prevReserva->id_reserva, $reserva);

      $reservasHabitacionesDb = new ReservasHabitacionesDb();
      $resultHabitacionesActualizadas = $reservasHabitacionesDb->actualizarFechasPorReserva($reserva->nro_reserva, $reserva->fecha_llegada, $reserva->fecha_salida);

      $result = $resultReservaActualizada && $resultHabitacionesActualizadas;

      $response = $result ? [
        "mensaje" => "Reserva actualizada correctamente",
        "resultado" => $reservasDb->obtenerReserva($id)
      ] : ["mensaje" => "Error al actualizar la Reserva"];
      $code = $result ? 200 : 400;

      $this->sendResponse($response, $code);

    } else if ($action == "estado") {
      $body = $this->getBody();
      $idReserva = $body->id_reserva;

      $reservasDb = new ReservasDb();

      $prevReserva = $reservasDb->obtenerReserva($idReserva);

      // comprobar que la reserva exista
      if (!$prevReserva) {
        $this->sendResponse(["mensaje" => "Reserva no encontrada"], 404);
        return;
      }

      $result = $reservasDb->actualizarEstado($idReserva, $body->estado);

      $this->sendResponse([
        "mensaje" => "Reserva actualizada correctamente",
        "resultado" => $reservasDb->obtenerReserva($idReserva)
      ], 200);

    } else {
      $this->sendResponse(["mensaje" => "Acción no encontrada"], 404);
    }
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
  $controller->sendResponse($controller->errorResponse($e), 500);
}
?>