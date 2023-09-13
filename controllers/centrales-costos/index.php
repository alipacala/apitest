<?php
require_once __DIR__ . "/../../inc/bootstrap.php";
require_once PROJECT_ROOT_PATH . "/controllers/BaseController.php";

require_once PROJECT_ROOT_PATH . "/models/CentralesDeCostosDb.php";

class CentralesDeCostosController extends BaseController
{
  public function get()
  {
    $centralesDeCostosDb = new CentralesDeCostosDb();
    $result = $centralesDeCostosDb->listarCentralesDeCostos();

    $this->sendResponse($result, 200);
  }

  public function getOne($id)
  {
    $centralesDeCostosDb = new CentralesDeCostosDb();
    $centralDeCostos = $centralesDeCostosDb->obtenerCentralDeCostos($id);

    $response = $centralDeCostos ? $centralDeCostos : ["mensaje" => "Central de Costos no encontrada"];
    $code = $centralDeCostos ? 200 : 404;

    $this->sendResponse($response, $code);
  }

  public function create()
  {
    $centralDeCostosDelBody = $this->getBody();
    $centralDeCostos = $this->mapJsonToClass($centralDeCostosDelBody, CentralDeCostos::class);

    $centralesDeCostosDb = new CentralesDeCostosDb();
    $id = $centralesDeCostosDb->crearCentralDeCostos($centralDeCostos);

    $response = $id ? [
      "mensaje" => "Central de Costos creada correctamente",
      "resultado" => array_merge([$centralesDeCostosDb->idName => intval($id)], (array) $centralDeCostosDelBody)
    ] : ["mensaje" => "Error al crear la Central de Costos"];
    $code = $id ? 201 : 400;

    $this->sendResponse($response, $code);
  }

  public function update($id)
  {
    $centralDeCostosDelBody = $this->getBody();
    $centralDeCostos = $this->mapJsonToClass($centralDeCostosDelBody, CentralDeCostos::class);

    $centralesDeCostosDb = new CentralesDeCostosDb();

    $prevCentralDeCostos = $centralesDeCostosDb->obtenerCentralDeCostos($id);
    unset($prevCentralDeCostos->id_central_de_costos);

    // comprobar que la central de costos exista
    if (!$prevCentralDeCostos) {
      $this->sendResponse(["mensaje" => "Central de Costos no encontrada"], 404);
      return;
    }

    // si los datos son iguales, no se hace nada
    if ($prevCentralDeCostos == $centralDeCostos) {
      $this->sendResponse(["mensaje" => "No se realizaron cambios"], 200);
      return;
    }

    $result = $centralesDeCostosDb->actualizarCentralDeCostos($id, $centralDeCostos);

    $response = $result ? [
      "mensaje" => "Central de Costos actualizada correctamente",
      "resultado" => $centralesDeCostosDb->obtenerCentralDeCostos($id)
    ] : ["mensaje" => "Error al actualizar la Central de Costos"];
    $code = $result ? 200 : 400;

    $this->sendResponse($response, $code);
  }

  public function delete($id)
  {
    $centralesDeCostosDb = new CentralesDeCostosDb();
    $prevCentralDeCostos = $centralesDeCostosDb->obtenerCentralDeCostos($id);

    // comprobar que la central de costos exista
    if (!$prevCentralDeCostos) {
      $this->sendResponse(["mensaje" => "Central de Costos no encontrada"], 404);
      return;
    }

    $result = $centralesDeCostosDb->eliminarCentralDeCostos($id);

    $response = $result ? [
      "mensaje" => "Central de Costos eliminada correctamente",
      "resultado" => $prevCentralDeCostos
    ] : ["mensaje" => "Error al eliminar la Central de Costos"];
    $code = $result ? 200 : 400;

    $this->sendResponse($response, $code);
  }
}

try {
  $controller = new CentralesDeCostosController();
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