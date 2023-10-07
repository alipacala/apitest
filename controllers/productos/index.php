<?php
require_once __DIR__ . "/../../inc/bootstrap.php";
require_once PROJECT_ROOT_PATH . "/controllers/BaseController.php";

require_once PROJECT_ROOT_PATH . "/models/ProductosDb.php";
require_once PROJECT_ROOT_PATH . "/models/ProductosRecetaDb.php";
require_once PROJECT_ROOT_PATH . "/models/ProductosPaqueteDb.php";
require_once PROJECT_ROOT_PATH . "/models/ConfigDb.php";

class ProductosController extends BaseController
{
  public function get()
  {
    $params = $this->getParams();
    $grupos = $params['grupos'] ?? null;
    $nombreProducto = $params['nombre_producto'] ?? null;

    $hospedajes = isset($params['hospedajes']);

    $productosDb = new ProductosDb();

    if ($hospedajes) {
      $result = $productosDb->listarHospedajes();
    }
    if ($grupos) {
      $result = $productosDb->listarPorGrupo($grupos);
    }
    if ($nombreProducto) {
      $result = $productosDb->buscarPorNombre($nombreProducto);
    }
    if (count($params) === 0) {
      $result = $productosDb->listarProductos();
    }

    $this->sendResponse($result, 200);
  }

  public function getOne($id)
  {
    $productosDb = new ProductosDb();
    $producto = $productosDb->obtenerProducto($id);

    $response = $producto ? $producto : ["mensaje" => "Producto no encontrado"];
    $code = $producto ? 200 : 404;

    $this->sendResponse($response, $code);
  }

  public function getOneCustom($id, $action = null)
  {
    if ($action == "con-insumos") {
      $productosDb = new ProductosDb();
      $producto = $productosDb->obtenerProducto($id);

      // comprobar que el producto exista
      if (!$producto) {
        $this->sendResponse(["mensaje" => "Producto no encontrado"], 404);
        return;
      }

      if ($producto->tipo == 'PAQ') {
        $productosPaqueteDb = new ProductosPaqueteDb();
        $insumos = $productosPaqueteDb->buscarSubproductos($id);
      } else if (in_array($producto->tipo, ['RST', 'SRV'])) {
        $productosRecetaDb = new ProductosRecetaDb();
        $insumos = $productosRecetaDb->buscarInsumos($id);
      } else {
        $insumos = null;
      }

      $response = array_merge((array) $producto, ["insumos" => $insumos]);
      $code = 200;

      $this->sendResponse($response, $code);
    } else {
      $this->sendResponse(["mensaje" => "Acción no válida"], 400);
    }
  }

  public function create()
  {
    $productoDelBody = $this->getBody();
    $producto = new Producto();
    $this->mapJsonToObj($productoDelBody, $producto);

    $productosDb = new ProductosDb();
    $id = $productosDb->crearProducto($producto);

    $response = $id ? [
      "mensaje" => "Producto creado correctamente",
      "resultado" => array_merge([$productosDb->idName => intval($id)], (array) $productoDelBody)
    ] : ["mensaje" => "Error al crear el Producto"];
    $code = $id ? 201 : 400;

    $this->sendResponse($response, $code);
  }

