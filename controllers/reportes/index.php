<?php
require_once __DIR__ . "/../../inc/bootstrap.php";
require_once PROJECT_ROOT_PATH . "/controllers/BaseController.php";

require_once PROJECT_ROOT_PATH . "/models/ReportesDb.php";
require_once PROJECT_ROOT_PATH . "/models/CheckingsDb.php";
require_once PROJECT_ROOT_PATH . "/models/GruposDeLaCartaDb.php";
require_once PROJECT_ROOT_PATH . "/models/ComprobantesVentasDb.php";
require_once PROJECT_ROOT_PATH . "/models/UsuariosDb.php";
require_once PROJECT_ROOT_PATH . "/models/ConfigDb.php";
require_once PROJECT_ROOT_PATH . "/models/ProductosDb.php";
require_once PROJECT_ROOT_PATH . "/models/DocumentosDetallesDb.php";
require_once PROJECT_ROOT_PATH . "/models/RoomingDb.php";
require_once PROJECT_ROOT_PATH . "/models/TerapistasDb.php";
require_once PROJECT_ROOT_PATH . "/models/AcompanantesDb.php";
require_once PROJECT_ROOT_PATH . "/models/PersonasDb.php";

require_once PROJECT_ROOT_PATH . "/fpdf/fpdf.php";

require_once PROJECT_ROOT_PATH . "/controllers/reportes/ReporteEstadoCuenta.php";
require_once PROJECT_ROOT_PATH . "/controllers/reportes/ReporteRegistroVentas.php";
require_once PROJECT_ROOT_PATH . "/controllers/reportes/ComprobanteImprimible.php";
require_once PROJECT_ROOT_PATH . "/controllers/reportes/ReporteDiarioCaja.php";
require_once PROJECT_ROOT_PATH . "/controllers/reportes/ReporteDiarioDetalles.php";
require_once PROJECT_ROOT_PATH . "/controllers/reportes/ReporteListadoCatalogo.php";
require_once PROJECT_ROOT_PATH . "/controllers/reportes/ReporteCompras.php";
require_once PROJECT_ROOT_PATH . "/controllers/reportes/ReporteConsultaProductosInsumos.php";
require_once PROJECT_ROOT_PATH . "/controllers/reportes/ReporteKardex.php";
require_once PROJECT_ROOT_PATH . "/controllers/reportes/ReportePedido.php";
require_once PROJECT_ROOT_PATH . "/controllers/reportes/ReporteDesayunos.php";
require_once PROJECT_ROOT_PATH . "/controllers/reportes/ReporteLiquidacionServicios.php";
require_once PROJECT_ROOT_PATH . "/controllers/reportes/ReporteFichaChecking.php";

