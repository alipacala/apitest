<?php
require_once __DIR__ . "/../../inc/bootstrap.php";
require_once PROJECT_ROOT_PATH . "/controllers/BaseController.php";

require_once PROJECT_ROOT_PATH . "/models/ImpresorasDb.php";

class ImpresorasController extends BaseController
{
  public function get()
  {
    $impresorasDb = new ImpresorasDb();
    $result = $impresorasDb->listarImpresoras();

    $this->sendResponse($result, 200);
  }

  public function getOne($id)
  {
    $impresorasDb = new ImpresorasDb();
    $impresora = $impresorasDb->obtenerImpresora($id);

    $response = $impresora ? $impresora : ["mensaje" => "Impresora no encontrada"];
    $code = $impresora ? 200 : 404;

    $this->sendResponse($response, $code);
  }

  public function create()
  {
    $impresoraDelBody = $this->getBody();
    $impresora = new Impresora();
    $this->mapJsonToObj($impresoraDelBody, $impresora);

    $impresorasDb = new ImpresorasDb();
    $id = $impresorasDb->crearImpresora($impresora);

    $response = $id ? [
      "mensaje" => "Impresora creada correctamente",
      "resultado" => array_merge([$impresorasDb->idName => intval($id)], (array) $impresoraDelBody)
    ] : ["mensaje" => "Error al crear la Impresora"];
    $code = $id ? 201 : 400;

    $this->sendResponse($response, $code);
  }

  public function update($id)
  {
    $impresoraDelBody = $this->getBody();
    $impresora = new Impresora();
    $this->mapJsonToObj($impresoraDelBody, $impresora);

    $impresorasDb = new ImpresorasDb();

    $prevImpresora = $impresorasDb->obtenerImpresora($id);
    unset($prevImpresora->id_impresora);

    // comprobar que la impresora exista
    if (!$prevImpresora) {
      $this->sendResponse(["mensaje" => "Impresora no encontrada"], 404);
      return;
    }

    // si los datos son iguales, no se hace nada
    if ($this->compararObjetoActualizar($impresora, $prevImpresora)) {
      $this->sendResponse(["mensaje" => "No se realizaron cambios"], 200);
      return;
    }

    $result = $impresorasDb->actualizarImpresora($id, $impresora);

    $response = $result ? [
      "mensaje" => "Impresora actualizada correctamente",
      "resultado" => $impresorasDb->obtenerImpresora($id)
    ] : ["mensaje" => "Error al actualizar la Impresora"];
    $code = $result ? 200 : 400;

    $this->sendResponse($response, $code);
  }

  public function delete($id)
  {
    $impresorasDb = new ImpresorasDb();
    $prevImpresora = $impresorasDb->obtenerImpresora($id);

    // comprobar que la impresora exista
    if (!$prevImpresora) {
      $this->sendResponse(["mensaje" => "Impresora no encontrada"], 404);
      return;
    }

    $result = $impresorasDb->eliminarImpresora($id);

    $response = $result ? [
      "mensaje" => "Impresora eliminada correctamente",
      "resultado" => $prevImpresora
    ] : ["mensaje" => "Error al eliminar la Impresora"];
    $code = $result ? 200 : 400;

    $this->sendResponse($response, $code);
  }
}

try {
  $controller = new ImpresorasController();
  $controller->route();
} catch (Exception $e) {
  $controller->sendResponse($controller->errorResponse($e), 500);
}
?>