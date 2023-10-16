<?php
require_once __DIR__ . "/../../inc/bootstrap.php";
require_once PROJECT_ROOT_PATH . "/controllers/BaseController.php";

require_once PROJECT_ROOT_PATH . "/models/DocumentosMovimientosDb.php";
require_once PROJECT_ROOT_PATH . "/models/DocumentosDetallesDb.php";
require_once PROJECT_ROOT_PATH . "/models/ConfigDb.php";
require_once PROJECT_ROOT_PATH . "/models/CheckingsDb.php";
require_once PROJECT_ROOT_PATH . "/models/ProductosDb.php";
require_once PROJECT_ROOT_PATH . "/models/ProductosRecetaDb.php";
require_once PROJECT_ROOT_PATH . "/models/ProductosPaqueteDb.php";
require_once PROJECT_ROOT_PATH . "/models/PersonasDb.php";

class DocumentosMovimientoController extends BaseController
{
  public function get()
  {
    $documentosMovimientosDb = new DocumentosMovimientosDb();
    $result = $documentosMovimientosDb->listarDocumentosMovimientos();
    $this->sendResponse($result, 200);
  }

  public function getOne($id)
  {
    $documentosMovimientosDb = new DocumentosMovimientosDb();
    $documentoMovimiento = $documentosMovimientosDb->obtenerDocumentoMovimiento($id);

    $response = $documentoMovimiento ? $documentoMovimiento : ["mensaje" => "Documento Movimiento no encontrado"];
    $code = $documentoMovimiento ? 200 : 404;

    $this->sendResponse($response, $code);
  }

  public function create()
  {
    $documentoMovimientoDelBody = $this->getBody();
    $documentoMovimiento = new DocumentoMovimiento();
    $this->mapJsonToObj($documentoMovimientoDelBody, $documentoMovimiento);

    $documentosMovimientosDb = new DocumentosMovimientosDb();
    $id = $documentosMovimientosDb->crearDocumentoMovimiento($documentoMovimiento);

    $response = $id ? [
      "mensaje" => "Documento Movimiento creado correctamente",
      "resultado" => array_merge([$documentosMovimientosDb->idName => intval($id)], (array) $documentoMovimientoDelBody)
    ] : ["mensaje" => "Error al crear el Documento Movimiento"];
    $code = $id ? 201 : 400;

    $this->sendResponse($response, $code);
  }