  public function createCustom($action)
  {
    switch ($action) {
      case "insumo-terminado":

        $productoDelBody = $this->getBody();
        $producto = new Producto();
        $this->mapJsonToObj($productoDelBody, $producto);

        $producto->tipo = "PRD";
        $producto->activo = 1;

        // comprobar que el producto tenga los datos necesarios
        $camposRequeridos = ["nombre_producto", "codigo", "tipo_de_unidad", "id_grupo", "id_central_de_costos", "id_tipo_de_producto", "fecha_de_vigencia", "stock_min_temporada_baja", "stock_max_temporada_baja", "stock_min_temporada_alta", "stock_max_temporada_alta", "cantidad_de_fracciones"];
        $camposFaltantes = $this->comprobarCamposRequeridos($camposRequeridos, $producto);

        if (count($camposFaltantes) > 0) {
          $this->sendResponse(["mensaje" => "Faltan los siguientes campos: " . implode(", ", $camposFaltantes)], 400);
          return;
        }

        // si la fecha_vigencia es hoy, se guarda como null
        $fechaHoy = date("Y-m-d");
        if ($producto->fecha_de_vigencia == $fechaHoy) {
          $producto->fecha_de_vigencia = null;
        }

        $productosDb = new ProductosDb();
        $id = $productosDb->crearProducto($producto);

        if ($id) {
          $configDb = new ConfigDb();
          $configDb->incrementarCorrelativo(6); // 6 es el id de la configuración de productos

          $response = [
            "mensaje" => "Insumo creado correctamente",
            "resultado" => array_merge([$productosDb->idName => intval($id)], (array) $producto)
          ];
          $code = 201;
        } else {
          $response = ["mensaje" => "Error al crear el Insumo"];
          $code = 400;
        }

        $this->sendResponse($response, $code);

        break;
      case "receta":

        $productoDelBody = $this->getBody();

        $insumos = $productoDelBody->insumos;
        unset($productoDelBody->insumos);

        $producto = new Producto();
        $this->mapJsonToObj($productoDelBody, $producto);

        $producto->tipo = "RST";
        $producto->id_tipo_de_producto = 12;
        $producto->activo = 1;

        // comprobar que el producto tenga los datos necesarios
        $camposRequeridos = ["nombre_producto", "descripcion_del_producto", "codigo", "id_grupo", "id_central_de_costos", "fecha_de_vigencia", "tiempo_estimado", "preparacion"];
        $camposFaltantes = $this->comprobarCamposRequeridos($camposRequeridos, $producto);

        if (count($camposFaltantes) > 0) {
          $this->sendResponse(["mensaje" => "Faltan los siguientes campos: " . implode(", ", $camposFaltantes)], 400);
          return;
        }

        // si la fecha_vigencia es hoy, se guarda como null
        $fechaHoy = date("Y-m-d");
        if ($producto->fecha_de_vigencia == $fechaHoy) {
          $producto->fecha_de_vigencia = null;
        }

        // comprobar que los insumos tengan los datos necesarios
        $camposRequeridos = ["id_producto_insumo", "cantidad", "tipo_de_unidad"];

        foreach ($insumos as $insumo) {
          $insumoTemp = $insumo;
          $insumo = new ProductoReceta();
          $this->mapJsonToObj($insumoTemp, $insumo);
          $camposFaltantes = $this->comprobarCamposRequeridos($camposRequeridos, $insumo);

          if (count($camposFaltantes) > 0) {
            $this->sendResponse(["mensaje" => "Faltan los siguientes campos de un insumo: " . implode(", ", $camposFaltantes)], 400);
            return;
          }
        }

        try {
          $productosDb = new ProductosDb();
          $productosDb->empezarTransaccion();

          // calcular el costo unitario sumando los precios de los insumos
          $producto->costo_unitario = 0;
          foreach ($insumos as $insumo) {
            $productoInsumo = $productosDb->obtenerProducto($insumo->id_producto_insumo);
            if (!$productoInsumo) {
              $this->sendResponse(["mensaje" => "No se encontró el insumo con id " . $insumo->id_producto_insumo], 400);
              return;
            }
            $producto->costo_unitario += $productoInsumo->costo_unitario * $insumo->cantidad;
          }

          $idReceta = $productosDb->crearProducto($producto);

          $productosRecetaDb = new ProductosRecetaDb();
          $insumosCreados = [];

          foreach ($insumos as $insumo) {
            $insumoTemp = $insumo;
            $insumo = new ProductoReceta();
            $this->mapJsonToObj($insumoTemp, $insumo);

            $insumo->id_producto = $idReceta;
            $idInsumo = $productosRecetaDb->crearProductoReceta($insumo);

            $insumo->id_receta = $idInsumo;
            $insumosCreados[] = $insumo;
          }

          $productosDb->terminarTransaccion();
        } catch (Exception $e) {
          $productosDb->cancelarTransaccion();
          $newException = new Exception("Error al crear la Receta", 0, $e);
          throw $newException;
        }

        $recetaEInsumosCreados = $idReceta && count($insumos) === count($insumosCreados);

        if ($recetaEInsumosCreados) {
          $configDb = new ConfigDb();
          $configDb->incrementarCorrelativo(7); // 7 es el id de la configuración de recetas

          $response = [
            "mensaje" => "Receta creada correctamente",
            "resultado" => array_merge([$productosDb->idName => intval($idReceta)], (array) $producto, ["insumos" => $insumosCreados])
          ];
          $code = 201;
        } else {
          $response = ["mensaje" => "Error al crear la Receta"];
          $code = 400;
        }

        $this->sendResponse($response, $code);

        break;
      case "hospedaje":

        $productoDelBody = $this->getBody();
        $producto = new Producto();
        $this->mapJsonToObj($productoDelBody, $producto);

        $producto->tipo = "SVH";
        $producto->id_tipo_de_producto = 12; // id de servicio
        $producto->activo = 1;

        // comprobar que el producto tenga los datos necesarios
        $camposRequeridos = ["nombre_producto", "descripcion_del_producto", "codigo", "id_grupo", "id_central_de_costos", "fecha_de_vigencia"];
        $camposFaltantes = $this->comprobarCamposRequeridos($camposRequeridos, $producto);

        if (count($camposFaltantes) > 0) {
          $this->sendResponse(["mensaje" => "Faltan los siguientes campos: " . implode(", ", $camposFaltantes)], 400);
          return;
        }

        // si la fecha_vigencia es hoy, se guarda como null
        $fechaHoy = date("Y-m-d");
        if ($producto->fecha_de_vigencia == $fechaHoy) {
          $producto->fecha_de_vigencia = null;
        }

        $productosDb = new ProductosDb();
        $id = $productosDb->crearProducto($producto);

        if ($id) {
          $configDb = new ConfigDb();
          $configDb->incrementarCorrelativo(8); // 8 es el id de la configuración de hospedajes

          $response = [
            "mensaje" => "Hospedaje creado correctamente",
            "resultado" => array_merge([$productosDb->idName => intval($id)], (array) $producto)
          ];
          $code = 201;
        } else {
          $response = ["mensaje" => "Error al crear el Hospedaje"];
          $code = 400;
        }

        $this->sendResponse($response, $code);

        break;
      case "servicio":

        $productoDelBody = $this->getBody();

        $insumos = $productoDelBody->insumos;
        unset($productoDelBody->insumos);

        $producto = new Producto();
        $this->mapJsonToObj($productoDelBody, $producto);

        $producto->tipo = "SRV";
        $producto->id_tipo_de_producto = 12; // TODO: cambiar al id de servicio
        $producto->activo = 1;

        // comprobar que el producto tenga los datos necesarios
        $camposRequeridos = ["nombre_producto", "descripcion_del_producto", "codigo", "id_grupo", "id_central_de_costos", "fecha_de_vigencia", "requiere_programacion", "tiempo_estimado", "codigo_habilidad"];
        $camposFaltantes = $this->comprobarCamposRequeridos($camposRequeridos, $producto);

        if (count($camposFaltantes) > 0) {
          $this->sendResponse(["mensaje" => "Faltan los siguientes campos: " . implode(", ", $camposFaltantes)], 400);
          return;
        }

        // si la fecha_vigencia es hoy, se guarda como null
        $fechaHoy = date("Y-m-d");
        if ($producto->fecha_de_vigencia == $fechaHoy) {
          $producto->fecha_de_vigencia = null;
        }

        // comprobar que los insumos tengan los datos necesarios
        $camposRequeridos = ["id_producto_insumo", "cantidad", "tipo_de_unidad"];
        foreach ($insumos as $insumo) {
          $insumoTemp = $insumo;
          $insumo = new ProductoReceta();
          $this->mapJsonToObj($insumoTemp, $insumo);
          $camposFaltantes = $this->comprobarCamposRequeridos($camposRequeridos, $insumo);

          if (count($camposFaltantes) > 0) {
            $this->sendResponse(["mensaje" => "Faltan los siguientes campos de un insumo: " . implode(", ", $camposFaltantes)], 400);
            return;
          }
        }

        try {
          $productosDb = new ProductosDb();
          $productosDb->empezarTransaccion();

          // calcular el costo unitario sumando los precios de los insumos
          $producto->costo_unitario = 0;
          foreach ($insumos as $insumo) {
            $productoInsumo = $productosDb->obtenerProducto($insumo->id_producto_insumo);
            if (!$productoInsumo) {
              $this->sendResponse(["mensaje" => "No se encontró el insumo con id " . $insumo->id_producto_insumo], 400);
              return;
            }
            $producto->costo_unitario += $productoInsumo->costo_unitario * $insumo->cantidad;
          }

          $idServicio = $productosDb->crearProducto($producto);

          $productosRecetaDb = new ProductosRecetaDb();
          $insumosCreados = [];

          foreach ($insumos as $insumo) {
            $insumoTemp = $insumo;
            $insumo = new ProductoReceta();
            $this->mapJsonToObj($insumoTemp, $insumo);

            $insumo->id_producto = $idServicio;
            $idInsumo = $productosRecetaDb->crearProductoReceta($insumo);

            $insumo->id_receta = $idInsumo;
            $insumosCreados[] = $insumo;
          }

          $productosDb->terminarTransaccion();
        } catch (Exception $e) {
          $productosDb->cancelarTransaccion();
          $newException = new Exception("Error al crear el Servicio", 0, $e);
          throw $newException;
        }

        $servicioEInsumosCreados = $idServicio && count($insumos) === count($insumosCreados);

        if ($servicioEInsumosCreados) {
          $configDb = new ConfigDb();
          $configDb->incrementarCorrelativo(9); // 9 es el id de la configuración de servicios

          $response = [
            "mensaje" => "Servicio creado correctamente",
            "resultado" => array_merge([$productosDb->idName => intval($idServicio)], (array) $producto, ["insumos" => $insumosCreados])
          ];
          $code = 201;
        } else {
          $response = ["mensaje" => "Error al crear el Servicio"];
          $code = 400;
        }

        $this->sendResponse($response, $code);

        break;
      case "paquete":

        $productoDelBody = $this->getBody();

        $insumos = $productoDelBody->insumos;
        unset($productoDelBody->insumos);

        $producto = new Producto();
        $this->mapJsonToObj($productoDelBody, $producto);

        $producto->tipo = "PAQ";
        $producto->id_tipo_de_producto = 12; // TODO: cambiar al id de paquete
        $producto->activo = 1;

        // comprobar que el producto tenga los datos necesarios
        $camposRequeridos = ["nombre_producto", "descripcion_del_producto", "codigo", "id_grupo", "fecha_de_vigencia"];
        $camposFaltantes = $this->comprobarCamposRequeridos($camposRequeridos, $producto);

        if (count($camposFaltantes) > 0) {
          $this->sendResponse(["mensaje" => "Faltan los siguientes campos: " . implode(", ", $camposFaltantes)], 400);
          return;
        }

        // si la fecha_vigencia es hoy, se guarda como null
        $fechaHoy = date("Y-m-d");
        if ($producto->fecha_de_vigencia == $fechaHoy) {
          $producto->fecha_de_vigencia = null;
        }

        // comprobar que los insumos tengan los datos necesarios
        $camposRequeridos = ["id_producto_producto", "cantidad", "tipo_de_unidad"];
        foreach ($insumos as $insumo) {
          $insumoTemp = $insumo;
          $insumo = new ProductoPaquete();
          $this->mapJsonToObj($insumoTemp, $insumo);

          $camposFaltantes = $this->comprobarCamposRequeridos($camposRequeridos, $insumo);

          if (count($camposFaltantes) > 0) {
            $this->sendResponse(["mensaje" => "Faltan los siguientes campos de un insumo: " . implode(", ", $camposFaltantes)], 400);
            return;
          }
        }

        try {
          $productosDb = new ProductosDb();
          $productosDb->empezarTransaccion();

          // calcular el costo unitario sumando los precios de los productos
          $producto->costo_unitario = 0;
          foreach ($insumos as $insumo) {
            $productoInsumo = $productosDb->obtenerProducto($insumo->id_producto_producto);
            if (!$productoInsumo) {
              $this->sendResponse(["mensaje" => "No se encontró el producto con id " . $insumo->id_producto_producto], 400);
              return;
            }
            $producto->costo_unitario += $productoInsumo->costo_unitario * $insumo->cantidad;
          }

          $idPaquete = $productosDb->crearProducto($producto);

          $productosPaqueteDb = new ProductosPaqueteDb();
          $insumosCreados = [];

          foreach ($insumos as $insumo) {
            $insumoTemp = $insumo;
            $insumo = new ProductoPaquete();
            $this->mapJsonToObj($insumoTemp, $insumo);

            $insumo->id_producto = $idPaquete;
            $idInsumo = $productosPaqueteDb->crearProductoPaquete($insumo);

            $insumo->id_paquete = $idInsumo;
            $insumosCreados[] = $insumo;
          }

          $productosDb->terminarTransaccion();
        } catch (Exception $e) {
          $productosDb->cancelarTransaccion();
          $newException = new Exception("Error al crear el Paquete", 0, $e);
          throw $newException;
        }

        $recetaEInsumosCreados = $idPaquete && count($insumos) === count($insumosCreados);

        if ($recetaEInsumosCreados) {
          $configDb = new ConfigDb();
          $configDb->incrementarCorrelativo(10); // 10 es el id de la configuración de paquetes

          $response = [
            "mensaje" => "Paquete creado correctamente",
            "resultado" => array_merge([$productosDb->idName => intval($idPaquete)], (array) $producto, ["insumos" => $insumosCreados])
          ];
          $code = 201;
        } else {
          $response = ["mensaje" => "Error al crear el Paquete"];
          $code = 400;
        }

        $this->sendResponse($response, $code);

        break;
      default:
        $this->sendResponse(["mensaje" => "Acción no válida"], 400);
        break;
    }
  }

