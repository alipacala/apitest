<?php
require_once __DIR__ . "/../../inc/bootstrap.php";
require_once PROJECT_ROOT_PATH . "/controllers/BaseController.php";

require_once PROJECT_ROOT_PATH . "/models/PaisesDb.php";

class PaisesController extends BaseController
{
  public function get()
  {
    $paisesDb = new PaisesDb();
    $result = $paisesDb->listarPaises();

    $this->sendResponse($result, 200);
  }

  public function getOne($id)
  {
    $paisesDb = new PaisesDb();
    $pais = $paisesDb->obtenerPais($id);

    $response = $pais ? $pais : ["mensaje" => "País no encontrado"];
    $code = $pais ? 200 : 404;

    $this->sendResponse($response, $code);
  }
}

try {
  $controller = new PaisesController();
  $controller->route();
} catch (Exception $e) {
  $controller->sendResponse($controller->errorResponse($e), 500);
}
?>