class ReportesController extends BaseController
{
  public function get()
  {
    $params = $this->getParams();
    $tipo = $params['tipo'] ?? null;

    $fecha = $params['fecha'] ?? null;

    $mes = $params['mes'] ?? null;
    $anio = $params['anio'] ?? null;
    $soloBolFact = isset($params['solo_bol_fact']);
    $idUsuario = $params['id_usuario'] ?? null;

    $consumos = $params['consumos'] ?? null;
    $nroRegistroMaestro = $params['nro_registro_maestro'] ?? null;

    $nroComprobante = $params['nro_comprobante'] ?? null;

    $idGrupo = $params['id_grupo'] ?? null;

    $fechaInicio = $params['fecha_inicio'] ?? null;
    $fechaFin = $params['fecha_fin'] ?? null;

    $nombreProducto = $params['nombre_producto'] ?? null;
    $tipoProducto = $params['tipo_producto'] ?? null;

    $idProducto = $params['id_producto'] ?? null;
    $reportesDb = new ReportesDb();

    $idProfesional = $params['id_profesional'] ?? null;

    if ($tipo == "caja") {
      $result = $reportesDb->obtenerReporteDiarioCaja($fecha);

      $result = array_map(function ($recibo) {
        return [
          "fecha_comprobante" => $recibo["fecha_documento"],
          "tipo_comprobante" => $recibo["tipo_comprobante"],
          "nro_comprobante" => $recibo["nro_comprobante"],
          "nro_doc_cliente" => $recibo["nro_documento_cliente"],
          "nombre_razon_social" => $recibo["rznSocialUsuario"],
          "total_comprobante" => $recibo["total_comprobante"],
          "nro_registro_maestro" => $recibo["nro_registro_maestro"],
          "por_pagar" => $recibo["por_pagar"],
          "tipo_pago" => $recibo["medio_pago"],
          "total_recibo" => $recibo["total_recibo"],
          "nro_cierre_turno" => $recibo["nro_cierre_turno"]
        ];
      }, $result);

      $checkingsDb = new CheckingsDb();
      $checkings = $checkingsDb->listarCheckings();

      $reporteDiarioCaja = new ReporteDiarioCaja();
      $this->sendResponse($reporteDiarioCaja->generarReporte($result, $fecha, $checkings), 200);

    } else if ($tipo == "detalles") {
      $result = $reportesDb->obtenerReporteDiarioDetalles($fecha);

      $gruposDeLaCartaDb = new GruposDeLaCartaDb();
      $gruposDeLaCarta = $gruposDeLaCartaDb->listarGruposDeLaCarta();

      $reporteDiarioDetalles = new ReporteDiarioDetalles();
      $this->sendResponse($reporteDiarioDetalles->generarReporte($fecha, $result, $gruposDeLaCarta), 200);

    } else if ($tipo == "estado-cuenta-cliente") {
      $result = $reportesDb->obtenerReporteEstadoCuenta($consumos, $nroRegistroMaestro);

      $reporteEstadoCuenta = new ReporteEstadoCuenta();
      $this->sendResponse($reporteEstadoCuenta->generarReporte($result, $nroRegistroMaestro), 200);

    } else if ($tipo == "registro-ventas") {

      $comprobantesVentasDb = new ComprobantesVentasDb();
      $result = $comprobantesVentasDb->listarComprobantesVentas($fecha, $mes, $anio, $soloBolFact);

      $usuariosDb = new UsuariosDb();
      $usuario = $usuariosDb->obtenerNombreUsuario($idUsuario)[0]["usuario"];

      $tiposDoc = [
        "00" => "PD",
        "01" => "FA",
        "03" => "BO",
      ];

      $result = array_map(function ($comprobante) use ($tiposDoc) {
        return [
          "fecha" => $comprobante["fecha_documento"],
          "tipo_doc" => $tiposDoc[$comprobante["tipo_comprobante"]],
          "nro_comprobante" => $comprobante["nro_comprobante"],
          "nombre" => $comprobante["rznSocialUsuario"],
          "estado" => $comprobante["estado"] ? "" : "ANULADO",
          "dni_ruc" => $comprobante["nro_documento_cliente"],
          "monto" => $comprobante["total"],
        ];
      }, $result);

      $reporteRegistroVentas = new ReporteRegistroVentas();
      $this->sendResponse($reporteRegistroVentas->generarReporte($result, $usuario, $fecha, $mes, $anio), 200);

    } else if ($tipo == "generar-factura") {
      $result = $reportesDb->generarComprobante($nroComprobante);

      $configDb = new ConfigDb();
      $porcIGV = $configDb->obtenerConfig(17)->numero_correlativo; // 17 = ID DEL PORCENTAJE IGV

      $comprobanteImprimible = new ComprobanteImprimible();
      $this->sendResponse($comprobanteImprimible->generarReporte($result, $porcIGV), 200);

    } else if ($tipo == "listado-catalogo") {
      $result = $reportesDb->obtenerReporteListadoCatalogo($idGrupo);

      $reporteListadoCatalogo = new ReporteListadoCatalogo();
      $this->sendResponse($reporteListadoCatalogo->generarReporte($result), 200);

    } else if ($tipo == "compras") {
      $comprobantesVentasDb = new ComprobantesVentasDb();
      $result = $comprobantesVentasDb->listarComprasEnRangoFechas($fechaInicio, $fechaFin);

      $result = array_map(function ($comprobante) {
        $proveedor = in_array($comprobante["tipo_documento_cliente"], [1, 7]) ? $comprobante["nombres"] . ", " . $comprobante["apellidos"] : $comprobante["apellidos"];

        return [
          "id_comprobante" => $comprobante["id_comprobante_ventas"],
          "fecha" => $comprobante["fecha_documento"],
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
        ];
      }, $result);

      $reporteCompras = new ReporteCompras();
      $this->sendResponse($reporteCompras->generarReporte($result, $fechaInicio, $fechaFin), 200);

    } else if ($tipo == "consulta-productos-insumos") {

      $nombresProducto = explode(" ", $nombreProducto);

      $productosDb = new ProductosDb();

      $result = null;
      if ($nombreProducto) {
        $result = $productosDb->buscarConDocDetallesPorNombreProducto($nombresProducto);
      } else if ($tipoProducto) {
        $result = $productosDb->buscarConDocDetallesPorTipoProducto($tipoProducto);
      }

      // agrupar los resultados por tipo de producto
      $result = array_reduce($result, function ($acc, $producto) {
        $acc[$producto["tipo_producto"]][] = $producto;
        return $acc;
      }, []);

      $reporteConsultaProductosInsumos = new ReporteConsultaProductosInsumos();
      $this->sendResponse($reporteConsultaProductosInsumos->generarReporte($result, $nombreProducto, $tipoProducto), 200);

    } else if ($tipo == 'kardex') {

      $documentosDetallesDb = new DocumentosDetallesDb();
      $result =  $documentosDetallesDb->generarKardex($idProducto, $fechaInicio, $fechaFin);

      // obtener nombre del producto
      $productosDb = new ProductosDb();
      $nombreProducto = $productosDb->obtenerProducto($idProducto)->nombre_producto;

      $reporteKardex = new ReporteKardex();
      $this->sendResponse($reporteKardex->generarReporte($result, $nombreProducto, $fechaInicio, $fechaFin), 200);

    } else if ($tipo == 'pedido') {

      $productosDb = new ProductosDb();
      $result = $productosDb->listarConCentralesCostos(null, null, true);

      $reportePedido = new ReportePedido();
      $this->sendResponse($reportePedido->generarReporte($result), 200);

    } else if ($tipo == 'desayunos') {

      $roomingDb = new RoomingDb();
      $result = $roomingDb->listarRoomingConDatos($fecha);

      $reporteDesayunos = new ReporteDesayunos();
      $this->sendResponse($reporteDesayunos->generarReporte($result, $fecha), 200);

    } else if ($tipo == 'liquidacion') {

      $terapistasDb = new TerapistasDb();
      $terapista = $terapistasDb->obtenerTerapista($idProfesional);
      $nombreTerapista = $terapista->nombres . " " . $terapista->apellidos;

      $documentosDetallesDb = new DocumentosDetallesDb();
      $result = $documentosDetallesDb->buscarServiciosLiquidacion($fecha, $idProfesional);

      $reporteLiquidacionServicios = new ReporteLiquidacionServicios();
      $this->sendResponse($reporteLiquidacionServicios->generarReporte($result, $fecha, $nombreTerapista), 200);

    } else if ($tipo == 'ficha-checkin') {

      $checkingsDb = new CheckingsDb();
      $checking = $checkingsDb->buscarPorNroRegistroMaestro($nroRegistroMaestro);

      $personasDb = new PersonasDb();
      $persona = $personasDb->obtenerPersona($checking->id_persona);

      $roomingDb = new RoomingDb();
      $roomings = $roomingDb->buscarPorNroRegistroMaestroConFechaINOUT($nroRegistroMaestro);

      $acompanantesDb = new AcompanantesDb();
      $acompanantes = $acompanantesDb->buscarPorNroRegistroMaestro($nroRegistroMaestro);

      $reporteFichaChecking = new ReporteFichaChecking();
      $this->sendResponse($reporteFichaChecking->generarReporte($checking, $persona, $roomings, $acompanantes), 200);

    } else {
      // no hay ese tipo de reporte
      $this->sendResponse([
        "mensaje" => "No existe ese tipo de reporte"
      ], 400);
    }
  }
}

try {
  $controller = new ReportesController();
  $controller->route();
} catch (Exception $e) {
  $controller->sendResponse($controller->errorResponse($e), 500);
}
?>