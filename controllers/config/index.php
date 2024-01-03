<?php
require_once __DIR__ . "/../../inc/bootstrap.php";
require_once PROJECT_ROOT_PATH . "/controllers/BaseController.php";

require_once PROJECT_ROOT_PATH . "/models/ConfigDb.php";

class ConfigController extends BaseController
{
  public function get()
  {
    $params = $this->getParams();
    $codigo = $params['codigo'] ?? null;

    $configDb = new ConfigDb();

    $cantidadDigitos = 6;

    if ($codigo) {
      $result = $configDb->obtenerCodigoOGenerar($codigo);
    } else {
      $result = $configDb->listarConfig();
    }

    $this->sendResponse($result, 200);
  }

  public function getOne($id)
  {
    $configDb = new ConfigDb();
    $config = $configDb->obtenerConfig($id);

    $response = $config ? $config : ["mensaje" => "Config no encontrado"];
    $code = $config ? 200 : 404;

    $this->sendResponse($response, $code);
  }

  public function getOneCustom($id, $action)
  {
    if ($action == "codigo") {
      $configDb = new ConfigDb();
      $config = $configDb->obtenerCodigo($id);

      $response = $config ? $config : ["mensaje" => "Config no encontrada"];
      $code = $config ? 200 : 404;

      $this->sendResponse($response, $code);
    } else {
      $this->sendResponse(["mensaje" => "Acción no encontrada"], 404);
    }
  }

  public function updatePartial($id, $action = null)
  {
    if ($action == 'correlativo') {
      $configDb = new ConfigDb();
      $prevConfig = $configDb->obtenerConfig($id);

      // comprobar que el config exista
      if (!$prevConfig) {
        $this->sendResponse(["mensaje" => "Config no encontrada"], 404);
        return;
      }

      $id = $configDb->incrementarCorrelativo($id);

      $response = $id ? ["mensaje" => "Correlativo actualizado correctamente"]
        : ["mensaje" => "Error al actualizar el Correlativo"];

      $code = $id ? 200 : 400;

      $this->sendResponse($response, $code);
    } else {
      $this->sendResponse(["mensaje" => "Acción no encontrada"], 404);
    }
  }
}

try {
  $controller = new ConfigController();
  $controller->route();
} catch (Exception $e) {
  $controller->sendResponse($controller->errorResponse($e), 500);
}
?>