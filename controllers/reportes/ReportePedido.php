<?php
require_once PROJECT_ROOT_PATH . "/fpdf/fpdf.php";

class ReportePedido
{
  function generarReporte($result = null)
  {
    [$result, $header] = $this->prepararDatos($result);

    $pdf = new FPDF();
    $pdf->AddPage(null, 'A4');

    $lineHeight = 4;
    $tamanoLetra = 6;

    $this->imprimirCabecera($pdf, $lineHeight, $tamanoLetra, $header);
    $this->imprimirCabeceraTabla($pdf, $lineHeight, $tamanoLetra);
    $this->imprimirCentralesCostosTabla($pdf, $lineHeight, $tamanoLetra, $result);
    $this->imprimirTotales($pdf, $lineHeight, $tamanoLetra, $header);

    $pdf->Output();
  }

  function prepararDatos($result = null)
  {
    $header = [];

    // calcular el total y el total por cobrar
    $header["TOTAL"] = 0;

    // agrupar por central de costos
    $result = array_reduce($result, function ($acc, $producto) use (&$header) {
      // buscar si ya existe la central de costos en el acumulador
      $key = array_search($producto["id_central_de_costos"], array_column($acc, 'id_central_de_costos'));
      
      // se agrega el costo total del producto al total
      $producto["costo_total"] = $producto["costo_unitario"] * $producto["cantidad_pedido"];
      $header["TOTAL"] += $producto["costo_total"];

      if ($key === false) {
        // si no existe, se agrega
        $acc[] = [
          "id_central_de_costos" => $producto["id_central_de_costos"],
          "nombre_del_costo" => $producto["nombre_del_costo"],
          "productos" => [$producto]
        ];
      } else {
        // si existe, se agrega el producto al array de productos
        $acc[$key]["productos"][] = $producto;
      }

      return $acc;
    }, []);

    return [$result, $header];
  }

  function imprimirCabecera(FPDF $pdf, $lineHeight, $tamanoLetra, $header)
  {
    $pdf->Image(PROJECT_ROOT_PATH . DIRECTORY_SEPARATOR . "assets" . DIRECTORY_SEPARATOR . "img" . DIRECTORY_SEPARATOR . "logo.png", null, null, 30, 0, "PNG");

    $pdf->Cell(0);
    $pdf->Ln();

    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, $lineHeight, "REPORTE DE PEDIDO", 0, 0, "C");
    $pdf->Ln();
    $pdf->Ln();

    $pdf->SetFont('Arial', null, $tamanoLetra);
    $pdf->Cell(160);
    $pdf->Cell(40, $lineHeight, "Fecha: " . date("d/m/Y"));
    $pdf->Ln();
    $pdf->Cell(160);
    $pdf->Cell(40, $lineHeight, "Hora: " . date("H:i:s"));

    $pdf->Ln();
    $pdf->Ln();
  }

  function imprimirCabeceraTabla(FPDF $pdf, $lineHeight, $tamanoLetra)
  {
    $pdf->Cell(56, $lineHeight, "Central de costos", 1);
    $pdf->Cell(28, $lineHeight, "Temporada Baja", 1, 0, "C");
    $pdf->Cell(28, $lineHeight, "Temporada Alta", 1, 0, "C");
    $pdf->Cell(42, $lineHeight, null, 1);

    $pdf->Ln();

    $pdf->Cell(14, $lineHeight, "Codigo", 1, 0, "C");
    $pdf->Cell(42, $lineHeight, "Nombre del producto", 1, 0, "C");
    $pdf->Cell(14, $lineHeight, "Stock Min", 1, 0, "C");
    $pdf->Cell(14, $lineHeight, "Stock Max", 1, 0, "C");
    $pdf->Cell(14, $lineHeight, "Stock Min", 1, 0, "C");
    $pdf->Cell(14, $lineHeight, "Stock Max", 1, 0, "C");
    $pdf->Cell(14, $lineHeight, "Costo unitario", 1, 0, "C");
    $pdf->Cell(14, $lineHeight, "Stock actual", 1, 0, "C");
    $pdf->Cell(14, $lineHeight, "T. Unidad", 1, 0, "C");
    $pdf->Cell(8); 
    $pdf->Cell(14, $lineHeight, "Cant. Pedido", 1, 0, "C");
    $pdf->Cell(14, $lineHeight, "Costo Total", 1, 0, "C");

    $pdf->Ln();
  }

  function imprimirCentralesCostosTabla(FPDF $pdf, $lineHeight, $tamanoLetra, $result)
  {
    foreach ($result as $centralCostos) {
      $pdf->SetFont('Arial', 'B', $tamanoLetra);
      $pdf->Cell(14, $lineHeight, $centralCostos['nombre_del_costo']);
      $pdf->Ln();

      $pdf->SetFont('Arial', null, $tamanoLetra);

      foreach ($centralCostos['productos'] as $producto) {
        $pdf->Cell(14, $lineHeight, $producto["codigo"], 0, 0, "C");
        $pdf->Cell(42, $lineHeight, $producto["nombre_producto"]);
        $pdf->Cell(14, $lineHeight, $producto["stock_min_temporada_baja"], 0, 0, "C");
        $pdf->Cell(14, $lineHeight, $producto["stock_max_temporada_baja"], 0, 0, "C");
        $pdf->Cell(14, $lineHeight, $producto["stock_min_temporada_alta"], 0, 0, "C");
        $pdf->Cell(14, $lineHeight, $producto["stock_max_temporada_alta"], 0, 0, "C");
        $pdf->Cell(14, $lineHeight, $this->darFormatoMoneda($producto["costo_unitario"]), 0, 0, "R");
        $pdf->Cell(14, $lineHeight, $this->darFormatoMoneda($producto["stock"]), 0, 0, "C");
        $pdf->Cell(14, $lineHeight, $producto["tipo_de_unidad"], 0, 0, "C");
        $pdf->Cell(8);
        $pdf->Cell(14, $lineHeight, $producto["cantidad_pedido"], 0, 0, "C");
        $pdf->Cell(14, $lineHeight, $this->darFormatoMoneda($producto["costo_total"]), 0, 0, "R");
        $pdf->Ln();
      }

      $pdf->Ln();
    }

    $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
  }

  function imprimirTotales(FPDF $pdf, $lineHeight, $tamanoLetra, $header)
  {
    $pdf->SetFont('Arial', null, $tamanoLetra);
    $pdf->Cell(174, $lineHeight, "TOTAL: ", 0, 0, "R");

    $pdf->SetFont('Arial', 'B', $tamanoLetra);
    $pdf->Cell(14, $lineHeight, $this->darFormatoMoneda($header["TOTAL"]), 0, 0, "R");
    $pdf->Ln();
    $pdf->Ln();

    $pdf->Line(72, $pdf->GetY(), 132, $pdf->GetY());
  }

  function darFormatoMoneda($monto)
  {
    return number_format($monto, 2, '.', ',');
  }
}

?>