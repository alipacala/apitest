<?php
require_once __DIR__ . "/../../inc/bootstrap.php";
require_once PROJECT_ROOT_PATH . "/controllers/BaseController.php";

require_once PROJECT_ROOT_PATH . "/models/RecibosPagoDb.php";
require_once PROJECT_ROOT_PATH . "/models/ConfigDb.php";
require_once PROJECT_ROOT_PATH . "/models/ComprobantesVentasDb.php";
require_once PROJECT_ROOT_PATH . "/models/DocumentosDetallesDb.php";
require_once PROJECT_ROOT_PATH . "/models/UsuariosDb.php";

class RecibosPagoController extends BaseController
{
  public function get()
  {
    $recibosPagoDb = new RecibosPagoDb();
    $result = $recibosPagoDb->listarRecibosPago();

    $this->sendResponse($result, 200);
  }

  public function getOne($id)
  {
    $recibosPagoDb = new RecibosPagoDb();
    $reciboPago = $recibosPagoDb->obtenerReciboPago($id);

    $response = $reciboPago ? $reciboPago : ["mensaje" => "ReciboPago no encontrada"];
    $code = $reciboPago ? 200 : 404;

    $this->sendResponse($response, $code);
  }

  public function create()
  {
    $reciboPagoDelBody = $this->getBody();

    $esCompra = isset($reciboPagoDelBody->esCompra);

    $reciboPago = new ReciboPago();
    $this->mapJsonToObj($reciboPagoDelBody, $reciboPago);

    $camposRequeridos = ["id_comprobante_ventas", "medio_pago", "total", "id_usuario"];
    $camposFaltantes = $this->comprobarCamposRequeridos($camposRequeridos, $reciboPago);

    if (count($camposFaltantes) > 0) {
      $this->sendResponse(["mensaje" => "Faltan los siguientes campos: " . implode(", ", $camposFaltantes)], 400);
      return;
    }

    $reciboPago->tipo_movimiento = $esCompra ? "IN" : "SA";

    $recibosPagoDb = new RecibosPagoDb();
    $usuariosDb = new UsuariosDb();

    try {
      $recibosPagoDb->empezarTransaccion();

      $usuario = $usuariosDb->obtenerUsuario($reciboPago->id_usuario);
      if (!$usuario) {
        throw new Exception("El usuario no existe");
      }
      $reciboPago->id_unidad_de_negocio = $usuario->id_unidad_de_negocio;

      $configDb = new ConfigDb();
      $serieReciboPago = $configDb->obtenerConfig(18)->numero_correlativo;
      $corrReciboPago = $configDb->obtenerConfig(19)->numero_correlativo;
      $reciboPago->nro_recibo = "RE" . str_pad($serieReciboPago, 2, "0", STR_PAD_LEFT) . "-" . str_pad($corrReciboPago, 6, "0", STR_PAD_LEFT);

      $reciboPago->nro_de_caja = $esCompra ? $reciboPago->nro_de_caja : 1;
      $reciboPago->moneda = "PEN";
      $reciboPago->fecha = $recibosPagoDb->obtenerFechaYHora()["fecha_y_hora"];
      $reciboPago->fecha_hora_registro = $recibosPagoDb->obtenerFechaYHora()["fecha_y_hora"];

      $recibosPagoDb = new RecibosPagoDb();
      $id = $recibosPagoDb->crearReciboPago($reciboPago);

      $comprobantesVentasDb = new ComprobantesVentasDb();
      $comprobanteVenta = $comprobantesVentasDb->obtenerComprobanteVentas($reciboPago->id_comprobante_ventas);

      if ($comprobanteVenta->por_pagar == 0) {
        throw new Exception("El comprobante de venta ya fue pagado");
      }

      if ($comprobanteVenta->por_pagar < $reciboPago->total) {
        throw new Exception("El monto a pagar es mayor al monto pendiente del comprobante de venta");
      }

      $comprobantesVentasDb->pagar($reciboPago->id_comprobante_ventas, $reciboPago->total);

      // actualizar el id_recibo_de_pago de los documentos detalles
      $nroComprobante = $comprobanteVenta->nro_comprobante;

      $documentosDetallesDb = new DocumentosDetallesDb();
      $documentosDetalles = $documentosDetallesDb->buscarPorNroComprobanteVenta($nroComprobante);

      foreach ($documentosDetalles as $documentoDetalle) {
        $documentoDetalleActualizar = new DocumentoDetalle();
        $documentoDetalleActualizar->id_recibo_de_pago = $id;
        $documentosDetallesDb->actualizarDocumentoDetalle($documentoDetalle->id_documentos_detalle, $documentoDetalleActualizar);
      }

      $configDb->incrementarCorrelativo(19);

      $recibosPagoDb->terminarTransaccion();
    } catch (Exception $e) {
      $recibosPagoDb->cancelarTransaccion();
      throw $e;
    }

    $response = $id ? [
      "mensaje" => "ReciboPago creada correctamente",
      "resultado" => array_merge([$recibosPagoDb->idName => intval($id)], (array) $reciboPagoDelBody)
    ] : ["mensaje" => "Error al crear la ReciboPago"];
    $code = $id ? 201 : 400;

    $this->sendResponse($response, $code);
  }

