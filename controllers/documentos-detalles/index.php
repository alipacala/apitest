<?php
require_once __DIR__ . "/../../inc/bootstrap.php";
require_once PROJECT_ROOT_PATH . "/controllers/BaseController.php";

require_once PROJECT_ROOT_PATH . "/models/DocumentosDetallesDb.php";
require_once PROJECT_ROOT_PATH . "/models/RecibosPagoDb.php";
require_once PROJECT_ROOT_PATH . "/models/ProductosDb.php";

class DocumentosDetallesController extends BaseController
{
  public function get()
  {
    $params = $this->getParams();
    $nroRegistroMaestro = $params['nro_registro_maestro'] ?? null;
    $nroComprobanteVenta = $params['nro_comprobante_venta'] ?? null;

    $kardex = isset($params['kardex']);
    $fechaInicio = $params['fecha_inicio'] ?? null;
    $fechaFin = $params['fecha_fin'] ?? null;
    $idProducto = $params['id_producto'] ?? null;

    $documentoMovimiento = $params['documento_movimiento'] ?? null;

    $servicios = isset($params['servicios']);
    $fecha = $params['fecha'] ?? null;

    $liquidacion = isset($params['liquidacion']);
    $idProfesional = $params['id_profesional'] ?? null;

    $serviciosTerapista = isset($params['servicios-terapista']);

    $documentosDetallesDb = new DocumentosDetallesDb();

    if ($nroRegistroMaestro) {
      $result = $documentosDetallesDb->buscarPorNroRegistroMaestro($nroRegistroMaestro);

      $result = array_map(function ($documentoDetalle) {
        $recibosPagoDb = new RecibosPagoDb();
        $reciboPago = $recibosPagoDb->obtenerReciboPago($documentoDetalle->id_recibo_de_pago);

        $documentoDetalle->fecha_pago = $reciboPago ? date_format(date_create($reciboPago->fecha), "d/m H:i") : "";
        return $documentoDetalle;
      }, $result);
    }
    if ($nroComprobanteVenta) {
      $result = $documentosDetallesDb->buscarPorNroComprobanteVenta($nroComprobanteVenta);
    }
    if ($kardex) {
      $result = $documentosDetallesDb->listarDocumentosDetallesPorProductoyAntesDeFecha($idProducto, $fechaFin);

      // Filtrar datos para dd_antes_fecha
      $DDAntesFecha = array_filter($result, function ($item) use ($fechaInicio) {
        return $item['fecha'] < $fechaInicio;
      });

      // Filtrar datos para dd_entre_fechas
      $DDEntreFechas = array_filter($result, function ($item) use ($fechaInicio, $fechaFin) {
        return $item['fecha'] >= $fechaInicio && $item['fecha'] <= $fechaFin;
      });

      // Calcular monto_antes_fecha
      $montoAntesFecha = 0;
      $existenciasAntesFecha = 0;
      $ingresosAntesFecha = 0;
      $salidasAntesFecha = 0;

      foreach ($DDAntesFecha as $item) {
        $ingresosAntesFecha += ($item['tipo_movimiento'] == 'IN') ? $item['cantidad'] : 0;
        $salidasAntesFecha += ($item['tipo_movimiento'] == 'IN') ? 0 : $item['cantidad'];
        $existenciasAntesFecha += ($item['tipo_movimiento'] == 'IN') ? $item['cantidad'] : -$item['cantidad'];
        $montoAntesFecha += ($item['tipo_movimiento'] == 'IN') ? $item['precio_unitario'] * $item['cantidad'] : -$item['precio_unitario'] * $item['cantidad'];
      }

      $existenciasAcumuladas = $existenciasAntesFecha;
      $existenciasMontoAcumulado = $montoAntesFecha;

      // Procesar monto_antes_y_dd_prev
      foreach ($DDEntreFechas as &$item) {
        $existenciasAcumuladas += $item['ingreso'] - $item['salida'];
        $existenciasMontoAcumulado += ($item['ingreso'] - $item['salida']) * $item['precio_unitario'];

        // Agregar resultados a $item
        $item['existencias'] = $existenciasAcumuladas;
        $item['monto_total'] = $existenciasMontoAcumulado;
      }

      $precioUnitario = isset($DDAntesFecha[0]) ? $DDAntesFecha[0]['precio_unitario'] : null;
      $precioTotal = isset($DDAntesFecha[0]) ? $DDAntesFecha[0]['precio_unitario'] * $existenciasAntesFecha : null;

      // unir resultados
      $result = [
        [
          "monto_total" => $montoAntesFecha,
          "existencias" => $existenciasAntesFecha,
          "ingreso" => $ingresosAntesFecha,
          "salida" => $salidasAntesFecha,
          "tipo_movimiento" => null,
          "fecha" => null,
          "id_producto" => null,
          "precio_unitario" => $precioUnitario,
          "precio_total" => $precioTotal,
        ],
        ...$DDEntreFechas
      ];
    }
    if ($documentoMovimiento) {
      $result = $documentosDetallesDb->buscarPorDocumentoMovimiento($documentoMovimiento);
    }
    if ($servicios) {
      $result = $documentosDetallesDb->buscarServicios($fecha);
    }
    if ($liquidacion) {
      $result = $documentosDetallesDb->buscarServiciosLiquidacion($fecha, $idProfesional);
    }
    if ($serviciosTerapista) {
      $result = $documentosDetallesDb->buscarServiciosTerapista($fecha, $idProfesional);
    }
    if (count($params) === 0) {
      $result = $documentosDetallesDb->listarDocumentosDetalles();
    }

    $this->sendResponse($result, 200);
  }