  public function createCustom($action)
  {
    if ($action == 'detalles') {
      $documentoMovimientoDelBody = $this->getBody();
      $documentoMovimiento = new DocumentoMovimiento();
      $this->mapJsonToObj($documentoMovimientoDelBody, $documentoMovimiento);

      $documentosMovimientosDb = new DocumentosMovimientosDb();
      $configDb = new ConfigDb();
      $checkingsDb = new CheckingsDb();
      $productosDb = new ProductosDb();

      $detalles = $documentoMovimientoDelBody->detalles;
      unset($documentoMovimientoDelBody->detalles);

      // obtener el checkin
      $checkin = $checkingsDb->buscarPorNroRegistroMaestro($documentoMovimiento->nro_registro_maestro);

      $documentoMovimiento->tipo_movimiento = 'SA'; // salida
      $documentoMovimiento->tipo_documento = $checkin->tipo_documento; // tipo de documento del cliente
      $documentoMovimiento->nro_documento = $checkin->nro_documento; // nro de documento del cliente

      $fechaYHora = $documentosMovimientosDb->obtenerFechaYHora();

      $documentoMovimiento->fecha_movimiento = $fechaYHora['fecha'];
      $documentoMovimiento->fecha_documento = $documentoMovimiento->fecha_movimiento; // el mismo que la fecha de movimiento
      $documentoMovimiento->hora_movimiento = $fechaYHora['hora'];
      $documentoMovimiento->fecha_hora_registro = $fechaYHora['fecha_y_hora'];

      $documentoMovimiento->nro_de_comanda = $configDb->obtenerCodigo(5)["codigo"]; // 5 es el id de las comandas en la tabla config

      $documentoMovimiento->id_unidad_de_negocio = $checkingsDb->buscarPorNroRegistroMaestro($documentoMovimiento->nro_registro_maestro)->id_unidad_de_negocio;

      // comprobar que el documento movimiento tenga los datos necesarios
      $camposRequeridos = ["nro_registro_maestro", "id_usuario"];
      $camposFaltantes = $this->comprobarCamposRequeridos($camposRequeridos, $documentoMovimientoDelBody);

      if (count($camposFaltantes) > 0) {
        $this->sendResponse(["mensaje" => "Faltan los siguientes campos: " . implode(", ", $camposFaltantes)], 400);
        return;
      }

      // comprobar que los detalles del documento movimiento tenga los datos necesarios
      $camposRequeridos = ["id_producto", "cantidad", "precio_unitario", "id_acompanate"];
      foreach ($detalles as $detalle) {
        $camposFaltantes = $this->comprobarCamposRequeridos($camposRequeridos, $detalle);

        if (count($camposFaltantes) > 0) {
          $this->sendResponse(["mensaje" => "Faltan los siguientes campos en los detalles: " . implode(", ", $camposFaltantes)], 400);
          return;
        }
      }

      $documentoMovimiento->total = 0;

      $detallesDescargo1 = [];
      $iterador = 1;

      // calcular el total del documento movimiento
      foreach ($detalles as $detalle) {
        $detalle->precio_total = $detalle->cantidad * $detalle->precio_unitario;
        $documentoMovimiento->total += $detalle->precio_total;
      }

      // crear el documento movimiento
      $idDocumento = $documentosMovimientosDb->crearDocumentoMovimiento($documentoMovimiento);

      foreach ($detalles as $detalle) {
        $detalleTemp = $detalle;
        $detalle = new DocumentoDetalle();
        $this->mapJsonToObj($detalleTemp, $detalle);
        $producto = $productosDb->obtenerProducto($detalle->id_producto);

        if (!$producto) {
          $this->sendResponse(["mensaje" => "No se encontró el producto con id " . $detalle->id_producto], 400);
          return;
        }

        $detalle->tipo_movimiento = $documentoMovimiento->tipo_movimiento;
        $detalle->nro_registro_maestro = $documentoMovimiento->nro_registro_maestro;
        $detalle->fecha_hora_registro = $documentoMovimiento->fecha_hora_registro;
        $detalle->fecha = $documentoMovimiento->fecha_hora_registro;

        $detalle->nivel_descargo = 1;

        $detalle->tipo_de_unidad = $producto->tipo_de_unidad;
        $detalle->precio_total = $detalle->cantidad * $detalle->precio_unitario;

        $detalle->id_usuario = $documentoMovimiento->id_usuario;

        if ($producto->tipo == 'SRV') {
          $detalle->id_profesional = $detalle->id_profesional ?? null;
          $detalle->fecha_servicio = $detalle->fecha_servicio ?? null;
          $detalle->hora_servicio = $detalle->hora_servicio ?? null;
        }

        $detalle->id_documento_movimiento = $idDocumento;

        // crear el detalle del documento movimiento con nivel_descargo 1
        $documentosDetallesDb = new DocumentosDetallesDb();
        $idDetalle = $documentosDetallesDb->crearDocumentoDetalle($detalle);
        $detalle->id_documentos_detalle = $idDetalle;

        $detallesDescargo1[] = $detalle;
      }

      $detallesDescargo3 = [];
      $detallesDescargo13 = [];

      // obtener los subproductos de los paquetes
      foreach ($detallesDescargo1 as $detalleDescargo1) {
        $producto = $productosDb->obtenerProducto($detalleDescargo1->id_producto);

        if ($producto->tipo == 'PAQ') {
          $productosRecetaDb = new ProductosRecetaDb();
          $productosPaqueteDb = new ProductosPaqueteDb();

          $insumos = $productosPaqueteDb->buscarSubproductos($producto->id_producto);

          foreach ($insumos as $insumo) {
            $detalle = new DocumentoDetalle();

            $producto = $productosDb->obtenerProducto($insumo->id_producto_producto);

            $detalle->tipo_movimiento = $documentoMovimiento->tipo_movimiento;
            $detalle->nro_registro_maestro = $documentoMovimiento->nro_registro_maestro;
            $detalle->fecha_hora_registro = $documentoMovimiento->fecha_hora_registro;

            $detalle->id_producto = $insumo->id_producto_producto;
            $detalle->nivel_descargo = 3;

            $detalle->cantidad = $insumo->cantidad * $detalleDescargo1->cantidad;
            $detalle->tipo_de_unidad = $insumo->tipo_de_unidad;
            $detalle->precio_unitario = $producto->precio_venta_01;
            $detalle->precio_total = $detalle->cantidad * $detalle->precio_unitario;

            $detalle->fecha = $documentoMovimiento->fecha_hora_registro;

            $detalle->id_acompanate = $detalleDescargo1->id_acompanate;
            $detalle->id_profesional = $detalleDescargo1->id_profesional ?? null;
            $detalle->fecha_servicio = $detalleDescargo1->fecha_servicio ?? null;
            $detalle->hora_servicio = $detalleDescargo1->hora_servicio ?? null;
            $detalle->fecha_termino = $detalleDescargo1->fecha_termino ?? null;
            $detalle->hora_termino = $detalleDescargo1->hora_termino ?? null;
            $detalle->nro_comprobante = $detalleDescargo1->nro_comprobante ?? null;

            $detalle->observaciones = "";

            $detalle->id_recibo_de_pago = $detalleDescargo1->id_recibo_de_pago ?? null;
            $detalle->anulado = $detalleDescargo1->anulado ?? null;

            $detalle->id_usuario = $documentoMovimiento->id_usuario;
            $detalle->fecha_hora_registro = $documentoMovimiento->fecha_hora_registro;

            $detalle->id_documento_movimiento = $idDocumento;

            // asignar el id del detalle del documento movimiento con nivel_descargo 1
            $detalle->id_item = $detalleDescargo1->id_documentos_detalle;

            // crear el detalle del documento movimiento de los subproductos del paquete
            $documentosDetallesDb = new DocumentosDetallesDb();
            $idDetalle = $documentosDetallesDb->crearDocumentoDetalle($detalle);
            $detalle->id_documentos_detalle = $idDetalle;

            $detallesDescargo3[] = $detalle;
          }
        }
      }

      $detallesDescargo2 = [];
      $detallesDescargo13 = array_merge($detallesDescargo1, $detallesDescargo3);

      // obtener los insumos de los servicios y recetas
      foreach ($detallesDescargo13 as $detalleDescargo13) {
        $producto = $productosDb->obtenerProducto($detalleDescargo13->id_producto);

        if (in_array($producto->tipo, ['SRV', 'RST'])) {
          $productosRecetaDb = new ProductosRecetaDb();

          $insumos = $productosRecetaDb->buscarInsumos($detalleDescargo13->id_producto);

          foreach ($insumos as $insumo) {
            $detalle = new DocumentoDetalle();

            $detalle->tipo_movimiento = $documentoMovimiento->tipo_movimiento;
            $detalle->nro_registro_maestro = $documentoMovimiento->nro_registro_maestro;
            $detalle->fecha_hora_registro = $documentoMovimiento->fecha_hora_registro;

            $detalle->id_profesional = $producto->tipo == 'SRV' ? $detalleDescargo13->id_profesional : null;
            $detalle->id_producto = $insumo->id_producto_insumo;
            $detalle->nivel_descargo = 2;

            $detalle->fecha = $documentoMovimiento->fecha_hora_registro;

            $detalle->cantidad = $insumo->cantidad * $detalleDescargo13->cantidad;
            $detalle->tipo_de_unidad = $insumo->tipo_de_unidad;

            // buscar el precio del insumo en la tabla de productos
            $productoInsumo = $productosDb->obtenerProducto($insumo->id_producto_insumo);
            $detalle->precio_unitario = $productoInsumo->costo_unitario;
            $detalle->precio_total = 0; // solo los detalles con nivel_descargo 1 tienen precio_total

            $detalle->id_acompanate = $detalleDescargo13->id_acompanate;

            // datos de pago o de servicio
            $detalle->id_profesional = $detalleDescargo13->id_profesional ?? null;
            $detalle->fecha_servicio = $detalleDescargo13->fecha_servicio ?? null;
            $detalle->hora_servicio = $detalleDescargo13->hora_servicio ?? null;
            $detalle->fecha_termino = $detalleDescargo13->fecha_termino ?? null;
            $detalle->hora_termino = $detalleDescargo13->hora_termino ?? null;
            $detalle->nro_comprobante = $detalleDescargo13->nro_comprobante ?? null;
            $detalle->id_recibo_de_pago = $detalleDescargo13->id_recibo_de_pago ?? null;
            $detalle->anulado = $detalleDescargo13->anulado ?? null;

            $detalle->observaciones = $detalleDescargo13->observaciones ?? null;

            $detalle->id_usuario = $documentoMovimiento->id_usuario;
            $detalle->fecha_hora_registro = $documentoMovimiento->fecha_hora_registro;

            $detalle->id_documento_movimiento = $idDocumento;

            // asignar el id del detalle del documento movimiento con nivel_descargo 1 o 3
            $detalle->id_item = $detalleDescargo13->id_documentos_detalle;

            // crear el detalle del documento movimiento de los insumos de los servicios y recetas
            $documentosDetallesDb = new DocumentosDetallesDb();
            $idDetalle = $documentosDetallesDb->crearDocumentoDetalle($detalle);
            $detalle->id_documentos_detalle = $idDetalle;

            $detallesDescargo2[] = $detalle;
          }
        }
      }

      $detallesCreados = array_merge($detallesDescargo1, $detallesDescargo2, $detallesDescargo3);

      $documentoMovimientoYDetallesCreados = $idDocumento && count($detallesCreados) >= count($detallesDescargo1);

      if ($documentoMovimientoYDetallesCreados) {
        $configDb->incrementarCorrelativo(5); // 5 es el id de las comandas en la tabla config
        $response = [
          "mensaje" => "Documento Movimiento creado correctamente",
          "resultado" => array_merge(
            [$documentosMovimientosDb->idName => intval($idDocumento)],
            (array) $documentoMovimiento,
            ["detalles" => $detallesCreados]
          )
        ];
        $code = 201;
      } else {
        $response = ["mensaje" => "Error al crear el Documento Movimiento"];
        $code = 400;
      }

      $this->sendResponse($response, $code);

    } else if ($action == 'ingreso-egreso') {

      $documentoMovimientoDelBody = $this->getBody();

      $detalles = $documentoMovimientoDelBody->detalles;
      $ruc = $documentoMovimientoDelBody->nro_documento_proveedor;
      $razonSocial = $documentoMovimientoDelBody->razon_social_proveedor;

      unset($documentoMovimientoDelBody->detalles);
      unset($documentoMovimientoDelBody->nro_documento_proveedor);
      unset($documentoMovimientoDelBody->razon_social_proveedor);

      $documentoMovimiento = new DocumentoMovimiento();
      $this->mapJsonToObj($documentoMovimientoDelBody, $documentoMovimiento);

      // comprobar que el documento movimiento tenga los datos necesarios
      $camposRequeridos = ["id_unidad_de_negocio", "tipo_movimiento", "tipo_documento", "fecha_recepcion", "motivo", "observaciones", "id_usuario"];
      $camposFaltantes = $this->comprobarCamposRequeridos($camposRequeridos, $documentoMovimientoDelBody);

      if (count($camposFaltantes) > 0) {
        $this->sendResponse(["mensaje" => "Faltan los siguientes campos: " . implode(", ", $camposFaltantes)], 400);
        return;
      }

      // comprobar que los detalles sea un array con los datos necesarios
      foreach ($detalles as $detalle) {
        $camposRequeridos = ["id_producto", "tipo_de_unidad", "cantidad", "precio_unitario"];
        $camposFaltantes = $this->comprobarCamposRequeridos($camposRequeridos, $detalle);

        if (count($camposFaltantes) > 0) {
          $this->sendResponse(["mensaje" => "Faltan los siguientes campos en uno de los detalles: " . implode(", ", $camposFaltantes)], 400);
          return;
        }
      }

      $configDb = new ConfigDb();

      $documentoMovimiento->fecha_movimiento = $configDb->obtenerFechaYHora()["fecha"];
      $documentoMovimiento->hora_movimiento = $configDb->obtenerFechaYHora()["hora"];
      $documentoMovimiento->fecha_hora_registro = $documentoMovimiento->fecha_movimiento;

      // si es una guia interna, se le asigna el codigo de guia interna
      if ($documentoMovimiento->tipo_documento == 'GI') {
        if ($documentoMovimiento->id_unidad_de_destino == null) {
          $this->sendResponse(["mensaje" => "Falta la unidad de negocio de destino"], 400);
          return;
        }
        $documentoMovimiento->nro_documento = $configDb->obtenerCodigo(25)["codigo"]; // 25 es el id de las guias internas en la tabla config
        $documentoMovimiento->fecha_documento = $documentoMovimiento->fecha_movimiento;

      } else if ($documentoMovimiento->tipo_documento == 'GR') {
        if ($documentoMovimiento->nro_documento == null) {
          $this->sendResponse(["mensaje" => "Falta el nro de documento de la Guía de Remisión"], 400);
          return;
        }
        if ($documentoMovimiento->fecha_documento == null) {
          $this->sendResponse(["mensaje" => "Falta la fecha de la Guía de Remisión"], 400);
          return;
        }
        if ($ruc == null) {
          $this->sendResponse(["mensaje" => "Falta el RUC del proveedor"], 400);
          return;
        }
      }

      // calcular los totales
      $documentoMovimiento->total = 0;

      foreach ($detalles as $detalle) {
        $detalle->precio_total = $detalle->cantidad * $detalle->precio_unitario;
        $documentoMovimiento->total += $detalle->precio_total;
      }

      $documentosMovimientosDb = new DocumentosMovimientosDb();
      $documentosDetallesDb = new DocumentosDetallesDb();

      try {
        $documentosMovimientosDb->empezarTransaccion();

        // crear el documento movimiento principal
        $idDocumentoPrincipal = $documentosMovimientosDb->crearDocumentoMovimiento($documentoMovimiento);

        $idDocumentoComplementario = null;
        // si es una guia interna, se crea el documento movimiento complementario
        if ($documentoMovimiento->tipo_documento == 'GI') {
          $documentoMovimientoComplementario = $documentoMovimiento;
          $documentoMovimientoComplementario->tipo_movimiento = $documentoMovimiento->tipo_movimiento == 'SA' ? 'IN' : 'SA';

          // se intercambian las unidades de negocio
          $idUnidadNegocioTemp = $documentoMovimientoComplementario->id_unidad_de_destino;
          $documentoMovimientoComplementario->id_unidad_de_destino = $documentoMovimientoComplementario->id_unidad_de_negocio;
          $documentoMovimientoComplementario->id_unidad_de_negocio = $idUnidadNegocioTemp;

          $idDocumentoComplementario = $documentosMovimientosDb->crearDocumentoMovimiento($documentoMovimientoComplementario);
        }

        $detallesCreados = [];
        $detallesComplementariosCreados = [];

        // crear los detalles del documento movimiento
        foreach ($detalles as $detalle) {
          $detalleTemp = $detalle;
          $detalle = new DocumentoDetalle();
          $this->mapJsonToObj($detalleTemp, $detalle);

          $detalle->id_documento_movimiento = $idDocumentoPrincipal;

          $idDocumentoDetalle = $documentosDetallesDb->crearDocumentoDetalle($detalle);
          $detalle->id_documentos_detalle = $idDocumentoDetalle;

          $detallesCreados[] = $detalle;

          // si es una guia interna, se crea el detalle del documento movimiento complementario
          if ($documentoMovimiento->tipo_documento == 'GI') {
            $detalle->id_documento_movimiento = $idDocumentoComplementario;
            $detalle->tipo_movimiento = $documentoMovimientoComplementario->tipo_movimiento;

            $idDocumentoDetalleComplementario = $documentosDetallesDb->crearDocumentoDetalle($detalle);
            $detalle->id_documentos_detalle = $idDocumentoDetalleComplementario;

            $detallesComplementariosCreados[] = $detalle;
          }
        }

        // crear o actualizar la persona
        $personasDb = new PersonasDb();
        $personaPrev = $personasDb->buscarPorNroDocumento($ruc);

        if ($personaPrev) {
          $personaActualizar = new Persona();
          $personaActualizar->apellidos = $razonSocial;

          $personasDb->actualizarPersona($personaPrev->id_persona, $personaActualizar);
        } else {
          $personaCrear = new Persona();
          $personaCrear->nro_documento = $ruc;
          $personaCrear->tipo_documento = 6;
          $personaCrear->apellidos = $razonSocial;

          // TODO: puede que se requiera consultar la dirección y la ciudad del RUC en la api de la sunat
          
          $personasDb->crearPersona($personaCrear);
        }

        // si es una guia interna, se incrementa el correlativo
        if ($documentoMovimiento->tipo_documento == 'GI') {
          $configDb->incrementarCorrelativo(25); // 25 es el id de las guias internas en la tabla config
        }

        $documentosMovimientosDb->terminarTransaccion();

        $this->sendResponse([
          "mensaje" => "Documento Movimiento creado correctamente",
          "resultado" => array_merge(
            array(
              [$documentosMovimientosDb->idName => intval($idDocumentoPrincipal)],
              (array) $documentoMovimiento,
              ["detalles" => $detallesCreados]
            ),
            $idDocumentoComplementario ? array(
              [$documentosMovimientosDb->idName => intval($idDocumentoComplementario)],
              (array) $documentoMovimientoComplementario,
              ["detalles" => $detallesComplementariosCreados]
            ) : []
          )
        ], 201);

      } catch (Exception $e) {
        $documentosMovimientosDb->cancelarTransaccion();
        $this->sendResponse(["mensaje" => "Error al crear el Documento Movimiento"], 400);
        return;
      }

    } else {
      $this->sendResponse(["mensaje" => "Acción no válida"], 400);
    }
  }