  public function update($id)
  {
    $reciboPagoDelBody = $this->getBody();
    $reciboPago = new ReciboPago();
    $this->mapJsonToObj($reciboPagoDelBody, $reciboPago);

    $recibosPagoDb = new RecibosPagoDb();

    $prevReciboPago = $recibosPagoDb->obtenerReciboPago($id);
    unset($prevReciboPago->id_reciboPago);

    // comprobar que la reciboPago exista
    if (!$prevReciboPago) {
      $this->sendResponse(["mensaje" => "ReciboPago no encontrada"], 404);
      return;
    }

    // si los datos son iguales, no se hace nada
    if ($this->compararObjetoActualizar($reciboPago, $prevReciboPago)) {
      $this->sendResponse(["mensaje" => "No se realizaron cambios"], 200);
      return;
    }

    $result = $recibosPagoDb->actualizarReciboPago($id, $reciboPago);

    $response = $result ? [
      "mensaje" => "ReciboPago actualizada correctamente",
      "resultado" => $recibosPagoDb->obtenerReciboPago($id)
    ] : ["mensaje" => "Error al actualizar la ReciboPago"];
    $code = $result ? 200 : 400;

    $this->sendResponse($response, $code);
  }

  function updatePartial($id, $action = null)
  {
    if ($action == 'cerrar-turno') {
      $recibosPagoDb = new RecibosPagoDb();
      $configDb = new ConfigDb();

      $nroCierreTurno = $configDb->obtenerConfig(21)->numero_correlativo;

      // comprobar que el turno no haya sido cerrado
      $result = $recibosPagoDb->comprobarTurnoCerrado();
      if ($result[0]["cantidad"] <= 0) {
        $this->sendResponse(["mensaje" => "El turno ya fue cerrado"], 400);
        return;
      }

      $result = $recibosPagoDb->cerrarTurno($nroCierreTurno);

      $response = $result ? [
        "mensaje" => "Recibos de pago actualizados correctamente"
      ] : ["mensaje" => "Error al actualizar los recibos de pago"];
      $code = $result ? 200 : 400;

      $configDb->incrementarCorrelativo(21);

      $this->sendResponse($response, $code);
    } else {
      $this->sendResponse(["mensaje" => "Acción no implementada"], 501);
    }
  }

  public function delete($id)
  {
    $recibosPagoDb = new RecibosPagoDb();
    $prevReciboPago = $recibosPagoDb->obtenerReciboPago($id);

    // comprobar que la reciboPago exista
    if (!$prevReciboPago) {
      $this->sendResponse(["mensaje" => "ReciboPago no encontrada"], 404);
      return;
    }

    $result = $recibosPagoDb->eliminarReciboPago($id);

    $response = $result ? [
      "mensaje" => "ReciboPago eliminada correctamente",
      "resultado" => $prevReciboPago
    ] : ["mensaje" => "Error al eliminar la ReciboPago"];
    $code = $result ? 200 : 400;

    $this->sendResponse($response, $code);
  }
}

try {
  $controller = new RecibosPagoController();
  $controller->route();
} catch (Exception $e) {
  $controller->sendResponse($controller->errorResponse($e), 500);
}
?>