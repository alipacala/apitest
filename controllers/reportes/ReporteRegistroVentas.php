<?php
require_once PROJECT_ROOT_PATH . "/fpdf/fpdf.php";

class ReporteRegistroVentas
{
  function generarReporte($result = null, $usuario = null, $fecha = null, $mes = null, $anio = null)
  {
    [$result, $header] = $this->prepararDatos($result, $usuario, $fecha, $mes, $anio);

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

  function prepararDatos($result = null, $usuario = null, $fecha = null, $mes = null, $anio = null)
  {
    $meses = [
      1 => "ENERO",
      2 => "FEBRERO",
      3 => "MARZO",
      4 => "ABRIL",
      5 => "MAYO",
      6 => "JUNIO",
      7 => "JULIO",
      8 => "AGOSTO",
      9 => "SEPTIEMBRE",
      10 => "OCTUBRE",
      11 => "NOVIEMBRE",
      12 => "DICIEMBRE"
    ];

    $header = [];
    $header["USUARIO"] = $usuario;
    $header["FECHA"] = date("d/m/Y");
    $header["HORA"] = date("H:i:s");

    if ($fecha != null) {
      $header["CRITERIO"] = $fecha;
    } else if ($mes != null && $anio != null) {
      $header["CRITERIO"] = "$meses[$mes] $anio";
    } else {
      $header["CRITERIO"] = "TODOS";
    }

    // calcular el total
    $header["TOTAL"] = 0;

    foreach ($result as $comprobante) {
      $header["TOTAL"] += $comprobante["monto"];
    }

    return [$result, $header];
  }

  function imprimirCabecera(FPDF $pdf, $lineHeight, $tamanoLetra, $header)
  {
    $pdf->Image(PROJECT_ROOT_PATH . DIRECTORY_SEPARATOR . "assets" . DIRECTORY_SEPARATOR . "img" . DIRECTORY_SEPARATOR . "logo.png", null, null, 30, 0, "PNG");

    $pdf->Cell(0);
    $pdf->Ln();

    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, $lineHeight, "REPORTE DE VENTAS - " . $header["CRITERIO"], 0, 0, "C");
    $pdf->Ln();
    $pdf->Ln();

    $pdf->SetFont('Arial', null, $tamanoLetra);
    $pdf->Cell(160, $lineHeight, "Fecha: ", 0, 0, "R");
    $pdf->Cell(40, $lineHeight, $header["FECHA"]);
    $pdf->Ln();
    $pdf->Cell(160, $lineHeight, "Hora: ", 0, 0, "R");
    $pdf->Cell(40, $lineHeight, $header["HORA"]);
    $pdf->Ln();
    $pdf->Cell(160, $lineHeight, "Usuario: ", 0, 0, "R");
    $pdf->Cell(40, $lineHeight, $header["USUARIO"]);

    $pdf->Ln();
    $pdf->Ln();
  }

  function imprimirCabeceraTabla(FPDF $pdf, $lineHeight, $tamanoLetra)
  {
    $pdf->Cell(14, $lineHeight, "FECHA", 1, 0, "C");
    $pdf->Cell(12, $lineHeight, "TIPO DOC", 1, 0, "C");
    $pdf->Cell(24, $lineHeight, "NRO COMPROBANTE", 1, 0, "C");
    $pdf->Cell(96, $lineHeight, "NOMBRE", 1, 0, "C");
    $pdf->Cell(16, $lineHeight, "DNI/RUC", 1, 0, "C");
    $pdf->Cell(14, $lineHeight, "ESTADO", 1, 0, "C");
    $pdf->Cell(14, $lineHeight, "MONTO", 1, 0, "C");
    $pdf->Ln();
  }

  function imprimirComprobantesTabla(FPDF $pdf, $lineHeight, $tamanoLetra, $result)
  {
    foreach ($result as $comprobante) {
      $pdf->SetFont('Arial', null, $tamanoLetra);

      $pdf->Cell(14, $lineHeight, $comprobante["fecha"]);
      $pdf->Cell(12, $lineHeight, $comprobante["tipo_doc"], 0, 0, "C");
      $pdf->Cell(24, $lineHeight, $comprobante["nro_comprobante"]);
      $pdf->Cell(96, $lineHeight, $comprobante["nombre"]);
      $pdf->Cell(16, $lineHeight, $comprobante["dni_ruc"], 0, 0, "C");
      $pdf->Cell(14, $lineHeight, $comprobante["estado"], 0, 0, "C");
      $pdf->Cell(14, $lineHeight, $this->darFormatoMoneda($comprobante["monto"]), 0, 0, "R");
      $pdf->Ln();
    }
  }

  function imprimirTotales(FPDF $pdf, $lineHeight, $tamanoLetra, $header)
  {
    $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
    $pdf->SetFont('Arial', null, $tamanoLetra);
    $pdf->Cell(176, $lineHeight, "TOTAL: ", 0, 0, "R");

    $pdf->SetFont('Arial', 'B', $tamanoLetra);
    $pdf->Cell(14, $lineHeight, "S/ " . $this->darFormatoMoneda($header["TOTAL"]), 0, 0, "R");
    $pdf->Ln();
  }

  function darFormatoMoneda($monto)
  {
    return number_format($monto, 2, '.', ',');
  }
}

?>