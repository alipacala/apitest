<?php
require_once PROJECT_ROOT_PATH . "/fpdf/fpdf.php";

class ReporteLiquidacionServicios
{
  function generarReporte($result = null, $fecha = null, $nombreTerapista = null)
  {
    [$result, $header] = $this->prepararDatos($result, $fecha, $nombreTerapista);

    $pdf = new FPDF();
    $pdf->AddPage(null, 'A4');

    $lineHeight = 4;
    $tamanoLetra = 6;

    $this->imprimirCabecera($pdf, $lineHeight, $tamanoLetra, $header);
    $this->imprimirCabeceraTabla($pdf, $lineHeight, $tamanoLetra);
    $this->imprimirServiciosTabla($pdf, $lineHeight, $tamanoLetra, $result);
    $this->imprimirTotales($pdf, $lineHeight, $tamanoLetra, $header);

    $pdf->Output();
  }

  function prepararDatos($result = null, $fecha = null, $nombreTerapista = null)
  {
    $header["FECHA"] = date("d/m/Y");
    $header["HORA"] = date("H:i:s");
    $header["FECHA_SERVICIOS"] = $fecha;
    $header["PROFESIONAL"] = $nombreTerapista;

    // calcular el total
    $header["TOTAL"] = 0;

    foreach ($result as $servicio) {
      $header["TOTAL"] += $servicio["monto_comision"];
    }

    return [$result, $header];
  }

  function imprimirCabecera(FPDF $pdf, $lineHeight, $tamanoLetra, $header)
  {
    $pdf->Image(PROJECT_ROOT_PATH . DIRECTORY_SEPARATOR . "assets" . DIRECTORY_SEPARATOR . "img" . DIRECTORY_SEPARATOR . "logo.png", null, null, 30, 0, "PNG");

    $pdf->Cell(0);
    $pdf->Ln();

    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, $lineHeight, "LIQUIDACION DE SERVICIOS DE SPA - " . $header["FECHA_SERVICIOS"], 0, 0, "C");
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
    $pdf->Cell(64, $lineHeight, "Servicio", 1, 0, "C");
    $pdf->Cell(14, $lineHeight, "TIPO CL.", 1, 0, "C");
    $pdf->Cell(56, $lineHeight, "CLIENTE", 1, 0, "C");
    $pdf->Cell(14, $lineHeight, "Nro. Serv.", 1, 0, "C");
    $pdf->Cell(14, $lineHeight, "C. Servicio", 1, 0, "C");
    $pdf->Cell(14, $lineHeight, "P.COM. %", 1, 0, "C");
    $pdf->Cell(14, $lineHeight, "M. Comision", 1, 0, "C");
    $pdf->Ln();
  }

  function imprimirServiciosTabla(FPDF $pdf, $lineHeight, $tamanoLetra, $result)
  {
    foreach ($result as $servicio) {
      $pdf->SetFont('Arial', null, $tamanoLetra);

      $pdf->Cell(64, $lineHeight, $servicio["servicio"]);
      $pdf->Cell(14, $lineHeight, $servicio["tipo_cliente"]);
      $pdf->Cell(56, $lineHeight, $servicio["cliente"]);
      $pdf->Cell(14, $lineHeight, $servicio["nro_servicio"], 0, 0, "C");
      $pdf->Cell(14, $lineHeight, $this->darFormatoMoneda($servicio["costo_servicio"]), 0, 0, "R");
      $pdf->Cell(14, $lineHeight, $servicio["porc_comision"], 0, 0, "R");
      $pdf->Cell(14, $lineHeight, $this->darFormatoMoneda($servicio["monto_comision"]), 0, 0, "R");
      $pdf->Ln();
    }
  }

  function imprimirTotales(FPDF $pdf, $lineHeight, $tamanoLetra, $header)
  {
    $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
    $pdf->SetFont('Arial', null, $tamanoLetra);
    $pdf->Cell(176, $lineHeight, "TOTAL: ", 0, 0, "R");

    $pdf->SetFont('Arial', 'B', $tamanoLetra);
    $pdf->Cell(14, $lineHeight, $header["TOTAL"], 0, 0, "R");
    $pdf->Ln();
  }

  function darFormatoMoneda($monto)
  {
    return number_format($monto, 2, '.', ',');
  }
}

?>