  public function getOne($id)
  {
    $documentosDetallesDb = new DocumentosDetallesDb();
    $documentoDetalle = $documentosDetallesDb->obtenerDocumentoDetalle($id);

    $response = $documentoDetalle ? $documentoDetalle : ["mensaje" => "Documento Detalle no encontrado"];
    $code = $documentoDetalle ? 200 : 404;

    $this->sendResponse($response, $code);
  }

  public function create()
  {
    $documentoDetalleDelBody = $this->getBody();
    $documentoDetalle = new DocumentoDetalle();
    $this->mapJsonToObj($documentoDetalleDelBody, $documentoDetalle);

    $documentosDetallesDb = new DocumentosDetallesDb();
    $id = $documentosDetallesDb->crearDocumentoDetalle($documentoDetalle);

    $response = $id ? [
      "mensaje" => "Documento Detalle creado correctamente",
      "resultado" => array_merge([$documentosDetallesDb->idName => intval($id)], (array) $documentoDetalleDelBody)
    ] : ["mensaje" => "Error al crear el Documento Detalle"];
    $code = $id ? 201 : 400;

    $this->sendResponse($response, $code);
  }

  public function update($id)
  {
    $documentoDetalleDelBody = $this->getBody();
    $documentoDetalle = new DocumentoDetalle();
    $this->mapJsonToObj($documentoDetalleDelBody, $documentoDetalle);

    $documentosDetallesDb = new DocumentosDetallesDb();

    $prevDocumentoDetalle = $documentosDetallesDb->obtenerDocumentoDetalle($id);
    unset($prevDocumentoDetalle->id_central_de_costos);

    // comprobar que el documento detalle exista
    if (!$prevDocumentoDetalle) {
      $this->sendResponse(["mensaje" => "Documento Detalle no encontrado"], 404);
      return;
    }

    // si los datos son iguales, no se hace nada
    if ($this->compararObjetoActualizar($documentoDetalle, $prevDocumentoDetalle)) {
      $this->sendResponse(["mensaje" => "No se realizaron cambios"], 200);
      return;
    }

    $result = $documentosDetallesDb->actualizarDocumentoDetalle($id, $documentoDetalle);

    $response = $result ? [
      "mensaje" => "Documento Detalle actualizado correctamente",
      "resultado" => $documentosDetallesDb->obtenerDocumentoDetalle($id)
    ] : ["mensaje" => "Error al actualizar el Documento Detalle"];
    $code = $result ? 200 : 400;

    $this->sendResponse($response, $code);
  }

