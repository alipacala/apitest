<?php
require_once __DIR__ . "/../../inc/bootstrap.php";
require_once PROJECT_ROOT_PATH . "/controllers/BaseController.php";

require_once PROJECT_ROOT_PATH . "/models/ProductosRecetaDb.php";

class ProductosRecetaController extends BaseController
{
  public function get()
  {
    $productosRecetaDb = new ProductosRecetaDb();
    $result = $productosRecetaDb->listarProductosReceta();

    $this->sendResponse($result, 200);
  }

  public function getOne($id)
  {
    $productosRecetaDb = new ProductosRecetaDb();
    $productoReceta = $productosRecetaDb->obtenerProductoReceta($id);

    $response = $productoReceta ? $productoReceta : ["mensaje" => "Producto Receta no encontrado"];
    $code = $productoReceta ? 200 : 404;

    $this->sendResponse($response, $code);
  }

  public function create()
  {
    $productoRecetaDelBody = $this->getBody();
    $productoReceta = $this->mapJsonToClass($productoRecetaDelBody, ProductoReceta::class);

    $productosRecetaDb = new ProductosRecetaDb();
    $id = $productosRecetaDb->crearProductoReceta($productoReceta);

    $response = $id ? [
      "mensaje" => "Producto Receta creado correctamente",
      "resultado" => array_merge([$productosRecetaDb->idName => intval($id)], (array)$productoRecetaDelBody)
    ] : ["mensaje" => "Error al crear el Producto Receta"];
    $code = $id ? 201 : 400;

    $this->sendResponse($response, $code);
  }

  public function update($id)
  {
    $productoRecetaDelBody = $this->getBody();
    $productoReceta = $this->mapJsonToClass($productoRecetaDelBody, ProductoReceta::class);

    $productosRecetaDb = new ProductosRecetaDb();

    $prevProductoReceta = $productosRecetaDb->obtenerProductoReceta($id);
    unset($prevProductoReceta->id_receta);

    // comprobar que el producto paquete exista
    if (!$prevProductoReceta) {
      $this->sendResponse(["mensaje" => "Producto Receta no encontrado"], 404);
      return;
    }

    // si los datos son iguales, no se hace nada
    if ($prevProductoReceta == $productoReceta) {
      $this->sendResponse(["mensaje" => "No se realizaron cambios"], 200);
      return;
    }

    $result = $productosRecetaDb->actualizarProductoReceta($id, $productoReceta);

    $response = $result ? [
      "mensaje" => "Producto Receta actualizado correctamente",
      "resultado" => $productosRecetaDb->obtenerProductoReceta($id)
    ] : ["mensaje" => "Error al actualizar el Producto Receta"];
    $code = $result ? 200 : 400;

    $this->sendResponse($response, $code);
  }

  public function delete($id)
  {
    $productosRecetaDb = new ProductosRecetaDb();
    $prevProductoReceta = $productosRecetaDb->obtenerProductoReceta($id);

    // comprobar que el producto paquete exista
    if (!$prevProductoReceta) {
      $this->sendResponse(["mensaje" => "Producto Receta no encontrado"], 404);
      return;
    }

    $result = $productosRecetaDb->eliminarProductoReceta($id);

    $response = $result ? [
      "mensaje" => "Producto Receta eliminado correctamente",
      "resultado" => $prevProductoReceta
    ] : ["mensaje" => "Error al eliminar el Producto Receta"];
    $code = $result ? 200 : 400;

    $this->sendResponse($response, $code);
  }
}

try {
  $controller = new ProductosRecetaController();
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