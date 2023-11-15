<?php
require_once PROJECT_ROOT_PATH . "/fpdf/fpdf.php";

class ReporteDesayunos
{
  function generarReporte($result = null, $fecha = null)
  {
    [$result, $header] = $this->prepararDatos($result, $fecha);

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

  function prepararDatos($result = null, $fecha = null)
  {
    $header["FECHA"] = date("d/m/Y");
    $header["HORA"] = date("H:i:s");
    $header["FECHA_CONSULTA"] = $fecha;

    // filtrar registros por los que tienen nro_personas
    $result = array_filter($result, function ($habitacion) {
      return $habitacion["nro_personas"] > 0;
    });

    // calcular el total
    $header["TOTAL_PERSONAS"] = 0;

    foreach ($result as $habitacion) {
      $header["TOTAL_PERSONAS"] += $habitacion["nro_personas"];
    }

    return [$result, $header];
  }

  function imprimirCabecera(FPDF $pdf, $lineHeight, $tamanoLetra, $header)
  {
    $pdf->Image(PROJECT_ROOT_PATH . DIRECTORY_SEPARATOR . "assets" . DIRECTORY_SEPARATOR . "img" . DIRECTORY_SEPARATOR . "logo.png", null, null, 30, 0, "PNG");

    $pdf->Cell(0);
    $pdf->Ln();

    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, $lineHeight, "PROGRAMACION DESAYUNOS - " . $header["FECHA_CONSULTA"], 0, 0, "C");
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
    $pdf->Cell(42, $lineHeight, "PRODUCTO", 1, 0, "C");
    $pdf->Cell(18, $lineHeight, "NRO HAB.", 1, 0, "C");
    $pdf->Cell(42, $lineHeight, "CLIENTE", 1, 0, "C");
    $pdf->Cell(20, $lineHeight, "NRO PERSONAS", 1, 0, "C");
    $pdf->Cell(24, $lineHeight, "FECHA LLEGADA", 1, 0, "C");
    $pdf->Cell(24, $lineHeight, "FECHA SALIDA", 1, 0, "C");
    $pdf->Ln();
  }

  function imprimirComprobantesTabla(FPDF $pdf, $lineHeight, $tamanoLetra, $result)
  {
    foreach ($result as $habitacion) {
      $pdf->SetFont('Arial', null, $tamanoLetra);

      $pdf->Cell(42, $lineHeight, $habitacion["nombre_producto"]);
      $pdf->Cell(18, $lineHeight, $habitacion["nro_habitacion"], 0, 0, "C");
      $pdf->Cell(42, $lineHeight, $habitacion["nombre"]);
      $pdf->Cell(20, $lineHeight, $habitacion["nro_personas"], 0, 0, "C");
      $pdf->Cell(24, $lineHeight, $habitacion["fecha_in"], 0, 0, "C");
      $pdf->Cell(24, $lineHeight, $habitacion["fecha_out"], 0, 0, "C");
      $pdf->Ln();
    }
  }

  function imprimirTotales(FPDF $pdf, $lineHeight, $tamanoLetra, $header)
  {
    $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
    $pdf->SetFont('Arial', null, $tamanoLetra);
    $pdf->Cell(102, $lineHeight, "TOTAL PERSONAS: ", 0, 0, "R");

    $pdf->SetFont('Arial', 'B', $tamanoLetra);
    $pdf->Cell(20, $lineHeight, $header["TOTAL_PERSONAS"], 0, 0, "C");
    $pdf->Ln();
  }

  function darFormatoMoneda($monto)
  {
    return number_format($monto, 2, '.', ',');
  }
}

?>