  public function update($id)
  {
    $documentoMovimientoDelBody = $this->getBody();
    $documentoMovimiento = new DocumentoMovimiento();
    $this->mapJsonToObj($documentoMovimientoDelBody, $documentoMovimiento);

    $documentosMovimientosDb = new DocumentosMovimientosDb();

    $prevDocumentoMovimiento = $documentosMovimientosDb->obtenerDocumentoMovimiento($id);
    unset($prevDocumentoMovimiento->id_central_de_costos);

    // comprobar que el documento movimiento exista
    if (!$prevDocumentoMovimiento) {
      $this->sendResponse(["mensaje" => "Documento Movimiento no encontrado"], 404);
      return;
    }

    // si los datos son iguales, no se hace nada
    if ($this->compararObjetoActualizar($documentoMovimiento, $prevDocumentoMovimiento)) {
      $this->sendResponse(["mensaje" => "No se realizaron cambios"], 200);
      return;
    }

    $result = $documentosMovimientosDb->actualizarDocumentoMovimiento($id, $documentoMovimiento);

    $response = $result ? [
      "mensaje" => "Documento Movimiento actualizado correctamente",
      "resultado" => $documentosMovimientosDb->obtenerDocumentoMovimiento($id)
    ] : ["mensaje" => "Error al actualizar el Documento Movimiento"];
    $code = $result ? 200 : 400;

    $this->sendResponse($response, $code);
  }

  public function delete($id)
  {
    $documentosMovimientosDb = new DocumentosMovimientosDb();
    $prevDocumentoMovimiento = $documentosMovimientosDb->obtenerDocumentoMovimiento($id);

    // comprobar que el documento movimiento exista
    if (!$prevDocumentoMovimiento) {
      $this->sendResponse(["mensaje" => "Documento Movimiento no encontrado"], 404);
      return;
    }

    $result = $documentosMovimientosDb->eliminarDocumentoMovimiento($id);

    $response = $result ? [
      "mensaje" => "Documento Movimiento eliminado correctamente",
      "resultado" => $prevDocumentoMovimiento
    ] : ["mensaje" => "Error al eliminar el Documento Movimiento"];
    $code = $result ? 200 : 400;

    $this->sendResponse($response, $code);
  }
}

try {
  $controller = new DocumentosMovimientoController();
  $controller->route();
} catch (Exception $e) {
  $controller->sendResponse($controller->errorResponse($e), 500);
}
?>