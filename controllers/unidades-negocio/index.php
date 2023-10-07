<?php
require_once __DIR__ . "/../../inc/bootstrap.php";
require_once PROJECT_ROOT_PATH . "/controllers/BaseController.php";

require_once PROJECT_ROOT_PATH . "/models/UnidadesDeNegocioDb.php";

class UnidadesDeNegocioController extends BaseController
{
  public function get()
  {
    $unidadesDeNegocioDb = new UnidadesDeNegocioDb();
    $result = $unidadesDeNegocioDb->listarUnidadesDeNegocio();

    $this->sendResponse($result, 200);
  }

  public function getOne($id)
  {
    $unidadesDeNegocioDb = new UnidadesDeNegocioDb();
    $unidadDeNegocio = $unidadesDeNegocioDb->obtenerUnidadDeNegocio($id);

    $response = $unidadDeNegocio ? $unidadDeNegocio : ["mensaje" => "Unidad de Negocio no encontrada"];
    $code = $unidadDeNegocio ? 200 : 404;

    $this->sendResponse($response, $code);
  }

  public function create()
  {
    $unidadDeNegocioDelBody = $this->getBody();
    $unidadDeNegocio = new UnidadDeNegocio();
    $this->mapJsonToObj($unidadDeNegocioDelBody, $unidadDeNegocio);

    $unidadesDeNegocioDb = new UnidadesDeNegocioDb();
    $id = $unidadesDeNegocioDb->crearUnidadDeNegocio($unidadDeNegocio);

    $response = $id ? [
      "mensaje" => "Unidad de Negocio creada correctamente",
      "resultado" => array_merge([$unidadesDeNegocioDb->idName => intval($id)], (array) $unidadDeNegocioDelBody)
    ] : ["mensaje" => "Error al crear la Unidad de Negocio"];
    $code = $id ? 201 : 400;

    $this->sendResponse($response, $code);
  }

  public function update($id)
  {
    $unidadDeNegocioDelBody = $this->getBody();
    $unidadDeNegocio = new UnidadDeNegocio();
    $this->mapJsonToObj($unidadDeNegocioDelBody, $unidadDeNegocio);

    $unidadesDeNegocioDb = new UnidadesDeNegocioDb();

    $prevUnidadDeNegocio = $unidadesDeNegocioDb->obtenerUnidadDeNegocio($id);
    unset($prevUnidadDeNegocio->id_unidadDeNegocio);

    // comprobar que la unidadDeNegocio exista
    if (!$prevUnidadDeNegocio) {
      $this->sendResponse(["mensaje" => "Unidad de Negocio no encontrada"], 404);
      return;
    }

    // si los datos son iguales, no se hace nada
    if ($this->compararObjetoActualizar($unidadDeNegocio, $prevUnidadDeNegocio)) {
      $this->sendResponse(["mensaje" => "No se realizaron cambios"], 200);
      return;
    }

    $result = $unidadesDeNegocioDb->actualizarUnidadDeNegocio($id, $unidadDeNegocio);

    $response = $result ? [
      "mensaje" => "Unidad de Negocio actualizada correctamente",
      "resultado" => $unidadesDeNegocioDb->obtenerUnidadDeNegocio($id)
    ] : ["mensaje" => "Error al actualizar la Unidad de Negocio"];
    $code = $result ? 200 : 400;

    $this->sendResponse($response, $code);
  }

  public function delete($id)
  {
    $unidadesDeNegocioDb = new UnidadesDeNegocioDb();
    $prevUnidadDeNegocio = $unidadesDeNegocioDb->obtenerUnidadDeNegocio($id);

    // comprobar que la unidadDeNegocio exista
    if (!$prevUnidadDeNegocio) {
      $this->sendResponse(["mensaje" => "Unidad de Negocio no encontrada"], 404);
      return;
    }

    $result = $unidadesDeNegocioDb->eliminarUnidadDeNegocio($id);

    $response = $result ? [
      "mensaje" => "Unidad de Negocio eliminada correctamente",
      "resultado" => $prevUnidadDeNegocio
    ] : ["mensaje" => "Error al eliminar la Unidad de Negocio"];
    $code = $result ? 200 : 400;

    $this->sendResponse($response, $code);
  }
}

try {
  $controller = new UnidadesDeNegocioController();
  $controller->route();
} catch (Exception $e) {
  $controller->sendResponse($controller->errorResponse($e), 500);
}
?>