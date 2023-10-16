<?php
require_once PROJECT_ROOT_PATH . "/fpdf/fpdf.php";

class ReporteConsultaProductosInsumos
{
  function generarReporte($result = null, $nombreProducto = null, $tipoProducto = null)
  {
    [$result, $header] = $this->prepararDatos($result, $nombreProducto, $tipoProducto);

    $pdf = new FPDF();
    $pdf->AddPage(null, 'A4');

    $lineHeight = 4;
    $tamanoLetra = 6;

    $this->imprimirCabecera($pdf, $lineHeight, $tamanoLetra, $header);
    $this->imprimirCabeceraTabla($pdf, $lineHeight, $tamanoLetra);
    $this->imprimirProductosTabla($pdf, $lineHeight, $tamanoLetra, $result);
    $this->imprimirTotales($pdf, $lineHeight, $tamanoLetra, $header);

    $pdf->Output();
  }

  function prepararDatos($result = null, $nombreProducto = null, $tipoProducto = null)
  {
    $header = [];
    $header["FECHA"] = date("d/m/Y");
    $header["HORA"] = date("H:i:s");

    if ($nombreProducto) {
      $header["PARAMETRO"] = $nombreProducto;
    } else if ($tipoProducto) {
      $nombreTipo = array_key_first($result);
      $header["PARAMETRO"] = $nombreTipo;
    } else {
      $header["PARAMETRO"] = "";
    }

    // calcular el total
    $header["COSTO_TOTAL"] = 0;
    $header["STOCK"] = 0;

    foreach ($result as $grupo => $productos) {
      foreach ($productos as $producto) {
        $header["COSTO_TOTAL"] += $producto["costo_total"];
        $header["STOCK"] += $producto["stock"];
      }
    }

    return [$result, $header];
  }

  function imprimirCabecera(FPDF $pdf, $lineHeight, $tamanoLetra, $header)
  {
    $pdf->Image(PROJECT_ROOT_PATH . DIRECTORY_SEPARATOR . "assets" . DIRECTORY_SEPARATOR . "img" . DIRECTORY_SEPARATOR . "logo.png", null, null, 30, 0, "PNG");

    $pdf->Cell(0);
    $pdf->Ln();

    $pdf->Cell(0, $lineHeight);
    $pdf->Ln();

    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, $lineHeight, 'REPORTE DE CONSULTA DE PRODUCTOS / INSUMOS', 0, 0, "C");
    $pdf->Ln();
    $pdf->Ln();
    $pdf->Cell(0, $lineHeight, 'PARAMETRO: "' . $header["PARAMETRO"] . '"', 0, 0, "C");
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
    $pdf->Cell(30, $lineHeight);

    $pdf->Cell(64, $lineHeight, "Tipo de insumo / Producto", 1, 0, "C");
    $pdf->Cell(14, $lineHeight, "P. Costo", 1, 0, "C");
    $pdf->Cell(14, $lineHeight, "Costo total", 1, 0, "C");
    $pdf->Cell(14, $lineHeight, "Stock", 1, 0, "C");
    $pdf->Cell(14, $lineHeight, "T. Unidad", 1, 0, "C");
    $pdf->Ln();
  }

  function imprimirProductosTabla(FPDF $pdf, $lineHeight, $tamanoLetra, $result)
  {
    foreach ($result as $grupo => $productos) {
      $pdf->SetFont('Arial', 'B', $tamanoLetra);

      $pdf->Cell(30, $lineHeight);
      $pdf->Cell(120, $lineHeight, $grupo);

      $pdf->SetFont('Arial', null, $tamanoLetra);

      foreach ($productos as $producto) {
        $pdf->Ln();

        $pdf->Cell(35, $lineHeight);

        $pdf->Cell(59, $lineHeight, $producto["nombre_producto"], 0, 0, "L");
        $pdf->Cell(14, $lineHeight, $this->darFormatoMoneda($producto["costo_unitario"]), 0, 0, "R");
        $pdf->Cell(14, $lineHeight, $this->darFormatoMoneda($producto["costo_total"]), 0, 0, "R");
        $pdf->Cell(14, $lineHeight, $this->darFormatoMoneda($producto["stock"]), 0, 0, "R");
        $pdf->Cell(14, $lineHeight, $producto["tipo_de_unidad"], 0, 0, "C");
      }

      $pdf->Ln();
    }
  }

  function imprimirTotales(FPDF $pdf, $lineHeight, $tamanoLetra, $header)
  {
    $pdf->Line(40, $pdf->GetY(), 160, $pdf->GetY());
    $pdf->SetFont('Arial', null, $tamanoLetra);
    $pdf->Cell(108, $lineHeight, "TOTAL: ", 0, 0, "R");

    $pdf->SetFont('Arial', 'B', $tamanoLetra);
    $pdf->Cell(14, $lineHeight, $this->darFormatoMoneda($header["COSTO_TOTAL"]), 0, 0, "R");
    $pdf->Cell(14, $lineHeight, $this->darFormatoMoneda($header["STOCK"]), 0, 0, "R");
    $pdf->Cell(14, $lineHeight);
  }

  function darFormatoMoneda($monto)
  {
    return number_format($monto, 2, '.', ',');
  }
}

?>