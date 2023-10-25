<?php
require_once __DIR__ . "/../../inc/bootstrap.php";
require_once PROJECT_ROOT_PATH . "/controllers/BaseController.php";

require_once PROJECT_ROOT_PATH . "/models/ModalidadesClienteDb.php";

class ModalidadesClienteController extends BaseController
{
  public function get()
  {
    $modalidadesClienteDb = new ModalidadesClienteDb();
    $result = $modalidadesClienteDb->listarModalidadesCliente();

    $this->sendResponse($result, 200);
  }

  public function getOne($id)
  {
    $modalidadesClienteDb = new ModalidadesClienteDb();
    $modalidadCliente = $modalidadesClienteDb->obtenerModalidadCliente($id);

    $response = $modalidadCliente ? $modalidadCliente : ["mensaje" => "Modalidad Cliente no encontrada"];
    $code = $modalidadCliente ? 200 : 404;

    $this->sendResponse($response, $code);
  }

  public function create()
  {
    $modalidadClienteDelBody = $this->getBody();
    $modalidadCliente = new ModalidadCliente();
    $this->mapJsonToObj($modalidadClienteDelBody, $modalidadCliente);

    $modalidadesClienteDb = new ModalidadesClienteDb();
    $id = $modalidadesClienteDb->crearModalidadCliente($modalidadCliente);

    $response = $id ? [
      "mensaje" => "Modalidad Cliente creada correctamente",
      "resultado" => array_merge([$modalidadesClienteDb->idName => intval($id)], (array) $modalidadClienteDelBody)
    ] : ["mensaje" => "Error al crear la Modalidad Cliente"];
    $code = $id ? 201 : 400;

    $this->sendResponse($response, $code);
  }

  public function update($id)
  {
    $modalidadClienteDelBody = $this->getBody();
    $modalidadCliente = new ModalidadCliente();
    $this->mapJsonToObj($modalidadClienteDelBody, $modalidadCliente);

    $modalidadesClienteDb = new ModalidadesClienteDb();

    $prevModalidadCliente = $modalidadesClienteDb->obtenerModalidadCliente($id);
    unset($prevModalidadCliente->id_modalidadCliente);

    // comprobar que la modalidadCliente exista
    if (!$prevModalidadCliente) {
      $this->sendResponse(["mensaje" => "Modalidad Cliente no encontrada"], 404);
      return;
    }

    // si los datos son iguales, no se hace nada
    if ($this->compararObjetoActualizar($modalidadCliente, $prevModalidadCliente)) {
      $this->sendResponse(["mensaje" => "No se realizaron cambios"], 200);
      return;
    }

    $result = $modalidadesClienteDb->actualizarModalidadCliente($id, $modalidadCliente);

    $response = $result ? [
      "mensaje" => "Modalidad Cliente actualizada correctamente",
      "resultado" => $modalidadesClienteDb->obtenerModalidadCliente($id)
    ] : ["mensaje" => "Error al actualizar la Modalidad Cliente"];
    $code = $result ? 200 : 400;

    $this->sendResponse($response, $code);
  }

  public function delete($id)
  {
    $modalidadesClienteDb = new ModalidadesClienteDb();
    $prevModalidadCliente = $modalidadesClienteDb->obtenerModalidadCliente($id);

    // comprobar que la modalidadCliente exista
    if (!$prevModalidadCliente) {
      $this->sendResponse(["mensaje" => "Modalidad Cliente no encontrada"], 404);
      return;
    }

    $result = $modalidadesClienteDb->eliminarModalidadCliente($id);

    $response = $result ? [
      "mensaje" => "Modalidad Cliente eliminada correctamente",
      "resultado" => $prevModalidadCliente
    ] : ["mensaje" => "Error al eliminar la Modalidad Cliente"];
    $code = $result ? 200 : 400;

    $this->sendResponse($response, $code);
  }
}

try {
  $controller = new ModalidadesClienteController();
  $controller->route();
} catch (Exception $e) {
  $controller->sendResponse($controller->errorResponse($e), 500);
}
?>