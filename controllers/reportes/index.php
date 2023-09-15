<?php
require_once __DIR__ . "/../../inc/bootstrap.php";
require_once PROJECT_ROOT_PATH . "/controllers/BaseController.php";

require_once PROJECT_ROOT_PATH . "/models/ReportesDb.php";

require_once PROJECT_ROOT_PATH . "/fpdf/fpdf.php";

class ReportesController extends BaseController
{
  public function get()
  {
    $params = $this->getParams();
    $fecha = $params['fecha'] ?? null;

    $reportesDb = new ReportesDb();
    $result = $reportesDb->obtenerReporte($fecha);

    $result = array_map(function ($recibo) {
      return [
        "fecha_comprobante" => $recibo["fecha_documento"],
        "tipo_comprobante" => $recibo["tipo_comprobante"],
        "nro_comprobante" => $recibo["nro_comprobante"],
        "nro_doc_cliente" => $recibo["nro_documento_cliente"],
        "nombre_razon_social" => $recibo["rznSocialUsuario"],
        "total_comprobante" => $recibo["total_comprobante"],
        "tipo_pago" => $recibo["medio_pago"],
        "total_recibo" => $recibo["total_recibo"],
        "nro_cierre_turno" => $recibo["nro_cierre_turno"]
      ];
    }, $result);

    $this->sendResponse($this->generarReporte($fecha, $result), 200);
  }

  public function generarReporte($fecha = null, $data = null)
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
      "DEP" => "DEPOSTO",
      "TRA" => "TRANSF",
    ];

    // agrupar comprobantes por nro_cierre_turno
    $data = array_reduce($data, function ($result, $item) {
      $result[$item["nro_cierre_turno"]][] = $item;
      return $result;
    }, []);

    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->

    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 20, "REPORTE DIARIO DE CAJA - $fecha", 0, 0, "C");
    $pdf->Ln();

    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(18, 8, 'Fecha', 1, 0, "C");
    $pdf->Cell(10, 8, 'T.Doc', 1, 0, "C");
    $pdf->Cell(26, 8, 'Nro DOC.', 1, 0, "C");
    $pdf->Cell(16, 8, 'Dni/Ruc', 1, 0, "C");
    $pdf->Cell(72, 8, 'Nombre Cliente', 1, 0, "C");
    $pdf->Cell(14, 8, 'TOTAL', 1, 0, "C");
    $pdf->Cell(18, 8, 'F. Pago', 1, 0, "C");
    $pdf->Cell(14, 8, 'M. Pago', 1, 0, "C");
    $pdf->Ln();

    $turnoIterador = 1;

    foreach ($data as $cierre_turno) {

      foreach ($cierre_turno as $recibo) {
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(18, 8, $recibo["fecha_comprobante"], 1, 0, "C");
        $pdf->Cell(10, 8, $tiposDoc[$recibo["tipo_comprobante"]], 1, 0, "C");
        $pdf->Cell(26, 8, $recibo["nro_comprobante"], 1, 0, "C");
        $pdf->Cell(16, 8, $recibo["nro_doc_cliente"], 1, 0, "C");
        $pdf->Cell(72, 8, $recibo["nombre_razon_social"], 1);
        $pdf->Cell(14, 8, $recibo["total_comprobante"], 1, 0, "R");
        $pdf->Cell(18, 8, $formasPago[$recibo["tipo_pago"]], 1);
        $pdf->Cell(14, 8, $recibo["total_recibo"], 1, 0, "R");
        $pdf->Ln();
      }

      $totalComprobantes = array_reduce($cierre_turno, function ($result, $item) {
        $result += $item["total_comprobante"];
        return $result;
      }, 0);

      $totalRecibos = array_reduce($cierre_turno, function ($result, $item) {
        $result += $item["total_recibo"];
        return $result;
      }, 0);

      $pdf->Cell(142, 8, "TOTAL $turnoIterador TURNO:", 1, 0, "R");
      $pdf->Cell(14, 8, $totalComprobantes, 1, 0, "R");
      $pdf->Cell(32, 8, $totalComprobantes, 1, 0, "R");

      $pdf->Ln();
      $pdf->Ln();
    }

    return $pdf->Output();
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