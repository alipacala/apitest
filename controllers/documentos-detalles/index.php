<?php
require_once __DIR__ . "/../../inc/bootstrap.php";
require_once PROJECT_ROOT_PATH . "/controllers/BaseController.php";

require_once PROJECT_ROOT_PATH . "/models/DocumentosDetallesDb.php";
require_once PROJECT_ROOT_PATH . "/models/RecibosPagoDb.php";

class DocumentosDetallesController extends BaseController
{
  public function get()
  {
    $params = $this->getParams();
    $nroRegistroMaestro = $params['nro_registro_maestro'] ?? null;
    $nroComprobanteVenta = $params['nro_comprobante_venta'] ?? null;

    $documentosDetallesDb = new DocumentosDetallesDb();
    $result = $documentosDetallesDb->listarDocumentosDetalles($nroRegistroMaestro, $nroComprobanteVenta);

    if ($nroRegistroMaestro) {
      $result = array_map(function ($documentoDetalle) {
        $recibosPagoDb = new RecibosPagoDb();
        $reciboPago = $recibosPagoDb->obtenerReciboPago($documentoDetalle->id_recibo_de_pago);

        $documentoDetalle->fecha_pago = $reciboPago ? date_format(date_create($reciboPago->fecha), "d/m H:i") : "";
        return $documentoDetalle;
      }, $result);
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
    $documentoDetalle = $this->mapJsonToClass($documentoDetalleDelBody, DocumentoDetalle::class);

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
    $documentoDetalle = $this->mapJsonToClass($documentoDetalleDelBody, DocumentoDetalle::class);

    $documentosDetallesDb = new DocumentosDetallesDb();

    $prevDocumentoDetalle = $documentosDetallesDb->obtenerDocumentoDetalle($id);
    unset($prevDocumentoDetalle->id_central_de_costos);

    // comprobar que el documento detalle exista
    if (!$prevDocumentoDetalle) {
      $this->sendResponse(["mensaje" => "Documento Detalle no encontrado"], 404);
      return;
    }

    // si los datos son iguales, no se hace nada
    if ($prevDocumentoDetalle == $documentoDetalle) {
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
}

try {
  $controller = new DocumentosDetallesController();
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