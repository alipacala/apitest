<?php
require_once PROJECT_ROOT_PATH . "/fpdf/fpdf.php";

class ReporteKardex
{
  function generarReporte($result = null, $nombreProducto = null, $fechaInicio = null, $fechaFin = null)
  {
    [$result, $header] = $this->prepararDatos($result, $nombreProducto, $fechaInicio, $fechaFin);

    $pdf = new FPDF();
    $pdf->AddPage(null, 'A4');

    $lineHeight = 4;
    $tamanoLetra = 6;

    $this->imprimirCabecera($pdf, $lineHeight, $tamanoLetra, $header);
    $this->imprimirCabeceraTabla($pdf, $lineHeight, $tamanoLetra);
    $this->imprimirPrimerItemTabla($pdf, $lineHeight, $tamanoLetra, $result);
    $this->imprimirItemsTabla($pdf, $lineHeight, $tamanoLetra, $result);

    $pdf->Output();
  }

  function prepararDatos($result = null, $nombreProducto = null, $fechaInicio = null, $fechaFin = null)
  {
    $header = [];
    $header["FECHA"] = date("d/m/Y");
    $header["HORA"] = date("H:i:s");
    $header["FECHA_INICIO"] = $fechaInicio;
    $header["FECHA_FIN"] = $fechaFin;
    $header["NOMBRE_PRODUCTO"] = $nombreProducto;

    // obtener el stock total que está en la última fila
    $header["STOCK"] = $result[count($result) - 1]["existencias"];

    return [$result, $header];
  }

  function imprimirCabecera(FPDF $pdf, $lineHeight, $tamanoLetra, $header)
  {
    $pdf->Image(PROJECT_ROOT_PATH . DIRECTORY_SEPARATOR . "assets" . DIRECTORY_SEPARATOR . "img" . DIRECTORY_SEPARATOR . "logo.png", null, null, 30, 0, "PNG");

    $pdf->Cell(0, 4);
    $pdf->Ln();

    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, $lineHeight, "KARDEX DE \"". $header["NOMBRE_PRODUCTO"] ."\" - DEL " . $header["FECHA_INICIO"] . " AL " . $header["FECHA_FIN"], 0, 0, "C");
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
    $pdf->Cell(24, $lineHeight, "FECHA", 1, 0, "C");
    $pdf->Cell(20, $lineHeight, "NRO DOC", 1, 0, "C");
    $pdf->Cell(58, $lineHeight, "NOMBRE", 1, 0, "C");
    $pdf->Cell(14, $lineHeight, "INGRESO", 1, 0, "C");
    $pdf->Cell(14, $lineHeight, "SALIDA", 1, 0, "C");
    $pdf->Cell(14, $lineHeight, "EXIST", 1, 0, "C");
    $pdf->Cell(14, $lineHeight, "T. UND", 1, 0, "C");
    $pdf->Cell(14, $lineHeight, "P. COSTO", 1, 0, "C");
    $pdf->Cell(14, $lineHeight, "P. TOTAL", 1, 0, "C");
    $pdf->Ln();
  }

  function imprimirPrimerItemTabla(FPDF $pdf, $lineHeight, $tamanoLetra, $result)
  {
    $pdf->SetFont('Arial', null, $tamanoLetra);

    $pdf->Cell(102, $lineHeight, "Viene:");
    $pdf->Cell(14, $lineHeight, round($result[0]["ingreso"], 0), 0, 0, "C");
    $pdf->Cell(14, $lineHeight, round($result[0]["salida"], 0), 0, 0, "C");
    $pdf->Cell(14, $lineHeight, round($result[0]["existencias"], 0), 0, 0, "C");
    $pdf->Cell(14, $lineHeight, $result[0]["tipo_de_unidad"], 0, 0, "C");
    $pdf->Cell(14, $lineHeight, $this->darFormatoMoneda($result[0]["precio_unitario"]), 0, 0, "R");
    $pdf->Cell(14, $lineHeight, $this->darFormatoMoneda($result[0]["monto_total"]), 0, 0, "R");

    $pdf->Ln();
  }

  function imprimirItemsTabla(FPDF $pdf, $lineHeight, $tamanoLetra, $result)
  {
    foreach ($result as $item) {
      $pdf->SetFont('Arial', null, $tamanoLetra);

      $pdf->Cell(24, $lineHeight, $item["fecha"]);
      $pdf->Cell(20, $lineHeight, $item["nro_doc"]);
      $pdf->Cell(58, $lineHeight, $item["apellidos"] ? $item["apellidos"] . ', ' . $item["nombres"] : "---");
      $pdf->Cell(14, $lineHeight, round($item["ingreso"], 0), 0, 0, "C");
      $pdf->Cell(14, $lineHeight, round($item["salida"], 0), 0, 0, "C");
      $pdf->Cell(14, $lineHeight, round($item["existencias"], 0), 0, 0, "C");
      $pdf->Cell(14, $lineHeight, $item["tipo_de_unidad"], 0, 0, "C");
      $pdf->Cell(14, $lineHeight, $this->darFormatoMoneda($item["precio_unitario"]), 0, 0, "R");
      $pdf->Cell(14, $lineHeight, $this->darFormatoMoneda($item["monto_total"]), 0, 0, "R");

      $pdf->Ln();
    }
  }

  function darFormatoMoneda($monto)
  {
    return number_format($monto, 2, '.', ',');
  }
}

?>