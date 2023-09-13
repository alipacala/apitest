<?php
require_once __DIR__ . "/../../inc/bootstrap.php";
require_once PROJECT_ROOT_PATH . "/controllers/BaseController.php";

require_once PROJECT_ROOT_PATH . "/models/TiposDeProductosDb.php";
require_once PROJECT_ROOT_PATH . "/entities/TipoDeProductos.php";

class TiposDeProductosController extends BaseController
{
  public function get()
  {
    $tiposDeProductosDb = new TiposDeProductosDb();
    $result = $tiposDeProductosDb->listarTiposDeProductos();

    $this->sendResponse($result, 200);
  }

  public function getOne($id)
  {
    $tiposDeProductosDb = new TiposDeProductosDb();
    $tipoDeProductos = $tiposDeProductosDb->obtenerTipoDeProductos($id);

    $response = $tipoDeProductos ? $tipoDeProductos : ["mensaje" => "Tipo de productos no encontrado"];
    $code = $tipoDeProductos ? 200 : 404;

    $this->sendResponse($response, $code);
  }

  public function create()
  {
    $tipoDeProductosDelBody = $this->getBody();
    $tipoDeProductos = $this->mapJsonToClass($tipoDeProductosDelBody, TipoDeProductos::class);

    $tiposDeProductosDb = new TiposDeProductosDb();
    $id = $tiposDeProductosDb->crearTipoDeProductos($tipoDeProductos);

    $response = $id ? [
      "mensaje" => "Tipo de Productos creado correctamente",
      "resultado" => array_merge([$tiposDeProductosDb->idName => intval($id)], (array) $tipoDeProductosDelBody)
    ] : ["mensaje" => "Error al crear el Tipo de Productos"];
    $code = $id ? 201 : 400;

    $this->sendResponse($response, $code);
  }

  public function update($id)
  {
    $tipoDeProductosDelBody = $this->getBody();
    $tipoDeProductos = $this->mapJsonToClass($tipoDeProductosDelBody, TipoDeProductos::class);

    $tiposDeProductosDb = new TiposDeProductosDb();

    $prevTipoDeProductos = $tiposDeProductosDb->obtenerTipoDeProductos($id);
    unset($prevTipoDeProductos->id_tipo_producto);

    // comprobar que el tipo de productos exista
    if (!$prevTipoDeProductos) {
      $this->sendResponse(["mensaje" => "Tipo de Productos no encontrado"], 404);
      return;
    }

    // si los datos son iguales, no se hace nada
    if ($prevTipoDeProductos == $tipoDeProductos) {
      $this->sendResponse(["mensaje" => "No se han realizado cambios"], 200);
      return;
    }

    $result = $tiposDeProductosDb->actualizarTipoDeProductos($id, $tipoDeProductos);

    $response = $result ? [
      "mensaje" => "Tipo de Productos actualizado correctamente",
      "resultado" => $tiposDeProductosDb->obtenerTipoDeProductos($id)
    ] : ["mensaje" => "Error al actualizar el Tipo de Productos"];
    $code = $result ? 200 : 400;

    $this->sendResponse($response, $code);
  }

  public function delete($id)
  {
    $tiposDeProductosDb = new TiposDeProductosDb();
    $prevTipoDeProductos = $tiposDeProductosDb->obtenerTipoDeProductos($id);

    // comprobar que el tipo de productos exista
    if (!$prevTipoDeProductos) {
      $this->sendResponse(["mensaje" => "Tipo de Productos no encontrado"], 404);
      return;
    }

    try {
      $result = $tiposDeProductosDb->eliminarTipoDeProductos($id);
    } catch (PDOException $e) {
      if (str_contains($e->getMessage(), "23000")) {
        $this->sendResponse(["mensaje" => "No se puede eliminar el Tipo de Producto porque tiene productos asociados"], 400);
        return;
      }
    }

    $response = $result ? [
      "mensaje" => "Tipo de Productos eliminado correctamente",
      "resultado" => $prevTipoDeProductos
    ] : ["mensaje" => "Error al eliminar el Tipo de Productos"];
    $code = $result ? 200 : 400;

    $this->sendResponse($response, $code);
  }
}

try {
  $controller = new TiposDeProductosController();
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