  public function updatePartial($id, $action = null)
  {
    if ($action == 'estado') {
      $documentoDetalleDelBody = $this->getBody();
      $documentoDetalle = new DocumentoDetalle();
      $this->mapJsonToObj($documentoDetalleDelBody, $documentoDetalle);

      $documentosDetallesDb = new DocumentosDetallesDb();

      $prevDocumentoDetalle = $documentosDetallesDb->obtenerDocumentoDetalle($id);
      unset($prevDocumentoDetalle->id_central_de_costos);

      // comprobar que el documento detalle exista
      if (!$prevDocumentoDetalle) {
        $this->sendResponse(["mensaje" => "Documento Detalle no encontrado"], 404);
        return;
      }

      // si los datos son iguales, no se hace nada
      if ($this->compararObjetoActualizar($documentoDetalle, $prevDocumentoDetalle)) {
        $this->sendResponse(["mensaje" => "No se realizaron cambios"], 200);
        return;
      }

      if ($documentoDetalle->estado_servicio == "0") {
        $documentoDetalle->fecha_servicio = null;
        $documentoDetalle->hora_servicio = null;
        $documentoDetalle->fecha_termino = null;
        $documentoDetalle->hora_termino = null;
      } else if ($documentoDetalle->estado_servicio == "1") {
        $documentoDetalle->fecha_termino = date("Y-m-d");
        $documentoDetalle->hora_termino = date("H:i");
      } else if ($documentoDetalle->estado_servicio == "3") {
        $documentoDetalle->fecha_servicio = date("Y-m-d");
        $documentoDetalle->hora_servicio = date("H:i");
      }

      $result = $documentosDetallesDb->actualizarDocumentoDetalle($id, $documentoDetalle);

      $response = $result ? [
        "mensaje" => "Estado del servicio actualizado correctamente",
        "resultado" => $documentosDetallesDb->obtenerDocumentoDetalle($id)
      ] : ["mensaje" => "Error al actualizar el estado del servicio"];
      $code = $result ? 200 : 400;

      $this->sendResponse($response, $code);

    } else if ($action == "servicio") {

      $documentoDetalleDelBody = $this->getBody();
      $documentoDetalle = new DocumentoDetalle();
      $this->mapJsonToObj($documentoDetalleDelBody, $documentoDetalle);

      $documentosDetallesDb = new DocumentosDetallesDb();

      $prevDocumentoDetalle = $documentosDetallesDb->obtenerDocumentoDetalle($id);
      unset($prevDocumentoDetalle->id_central_de_costos);

      // comprobar que el documento detalle exista
      if (!$prevDocumentoDetalle) {
        $this->sendResponse(["mensaje" => "Documento Detalle no encontrado"], 404);
        return;
      }

      // si los datos son iguales, no se hace nada
      if ($this->compararObjetoActualizar($documentoDetalle, $prevDocumentoDetalle)) {
        $this->sendResponse(["mensaje" => "No se realizaron cambios"], 200);
        return;
      }

      $productosDb = new ProductosDb();
      $producto = $productosDb->obtenerProducto($documentoDetalle->id_producto);
      $documentoDetalle->precio_unitario = $producto->precio_venta_01;
      $documentoDetalle->precio_total = $documentoDetalle->precio_unitario;

      $result = $documentosDetallesDb->actualizarDocumentoDetalle($id, $documentoDetalle);

      $response = $result ? [
        "mensaje" => "Documento Detalle actualizado correctamente",
        "resultado" => $documentosDetallesDb->obtenerDocumentoDetalle($id)
      ] : ["mensaje" => "Error al actualizar el Documento Detalle"];
      $code = $result ? 200 : 400;

      $this->sendResponse($response, $code);

    } else {
      $this->sendResponse(["mensaje" => "Acción no encontrada"], 404);
    }
  }

  public function delete($id)
  {
    $documentosDetallesDb = new DocumentosDetallesDb();
    $prevDocumentoDetalle = $documentosDetallesDb->obtenerDocumentoDetalle($id);

    // comprobar que el documento detalle exista
    if (!$prevDocumentoDetalle) {
      $this->sendResponse(["mensaje" => "Documento Detalle no encontrado"], 404);
      return;
    }

    $result = $documentosDetallesDb->eliminarDocumentoDetalle($id);

    $response = $result ? [
      "mensaje" => "Documento Detalle eliminado correctamente",
      "resultado" => $prevDocumentoDetalle
    ] : ["mensaje" => "Error al eliminar el Documento Detalle"];
    $code = $result ? 200 : 400;

    $this->sendResponse($response, $code);
  }

  public function deleteCustom($id, $action)
  {
    if ($action == "anular") {
      $documentosDetallesDb = new DocumentosDetallesDb();

      try {
        $documentosDetallesDb->empezarTransaccion();

        $documentosDetallesDb->anularDocumentoDetalle($id);
        $this->sendResponse(["mensaje" => "Documento Detalle anulado correctamente"], 200);

        $documentosDetallesDb->terminarTransaccion();
      } catch (Exception $e) {
        $documentosDetallesDb->cancelarTransaccion();
        $this->sendResponse(["mensaje" => $e->getMessage()], 400);
      }

    } else {
      $this->sendResponse(["mensaje" => "Acción no encontrada"], 404);
    }
  }
}

try {
  $controller = new DocumentosDetallesController();
  $controller->route();
} catch (Exception $e) {
  $controller->sendResponse($controller->errorResponse($e), 500);
}
?>