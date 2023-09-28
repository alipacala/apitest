<?php
require_once __DIR__ . "/../../inc/bootstrap.php";
require_once PROJECT_ROOT_PATH . "/controllers/BaseController.php";

require_once PROJECT_ROOT_PATH . "/models/ComprobantesVentasDb.php";
require_once PROJECT_ROOT_PATH . "/models/ComprobantesDetallesDb.php";
require_once PROJECT_ROOT_PATH . "/models/FeComprobantesDb.php";
require_once PROJECT_ROOT_PATH . "/models/FeItemsDb.php";
require_once PROJECT_ROOT_PATH . "/models/ProductosDb.php";
require_once PROJECT_ROOT_PATH . "/models/ConfigDb.php";
require_once PROJECT_ROOT_PATH . "/models/DocumentosDetallesDb.php";
require_once PROJECT_ROOT_PATH . "/models/PersonasDb.php";
require_once PROJECT_ROOT_PATH . "/models/CheckingsDb.php";
require_once PROJECT_ROOT_PATH . "/models/RecibosPagoDb.php";
require_once PROJECT_ROOT_PATH . "/models/UsuariosDb.php";

class ComprobantesVentasController extends BaseController
{
  public function get()
  {
    $params = $this->getParams();
    $nroRegistroMaestro = $params['nro_registro_maestro'] ?? null;

    $fecha = $params['fecha'] ?? null;
    $mes = $params['mes'] ?? null;
    $anio = $params['anio'] ?? null;
    $soloBolFact = boolval(($params['solo_bol_fact'] ?? null) === "");

    $comprobantesVentasDb = new ComprobantesVentasDb();
    $result = $comprobantesVentasDb->listarComprobantesVentas($nroRegistroMaestro, $fecha, $mes, $anio, $soloBolFact);

    if ($nroRegistroMaestro) {
      $result = array_map(function ($recibo) {
        return [
          "fecha_comprobante" => $recibo["fecha_documento"],
          "nro_comprobante" => $recibo["nro_comprobante"],
          "nro_doc_cliente" => $recibo["nro_documento_cliente"],
          "nombre_razon_social" => $recibo["rznSocialUsuario"],
          "id_comprobante_ventas" => $recibo["id_comprobante_ventas"],
          "tipo_comprobante" => $recibo["tipo_comprobante"],
          "tipo_pago" => $recibo["medio_pago"],
          "total_comprobante" => $recibo["total_comprobante"],
          "total_recibo" => $recibo["total_recibo"],
          "por_pagar" => $recibo["por_pagar"],
          "estado" => $recibo["estado"]
        ];
      }, $result);

    } else {

      $result = array_map(function ($comprobante) {
        $tiposDoc = [
          "00" => "PD",
          "01" => "FA",
          "03" => "BO"
        ];

        return [
          "fecha" => $comprobante["fecha_documento"],
          "tipo_doc" => $tiposDoc[$comprobante["tipo_comprobante"]],
          "nro_comprobante" => $comprobante["nro_comprobante"],
          "nombre" => $comprobante["rznSocialUsuario"],
          "dni_ruc" => $comprobante["nro_documento_cliente"],
          "monto" => $comprobante["total"],
          "estado" => $comprobante["estado"] ? null : "ANULADO",
          "usuario_reg" => $comprobante["usuario"],
          "id" => $comprobante["id_comprobante_ventas"]
        ];
      }, $result);
    }

    $this->sendResponse($result, 200);
  }

  public function getOne($id)
  {
    $comprobantesVentasDb = new ComprobantesVentasDb();
    $comprobante = $comprobantesVentasDb->obtenerComprobanteVentas($id);

    $response = $comprobante ? $comprobante : ["mensaje" => "Comprobante de Ventas no encontrada"];
    $code = $comprobante ? 200 : 404;

    $this->sendResponse($response, $code);
  }

