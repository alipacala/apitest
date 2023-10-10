<?php
require_once PROJECT_ROOT_PATH . "/fpdf/fpdf.php";

class ReporteCompras
{
  function generarReporte($result = null, $fechaInicio = null, $fechaFin = null)
  {
    [$result, $header] = $this->prepararDatos($result, $fechaInicio, $fechaFin);

    $pdf = new FPDF();
    $pdf->AddPage(null, 'A4');

    $lineHeight = 4;
    $tamanoLetra = 6;

    $this->imprimirCabecera($pdf, $lineHeight, $tamanoLetra, $header);
    $this->imprimirCabeceraTabla($pdf, $lineHeight, $tamanoLetra);
    $this->imprimirComprobantesTabla($pdf, $lineHeight, $tamanoLetra, $result);
    $this->imprimirTotales($pdf, $lineHeight, $tamanoLetra, $header);

    $pdf->Output();
  }

  function prepararDatos($result = null, $fechaInicio = null, $fechaFin = null)
  {
    $header = [];
    $header["FECHA"] = date("d/m/Y");
    $header["HORA"] = date("H:i:s");
    $header["FECHA_INICIO"] = $fechaInicio;
    $header["FECHA_FIN"] = $fechaFin;

    // calcular el total
    $header["SUBTOTAL"] = 0;
    $header["IGV"] = 0;
    $header["TOTAL"] = 0;
    $header["PERCEPCION"] = 0;
    $header["GRAN_TOTAL"] = 0;
    $header["X_PAGAR"] = 0;

    foreach ($result as $comprobante) {
      $header["SUBTOTAL"] += $comprobante["subtotal"];
      $header["IGV"] += $comprobante["igv"];
      $header["TOTAL"] += $comprobante["total"];
      $header["PERCEPCION"] += $comprobante["percepcion"];
      $header["GRAN_TOTAL"] += $comprobante["gran_total"];
      $header["X_PAGAR"] += $comprobante["por_pagar"];
    }

    return [$result, $header];
  }

  function imprimirCabecera(FPDF $pdf, $lineHeight, $tamanoLetra, $header)
  {
    $pdf->Image(PROJECT_ROOT_PATH . DIRECTORY_SEPARATOR . "assets" . DIRECTORY_SEPARATOR . "img" . DIRECTORY_SEPARATOR . "logo.png", null, null, 30, 0, "PNG");

    $pdf->Cell(0);
    $pdf->Ln();

    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, $lineHeight, "REPORTE DE COMPRAS - DEL " . $header["FECHA_INICIO"] . " AL " . $header["FECHA_FIN"], 0, 0, "C");
    $pdf->Ln();
    $pdf->Ln();

    $pdf->SetFont('Arial', null, $tamanoLetra);
    $pdf->Cell(160, $lineHeight, "Fecha: ", 0, 0, "R");
    $pdf->Cell(40, $lineHeight, $header["FECHA"]);
    $pdf->Ln();
    $pdf->Cell(160, $lineHeight, "Hora: ", 0, 0, "R");
    $pdf->Cell(40, $lineHeight, $header["HORA"]);

    $pdf->Ln();
    $pdf->Ln();
  }

  function imprimirCabeceraTabla(FPDF $pdf, $lineHeight, $tamanoLetra)
  {
    $pdf->Cell(14, $lineHeight, "FECHA", 1, 0, "C");
    $pdf->Cell(18, $lineHeight, "NRO COMPR.", 1, 0, "C");
    $pdf->Cell(14, $lineHeight, "RUC", 1, 0, "C");
    $pdf->Cell(44, $lineHeight, "PROVEEDOR", 1, 0, "C");
    $pdf->Cell(14, $lineHeight, "SUBTOTAL", 1, 0, "C");
    $pdf->Cell(14, $lineHeight, "IGV", 1, 0, "C");
    $pdf->Cell(14, $lineHeight, "TOTAL", 1, 0, "C");
    $pdf->Cell(14, $lineHeight, "PERC.", 1, 0, "C");
    $pdf->Cell(14, $lineHeight, "G. TOTAL", 1, 0, "C");
    $pdf->Cell(14, $lineHeight, "ESTADO", 1, 0, "C");
    $pdf->Cell(14, $lineHeight, "X PAGAR", 1, 0, "C");
    $pdf->Ln();
  }

  function imprimirComprobantesTabla(FPDF $pdf, $lineHeight, $tamanoLetra, $result)
  {
    foreach ($result as $comprobante) {
      $pdf->SetFont('Arial', null, $tamanoLetra);

      $pdf->Cell(14, $lineHeight, $comprobante["fecha"]);
      $pdf->Cell(18, $lineHeight, $comprobante["nro_comprobante"]);
      $pdf->Cell(14, $lineHeight, $comprobante["ruc"], 0, 0, "C");
      $pdf->Cell(44, $lineHeight, $comprobante["proveedor"]);
      $pdf->Cell(14, $lineHeight, $this->darFormatoMoneda($comprobante["subtotal"]), 0, 0, "R");
      $pdf->Cell(14, $lineHeight, $this->darFormatoMoneda($comprobante["igv"]), 0, 0, "R");
      $pdf->Cell(14, $lineHeight, $this->darFormatoMoneda($comprobante["total"]), 0, 0, "R");
      $pdf->Cell(14, $lineHeight, $this->darFormatoMoneda($comprobante["percepcion"]), 0, 0, "R");
      $pdf->Cell(14, $lineHeight, $this->darFormatoMoneda($comprobante["gran_total"]), 0, 0, "R");
      $pdf->Cell(14, $lineHeight, $comprobante["estado"], 0, 0, "C");
      $pdf->Cell(14, $lineHeight, $this->darFormatoMoneda($comprobante["por_pagar"]), 0, 0, "R");
      $pdf->Ln();
    }
  }

  function imprimirTotales(FPDF $pdf, $lineHeight, $tamanoLetra, $header)
  {
    $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
    $pdf->SetFont('Arial', null, $tamanoLetra);
    $pdf->Cell(90, $lineHeight, "TOTAL: ", 0, 0, "R");

    $pdf->SetFont('Arial', 'B', $tamanoLetra);
    $pdf->Cell(14, $lineHeight, $this->darFormatoMoneda($header["SUBTOTAL"]), 0, 0, "R");
    $pdf->Cell(14, $lineHeight, $this->darFormatoMoneda($header["IGV"]), 0, 0, "R");
    $pdf->Cell(14, $lineHeight, $this->darFormatoMoneda($header["TOTAL"]), 0, 0, "R");
    $pdf->Cell(14, $lineHeight, $this->darFormatoMoneda($header["PERCEPCION"]), 0, 0, "R");
    $pdf->Cell(14, $lineHeight, $this->darFormatoMoneda($header["GRAN_TOTAL"]), 0, 0, "R");
    $pdf->Cell(14, $lineHeight);
    $pdf->Cell(14, $lineHeight, $this->darFormatoMoneda($header["X_PAGAR"]), 0, 0, "R");
  }

  function darFormatoMoneda($monto)
  {
    return number_format($monto, 2, '.', ',');
  }
}

?>