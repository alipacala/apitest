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
    $reservaDelBody = $this->getBody();
    $reserva = new Reserva();
    $this->mapJsonToObj($reservaDelBody, $reserva);

    $reserva->id_unidad_de_negocio = 3;
    $reserva->nro_registro_maestro = null;
    $reserva->fecha_pago = date("Y-m-d");
    $reserva->estado_pago = 0;

    $reservasDb = new ReservasDb();
    $configDb = new ConfigDb();

    try {
      $reservasDb->empezarTransaccion();

      $id = $reservasDb->crearReserva($reserva);

      if (!$id) {
        $this->sendResponse(["mensaje" => "Error al crear la Reserva"], 400);
        return;
      }

      // actualizar config de reservas
      $codigo = "RE" . date("y");
      $seActualizoConfig = $configDb->actualizarNumeroCorrelativoReserva($codigo);

      if (!$seActualizoConfig) {
        $this->sendResponse(["mensaje" => "Error al actualizar el número correlativo de la Reserva"], 400);
        return;
      }

      if ($id && $seActualizoConfig) {
        $this->sendResponse([
          "mensaje" => "Reserva creada correctamente",
          "resultado" => $reservasDb->obtenerReserva($id)
        ], 201);
        return;
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

      $result = $reservasDb->actualizarReserva($id, $reserva);

      $response = $result ? [
        "mensaje" => "Reserva actualizada correctamente",
        "resultado" => $reservasDb->obtenerReserva($id)
      ] : ["mensaje" => "Error al actualizar la Reserva"];
      $code = $result ? 200 : 400;

      $this->sendResponse($response, $code);
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