  public function create()
  {
    $comprobanteDelBody = $this->getBody();

    $detalles = $comprobanteDelBody->detalles;
    $nombre = $comprobanteDelBody->nombre;
    $lugarCliente = $comprobanteDelBody->lugar_cliente;

    unset($comprobanteDelBody->detalles);
    unset($comprobanteDelBody->nombre);
    unset($comprobanteDelBody->lugar_cliente);

    $comprobante = $this->mapJsonToClass($comprobanteDelBody, ComprobanteVentas::class);

    // comprobar que el comprobante tenga los datos necesarios
    $camposRequeridos = ["tipo_comprobante", "tipo_documento_cliente", "id_usuario", "nro_registro_maestro"];
    $camposFaltantes = $this->comprobarCamposRequeridos($camposRequeridos, $comprobante);

    if (count($camposFaltantes) > 0) {
      $this->sendResponse(["mensaje" => "Faltan los siguientes campos: " . implode(", ", $camposFaltantes)], 400);
      return;
    }

    // comprobar que los detalles sea un array de ids de documentos detalles
    foreach ($detalles as $detalleId) {
      if (!is_int($detalleId)) {
        $this->sendResponse(["mensaje" => "El campo detalles debe ser un array de ids de documentos detalles"], 400);
        return;
      }
    }

    $comprobante->id_unidad_de_negocio = 3; // es el id del Hotel Arenas Spa
    $comprobante->tipo_movimiento = "SA";

    $configDb = new ConfigDb();

    $serie = "";

    if ($comprobante->tipo_comprobante === "03") {
      $serie = $configDb->obtenerConfig(13)->numero_correlativo; // 13 es el id de la serie de boletas
      $correlativoBoleta = $configDb->obtenerConfig(15)->numero_correlativo; // 15 es el id del correlativo de boletas
      $serie = "B";
    } else if ($comprobante->tipo_comprobante === "01") {
      $serie = $configDb->obtenerConfig(14)->numero_correlativo; // 14 es el id de la serie de facturas
      $correlativoBoleta = $configDb->obtenerConfig(16)->numero_correlativo; // 16 es el id del correlativo de facturas
      $serie = "F";
    } else {
      $serie = "";
      $correlativoBoleta = $configDb->obtenerConfig(20)->numero_correlativo; // 20 es el id del correlativo de los pedidos
      $serie = "P";
    }

    $serie .= str_pad($serie, 3, "0", STR_PAD_LEFT);
    $nro = str_pad($correlativoBoleta, 8, "0", STR_PAD_LEFT);

    $comprobante->nro_comprobante = $serie . "-" . $nro;

    $comprobante->fecha_documento = $configDb->obtenerFechaYHora()["fecha"];
    $comprobante->hora_documento = $configDb->obtenerFechaYHora()["hora"];

    $comprobante->monto_inicial = 0;
    $comprobante->por_pagar = 0;

    $comprobante->fecha_hora_registro = $configDb->obtenerFechaYHora()["fecha_y_hora"];

    // crear el comprobante y los detalles
    $documentosDetallesDb = new DocumentosDetallesDb();
    $comprobantesVentasDb = new ComprobantesVentasDb();

    $porcentajeIGV = $configDb->obtenerConfig(17)->numero_correlativo; // 17 es el id del porcentaje de igv, 2 es el índice del valor en el array

    try {
      $documentosDetallesDb->empezarTransaccion();
      $documentosDetalles = [];

      // calcular el costo unitario sumando los precios de los detalles
      $comprobante->subtotal = 0;
      foreach ($detalles as $detalle) {
        $documentoDetalle = $documentosDetallesDb->obtenerDocumentoDetalle($detalle);
        if (!$documentoDetalle) {
          $this->sendResponse(["mensaje" => "No se encontró el detalle con id " . $detalle], 400);
          return;
        }
        $comprobante->subtotal += $documentoDetalle->precio_total;
        $documentosDetalles[] = $documentoDetalle;
      }

      $comprobante->porcentaje_igv = $porcentajeIGV / 100;
      $comprobante->subtotal = $comprobante->subtotal / (1 + $comprobante->porcentaje_igv);
      $comprobante->igv = $comprobante->subtotal * $comprobante->porcentaje_igv;
      $comprobante->total = $comprobante->subtotal + $comprobante->igv;
      $comprobante->por_pagar = $comprobante->total;
      $comprobante->estado = 1;

      $idComprobante = $comprobantesVentasDb->crearComprobanteVentas($comprobante);

      $comprobantesDetallesDb = new ComprobantesDetallesDb();
      $comprobantesDetallesCreados = [];

      foreach ($documentosDetalles as $documentoDetalle) {
        // convertir el documentoDetalle en un comprobanteDetalle
        $documentoDetalleArray = get_object_vars($documentoDetalle);
        $comprobanteDetalle = $this->mapJsonToClass($documentoDetalleArray, ComprobanteDetalle::class);
        $comprobanteDetalle->id_comprobante_ventas = $idComprobante;
        $comprobanteDetalle->id_usuario = $comprobante->id_usuario;

        $idDetalle = $comprobantesDetallesDb->crearComprobanteDetalle($comprobanteDetalle);

        $comprobanteDetalle->id_receta = $idDetalle;
        $comprobantesDetallesCreados[] = $comprobanteDetalle;
      }

      $feComprobante = new FeComprobante();

      $feComprobante->NroMov = $idComprobante;
      // obtener los 4 primeros caracteres del nro_comprobante
      $feComprobante->serieComprobante = $serie;
      // obtener los 8 últimos caracteres del nro_comprobante
      $feComprobante->nroComprobante = $nro;
      $feComprobante->tipOperacion = "0101";
      $feComprobante->fecEmision = $comprobante->fecha_documento;
      $feComprobante->fecPago = $comprobante->fecha_documento;
      $feComprobante->codLocalEmisor = "0000";
      $feComprobante->TipDocUsuario = $comprobante->tipo_documento_cliente === "D" ? "1" : "6";
      $feComprobante->rznSocialUsuario = $nombre;

      $feComprobante->tipMoneda = "PEN";
      $feComprobante->mtoOperGravadas = $comprobante->subtotal;
      $feComprobante->mtoIGV = $comprobante->igv;
      $feComprobante->mtoImpVenta = $comprobante->total;

      // crear el fe_comprobante y los fe_items

      $feItemsDb = new FeItemsDb();
      $feComprobantesDb = new FeComprobantesDb();
      $productosDb = new ProductosDb();

      $idFeComprobante = $feComprobantesDb->crearFeComprobante($feComprobante);

      $feItemsCreados = [];

      foreach ($comprobantesDetallesCreados as $comprobanteDetalle) {
        $feItem = new FeItem();

        $feItem->NroMov = $idComprobante;
        $feItem->codUnidadMedida = "NIU";
        $feItem->ctdUnidadItem = $comprobanteDetalle->cantidad;

        $feItem->serieComprobante = $serie;
        $feItem->nroComprobante = $nro;

        $producto = $productosDb->obtenerProducto($comprobanteDetalle->id_producto);
        $feItem->desItem = $producto->nombre_producto;

        $feItem->mtoValorUnitario = $comprobanteDetalle->precio_unitario / (1 + $comprobante->porcentaje_igv);

        $feItem->mtoIgvItem = $feItem->mtoValorUnitario * $comprobante->porcentaje_igv;
        $feItem->tipAfeIGV = $porcentajeIGV;
        $feItem->mtoPrecioVentaItem = $feItem->mtoValorUnitario + $feItem->mtoIgvItem;
        $feItem->mtoValorVentaItem = $feItem->mtoValorUnitario * $feItem->ctdUnidadItem;

        $idDetalle = $feItemsDb->crearFeItem($feItem);

        $feItem->IdfeItem = $idDetalle;
        $feItemsCreados[] = $feItem;
      }

      // actualizar los detalles de documentos
      foreach ($documentosDetalles as $documentoDetalle) {
        $idDocumentoDetalle = $documentoDetalle->id_documentos_detalle;
        unset($documentoDetalle->id_documentos_detalle);

        $detalleAActualizar = new DocumentoDetalle();
        $detalleAActualizar->nro_comprobante = $comprobante->nro_comprobante;

        $documentosDetallesDb->actualizarDocumentoDetalle($idDocumentoDetalle, $detalleAActualizar, true);
      }

      // actualizar datos de la personanaturaljuridica
      $personasDb = new PersonasDb();
      $persona = $personasDb->listarPersonas($comprobante->nro_documento_cliente);

      if ($persona) {
        $personaActualizar = new Persona();

        $personaActualizar->direccion = $comprobante->direccion_cliente;
        $personaActualizar->ciudad = $lugarCliente;

        $personasDb->actualizarPersona($persona->id_persona, $personaActualizar);
      } else {
        // crear la personanaturaljuridica
        $personaCrear = new Persona();
        $personaCrear->nro_documento = $comprobante->nro_documento_cliente;
        $personaCrear->tipo_documento = $comprobante->tipo_documento_cliente;

        // buscar la última coma
        $posicionUltimaComa = strrpos($nombre, ",");

        if ($posicionUltimaComa !== false) {
          $apellidos = trim(substr($nombre, 0, $posicionUltimaComa));
          $nombres = trim(substr($nombre, $posicionUltimaComa + 1));
        } else {
          // buscar el último espacio en blanco
          $posicionUltimoEspacio = strrpos($nombre, " ");
          if ($posicionUltimoEspacio !== false) {
            $apellidos = trim(substr($nombre, 0, $posicionUltimoEspacio));
            $nombres = trim(substr($nombre, $posicionUltimoEspacio + 1));
          } else {
            $apellidos = $nombre;
            $nombres = "";
          }
        }

        $personaCrear->apellidos = $apellidos;
        $personaCrear->nombres = $nombres;
        $personaCrear->direccion = $comprobante->direccion_cliente;
        $personaCrear->ciudad = $lugarCliente;

        $personasDb->crearPersona($personaCrear);
      }

      // actualizar datos del checkin
      $checkingsDb = new CheckingsDb();
      $checking = $checkingsDb->listarCheckings($comprobante->nro_registro_maestro);

      if ($checking) {
        $checkingActualizar = new Checking();

        $checkingActualizar->tipo_documento = $comprobante->tipo_documento_cliente;
        $checkingActualizar->nro_documento = $comprobante->nro_documento_cliente;
        $checkingActualizar->razon_social = $nombre;
        $checkingActualizar->direccion_comprobante = $comprobante->direccion_cliente;

        $checkingsDb->actualizarChecking($checking->id_checkin, $checkingActualizar);
      }

      $documentosDetallesDb->terminarTransaccion();
    } catch (Exception $e) {
      $documentosDetallesDb->cancelarTransaccion();
      $newException = new Exception("Error al crear el comprobante, el fe_comprobante o actualizar los detalles de documento", 0, $e);
      throw $newException;
    }

    $comprobanteYDetallesCreados = $idComprobante && $idFeComprobante && count($detalles) === count($comprobantesDetallesCreados) && count($comprobantesDetallesCreados) === count($feItemsCreados);

    if ($comprobanteYDetallesCreados) {
      $configDb = new ConfigDb();

      if ($comprobante->tipo_comprobante === "03") {
        $configDb->incrementarCorrelativo(15); // 15 es el id del correlativo de boletas
      } else if ($comprobante->tipo_comprobante === "01") {
        $configDb->incrementarCorrelativo(16); // 16 es el id del correlativo de facturas
      } else {
        $configDb->incrementarCorrelativo(20); // 20 es el id del correlativo de pedidos
      }

      $response = [
        "mensaje" => "Comprobante, Fe_Comprobante y sus detalles se han creado correctamente",
        "resultado" => [
          "comprobante" => array_merge([$comprobantesVentasDb->idName => intval($idComprobante)], (array) $comprobante, ["detalles" => $comprobantesDetallesCreados]),
          "fe_comprobante" => array_merge([$feComprobantesDb->idName => intval($idFeComprobante)], (array) $feComprobante, ["detalles" => $feItemsCreados])
        ]
      ];
      $code = 201;
    } else {
      $response = ["mensaje" => "Error al crear el Comprobante o el Fe_Comprobante"];
      $code = 400;
    }

    $this->sendResponse($response, $code);
  }

