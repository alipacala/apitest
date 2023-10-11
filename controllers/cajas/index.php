<?php
require_once __DIR__ . "/../../inc/bootstrap.php";
require_once PROJECT_ROOT_PATH . "/controllers/BaseController.php";

require_once PROJECT_ROOT_PATH . "/models/CajasDb.php";

class CajasController extends BaseController
{
  public function get()
  {
    $cajasDb = new CajasDb();
    $result = $cajasDb->listarCajas();

    $this->sendResponse($result, 200);
  }

  public function getOne($id)
  {
    $cajasDb = new CajasDb();
    $caja = $cajasDb->obtenerCaja($id);

    $response = $caja ? $caja : ["mensaje" => "Caja no encontrada"];
    $code = $caja ? 200 : 404;

    $this->sendResponse($response, $code);
  }

  public function create()
  {
    $cajaDelBody = $this->getBody();
    $caja = new Caja();
    $this->mapJsonToObj($cajaDelBody, $caja);

    $cajasDb = new CajasDb();
    $id = $cajasDb->crearCaja($caja);

    $response = $id ? [
      "mensaje" => "Caja creada correctamente",
      "resultado" => array_merge([$cajasDb->idName => intval($id)], (array) $cajaDelBody)
    ] : ["mensaje" => "Error al crear la Caja"];
    $code = $id ? 201 : 400;

    $this->sendResponse($response, $code);
  }

  public function update($id)
  {
    $cajaDelBody = $this->getBody();
    $caja = new Caja();
    $this->mapJsonToObj($cajaDelBody, $caja);

    $cajasDb = new CajasDb();

    $prevCaja = $cajasDb->obtenerCaja($id);
    unset($prevCaja->id_caja);

    // comprobar que la caja exista
    if (!$prevCaja) {
      $this->sendResponse(["mensaje" => "Caja no encontrada"], 404);
      return;
    }

    // si los datos son iguales, no se hace nada
    if ($this->compararObjetoActualizar($caja, $prevCaja)) {
      $this->sendResponse(["mensaje" => "No se realizaron cambios"], 200);
      return;
    }

    $result = $cajasDb->actualizarCaja($id, $caja);

    $response = $result ? [
      "mensaje" => "Caja actualizada correctamente",
      "resultado" => $cajasDb->obtenerCaja($id)
    ] : ["mensaje" => "Error al actualizar la Caja"];
    $code = $result ? 200 : 400;

    $this->sendResponse($response, $code);
  }

  public function delete($id)
  {
    $cajasDb = new CajasDb();
    $prevCaja = $cajasDb->obtenerCaja($id);

    // comprobar que la caja exista
    if (!$prevCaja) {
      $this->sendResponse(["mensaje" => "Caja no encontrada"], 404);
      return;
    }

    $result = $cajasDb->eliminarCaja($id);

    $response = $result ? [
      "mensaje" => "Caja eliminada correctamente",
      "resultado" => $prevCaja
    ] : ["mensaje" => "Error al eliminar la Caja"];
    $code = $result ? 200 : 400;

    $this->sendResponse($response, $code);
  }
}

try {
  $controller = new CajasController();
  $controller->route();
} catch (Exception $e) {
  $controller->sendResponse($controller->errorResponse($e), 500);
}
?>