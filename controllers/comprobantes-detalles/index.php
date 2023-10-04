<?php
require_once __DIR__ . "/../../inc/bootstrap.php";
require_once PROJECT_ROOT_PATH . "/controllers/BaseController.php";

require_once PROJECT_ROOT_PATH . "/models/ComprobantesDetallesDb.php";

class ComprobantesDetallesController extends BaseController
{
  public function get()
  {
    $params = $this->getParams();
    $comprobante = $params['comprobante'] ?? null;

    $comprobantesDetallesDb = new ComprobantesDetallesDb();

    if ($comprobante) {
      $result = $comprobantesDetallesDb->buscarComprobantesDetallesPorIdComprobante($comprobante);
      $this->sendResponse($result, 200);
      return;
    }

    $result = $comprobantesDetallesDb->listarComprobantesDetalles();

    $this->sendResponse($result, 200);
  }

  public function getOne($id)
  {
    $comprobantesDetallesDb = new ComprobantesDetallesDb();
    $comprobanteDetalle = $comprobantesDetallesDb->obtenerComprobanteDetalle($id);

    $response = $comprobanteDetalle ? $comprobanteDetalle : ["mensaje" => "Comprobante Detalle no encontrado"];
    $code = $comprobanteDetalle ? 200 : 404;

    $this->sendResponse($response, $code);
  }
}

try {
  $controller = new ComprobantesDetallesController();
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