  public function update($id)
  {
    $comprobanteDelBody = $this->getBody();
    $comprobante = $this->mapJsonToClass($comprobanteDelBody, ComprobanteVentas::class);

    $comprobantesVentasDb = new ComprobantesVentasDb();

    $prevComprobanteVentas = $comprobantesVentasDb->obtenerComprobanteVentas($id);
    unset($prevComprobanteVentas->id_comprobante);

    // comprobar que el comprobante exista
    if (!$prevComprobanteVentas) {
      $this->sendResponse(["mensaje" => "Comprobante de Ventas no encontrada"], 404);
      return;
    }

    // si los datos son iguales, no se hace nada
    if ($prevComprobanteVentas == $comprobante) {
      $this->sendResponse(["mensaje" => "No se realizaron cambios"], 200);
      return;
    }

    $result = $comprobantesVentasDb->actualizarComprobanteVentas($id, $comprobante);

    $response = $result ? [
      "mensaje" => "Comprobante de Ventas actualizada correctamente",
      "resultado" => $comprobantesVentasDb->obtenerComprobanteVentas($id)
    ] : ["mensaje" => "Error al actualizar la Comprobante de Ventas"];
    $code = $result ? 200 : 400;

    $this->sendResponse($response, $code);
  }

  public function updatePartial($id, $action = null)
  {
    if ($action === "anular") {

      $body = $this->getBody();

      // comprobar que el body tenga el campo usuario y contraseña
      $camposRequeridos = ["usuario", "clave"];
      $camposFaltantes = $this->comprobarCamposRequeridos($camposRequeridos, $body);

      if (count($camposFaltantes) > 0) {
        $this->sendResponse(["mensaje" => "Faltan los siguientes campos: " . implode(", ", $camposFaltantes)], 400);
        return;
      }

      // comprobar que el usuario y contraseña sean correctos
      $usuariosDb = new UsuariosDb();
      $result = $usuariosDb->loginAdministrador($body->usuario, $body->clave);

      if (!$result[0]["logueado"]) {
        $this->sendResponse(["mensaje" => "Usuario o contraseña incorrectos"], 400);
        return;
      }

      $comprobantesVentasDb = new ComprobantesVentasDb();
      $prevComprobanteVentas = $comprobantesVentasDb->obtenerComprobanteVentas($id);

      // comprobar que el comprobante exista
      if (!$prevComprobanteVentas) {
        $this->sendResponse(["mensaje" => "Comprobante de Ventas no encontrada"], 404);
        return;
      }

      $seAnuloComprobante = true;

      try {
        $comprobantesVentasDb->empezarTransaccion();

        // borrar los detalles de comprobante
        $comprobantesDetallesDb = new ComprobantesDetallesDb();
        $comprobanteDetallesEliminados = $comprobantesDetallesDb->eliminarComprobanteDetallePorIdComprobante($id);

        // borrar el fe_comprobante y los fe_items
        $feItemsDb = new FeItemsDb();
        $feItemsEliminados = $feItemsDb->eliminarFeItemsPorIdComprobante($id);

        $feComprobantesDb = new FeComprobantesDb();
        $feComprobanteAnulado = $feComprobantesDb->anularFeComprobante($id);

        $documentosDetallesDb = new DocumentosDetallesDb();
        $documentosDetallesActualizados = $documentosDetallesDb->deshacerPagoDocumentosDetalles($id);

        $checkingsDb = new CheckingsDb();
        $checkingAbierto = $checkingsDb->deshacerCerradoChecking($prevComprobanteVentas->nro_registro_maestro);

        // borrar los recibos de pago
        $recibosPagoDb = new RecibosPagoDb();
        $recibosEliminados = $recibosPagoDb->eliminarRecibosPagoPorComprobante($id);

        // anular el comprobante
        $comprobanteAnulado = $comprobantesVentasDb->anularComprobanteVentas($id);

        $comprobantesVentasDb->terminarTransaccion();

      } catch (Exception $e) {
        $comprobantesVentasDb->cancelarTransaccion();
        $seAnuloComprobante = false;
        $newException = new Exception("Error al anular el comprobante, el fe_comprobante o actualizar los detalles de documento", 0, $e);
        throw $newException;
      }

      // $seAnuloComprobante = $comprobanteDetallesEliminados && $feItemsEliminados && $feComprobanteAnulado && $recibosEliminados && $documentosDetallesActualizados && $comprobanteAnulado;

      $comprobanteActualizado = $comprobantesVentasDb->obtenerComprobanteVentas($id);

      $response = $seAnuloComprobante ? [
        "mensaje" => "Comprobante de Ventas anulado correctamente",
        "resultado" => $comprobanteActualizado
      ] : ["mensaje" => "Error al anular el Comprobante de Ventas"];
      $code = $seAnuloComprobante ? 200 : 400;

      $this->sendResponse($response, $code);

    } else {
      $this->sendResponse(["mensaje" => "Acción no permitida"], 400);
    }
  }
}

try {
  $controller = new ComprobantesVentasController();
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