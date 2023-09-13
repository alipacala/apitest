<?php
require_once __DIR__ . "/../../inc/bootstrap.php";
require_once PROJECT_ROOT_PATH . "/controllers/BaseController.php";

require_once PROJECT_ROOT_PATH . "/models/ProductosPaqueteDb.php";
require_once PROJECT_ROOT_PATH . "/entities/ProductoPaquete.php";

class ProductosPaqueteController extends BaseController
{
  public function get()
  {
    $productosPaqueteDb = new ProductosPaqueteDb();
    $result = $productosPaqueteDb->listarProductosPaquete();

    $this->sendResponse($result, 200);
  }

  public function getOne($id)
  {
    $productosPaqueteDb = new ProductosPaqueteDb();
    $productoPaquete = $productosPaqueteDb->obtenerProductoPaquete($id);

    $response = $productoPaquete ? $productoPaquete : ["mensaje" => "Producto Paquete no encontrado"];
    $code = $productoPaquete ? 200 : 404;

    $this->sendResponse($response, $code);
  }

  public function create()
  {
    $productoPaqueteDelBody = $this->getBody();
    $productoPaquete = $this->mapJsonToClass($productoPaqueteDelBody, ProductoPaquete::class);

    $productosPaqueteDb = new ProductosPaqueteDb();
    $id = $productosPaqueteDb->crearProductoPaquete($productoPaquete);

    $response = $id ? [
      "mensaje" => "Producto Paquete creado correctamente",
      "resultado" => array_merge([$productosPaqueteDb->idName => intval($id)], (array)$productoPaqueteDelBody)
    ] : ["mensaje" => "Error al crear el Producto Paquete"];
    $code = $id ? 201 : 400;

    $this->sendResponse($response, $code);
  }

  public function update($id)
  {
    $productoPaqueteDelBody = $this->getBody();
    $productoPaquete = $this->mapJsonToClass($productoPaqueteDelBody, ProductoPaquete::class);

    $productosPaqueteDb = new ProductosPaqueteDb();

    $prevProductoPaquete = $productosPaqueteDb->obtenerProductoPaquete($id);
    unset($prevProductoPaquete->id_paquete);

    // comprobar que el producto paquete exista
    if (!$prevProductoPaquete) {
      $this->sendResponse(["mensaje" => "Producto Paquete no encontrado"], 404);
      return;
    }

    // si los datos son iguales, no se hace nada
    if ($prevProductoPaquete == $productoPaquete) {
      $this->sendResponse(["mensaje" => "No se realizaron cambios"], 200);
      return;
    }

    $result = $productosPaqueteDb->actualizarProductoPaquete($id, $productoPaquete);

    $response = $result ? [
      "mensaje" => "Producto Paquete actualizado correctamente",
      "resultado" => $productosPaqueteDb->obtenerProductoPaquete($id)
    ] : ["mensaje" => "Error al actualizar el Producto Paquete"];
    $code = $result ? 200 : 400;

    $this->sendResponse($response, $code);
  }

  public function delete($id)
  {
    $productosPaqueteDb = new ProductosPaqueteDb();
    $prevProductoPaquete = $productosPaqueteDb->obtenerProductoPaquete($id);

    // comprobar que el producto paquete exista
    if (!$prevProductoPaquete) {
      $this->sendResponse(["mensaje" => "Producto Paquete no encontrado"], 404);
      return;
    }

    $result = $productosPaqueteDb->eliminarProductoPaquete($id);

    $response = $result ? [
      "mensaje" => "Producto Paquete eliminado correctamente",
      "resultado" => $prevProductoPaquete
    ] : ["mensaje" => "Error al eliminar el Producto Paquete"];
    $code = $result ? 200 : 400;

    $this->sendResponse($response, $code);
  }
}

try {
  $controller = new ProductosPaqueteController();
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