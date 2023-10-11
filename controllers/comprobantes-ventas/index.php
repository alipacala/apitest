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
require_once PROJECT_ROOT_PATH . "/models/UnidadesDeNegocioDb.php";

class ComprobantesVentasController extends BaseController
{
  public function get()
  {
    $params = $this->getParams();
    $nroRegistroMaestro = $params['nro_registro_maestro'] ?? null;

    $fecha = $params['fecha'] ?? null;
    $mes = $params['mes'] ?? null;
    $anio = $params['anio'] ?? null;
    $soloBolFact = isset($params['solo_bol_fact']);

    $compras = isset($params['compras']);

    $fechaInicio = $params['fecha_inicio'] ?? null;
    $fechaFin = $params['fecha_fin'] ?? null;

    $comprobantesVentasDb = new ComprobantesVentasDb();

    $result = null;

    if ($nroRegistroMaestro) {
      $result = $comprobantesVentasDb->buscarPorNroRegistroMaestro($nroRegistroMaestro);

      $result = array_map(function ($recibo) {
        return [
          "id_comprobante" => $recibo["id_comprobante_ventas"],
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
    }

    if ($compras && $fechaInicio && $fechaFin) {
      $result = $comprobantesVentasDb->listarComprasEnRangoFechas($fechaInicio, $fechaFin);

      $result = array_map(function ($comprobante) {
        $proveedor = in_array($comprobante["tipo_documento_cliente"], [1, 7]) ? $comprobante["nombres"] . ", " . $comprobante["apellidos"] : $comprobante["apellidos"];

        return [
          "id_comprobante" => $comprobante["id_comprobante_ventas"],
          "fecha" => $comprobante["fecha_documento"],
          "tipo_comprobante" => $comprobante["tipo_comprobante"],
          "nro_comprobante" => $comprobante["nro_comprobante"],
          "ruc" => $comprobante["nro_documento_cliente"],
          "proveedor" => $proveedor,
          "subtotal" => $comprobante["subtotal"],
          "igv" => $comprobante["igv"],
          "total" => $comprobante["total"],
          "percepcion" => $comprobante["valor_percepcion"],
          "gran_total" => $comprobante["gran_total"],
          "estado" => $comprobante["estado"],
          "por_pagar" => $comprobante["por_pagar"],
          "nombre_gasto" => $comprobante["nombre_gasto"],
        ];
      }, $result);
    }

    if ($fecha || ($mes && $anio)) {
      $result = $comprobantesVentasDb->listarComprobantesVentas($fecha, $mes, $anio, $soloBolFact);

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

    $comprobante = new ComprobanteVentas();
    $this->mapJsonToObj($comprobanteDelBody, $comprobante);

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

    // buscar el usuario
    $usuariosDb = new UsuariosDb();
    $usuario = $usuariosDb->obtenerUsuario($comprobante->id_usuario);

    // asignar la unidad de negocio del usuario al comprobante
    $comprobante->id_unidad_de_negocio = $usuario->id_unidad_de_negocio;

    $comprobante->tipo_movimiento = "SA";

    $configDb = new ConfigDb();

    $serie = "";
    $pre = "";

    if ($comprobante->tipo_comprobante === "03") {
      $serie = $configDb->obtenerConfig(13)->numero_correlativo; // 13 es el id de la serie de boletas
      $correlativoBoleta = $configDb->obtenerConfig(15)->numero_correlativo; // 15 es el id del correlativo de boletas
      $pre = "B";
    } else if ($comprobante->tipo_comprobante === "01") {
      $serie = $configDb->obtenerConfig(14)->numero_correlativo; // 14 es el id de la serie de facturas
      $correlativoBoleta = $configDb->obtenerConfig(16)->numero_correlativo; // 16 es el id del correlativo de facturas
      $pre = "F";
    } else {
      $serie = "1"; // TODO: tal vez no sea necesario
      $correlativoBoleta = $configDb->obtenerConfig(20)->numero_correlativo; // 20 es el id del correlativo de los pedidos
      $pre = "P";
    }

    $serie = str_pad($serie, 3, "0", STR_PAD_LEFT);
    $nro = str_pad($correlativoBoleta, 8, "0", STR_PAD_LEFT);

    $comprobante->nro_comprobante = $pre . $serie . "-" . $nro;

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
        $comprobanteDetalle = new ComprobanteDetalle();
        $this->mapJsonToObj($documentoDetalleArray, $comprobanteDetalle);

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
      $feComprobante->tipOperacion = "0101"; // TODO: pendiente de revisar
      $feComprobante->fecEmision = $comprobante->fecha_documento;
      $feComprobante->fecPago = $comprobante->fecha_documento;

      // obtener la unidad de negocio
      $unidadesDeNegocioDb = new UnidadesDeNegocioDb();
      $unidadDeNegocio = $unidadesDeNegocioDb->obtenerUnidadDeNegocio($comprobante->id_unidad_de_negocio);

      // asignar el código de la unidad de negocio
      $feComprobante->codLocalEmisor = $unidadDeNegocio->codigo_unidad_de_negocio;

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

        $nroComprobante = $comprobante->nro_comprobante;
        $documentosDetallesDb->actualizarConSubproductos($idDocumentoDetalle, $nroComprobante);
      }

      // actualizar datos de la personanaturaljuridica
      $personasDb = new PersonasDb();
      $persona = $personasDb->buscarPorDni($comprobante->nro_documento_cliente);

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
      $checking = $checkingsDb->buscarPorNroRegistroMaestro($comprobante->nro_registro_maestro);

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

  public function createCustom($action)
  {
    if ($action == 'compra') {
      $comprobanteDelBody = $this->getBody();

      $detalles = $comprobanteDelBody->detalles;
      $nombreCliente = $comprobanteDelBody->nombre_cliente;
      $lugarCliente = $comprobanteDelBody->lugar_cliente;
      $direccionCliente = $comprobanteDelBody->direccion_cliente;

      unset($comprobanteDelBody->detalles);
      unset($comprobanteDelBody->nombre_cliente);
      unset($comprobanteDelBody->lugar_cliente);

      $comprobante = new ComprobanteVentas();
      $this->mapJsonToObj($comprobanteDelBody, $comprobante);

      // comprobar que el comprobante tenga los datos necesarios
      $camposRequeridos = ["tipo_comprobante", "fecha_documento", "tipo_documento_cliente", "nro_documento_cliente", "nro_orden_pedido", "id_usuario_responsable", "id_tipo_de_gasto", "id_unidad_de_negocio", "afecto_percepcion"];
      $camposFaltantes = $this->comprobarCamposRequeridos($camposRequeridos, $comprobante);

      if (count($camposFaltantes) > 0) {
        $this->sendResponse(["mensaje" => "Faltan los siguientes campos: " . implode(", ", $camposFaltantes)], 400);
        return;
      }

      // comprobar que los detalles sea un array con los datos necesarios
      foreach ($detalles as $detalle) {
        $camposRequeridos = ["id_producto", "descripcion", "tipo_de_unidad", "cantidad", "precio_unitario"];
        $camposFaltantes = $this->comprobarCamposRequeridos($camposRequeridos, $detalle);

        if (count($camposFaltantes) > 0) {
          $this->sendResponse(["mensaje" => "Faltan los siguientes campos en uno de los detalles: " . implode(", ", $camposFaltantes)], 400);
          return;
        }
      }

      $comprobante->tipo_movimiento = "IN";

      $configDb = new ConfigDb();

      if ($comprobante->tipo_comprobante == '00') {
        $serie = "1"; // TODO: tal vez no sea necesario
        $correlativoBoleta = $configDb->obtenerConfig(20)->numero_correlativo; // 20 es el id del correlativo de los pedidos
        $pre = "P";

        $serie = str_pad($serie, 3, "0", STR_PAD_LEFT);
        $nro = str_pad($correlativoBoleta, 8, "0", STR_PAD_LEFT);

        $comprobante->nro_comprobante = $pre . $serie . "-" . $nro;
      }

      $comprobante->monto_inicial = 0;
      $comprobante->por_pagar = 0;

      $comprobante->id_usuario = $comprobante->id_usuario_responsable;
      $comprobante->hora_documento = $configDb->obtenerFechaYHora()["hora"];

      $comprobante->fecha_hora_registro = $configDb->obtenerFechaYHora()["fecha_y_hora"];

      // crear el comprobante y los detalles
      $comprobantesVentasDb = new ComprobantesVentasDb();
      $comprobantesDetallesDb = new ComprobantesDetallesDb();
      $documentosDetallesDb = new DocumentosDetallesDb();
      $productosDb = new ProductosDb();

      $porcentajeIGV = $configDb->obtenerConfig(24)->numero_correlativo; // 17 es el id del porcentaje de igv para las compras
      $porcentajePercepcion = $configDb->obtenerConfig(22)->numero_correlativo; // 22 es el id del porcentaje de percepcion

      $comprobantesDetalles = [];

      // calcular el subtotal sumando los precios de los detalles
      $comprobante->subtotal = 0;
      foreach ($detalles as $detalle) {
        $comprobante->subtotal += $detalle->precio_unitario * $detalle->cantidad;
        $comprobantesDetalles[] = $detalle;
      }      

      // si es una factura
      if ($comprobante->tipo_comprobante == '01') {
        $comprobante->porcentaje_igv = $porcentajeIGV / 100;
        $comprobante->igv = $comprobante->subtotal * $comprobante->porcentaje_igv;
      } else {
        $comprobante->porcentaje_igv = 0;
        $comprobante->igv = 0;
      }

      $comprobante->total = $comprobante->subtotal + $comprobante->igv;
      $comprobante->estado = 1;

      if ($comprobante->afecto_percepcion) {
        $comprobante->porcentaje_percepcion = $porcentajePercepcion / 100;
        $comprobante->valor_percepcion = $comprobante->total * $comprobante->porcentaje_percepcion;
        $comprobante->gran_total = $comprobante->total + $comprobante->valor_percepcion;
      } else {
        $comprobante->gran_total = $comprobante->total;
      }

      $comprobante->por_pagar = $comprobante->gran_total;

      try {
        $comprobantesVentasDb->empezarTransaccion();

        $idComprobante = $comprobantesVentasDb->crearComprobanteVentas($comprobante);

        $comprobantesDetallesCreados = [];
        $documentosDetallesCreados = [];

        foreach ($comprobantesDetalles as $comprobanteDetalle) {
          $comprobanteDetalleArray = get_object_vars($comprobanteDetalle);
          $comprobanteDetalle = new ComprobanteDetalle();
          $this->mapJsonToObj($comprobanteDetalleArray, $comprobanteDetalle);

          $comprobanteDetalle->id_comprobante_ventas = $idComprobante;
          $comprobanteDetalle->id_usuario = $comprobante->id_usuario;
          $comprobanteDetalle->tipo_movimiento = $comprobante->tipo_movimiento;

          $comprobanteDetalle->precio_total = $comprobanteDetalle->precio_unitario * $comprobanteDetalle->cantidad;

          $documentoDetalle = new DocumentoDetalle();
          // convertir a documentoDetalle
          if ($comprobanteDetalle->id_producto != 0) {
            $documentoDetalle->nro_comprobante = $comprobante->nro_comprobante;
            $documentoDetalle->id_producto = $comprobanteDetalle->id_producto;
            $documentoDetalle->tipo_movimiento = $comprobante->tipo_movimiento;
            $documentoDetalle->id_usuario = $comprobante->id_usuario;
            $documentoDetalle->fecha_hora_registro = $configDb->obtenerFechaYHora()["fecha_y_hora"];

            $documentoDetalle->cantidad = $comprobanteDetalle->cantidad;
            $documentoDetalle->precio_unitario = $comprobanteDetalle->precio_unitario;
            $documentoDetalle->tipo_de_unidad = $comprobanteDetalle->tipo_de_unidad;

            $documentoDetalle->precio_total = $comprobanteDetalle->precio_total;

            $idDocumentoDetalle = $documentosDetallesDb->crearDocumentoDetalle($documentoDetalle);
            $documentoDetalle->id_documentos_detalle = $idDocumentoDetalle;

            // actualizar el costo unitario del producto con el precio unitario del detalle con IGV
            $producto = $productosDb->obtenerProducto($comprobanteDetalle->id_producto);

            if ($producto) {
              $productoActualizar = new Producto();
              $productoActualizar->costo_unitario = $comprobanteDetalle->precio_unitario * (1 + $comprobante->porcentaje_igv);

              $productosDb->actualizarProducto($producto->id_producto, $productoActualizar);
            } else {
              $this->sendResponse(["mensaje" => "No se encontró el producto con id " . $comprobanteDetalle->id_producto], 400);
              return;
            }

            // relaciona el comprobanteDetalle con el documentoDetalle
            $comprobanteDetalle->id_documentos_detalle = $documentoDetalle->id_documentos_detalle;

            $documentosDetallesCreados[] = $documentoDetalle;
          }

          $idComprobanteDetalle = $comprobantesDetallesDb->crearComprobanteDetalle($comprobanteDetalle);
          $comprobanteDetalle->id_comprobante_detalle = $idComprobanteDetalle;

          $comprobantesDetallesCreados[] = $idComprobanteDetalle;
        }

        // crear o actualizar la persona
        $personasDb = new PersonasDb();
        $personaPrev = $personasDb->buscarPorDni($comprobante->nro_documento_cliente);

        if ($personaPrev) {
          $personaActualizar = new Persona();
          $personaActualizar->direccion = $direccionCliente;
          $personaActualizar->ciudad = $lugarCliente;

          $personasDb->actualizarPersona($personaPrev->id_persona, $personaActualizar);
        } else {
          $personaCrear = new Persona();
          $personaCrear->nro_documento = $comprobante->nro_documento_cliente;
          $personaCrear->tipo_documento = $comprobante->tipo_documento_cliente;
          if (in_array($personaCrear->tipo_documento, [1, 7])) {

            // buscar la última coma
            $posicionUltimaComa = strrpos($nombreCliente, ",");

            if ($posicionUltimaComa !== false) {
              $apellidos = trim(substr($nombreCliente, 0, $posicionUltimaComa));
              $nombres = trim(substr($nombreCliente, $posicionUltimaComa + 1));
            } else {
              // buscar el último espacio en blanco
              $posicionUltimoEspacio = strrpos($nombreCliente, " ");
              if ($posicionUltimoEspacio !== false) {
                $apellidos = trim(substr($nombreCliente, 0, $posicionUltimoEspacio));
                $nombres = trim(substr($nombreCliente, $posicionUltimoEspacio + 1));
              } else {
                $apellidos = $nombreCliente;
                $nombres = "";
              }
            }

            $personaCrear->nombres = $nombres;
            $personaCrear->apellidos = $apellidos;
          } else {
            $personaCrear->apellidos = $nombreCliente;
          }
          $personaCrear->direccion = $direccionCliente;
          $personaCrear->ciudad = $lugarCliente;

          $personasDb->crearPersona($personaCrear);
        }

        $seHaCreadoComprobante = $idComprobante;

        // incrementar el correlativo
        if ($comprobante->tipo_comprobante == '00') {
          $configDb->incrementarCorrelativo(20); // 20 es el id del correlativo de pedidos
        } else if ($comprobante->tipo_comprobante == '05') {
          $configDb->incrementarCorrelativo(23); // 23 es el id del correlativo de comprobantes de servicios
        }

        $comprobantesVentasDb->terminarTransaccion();

        if ($seHaCreadoComprobante) {
          $response = [
            "mensaje" => "Comprobante y sus detalles se han creado correctamente",
            "resultado" => [
              "comprobante" => array_merge([$comprobantesVentasDb->idName => intval($idComprobante)], (array) $comprobante, ["detalles" => $comprobantesDetallesCreados]),
              "documentos_detalles" => $documentosDetallesCreados
            ]
          ];
          $code = 201;
        } else {
          $response = ["mensaje" => "Error al crear el Comprobante"];
          $code = 400;
        }

        $this->sendResponse($response, $code);
      } catch (Exception $e) {
        $comprobantesVentasDb->cancelarTransaccion();
        $newException = new Exception("Error al crear el comprobante, los detalles de comprobante, los detalles de documento, o actualizar/crear la persona", 0, $e);
        throw $newException;
      }
    } else {
      $this->sendResponse(["mensaje" => "Acción no encontrada"], 404);
    }
  }

  public function update($id)
  {
    $comprobanteDelBody = $this->getBody();
    $comprobante = new ComprobanteVentas();
    $this->mapJsonToObj($comprobanteDelBody, $comprobante);

    $comprobantesVentasDb = new ComprobantesVentasDb();

    $prevComprobanteVentas = $comprobantesVentasDb->obtenerComprobanteVentas($id);
    unset($prevComprobanteVentas->id_comprobante);

    // comprobar que el comprobante exista
    if (!$prevComprobanteVentas) {
      $this->sendResponse(["mensaje" => "Comprobante de Ventas no encontrada"], 404);
      return;
    }

    // si los datos son iguales, no se hace nada
    if ($this->compararObjetoActualizar($comprobante, $prevComprobanteVentas)) {
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

  public function deleteCustom($id, $action)
  {
    if ($action == 'compra') {
      $comprobantesVentasDb = new ComprobantesVentasDb();
      $comprobante = $comprobantesVentasDb->obtenerComprobanteVentas($id);

      if (!$comprobante) {
        $this->sendResponse(["mensaje" => "Comprobante de Ventas no encontrada"], 404);
        return;
      }

      $seEliminoComprobante = true;

      try {
        $comprobantesVentasDb->empezarTransaccion();

        // borrar los detalles de comprobante
        $comprobantesDetallesDb = new ComprobantesDetallesDb();
        $comprobanteDetallesEliminados = $comprobantesDetallesDb->eliminarComprobanteDetallePorIdComprobante($id);

        $documentosDetallesDb = new DocumentosDetallesDb();
        $documentosDetallesActualizados = $documentosDetallesDb->eliminarPorNroComprobante($comprobante->nro_comprobante);

        // eliminar el comprobante
        $comprobanteEliminado = $comprobantesVentasDb->eliminarComprobanteVentas($id);

        $comprobantesVentasDb->terminarTransaccion();

      } catch (Exception $e) {
        $comprobantesVentasDb->cancelarTransaccion();
        $seEliminoComprobante = false;
        $newException = new Exception("Error al anular el comprobante, el fe_comprobante o actualizar los detalles de documento", 0, $e);
        throw $newException;
      }

      $response = $seEliminoComprobante ? [
        "mensaje" => "Comprobante de Ventas eliminado correctamente",
        "resultado" => $comprobante
      ] : ["mensaje" => "Error al eliminar el Comprobante de Ventas"];
      $code = $seEliminoComprobante ? 200 : 400;

      $this->sendResponse($response, $code);
    } else {
      $this->sendResponse(["mensaje" => "Acción no permitida"], 404);
    }
  }
}

try {
  $controller = new ComprobantesVentasController();
  $controller->route();
} catch (Exception $e) {
  $controller->sendResponse($controller->errorResponse($e), 500);
}
?>