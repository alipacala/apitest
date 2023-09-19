<?php
require_once __DIR__ . "/../../inc/bootstrap.php";
require_once PROJECT_ROOT_PATH . "/controllers/BaseController.php";

require_once PROJECT_ROOT_PATH . "/models/ReportesDb.php";
require_once PROJECT_ROOT_PATH . "/models/CheckingsDb.php";
require_once PROJECT_ROOT_PATH . "/models/GruposDeLaCartaDb.php";

require_once PROJECT_ROOT_PATH . "/fpdf/fpdf.php";

class ReportesController extends BaseController
{
  public function get()
  {
    $params = $this->getParams();
    $tipo = $params['tipo'] ?? null;
    $fecha = $params['fecha'] ?? null;

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

    } else {
      // no hay ese tipo de reporte
      $this->sendResponse([
        "mensaje" => "No existe ese tipo de reporte"
      ], 400);
    }
  }

  public function generarReporteDiarioCaja($fecha = null, $data = null, $checkings = null)
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
        $nombre = strlen($comprobante["nombre_razon_social"]) > 36 ? substr($comprobante["nombre_razon_social"], 0, 36) . "..." : $comprobante["nombre_razon_social"];
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
    $pdf->Cell(34, $lineHeight);
    $turnoIterador = 1;
    foreach ($turnos as $turno => $comprobantes) {
      $pdf->Cell(16, $lineHeight, "TURNO $turnoIterador", 0, 0, "R");
      $turnoIterador++;
    }
    $pdf->Cell(16, $lineHeight, "TOTALES", 0, 0, "C");
    $pdf->Ln();
    $pdf->Line(10, $pdf->GetY(), 192, $pdf->GetY());

    // imprimir los totales cruzados
    $pdf->SetFont('Arial', '', $tamanoLetra);
    foreach ($formasPago as $formaPago => $nombreCompleto) {
      $pdf->Cell(10, $lineHeight);
      $pdf->Cell(24, $lineHeight, "$nombreCompleto :", 0, 0, "R");
      foreach ($totales[$formaPago] as $totalPorTurno) {
        $pdf->Cell(16, $lineHeight, $this->darFormatoMoneda($totalPorTurno), 0, 0, "R");
      }
      $pdf->Cell(16, $lineHeight, $this->darFormatoMoneda($totalesTotales[$formaPago]), 0, 0, "R");
      $pdf->Ln();
    }

    // imprimir los totales por turno
    $pdf->Cell(10, $lineHeight);

    $pdf->SetFont('Arial', 'B', $tamanoLetra);
    $pdf->Line(10, $pdf->GetY(), 192, $pdf->GetY());
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
    // cambiar el id del grupo de la carta por el nombre del grupo de la carta, pero el grupo al que pertenece si es un subgrupo
    $data = array_map(function ($producto) use ($gruposDeLaCarta) {
      $grupoDeLaCarta = array_values(array_filter($gruposDeLaCarta, function ($item) use ($producto) {
        return $item->codigo_grupo == $producto["codigo_grupo"];
      }))[0];

      if ($grupoDeLaCarta->codigo_grupo != $grupoDeLaCarta->codigo_subgrupo) {
        $grupoDeLaCarta = array_values(array_filter($gruposDeLaCarta, function ($item) use ($producto) {
          return $item->codigo_grupo == $producto["codigo_grupo"] && $item->codigo_subgrupo == $producto["codigo_subgrupo"];
        }))[0];
      }

      $producto["id_grupo"] = $grupoDeLaCarta->id_grupo;
      $producto["nombre_grupo"] = $grupoDeLaCarta->nombre_grupo;

      return $producto;
    }, $data);

    $comprobantesAgrupados = [];

    // agrupar productos por fecha, si la hora de la fecha es antes de las 3pm, entonces es turno mañana, sino es turno tarde
    foreach ($data as $producto) {
      $fecha = $producto['fecha'];
      $hora = date("H", strtotime($fecha));
      $turno = $hora < 15 ? "M" : "T";

      // Verificar si ya existe un comprobante agrupado con la misma fecha y turno
      $comprobanteAgrupado = array_filter($comprobantesAgrupados, function ($item) use ($fecha, $turno) {
        return $item['fecha'] == $fecha && $item['turno'] == $turno;
      });

      if (!empty($comprobanteAgrupado)) {
        // El comprobante ya existe en comprobantesAgrupados
        $comprobanteAgrupadoKey = key($comprobanteAgrupado);
        $comprobantesAgrupados[$comprobanteAgrupadoKey]['productos'][] = [
          'nombre_producto' => $producto['nombre_producto'],
          'cantidad' => $producto['cantidad'],
          'precio_unitario' => $producto['precio_unitario'],
          'precio_total' => $producto['precio_total'],
          'nombre_grupo' => $producto['nombre_grupo'],
        ];
      } else {
        // El comprobante no existe en comprobantesAgrupados, lo agregamos
        $comprobantesAgrupados[] = [
          'fecha' => $fecha,
          'turno' => $turno,
          'productos' => [
            [
              'nombre_producto' => $producto['nombre_producto'],
              'cantidad' => $producto['cantidad'],
              'precio_unitario' => $producto['precio_unitario'],
              'precio_total' => $producto['precio_total'],
              'nombre_grupo' => $producto['nombre_grupo'],
            ],
          ],
        ];
      }
    }

    // agrupar productos por grupo
    $gruposAgrupados = [];
    foreach ($comprobantesAgrupados as $comprobante) {
      foreach ($comprobante["productos"] as $producto) {
        $nombreGrupo = $producto["nombre_grupo"];

        // Verificar si ya existe un grupo agrupado con el mismo nombre de grupo
        $grupoAgrupado = array_filter($gruposAgrupados, function ($item) use ($nombreGrupo) {
          return $item['nombre_grupo'] == $nombreGrupo;
        });

        if (!empty($grupoAgrupado)) {
          // El grupo ya existe en gruposAgrupados
          $grupoAgrupadoKey = key($grupoAgrupado);
          $gruposAgrupados[$grupoAgrupadoKey]['productos'][] = [
            'nombre_producto' => $producto['nombre_producto'],
            'cantidad' => $producto['cantidad'],
            'precio_unitario' => $producto['precio_unitario'],
            'precio_total' => $producto['precio_total'],
          ];
        } else {
          // El grupo no existe en gruposAgrupados, lo agregamos
          $gruposAgrupados[] = [
            'nombre_grupo' => $nombreGrupo,
            'productos' => [
              [
                'nombre_producto' => $producto['nombre_producto'],
                'cantidad' => $producto['cantidad'],
                'precio_unitario' => $producto['precio_unitario'],
                'precio_total' => $producto['precio_total'],
              ],
            ],
          ];
        }
      }
    }

    // agrupar productos por comprobante en cada grupo
    return $gruposAgrupados;
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