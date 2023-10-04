<?php
require_once __DIR__ . "/../../inc/bootstrap.php";
require_once PROJECT_ROOT_PATH . "/controllers/BaseController.php";

require_once PROJECT_ROOT_PATH . "/models/TiposGastoDb.php";

class TiposGastoController extends BaseController
{
  public function get()
  {
    $tiposGastoDb = new TiposGastoDb();
    $result = $tiposGastoDb->listarTiposGasto();

    $this->sendResponse($result, 200);
  }

  public function getOne($id)
  {
    $tiposGastoDb = new TiposGastoDb();
    $tipoGasto = $tiposGastoDb->obtenerTipoGasto($id);

    $response = $tipoGasto ? $tipoGasto : ["mensaje" => "Tipo de Gasto no encontrada"];
    $code = $tipoGasto ? 200 : 404;

    $this->sendResponse($response, $code);
  }

  public function create()
  {
    $tipoGastoDelBody = $this->getBody();
    $tipoGasto = $this->mapJsonToClass($tipoGastoDelBody, TipoGasto::class);

    $tiposGastoDb = new TiposGastoDb();
    $id = $tiposGastoDb->crearTipoGasto($tipoGasto);

    $response = $id ? [
      "mensaje" => "Tipo de Gasto creada correctamente",
      "resultado" => array_merge([$tiposGastoDb->idName => intval($id)], (array) $tipoGastoDelBody)
    ] : ["mensaje" => "Error al crear la Tipo de Gasto"];
    $code = $id ? 201 : 400;

    $this->sendResponse($response, $code);
  }

  public function update($id)
  {
    $tipoGastoDelBody = $this->getBody();
    $tipoGasto = $this->mapJsonToClass($tipoGastoDelBody, TipoGasto::class);

    $tiposGastoDb = new TiposGastoDb();

    $prevTipoGasto = $tiposGastoDb->obtenerTipoGasto($id);
    unset($prevTipoGasto->id_tipoGasto);

    // comprobar que la tipoGasto exista
    if (!$prevTipoGasto) {
      $this->sendResponse(["mensaje" => "Tipo de Gasto no encontrada"], 404);
      return;
    }

    // si los datos son iguales, no se hace nada
    if ($this->compararObjetoActualizar($tipoGasto, $prevTipoGasto)) {
      $this->sendResponse(["mensaje" => "No se realizaron cambios"], 200);
      return;
    }

    $result = $tiposGastoDb->actualizarTipoGasto($id, $tipoGasto);

    $response = $result ? [
      "mensaje" => "Tipo de Gasto actualizada correctamente",
      "resultado" => $tiposGastoDb->obtenerTipoGasto($id)
    ] : ["mensaje" => "Error al actualizar la Tipo de Gasto"];
    $code = $result ? 200 : 400;

    $this->sendResponse($response, $code);
  }

  public function delete($id)
  {
    $tiposGastoDb = new TiposGastoDb();
    $prevTipoGasto = $tiposGastoDb->obtenerTipoGasto($id);

    // comprobar que la tipoGasto exista
    if (!$prevTipoGasto) {
      $this->sendResponse(["mensaje" => "Tipo de Gasto no encontrada"], 404);
      return;
    }

    $result = $tiposGastoDb->eliminarTipoGasto($id);

    $response = $result ? [
      "mensaje" => "Tipo de Gasto eliminada correctamente",
      "resultado" => $prevTipoGasto
    ] : ["mensaje" => "Error al eliminar la Tipo de Gasto"];
    $code = $result ? 200 : 400;

    $this->sendResponse($response, $code);
  }
}

try {
  $controller = new TiposGastoController();
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