  public function update($id)
  {
    $productoDelBody = $this->getBody();
    $producto = new Producto();
    $this->mapJsonToObj($productoDelBody, $producto);

    $productosDb = new ProductosDb();

    $prevProducto = $productosDb->obtenerProducto($id);
    unset($prevProducto->id_producto);

    // comprobar que el producto exista
    if (!$prevProducto) {
      $this->sendResponse(["mensaje" => "Producto no encontrado"], 404);
      return;
    }

    // si los datos son iguales, no se hace nada
    if ($this->compararObjetoActualizar($producto, $prevProducto)) {
      $this->sendResponse(["mensaje" => "No se realizaron cambios"], 200);
      return;
    }

    $result = $productosDb->actualizarProducto($id, $producto);

    $response = $result ? [
      "mensaje" => "Producto actualizado correctamente",
      "resultado" => $productosDb->obtenerProducto($id)
    ] : ["mensaje" => "Error al actualizar el Producto"];
    $code = $result ? 200 : 400;

    $this->sendResponse($response, $code);
  }

  public function updatePartial($id, $action = null)
  {
    switch ($action) {
      case "costos-precios":
        $productoDelBody = $this->getBody();
        $producto = new Producto();
        $this->mapJsonToObj($productoDelBody, $producto);

        // comprobar que el producto tenga los datos necesarios
        $camposRequeridos = ["costo_mano_de_obra", "costo_adicional", "porcentaje_margen", "precio_venta_01", "precio_venta_02", "precio_venta_03"];
        $camposFaltantes = $this->comprobarCamposRequeridos($camposRequeridos, $producto);

        if (count($camposFaltantes) > 0) {
          $this->sendResponse(["mensaje" => "Faltan los siguientes campos: " . implode(", ", $camposFaltantes)], 400);
          return;
        }

        $productosDb = new ProductosDb();

        $prevProducto = $productosDb->obtenerProducto($id);
        unset($prevProducto->id_producto);

        // comprobar que el producto exista
        if (!$prevProducto) {
          $this->sendResponse(["mensaje" => "Producto no encontrado"], 404);
          return;
        }

        // si los datos son iguales, no se hace nada
        if ($prevProducto == $productoDelBody) {
          $this->sendResponse(["mensaje" => "No se realizaron cambios"], 200);
          return;
        }

        $result = $productosDb->actualizarProducto($id, $producto);

        $response = $result ? [
          "mensaje" => "Producto actualizado correctamente",
          "resultado" => $productosDb->obtenerProducto($id)
        ] : ["mensaje" => "Error al actualizar el Producto"];
        $code = $result ? 200 : 400;

        $this->sendResponse($response, $code);

        break;
      case "con-insumos":
      case "con-subproductos":
        $conInsumos = $action == 'con-insumos';

        $productoDelBody = $this->getBody();

        $insumosAgregados = $productoDelBody->insumos_agregados;
        unset($productoDelBody->insumos_agregados);
        $idsInsumosEliminados = $productoDelBody->ids_insumos_eliminados;
        unset($productoDelBody->ids_insumos_eliminados);

        $producto = new Producto();
        $this->mapJsonToObj($productoDelBody, $producto);

        $productosDb = new ProductosDb();
        $prevProducto = $productosDb->obtenerProducto($id);
        unset($prevProducto->id_producto);

        // comprobar que el producto exista
        if (!$prevProducto) {
          $this->sendResponse(["mensaje" => "Producto no encontrado"], 404);
          return;
        }

        $sonProductosIguales = $this->compararObjetoActualizar($producto, $prevProducto);

        // si los datos son iguales, no se hace nada
        if ($sonProductosIguales && count($insumosAgregados) === 0 && count($idsInsumosEliminados) === 0) {
          $this->sendResponse(["mensaje" => "No se realizaron cambios"], 200);
          return;
        }

        // comprobar que los insumos tengan los datos necesarios
        $camposRequeridos = [$conInsumos ? 'id_producto_insumo' : 'id_producto_producto', "cantidad", "tipo_de_unidad"];
        foreach ($insumosAgregados as $insumo) {
          $insumoTemp = $insumo;
          $insumo = $conInsumos ? new ProductoReceta() : new ProductoPaquete();
          $this->mapJsonToObj($insumoTemp, $insumo);

          $camposFaltantes = $this->comprobarCamposRequeridos($camposRequeridos, $insumo);

          if (count($camposFaltantes) > 0) {
            $this->sendResponse(["mensaje" => "Faltan los siguientes campos de un insumo: " . implode(", ", $camposFaltantes)], 400);
            return;
          }
        }

        if ($conInsumos) {
          $productosRecetaDb = new ProductosRecetaDb();
        } else {
          $productosPaqueteDb = new ProductosPaqueteDb();
        }
        $insumosCreados = [];

        foreach ($insumosAgregados as $insumo) {
          $insumoTemp = $insumo;
          $insumo = $conInsumos ? new ProductoReceta() : new ProductoPaquete();
          $this->mapJsonToObj($insumoTemp, $insumo);

          $insumo->id_producto = $id;

          $idInsumo = $conInsumos ? $productosRecetaDb->crearProductoReceta($insumo) : $productosPaqueteDb->crearProductoPaquete($insumo);

          if ($conInsumos) {
            $insumo->id_receta = $idInsumo;
          } else {
            $insumo->id_paquete = $idInsumo;
          }

          $insumosCreados[] = $insumo;
        }

        $insumosEliminados = 0;
        foreach ($idsInsumosEliminados as $idInsumo) {
          $insumosEliminados += $conInsumos ?
            $productosRecetaDb->eliminarProductoReceta($idInsumo)
            : $productosPaqueteDb->eliminarProductoPaquete($idInsumo);
        }

        $result = $productosDb->actualizarProducto($id, $producto);

        $productoSeActualizo = $result || count($insumosAgregados) === count($insumosCreados) || $insumosEliminados === count($idsInsumosEliminados);

        $response = $productoSeActualizo ? [
          "mensaje" => "Producto actualizado correctamente",
          "resultado" => array_merge([$productosDb->idName => intval($id)], (array) $producto, ["insumos_agregados" => $insumosCreados, "ids_insumos_eliminados" => $idsInsumosEliminados])
        ] : ["mensaje" => "Error al actualizar el Producto"];
        $code = $productoSeActualizo ? 200 : 400;

        $this->sendResponse($response, $code);

        break;
      default:
        $this->sendResponse(["mensaje" => "Acción no válida"], 400);
        break;
    }
  }

  public function delete($id)
  {
    $productosDb = new ProductosDb();
    $prevProducto = $productosDb->obtenerProducto($id);

    // comprobar que el producto exista
    if (!$prevProducto) {
      $this->sendResponse(["mensaje" => "Producto no encontrado"], 404);
      return;
    }

    $result = $productosDb->eliminarProducto($id);

    $response = $result ? [
      "mensaje" => "Producto eliminado correctamente",
      "resultado" => $prevProducto
    ] : ["mensaje" => "Error al eliminar el Producto"];
    $code = $result ? 200 : 400;

    $this->sendResponse($response, $code);
  }
}

try {
  $controller = new ProductosController();
  $controller->route();
} catch (Exception $e) {
  $controller->sendResponse($controller->errorResponse($e), 500);
}
?>