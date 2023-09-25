<?php
require_once __DIR__ . "/../../inc/bootstrap.php";
require_once PROJECT_ROOT_PATH . "/controllers/BaseController.php";

require_once PROJECT_ROOT_PATH . "/models/ReportesDb.php";
require_once PROJECT_ROOT_PATH . "/models/CheckingsDb.php";
require_once PROJECT_ROOT_PATH . "/models/GruposDeLaCartaDb.php";
require_once PROJECT_ROOT_PATH . "/models/ComprobantesVentasDb.php";
require_once PROJECT_ROOT_PATH . "/models/UsuariosDb.php";

require_once PROJECT_ROOT_PATH . "/fpdf/fpdf.php";

require_once PROJECT_ROOT_PATH . "/controllers/reportes/ReporteEstadoCuenta.php";
require_once PROJECT_ROOT_PATH . "/controllers/reportes/ReporteRegistroVentas.php";

class ReportesController extends BaseController
{
  public function get()
  {
    $params = $this->getParams();
    $tipo = $params['tipo'] ?? null;

    $fecha = $params['fecha'] ?? null;

    $mes = $params['mes'] ?? null;
    $anio = $params['anio'] ?? null;
    $soloBolFact = boolval(($params['solo_bol_fact'] ?? null) === "");
    $idUsuario = $params['id_usuario'] ?? null;

    $consumos = $params['consumos'] ?? null;
    $nroRegistroMaestro = $params['nro_registro_maestro'] ?? null;

    $reportesDb = new ReportesDb();

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

      $this->sendResponse($this->generarReporteDiarioCaja($fecha, $result, $checkings), 200);

    } else if ($tipo == "detalles") {
      $result = $reportesDb->obtenerReporteDiarioDetalles($fecha);

      $gruposDeLaCartaDb = new GruposDeLaCartaDb();
      $gruposDeLaCarta = $gruposDeLaCartaDb->listarGruposDeLaCarta();

      $this->sendResponse($this->generarReporteDiarioDetalles($fecha, $result, $gruposDeLaCarta), 200);

    } else if ($tipo == "estado-cuenta-cliente") {
      $result = $reportesDb->obtenerReporteEstadoCuenta($consumos, $nroRegistroMaestro);

      $reporteEstadoCuenta = new ReporteEstadoCuenta();

      $this->sendResponse($reporteEstadoCuenta->generarReporte($result, $nroRegistroMaestro), 200);

    } else if ($tipo == "registro-ventas") {

      $comprobantesVentasDb = new ComprobantesVentasDb();
      $result = $comprobantesVentasDb->listarComprobantesVentas(null, $fecha, $mes, $anio, $soloBolFact);

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

    } else {
      // no hay ese tipo de reporte
      $this->sendResponse([
        "mensaje" => "No existe ese tipo de reporte"
      ], 400);
    }
  }

  function generarReporteDiarioCaja($fecha = null, $data = null, $checkings = null)
  {
    $tiposDoc = [
      "00" => "PD",
      "01" => "FA",
      "03" => "BO",
    ];

    $formasPago = [
      "EFE" => "EFECTIVO",
      "YAP" => "YAPE",
      "PLI" => "PLIN",
      "TJT" => "TARJETA",
      "DEP" => "DEPOSITO",
      "TRA" => "TRANSF",
    ];

    $comprobantesAgrupados = [];
    foreach ($data as $comprobante) {
      $nro_comprobante = $comprobante['nro_comprobante'];

      // Verificar si ya existe un comprobante agrupado con el mismo número de comprobante
      $comprobanteAgrupado = array_filter($comprobantesAgrupados, function ($item) use ($nro_comprobante) {
        return $item['nro_comprobante'] == $nro_comprobante;
      });

      if (!empty($comprobanteAgrupado)) {
        // El comprobante ya existe en comprobantesAgrupados
        $comprobanteAgrupadoKey = key($comprobanteAgrupado);
        $comprobantesAgrupados[$comprobanteAgrupadoKey]['recibos'][] = [
          'tipo_pago' => $comprobante['tipo_pago'] ?? "",
          'total_recibo' => $comprobante['total_recibo'] ?? 0,
          'nro_cierre_turno' => $comprobante['nro_cierre_turno'] ?? 99,
        ];
      } else {
        // El comprobante no existe en comprobantesAgrupados, lo agregamos

        // buscamos el checking correspondiente
        $nroRegistroMaestro = $comprobante['nro_registro_maestro'];
        $checking = array_values(array_filter($checkings, function ($item) use ($nroRegistroMaestro) {
          return $item->nro_registro_maestro == $nroRegistroMaestro;
        }))[0];

        $comprobantesAgrupados[] = [
          'nro_comprobante' => $comprobante['nro_comprobante'],
          'fecha_comprobante' => $comprobante['fecha_comprobante'],
          'tipo_comprobante' => $comprobante['tipo_comprobante'],
          'nro_doc_cliente' => $comprobante['nro_doc_cliente'],
          'nombre_razon_social' => $comprobante['nombre_razon_social'],
          'total_comprobante' => $comprobante['total_comprobante'],
          'por_pagar' => $comprobante['por_pagar'],
          'tipo_de_servicio' => $checking->tipo_de_servicio ?? "",
          'nro_habitacion' => $checking->nro_habitacion ?? "",
          'recibos' => [
            [
              'tipo_pago' => $comprobante['tipo_pago'] ?? "",
              'total_recibo' => $comprobante['total_recibo'] ?? 0,
              'nro_cierre_turno' => $comprobante['nro_cierre_turno'] ?? 99,
            ],
          ],
        ];
      }
    }

    // agrupar comprobantes por nro_cierre_turno
    $turnos = array_reduce($comprobantesAgrupados, function ($result, $comprobante) {
      $result[$comprobante["recibos"][0]["nro_cierre_turno"]][] = $comprobante;
      return $result;
    }, []);

    // reverse sort por nro_cierre_turno
    $turnos = array_reverse($turnos, true);

    if (empty($turnos)) {
      $this->imprimirReporteVacio($fecha);
      return;
    }

    // cambiar formato fecha a dd/mm/yyyy
    $fecha = date("d/m/Y", strtotime($fecha));

    $pdf = new FPDF();
    $pdf->AddPage(null, 'A4');

    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 20, "REPORTE DIARIO DE CAJA - $fecha", 0, 0, "C");
    $pdf->Ln();

    $lineHeight = 4;
    $tamanoLetra = 6;

    $pdf->SetFont('Arial', 'B', $tamanoLetra);
    $pdf->Cell(12, $lineHeight, 'Fecha', 1, 0, "C");
    $pdf->Cell(10, $lineHeight, 'Serv.', 1, 0, "C");
    $pdf->Cell(10, $lineHeight, 'T.Doc', 1, 0, "C");
    $pdf->Cell(26, $lineHeight, 'Nro DOC.', 1, 0, "C");
    $pdf->Cell(16, $lineHeight, 'Dni/Ruc', 1, 0, "C");
    $pdf->Cell(48, $lineHeight, 'Nombre Cliente', 1, 0, "C");
    $pdf->Cell(14, $lineHeight, 'TOTAL', 1, 0, "C");
    $pdf->Cell(18, $lineHeight, 'F. Pago', 1, 0, "C");
    $pdf->Cell(14, $lineHeight, 'M. Pago', 1, 0, "C");
    $pdf->Cell(14, $lineHeight, 'x Cobrar', 1, 0, "C");
    $pdf->Ln();

    $turnoIterador = 1;

    foreach ($turnos as $turno) {

      foreach ($turno as $comprobante) {

        $servicio = $comprobante["tipo_de_servicio"] == "HOTEL" ? "H $comprobante[nro_habitacion]" : $comprobante["tipo_de_servicio"];

        $pdf->SetFont('Arial', '', $tamanoLetra);

        $pdf->Cell(12, $lineHeight, date("d/m/Y", strtotime($comprobante["fecha_comprobante"])), 0, 0, "C");
        $pdf->Cell(10, $lineHeight, $servicio, 0, 0, "C");
        $pdf->Cell(10, $lineHeight, $tiposDoc[$comprobante["tipo_comprobante"]], 0, 0, "C");
        $pdf->Cell(26, $lineHeight, $comprobante["nro_comprobante"], 0, 0, "C");
        $pdf->Cell(16, $lineHeight, $comprobante["nro_doc_cliente"], 0, 0, "C");
        // mostrar solo los primeros 32 caracteres del nombre o razon social y si es mayor a 32, agregar puntos suspensivos
        $nombre = strlen($comprobante["nombre_razon_social"]) > 32 ? substr($comprobante["nombre_razon_social"], 0, 32) . "..." : $comprobante["nombre_razon_social"];
        $pdf->Cell(48, $lineHeight, $nombre, 0);
        $pdf->Cell(14, $lineHeight, $this->darFormatoMoneda($comprobante["total_comprobante"]), 0, 0, "R");

        $porPagar = $comprobante["por_pagar"] > 0 ? "X" : "";

        // si es el primer recibo

        foreach ($comprobante["recibos"] as $index => $recibo) {
          if ($index > 0) {
            $pdf->Cell(136, $lineHeight, "", 0);
          }
          $pdf->Cell(18, $lineHeight, $formasPago[$recibo["tipo_pago"]] ?? "", 0, 0, "C");
          $pdf->Cell(14, $lineHeight, $this->darFormatoMoneda($recibo["total_recibo"]), 0, 0, "R");
          if (!$porPagar && !($index == count($comprobante["recibos"]) - 1))
            $pdf->Ln();
        }

        // imprimir si es por cobrar
        $pdf->Cell(14, $lineHeight, $porPagar, 0, 0, "C");
        $pdf->Ln();
      }

      $totalComprobantes = array_reduce($turno, function ($result, $item) {
        $result += $item["total_comprobante"];
        return $result;
      }, 0);

      $totalRecibos = array_reduce($turno, function ($result, $item) {
        $totalRecibosComprobante = array_reduce($item["recibos"], function ($result, $item) {
          $result += $item["total_recibo"];
          return $result;
        }, 0);
        return $result + $totalRecibosComprobante;
      }, 0);

      $pdf->Line(10, $pdf->GetY(), 192, $pdf->GetY());
      $pdf->SetFont('Arial', 'B', $tamanoLetra);
      $pdf->Cell(122, $lineHeight, "TURNO $turnoIterador TOTAL:", 0, 0, "R");
      $pdf->Cell(14, $lineHeight, $this->darFormatoMoneda($totalComprobantes), 0, 0, "R");
      $pdf->Cell(32, $lineHeight, $this->darFormatoMoneda($totalRecibos), 0, 0, "R");

      $pdf->Ln();
      $pdf->Ln();

      $turnoIterador++;
    }

    $totales = [];

    // inicializar los totales cruzados
    foreach ($formasPago as $formaPago => $nombreCompleto) {
      foreach ($turnos as $turno => $comprobantes) {
        $totales[$formaPago][$turno] = 0;
      }
    }

    // calcular los totales cruzados
    foreach ($comprobantesAgrupados as $comprobante) {
      foreach ($comprobante["recibos"] as $recibo) {
        $tipoPago = $recibo["tipo_pago"];
        $turno = $recibo["nro_cierre_turno"];
        $totalRecibo = $recibo["total_recibo"];

        if ($tipoPago == "" && $turno == 99)
          continue;
        $totales[$tipoPago][$turno] += $totalRecibo;
      }
    }

    $totalesTotales = [];

    // inicializar los totales totales
    foreach ($formasPago as $formaPago => $nombreCompleto) {
      $totalesTotales[$formaPago] = 0;
    }
    foreach ($turnos as $turno => $comprobantes) {
      $totalesTotales[$turno] = 0;
    }
    $totalesTotales["TOTAL"] = 0;

    // calcular los totales totales
    foreach ($totales as $tipoPago => $totalesPorTipoPago) {
      foreach ($totalesPorTipoPago as $turno => $totalPorTurno) {
        $totalesTotales[$tipoPago] += $totalPorTurno;
        $totalesTotales[$turno] += $totalPorTurno;
        $totalesTotales["TOTAL"] += $totalPorTurno;
      }
    }


    $pdf->SetFont('Arial', 'B', $tamanoLetra);

    // imprimir la cabecera de turnos
    $pdf->Cell(114, $lineHeight);
    $turnoIterador = 1;
    foreach ($turnos as $turno => $comprobantes) {
      $pdf->Cell(16, $lineHeight, "TURNO $turnoIterador", 0, 0, "R");
      $turnoIterador++;
    }
    $pdf->Cell(16, $lineHeight, "TOTALES", 0, 0, "C");
    $pdf->Ln();
    $pdf->Line(102, $pdf->GetY(), 192, $pdf->GetY());

    // imprimir los totales cruzados
    $pdf->SetFont('Arial', '', $tamanoLetra);
    foreach ($formasPago as $formaPago => $nombreCompleto) {
      $pdf->Cell(90, $lineHeight);
      $pdf->Cell(24, $lineHeight, "$nombreCompleto :", 0, 0, "R");
      foreach ($totales[$formaPago] as $totalPorTurno) {
        $pdf->Cell(16, $lineHeight, $this->darFormatoMoneda($totalPorTurno), 0, 0, "R");
      }
      $pdf->Cell(16, $lineHeight, $this->darFormatoMoneda($totalesTotales[$formaPago]), 0, 0, "R");
      $pdf->Ln();
    }

    // imprimir los totales por turno
    $pdf->Cell(90, $lineHeight);

    $pdf->SetFont('Arial', 'B', $tamanoLetra);
    $pdf->Line(102, $pdf->GetY(), 192, $pdf->GetY());
    $pdf->Cell(24, $lineHeight, "TOTALES :", 0, 0, "R");

    foreach ($turnos as $turno => $comprobantes) {
      $pdf->Cell(16, $lineHeight, $this->darFormatoMoneda($totalesTotales[$turno]), 0, 0, "R");
    }

    // imprimir el total de totales
    $pdf->Cell(16, $lineHeight, $this->darFormatoMoneda($totalesTotales["TOTAL"]), 0, 0, "R");

    return $pdf->Output();
  }

  function imprimirReporteVacio($fecha)
  {
    $formasPago = [
      "EFE" => "EFECTIVO",
      "YAP" => "YAPE",
      "PLI" => "PLIN",
      "TJT" => "TARJETA",
      "DEP" => "DEPOSITO",
      "TRA" => "TRANSF",
    ];

    $fecha = date("d/m/Y", strtotime($fecha));

    $pdf = new FPDF();
    $pdf->AddPage(null, 'A4');

    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 20, "REPORTE DIARIO DE CAJA - $fecha", 0, 0, "C");
    $pdf->Ln();

    $lineHeight = 4;
    $tamanoLetra = 6;

    $pdf->SetFont('Arial', 'B', $tamanoLetra);
    $pdf->Cell(12, $lineHeight, 'Fecha', 1, 0, "C");
    $pdf->Cell(10, $lineHeight, 'Serv.', 1, 0, "C");
    $pdf->Cell(10, $lineHeight, 'T.Doc', 1, 0, "C");
    $pdf->Cell(26, $lineHeight, 'Nro DOC.', 1, 0, "C");
    $pdf->Cell(16, $lineHeight, 'Dni/Ruc', 1, 0, "C");
    $pdf->Cell(48, $lineHeight, 'Nombre Cliente', 1, 0, "C");
    $pdf->Cell(14, $lineHeight, 'TOTAL', 1, 0, "C");
    $pdf->Cell(18, $lineHeight, 'F. Pago', 1, 0, "C");
    $pdf->Cell(14, $lineHeight, 'M. Pago', 1, 0, "C");
    $pdf->Cell(14, $lineHeight, 'x Cobrar', 1, 0, "C");
    $pdf->Ln();

    $pdf->Line(10, $pdf->GetY(), 192, $pdf->GetY());
    $pdf->SetFont('Arial', 'B', $tamanoLetra);
    $pdf->Cell(122, $lineHeight, "TURNO 1 TOTAL:", 0, 0, "R");
    $pdf->Cell(14, $lineHeight, $this->darFormatoMoneda(0), 0, 0, "R");
    $pdf->Cell(32, $lineHeight, $this->darFormatoMoneda(0), 0, 0, "R");

    $pdf->Ln();
    $pdf->Ln();

    $pdf->SetFont('Arial', 'B', $tamanoLetra);

    $pdf->Cell(34, $lineHeight);
    $pdf->Cell(16, $lineHeight, "TURNO 1", 0, 0, "R");
    $pdf->Cell(16, $lineHeight, "TOTALES", 0, 0, "C");
    $pdf->Ln();
    $pdf->Line(10, $pdf->GetY(), 192, $pdf->GetY());

    $pdf->SetFont('Arial', '', $tamanoLetra);

    foreach ($formasPago as $formaPago => $nombreCompleto) {
      $pdf->Cell(10, $lineHeight);
      $pdf->Cell(24, $lineHeight, "$nombreCompleto :", 0, 0, "R");
      $pdf->Cell(16, $lineHeight, $this->darFormatoMoneda(0), 0, 0, "R");
      $pdf->Cell(16, $lineHeight, $this->darFormatoMoneda(0), 0, 0, "R");
      $pdf->Ln();
    }

    $pdf->Cell(10, $lineHeight);

    $pdf->SetFont('Arial', 'B', $tamanoLetra);
    $pdf->Line(10, $pdf->GetY(), 192, $pdf->GetY());
    $pdf->Cell(24, $lineHeight, "TOTALES :", 0, 0, "R");

    $pdf->Cell(16, $lineHeight, $this->darFormatoMoneda(0), 0, 0, "R");
    $pdf->Cell(16, $lineHeight, $this->darFormatoMoneda(0), 0, 0, "R");

    $pdf->Output();
  }

  function generarReporteDiarioDetalles($fecha = null, $data = null, $gruposDeLaCarta = null)
  {
    $data = array_map(function ($detalle) use ($gruposDeLaCarta) {

      // cambiar el id del grupo de la carta por el nombre del grupo de la carta, pero el grupo al que pertenece si es un subgrupo
      $grupoDeLaCarta = array_values(array_filter($gruposDeLaCarta, function ($item) use ($detalle) {
        return $item->id_grupo == $detalle["id_grupo"];
      }))[0];

      if ($grupoDeLaCarta->codigo_grupo != $grupoDeLaCarta->codigo_subgrupo) {
        $grupoDeLaCarta = array_values(array_filter($gruposDeLaCarta, function ($item) use ($grupoDeLaCarta) {
          return $item->codigo_grupo == $grupoDeLaCarta->codigo_grupo && $item->codigo_subgrupo == $item->codigo_grupo;
        }))[0];
      }

      // agregar un campo turno que separa el turno mañana hasta las 3pm y el turno tarde desde las 3pm
      $turno = date("H", strtotime($detalle["fecha"])) < 15 ? "MAÑANA" : "TARDE";

      // agregar un campo que indique si es un producto o receta, o si es un servicio o paquete
      $tipoGrande = $detalle['tipo'] == "PRD" || $detalle['tipo'] == "RST" ? "PRD_RST" : "SRV_PAQ";

      // agregar campo de tipo de servicio, si es HOTEL, entonces mostrar el nro de habitación como H202 por ejemplo, sino mostrar el tipo de servicio
      $tipoDeServicio = $detalle['tipo_de_servicio'] == "HOTEL" ? "H $detalle[nro_habitacion]" : $detalle['tipo_de_servicio'];

      $detalle["turno"] = $turno;
      $detalle["tipo_grande"] = $tipoGrande;
      $detalle["tipo_de_servicio"] = $tipoDeServicio;
      $detalle["id_grupo"] = $grupoDeLaCarta->id_grupo;
      $detalle["nombre_grupo"] = $grupoDeLaCarta->nombre_grupo;

      return $detalle;
    }, $data);

    $grupoPRD_RST = [];
    $grupoSRV_PAQ = [];

    // separar los detalles en dos grupos, uno de productos y recetas y otro de servicios y paquetes
    foreach ($data as $detalle) {
      if ($detalle["tipo_grande"] == "PRD_RST") {
        $grupoPRD_RST[] = $detalle;
      } else {
        $grupoSRV_PAQ[] = $detalle;
      }
    }

    // agrupar los detalles de productos y recetas por turno
    $grupoPRD_RST = array_reduce($grupoPRD_RST, function ($result, $detalle) {
      $result[$detalle["turno"]][] = $detalle;
      return $result;
    }, []);

    // agrupar los detalles que están en el mismo grupo
    $grupoPRD_RST = array_map(function ($grupo) {
      return array_reduce($grupo, function ($result, $detalle) {
        $grupo = &$result[$detalle["id_grupo"]];

        $grupo["detalles"][] = $detalle;
        $grupo["nombre_grupo"] = $detalle["nombre_grupo"];

        $grupo["p_total"] = isset($grupo["p_total"]) ? $grupo["p_total"] + $detalle["precio_total"] : $detalle["precio_total"];
        $grupo["x_cobrar"] = isset($grupo["x_cobrar"]) ? $grupo["x_cobrar"] + ($detalle["nro_comprobante"] == '' ? $detalle["precio_total"] : 0) : ($detalle["nro_comprobante"] == '' ? $detalle["precio_total"] : 0);
        $grupo["pagado"] = isset($grupo["pagado"]) ? $grupo["pagado"] + ($detalle["nro_comprobante"] != '' ? $detalle["precio_total"] : 0) : ($detalle["nro_comprobante"] != '' ? $detalle["precio_total"] : 0);

        return $result;
      }, []);
    }, $grupoPRD_RST);

    // inicializar los totales
    $grupoPRD_RST['TOTALES'] = [];
    $grupoPRD_RST['TOTALES']["TOTAL"] = 0;
    $grupoPRD_RST['TOTALES']["X_COBRAR"] = 0;
    $grupoPRD_RST['TOTALES']["PAGADO"] = 0;

    foreach ($grupoPRD_RST as $grupo => $detalles) {
      if ($grupo == "TOTALES")
        continue;

      $grupoPRD_RST[$grupo]["p_total"] = 0;
      $grupoPRD_RST[$grupo]["x_cobrar"] = 0;
      $grupoPRD_RST[$grupo]["pagado"] = 0;
    }

    // sumar los detalles a los totales
    foreach ($grupoPRD_RST as $grupo => $detalles) {
      if ($grupo == "TOTALES")
        continue;

      foreach ($detalles as $detalle) {
        if (!is_array($detalle))
          continue;

        $grupoPRD_RST[$grupo]["p_total"] += $detalle["p_total"];
        $grupoPRD_RST[$grupo]["x_cobrar"] += $detalle["x_cobrar"];
        $grupoPRD_RST[$grupo]["pagado"] += $detalle["pagado"];
      }

      $grupoPRD_RST['TOTALES']["TOTAL"] += $grupoPRD_RST[$grupo]["p_total"];
      $grupoPRD_RST['TOTALES']["X_COBRAR"] += $grupoPRD_RST[$grupo]["x_cobrar"];
      $grupoPRD_RST['TOTALES']["PAGADO"] += $grupoPRD_RST[$grupo]["pagado"];
    }

    // agrupar los detalles que están en el mismo grupo
    $grupoSRV_PAQ = array_reduce($grupoSRV_PAQ, function ($result, $detalle) {
      $grupo = &$result[$detalle["id_grupo"]];

      $grupo["detalles"][] = $detalle;
      $grupo["nombre_grupo"] = $detalle["nombre_grupo"];

      $grupo["p_total"] = isset($grupo["p_total"]) ?
        $grupo["p_total"] + $detalle["precio_total"] : $detalle["precio_total"];
      $grupo["x_cobrar"] = isset($grupo["x_cobrar"]) ?
        $grupo["x_cobrar"] + ($detalle["nro_comprobante"] == '' ? $detalle["precio_total"] : 0) : ($detalle["nro_comprobante"] == '' ? $detalle["precio_total"] : 0);
      $grupo["pagado"] = isset($grupo["pagado"]) ? $grupo["pagado"] + ($detalle["nro_comprobante"] != '' ? $detalle["precio_total"] : 0) : ($detalle["nro_comprobante"] != '' ? $detalle["precio_total"] : 0);

      return $result;
    }, []);

    // inicializar los totales
    $grupoSRV_PAQ['TOTALES'] = [];
    $grupoSRV_PAQ['TOTALES']["TOTAL"] = 0;
    $grupoSRV_PAQ['TOTALES']["X_COBRAR"] = 0;
    $grupoSRV_PAQ['TOTALES']["PAGADO"] = 0;

    // sumar los detalles a los totales
    foreach ($grupoSRV_PAQ as $grupo => $detalles) {
      if ($grupo == "TOTALES")
        continue;

      $grupoSRV_PAQ['TOTALES']["TOTAL"] += $grupoSRV_PAQ[$grupo]["p_total"];
      $grupoSRV_PAQ['TOTALES']["X_COBRAR"] += $grupoSRV_PAQ[$grupo]["x_cobrar"];
      $grupoSRV_PAQ['TOTALES']["PAGADO"] += $grupoSRV_PAQ[$grupo]["pagado"];
    }

    // cambiar formato fecha a dd/mm/yyyy
    $fecha = date("d/m/Y", strtotime($fecha));

    $pdf = new FPDF();
    $pdf->AddPage(null, 'A4');

    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 20, "REPORTE DE VENTAS DETALLE DE PRODUCTOS - $fecha", 0, 0, "C");
    $pdf->Ln();

    $lineHeight = 4;
    $tamanoLetra = 6;

    $pdf->SetFont('Arial', 'B', $tamanoLetra);

    $pdf->Cell(50, $lineHeight, 'DETALLE VENTA DE PRODUCTOS');

    $pdf->Ln();

    // imprimir la cabecera
    $pdf->Cell(32, $lineHeight, 'COMPROBANTE', 1, 0, "C");
    $pdf->Cell(12, $lineHeight, 'CANTIDAD', 1, 0, "C");
    $pdf->Cell(60, $lineHeight, 'PRODUCTO', 1, 0, "C");
    $pdf->Cell(14, $lineHeight, 'T. CLIENTE', 1, 0, "C");
    $pdf->Cell(14, $lineHeight, 'P. UNIT', 1, 0, "C");
    $pdf->Cell(14, $lineHeight, 'P. TOTAL', 1, 0, "C");
    $pdf->Cell(14, $lineHeight, 'X COBRAR', 1, 0, "C");
    $pdf->Cell(14, $lineHeight, 'PAGADO', 1, 0, "C");

    $pdf->Ln();

    foreach ($grupoPRD_RST as $nombreGrupo => $grupos) {
      if ($nombreGrupo == "TOTALES")
        continue;

      foreach ($grupos as $grupo) {
        if (!is_array($grupo))
          continue;

        $pdf->SetFont('Arial', 'B', $tamanoLetra);
        $pdf->Cell(32, $lineHeight, $grupo["nombre_grupo"], 0, 0, "C");

        $pdf->SetFont('Arial', null, $tamanoLetra);
        $pdf->Cell(12, $lineHeight, "TURNO " . mb_convert_encoding($nombreGrupo, "ISO-8859-1", "UTF-8"), 0, 0, "C");
        $pdf->Ln();

        foreach ($grupo["detalles"] as $detalle) {
          $pdf->Cell(32, $lineHeight, $detalle["nro_comprobante"] ?? "", 0, 0, "C");
          $pdf->Cell(12, $lineHeight, intval($detalle["cantidad"]), 0, 0, "C");

          $nombreProducto = strlen($detalle["nombre_producto"]) > 40 ? substr($detalle["nombre_producto"], 0, 40) . "..." : $detalle["nombre_producto"];

          $pdf->Cell(60, $lineHeight, $nombreProducto, 0);
          $pdf->Cell(14, $lineHeight, $detalle["tipo_de_servicio"], 0, 0, "C");
          $pdf->Cell(14, $lineHeight, $detalle["precio_unitario"] == 0 ? "" : $this->darFormatoMoneda($detalle["precio_unitario"]), 0, 0, "R");
          $pdf->Cell(14, $lineHeight, $detalle["precio_total"] == 0 ? "" : $this->darFormatoMoneda($detalle["precio_total"]), 0, 0, "R");
          $pdf->Cell(14, $lineHeight, $detalle["nro_comprobante"] == '' ? $this->darFormatoMoneda($detalle["precio_total"]) : "", 0, 0, "R");
          $pdf->Cell(14, $lineHeight, $detalle["nro_comprobante"] != '' ? $this->darFormatoMoneda($detalle["precio_total"]) : "", 0, 0, "R");
          $pdf->Ln();
        }

        $pdf->Line(10, $pdf->GetY(), 184, $pdf->GetY());
        $pdf->SetFont('Arial', 'B', $tamanoLetra);
        $pdf->Cell(132, $lineHeight, "TOTAL " . $grupo["nombre_grupo"] . ":", 0, 0, "R");
        $pdf->Cell(14, $lineHeight, $this->darFormatoMoneda($grupo["p_total"]), 0, 0, "R");
        $pdf->Cell(14, $lineHeight, $this->darFormatoMoneda($grupo["x_cobrar"]), 0, 0, "R");
        $pdf->Cell(14, $lineHeight, $this->darFormatoMoneda($grupo["pagado"]), 0, 0, "R");
        $pdf->SetFont('Arial', null, $tamanoLetra);

        $pdf->Ln();
        $pdf->Ln();
      }
    }

    // imprimir los totales de productos y recetas de la mañana

    if (isset($grupoPRD_RST["MAÑANA"])) {
      $pdf->SetFont('Arial', null, $tamanoLetra);

      $pdf->Cell(132, $lineHeight, "TURNO " . mb_convert_encoding("MAÑANA", "ISO-8859-1", "UTF-8"), 0, 0, "R");
      $pdf->Cell(14, $lineHeight, "TOTAL", 0, 0, "C");
      $pdf->Cell(14, $lineHeight, "X COBRAR", 0, 0, "C");
      $pdf->Cell(14, $lineHeight, "PAGADO", 0, 0, "C");
      $pdf->Ln();

      $pdf->Line(114, $pdf->GetY(), 184, $pdf->GetY());

      foreach ($grupoPRD_RST["MAÑANA"] as $valor) {
        if (!is_array($valor))
          continue;

        $pdf->Cell(132, $lineHeight, "TOTAL " . $valor["nombre_grupo"], 0, 0, "R");
        $pdf->Cell(14, $lineHeight, $this->darFormatoMoneda($valor["p_total"]), 0, 0, "R");
        $pdf->Cell(14, $lineHeight, $this->darFormatoMoneda($valor["x_cobrar"]), 0, 0, "R");
        $pdf->Cell(14, $lineHeight, $this->darFormatoMoneda($valor["pagado"]), 0, 0, "R");
        $pdf->Ln();
      }

      $pdf->Line(114, $pdf->GetY(), 184, $pdf->GetY());

      $pdf->SetFont('Arial', 'B', $tamanoLetra);
      $pdf->Cell(132, $lineHeight, "TOTAL", 0, 0, "R");
      $pdf->Cell(14, $lineHeight, $this->darFormatoMoneda($grupoPRD_RST["MAÑANA"]["p_total"]), 0, 0, "R");
      $pdf->Cell(14, $lineHeight, $this->darFormatoMoneda($grupoPRD_RST["MAÑANA"]["x_cobrar"]), 0, 0, "R");
      $pdf->Cell(14, $lineHeight, $this->darFormatoMoneda($grupoPRD_RST["MAÑANA"]["pagado"]), 0, 0, "R");
      $pdf->SetFont('Arial', null, $tamanoLetra);

      $pdf->Ln();
      $pdf->Ln();
    }

    // imprimir los totales de productos y recetas de la tarde

    if (isset($grupoPRD_RST["TARDE"])) {
      $pdf->SetFont('Arial', null, $tamanoLetra);

      $pdf->Cell(132, $lineHeight, "TURNO TARDE", 0, 0, "R");
      $pdf->Cell(14, $lineHeight, "TOTAL", 0, 0, "C");
      $pdf->Cell(14, $lineHeight, "X COBRAR", 0, 0, "C");
      $pdf->Cell(14, $lineHeight, "PAGADO", 0, 0, "C");
      $pdf->Ln();

      $pdf->Line(114, $pdf->GetY(), 184, $pdf->GetY());

      foreach ($grupoPRD_RST["TARDE"] as $valor) {
        if (!is_array($valor))
          continue;

        $pdf->Cell(132, $lineHeight, "TOTAL " . $valor["nombre_grupo"], 0, 0, "R");
        $pdf->Cell(14, $lineHeight, $this->darFormatoMoneda($valor["p_total"]), 0, 0, "R");
        $pdf->Cell(14, $lineHeight, $this->darFormatoMoneda($valor["x_cobrar"]), 0, 0, "R");
        $pdf->Cell(14, $lineHeight, $this->darFormatoMoneda($valor["pagado"]), 0, 0, "R");
        $pdf->Ln();
      }

      $pdf->Line(114, $pdf->GetY(), 184, $pdf->GetY());

      $pdf->SetFont('Arial', 'B', $tamanoLetra);
      $pdf->Cell(132, $lineHeight, "TOTAL", 0, 0, "R");
      $pdf->Cell(14, $lineHeight, $this->darFormatoMoneda($grupoPRD_RST["TARDE"]["p_total"]), 0, 0, "R");
      $pdf->Cell(14, $lineHeight, $this->darFormatoMoneda($grupoPRD_RST["TARDE"]["x_cobrar"]), 0, 0, "R");
      $pdf->Cell(14, $lineHeight, $this->darFormatoMoneda($grupoPRD_RST["TARDE"]["pagado"]), 0, 0, "R");
      $pdf->SetFont('Arial', null, $tamanoLetra);

      $pdf->Ln();
      $pdf->Ln();
    }

    $pdf->SetFont('Arial', 'B', $tamanoLetra);
    $pdf->Cell(132, $lineHeight, "TOTAL:", 0, 0, "R");
    $pdf->Cell(14, $lineHeight, $this->darFormatoMoneda($grupoPRD_RST['TOTALES']["TOTAL"]), 0, 0, "R");
    $pdf->Cell(14, $lineHeight, $this->darFormatoMoneda($grupoPRD_RST['TOTALES']["X_COBRAR"]), 0, 0, "R");
    $pdf->Cell(14, $lineHeight, $this->darFormatoMoneda($grupoPRD_RST['TOTALES']["PAGADO"]), 0, 0, "R");
    $pdf->SetFont('Arial', null, $tamanoLetra);
    $pdf->Ln();

    $pdf->Ln();

    $pdf->SetFont('Arial', 'B', $tamanoLetra);

    $pdf->Cell(50, $lineHeight, 'DETALLE VENTA DE SERVICIOS Y PAQUETES', 0, 0, "R");
    $pdf->Ln();

    // imprimir la cabecera
    $pdf->Cell(32, $lineHeight, 'COMPROBANTE', 1, 0, "C");
    $pdf->Cell(12, $lineHeight, 'CANTIDAD', 1, 0, "C");
    $pdf->Cell(42, $lineHeight, 'PRODUCTO', 1, 0, "C");
    $pdf->Cell(14, $lineHeight, 'T. CLIENTE', 1, 0, "C");
    $pdf->Cell(32, $lineHeight, 'CLIENTE', 1, 0, "C");
    $pdf->Cell(14, $lineHeight, 'P. TOTAL', 1, 0, "C");
    $pdf->Cell(14, $lineHeight, 'X COBRAR', 1, 0, "C");
    $pdf->Cell(14, $lineHeight, 'PAGADO', 1, 0, "C");

    $pdf->Ln();

    foreach ($grupoSRV_PAQ as $nombreGrupo => $grupo) {
      if ($nombreGrupo == "TOTALES")
        continue;

      $nombreGrupo = $grupo["nombre_grupo"];

      $pdf->SetFont('Arial', 'B', $tamanoLetra);
      $pdf->Cell(32, $lineHeight, $nombreGrupo, 0, 0, "C");
      $pdf->SetFont('Arial', null, $tamanoLetra);
      $pdf->Ln();

      foreach ($grupo["detalles"] as $detalle) {
        if (!is_array($detalle))
          continue;

        $pdf->Cell(32, $lineHeight, $detalle["nro_comprobante"] ?? "", 0, 0, "C");
        $pdf->Cell(12, $lineHeight, intval($detalle["cantidad"]), 0, 0, "C");

        $nombreProducto = strlen($detalle["nombre_producto"]) > 28 ? substr($detalle["nombre_producto"], 0, 28) . "..." : $detalle["nombre_producto"];

        $pdf->Cell(42, $lineHeight, $nombreProducto, 0);
        $pdf->Cell(14, $lineHeight, $detalle["tipo_de_servicio"], 0);

        $nombreCliente = strlen($detalle["apellidos_y_nombres"]) > 22 ? substr($detalle["apellidos_y_nombres"], 0, 22) . "..." : $detalle["apellidos_y_nombres"];

        $pdf->Cell(32, $lineHeight, $nombreCliente, 0);
        $pdf->Cell(14, $lineHeight, $this->darFormatoMoneda($detalle["precio_total"]), 0, 0, "R");
        $pdf->Cell(14, $lineHeight, $detalle["nro_comprobante"] == '' ? $detalle["precio_total"] : "", 0, 0, "R");
        $pdf->Cell(14, $lineHeight, $detalle["nro_comprobante"] != '' ? $detalle["precio_total"] : "", 0, 0, "R");
        $pdf->Ln();
      }

      $pdf->Line(10, $pdf->GetY(), 184, $pdf->GetY());
      $pdf->SetFont('Arial', 'B', $tamanoLetra);
      $pdf->Cell(132, $lineHeight, "TOTAL $nombreGrupo:", 0, 0, "R");
      $pdf->Cell(14, $lineHeight, $this->darFormatoMoneda($grupo["p_total"]), 0, 0, "R");
      $pdf->Cell(14, $lineHeight, $this->darFormatoMoneda($grupo["x_cobrar"]), 0, 0, "R");
      $pdf->Cell(14, $lineHeight, $this->darFormatoMoneda($grupo["pagado"]), 0, 0, "R");
      $pdf->SetFont('Arial', null, $tamanoLetra);

      $pdf->Ln();
      $pdf->Ln();
    }

    // imprimir los totales de servicios y paquetes
    $pdf->SetFont('Arial', null, $tamanoLetra);

    $pdf->Cell(132, $lineHeight, "RESUMEN", 0, 0, "R");
    $pdf->Cell(14, $lineHeight, "T.VENTAS", 0, 0, "C");
    $pdf->Cell(14, $lineHeight, "X COBRAR", 0, 0, "C");
    $pdf->Cell(14, $lineHeight, "COBRADOS", 0, 0, "C");
    $pdf->Ln();

    $pdf->Line(114, $pdf->GetY(), 184, $pdf->GetY());

    foreach ($grupoSRV_PAQ as $nombreGrupo => $valor) {
      if ($nombreGrupo == "TOTALES")
        continue;

      $pdf->Cell(132, $lineHeight, "TOTAL " . $valor["nombre_grupo"], 0, 0, "R");
      $pdf->Cell(14, $lineHeight, $this->darFormatoMoneda($valor["p_total"]), 0, 0, "R");
      $pdf->Cell(14, $lineHeight, $this->darFormatoMoneda($valor["x_cobrar"]), 0, 0, "R");
      $pdf->Cell(14, $lineHeight, $this->darFormatoMoneda($valor["pagado"]), 0, 0, "R");
      $pdf->Ln();
    }

    $pdf->Line(114, $pdf->GetY(), 184, $pdf->GetY());

    $pdf->SetFont('Arial', 'B', $tamanoLetra);
    $pdf->Cell(132, $lineHeight, "TOTAL", 0, 0, "R");
    $pdf->Cell(14, $lineHeight, $this->darFormatoMoneda($grupoSRV_PAQ["TOTALES"]["TOTAL"]), 0, 0, "R");
    $pdf->Cell(14, $lineHeight, $this->darFormatoMoneda($grupoSRV_PAQ["TOTALES"]["X_COBRAR"]), 0, 0, "R");
    $pdf->Cell(14, $lineHeight, $this->darFormatoMoneda($grupoSRV_PAQ["TOTALES"]["PAGADO"]), 0, 0, "R");
    $pdf->SetFont('Arial', null, $tamanoLetra);

    $pdf->Ln();
    $pdf->Ln();

    // resumen de resumenes
    $pdf->SetFont('Arial', 'B', $tamanoLetra);

    $pdf->Cell(132, $lineHeight, "TOTAL PRODUCTOS", 0, 0, "R");
    $pdf->Cell(14, $lineHeight, $this->darFormatoMoneda($grupoPRD_RST['TOTALES']["TOTAL"]), 0, 0, "R");
    $pdf->Cell(14, $lineHeight, $this->darFormatoMoneda($grupoPRD_RST['TOTALES']["X_COBRAR"]), 0, 0, "R");
    $pdf->Cell(14, $lineHeight, $this->darFormatoMoneda($grupoPRD_RST['TOTALES']["PAGADO"]), 0, 0, "R");
    $pdf->Ln();

    $pdf->Cell(132, $lineHeight, "TOTAL SERVICIOS", 0, 0, "R");
    $pdf->Cell(14, $lineHeight, $this->darFormatoMoneda($grupoSRV_PAQ["TOTALES"]["TOTAL"]), 0, 0, "R");
    $pdf->Cell(14, $lineHeight, $this->darFormatoMoneda($grupoSRV_PAQ["TOTALES"]["X_COBRAR"]), 0, 0, "R");
    $pdf->Cell(14, $lineHeight, $this->darFormatoMoneda($grupoSRV_PAQ["TOTALES"]["PAGADO"]), 0, 0, "R");
    $pdf->Ln();

    $pdf->Line(114, $pdf->GetY(), 184, $pdf->GetY());

    // sumar los totales de productos y recetas y servicios y paquetes
    $pdf->SetFont('Arial', 'B', $tamanoLetra);
    $pdf->Cell(132, $lineHeight, "TOTAL:", 0, 0, "R");
    $pdf->Cell(14, $lineHeight, $this->darFormatoMoneda($grupoSRV_PAQ["TOTALES"]["TOTAL"] + $grupoPRD_RST['TOTALES']["TOTAL"]), 0, 0, "R");
    $pdf->Cell(14, $lineHeight, $this->darFormatoMoneda($grupoSRV_PAQ["TOTALES"]["X_COBRAR"] + $grupoPRD_RST['TOTALES']["X_COBRAR"]), 0, 0, "R");
    $pdf->Cell(14, $lineHeight, $this->darFormatoMoneda($grupoSRV_PAQ["TOTALES"]["PAGADO"] + $grupoPRD_RST['TOTALES']["PAGADO"]), 0, 0, "R");

    $pdf->Output();
  }

  function darFormatoMoneda($monto)
  {
    return number_format($monto, 2, '.', ',');
  }
}

try {
  $controller = new ReportesController();
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