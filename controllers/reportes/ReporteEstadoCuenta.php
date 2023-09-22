<?php
require_once PROJECT_ROOT_PATH . "/fpdf/fpdf.php";

class ReporteEstadoCuenta
{
  function generarReporteEstadoCuenta($result = null, $nroRegistroMaestro = null)
  {
    [$result, $header] = $this->prepararDatosReporteEstadoCuenta($result, $nroRegistroMaestro);

    $pdf = new FPDF();
    $pdf->AddPage(null, 'A4');

    $lineHeight = 4;
    $tamanoLetra = 6;

    $this->imprimirCabeceraReporteEstadoCuenta($pdf, $lineHeight, $tamanoLetra, $header);
    $this->imprimirCabeceraTablaReporteEstadoCuenta($pdf, $lineHeight, $tamanoLetra);
    $this->imprimirGruposTablaReporteEstadoCuenta($pdf, $lineHeight, $tamanoLetra, $result);
    $this->imprimirTotalesReporteEstadoCuenta($pdf, $lineHeight, $tamanoLetra, $header);

    $pdf->Output();
  }

  function prepararDatosReporteEstadoCuenta($result = null, $nroRegistroMaestro = null)
  {
    $header = [];
    $header["TITULAR"] = $result[0]["titular"];
    $header["NRO_REGISTRO_MAESTRO"] = $nroRegistroMaestro;
    $header["FECHA_INGRESO"] = $result[0]["fecha_in"];

    // calcular el total y el total por cobrar
    $header["TOTAL"] = 0;
    $header["X_COBRAR"] = 0;

    foreach ($result as $grupo => $detalle) {
      $header["TOTAL"] += $detalle["precio_total"];
      $header["X_COBRAR"] += $detalle["estado"] == 'X COBRAR' || $detalle["estado"] == null
        ? $detalle["precio_total"] : 0;
    }

    foreach ($result as $grupo => $detalle) {
      $result[$detalle["grupo"]][] = $detalle;
    }

    // borrar los detalles no agrupados
    foreach ($result as $index => $detalle) {
      if (is_numeric($index))
        unset($result[$index]);
    }

    return [$result, $header];
  }

  function imprimirCabeceraReporteEstadoCuenta(FPDF $pdf, $lineHeight, $tamanoLetra, $header)
  {
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, $lineHeight, "ESTADO DE CUENTA", 0, 0, "C");
    $pdf->Ln();
    $pdf->Ln();

    $pdf->SetFont('Arial', null, $tamanoLetra);
    $pdf->Cell(160, $lineHeight, "Nro Maestro: " . $header["NRO_REGISTRO_MAESTRO"]);
    $pdf->Cell(40, $lineHeight, "Fecha: " . date("d/m/Y"));
    $pdf->Ln();
    $pdf->Cell(160, $lineHeight, "Nombre del cliente: " . $header["TITULAR"]);
    $pdf->Cell(40, $lineHeight, "Hora: " . date("H:i:s"));
    $pdf->Ln();
    $pdf->Cell(160, $lineHeight, "Fecha ingreso: " . $header["FECHA_INGRESO"]);

    $pdf->Ln();
    $pdf->Ln();
  }

  function imprimirCabeceraTablaReporteEstadoCuenta(FPDF $pdf, $lineHeight, $tamanoLetra)
  {
    $pdf->Cell(14, $lineHeight, "Fecha", 1, 0, "C");
    $pdf->Cell(10, $lineHeight, "Cantidad", 1, 0, "C");
    $pdf->Cell(60, $lineHeight, "Producto", 1, 0, "C");
    $pdf->Cell(10, $lineHeight, "Nro.Hab", 1, 0, "C");
    $pdf->Cell(14, $lineHeight, "P.Unitario", 1, 0, "C");
    $pdf->Cell(14, $lineHeight, "P.Total", 1, 0, "C");
    $pdf->Cell(16, $lineHeight, "Estado", 1, 0, "C");
    $pdf->Cell(20, $lineHeight, "Nro.Comprob.", 1, 0, "C");
    $pdf->Ln();
  }

  function imprimirGruposTablaReporteEstadoCuenta(FPDF $pdf, $lineHeight, $tamanoLetra, $result)
  {
    foreach ($result as $grupo => $detalles) {
      $pdf->SetFont('Arial', 'B', $tamanoLetra);
      $pdf->Cell(20, $lineHeight, $grupo);
      $pdf->Ln();

      $pdf->SetFont('Arial', null, $tamanoLetra);

      foreach ($detalles as $index => $detalle) {
        if (!is_numeric($index))
          continue;

        $pdf->Cell(14, $lineHeight, $grupo == 'SERVICIOS' ? $detalle["fecha_servicio"] : $detalle["fecha"], 0, 0, "C");
        $pdf->Cell(10, $lineHeight, intval($detalle["cantidad"]), 0, 0, "C");
        $pdf->Cell(60, $lineHeight, $detalle["nombre_producto"]);
        $pdf->Cell(10, $lineHeight, $detalle["nro_habitacion"], 0, 0, "C");
        $pdf->Cell(14, $lineHeight, $this->darFormatoMoneda($detalle["precio_unitario"]), 0, 0, "R");
        $pdf->Cell(14, $lineHeight, $this->darFormatoMoneda($detalle["precio_total"]), 0, 0, "R");
        $pdf->Cell(16, $lineHeight, $detalle["estado"], 0, 0, "C");
        $pdf->Cell(20, $lineHeight, $detalle["nro_comprobante"]);
        $pdf->Ln();

        if ($grupo == 'SERVICIOS') {
          // imprimir el cliente que consumio el servicio
          $pdf->Cell(24, $lineHeight, "", 0, 0, "C");
          $pdf->Cell(60, $lineHeight, "\t\t" . $detalle["cliente"]);
          $pdf->Ln();
        }
      }

      $pdf->Ln();
    }

    $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
    $pdf->Ln();
  }

  function imprimirTotalesReporteEstadoCuenta(FPDF $pdf, $lineHeight, $tamanoLetra, $header)
  {
    $pdf->SetFont('Arial', null, $tamanoLetra);
    $pdf->Cell(98, $lineHeight, "TOTAL CONSUMO: ", 0, 0, "R");

    $pdf->SetFont('Arial', 'B', $tamanoLetra);
    $pdf->Cell(24, $lineHeight, $this->darFormatoMoneda($header["TOTAL"]), 0, 0, "R");
    $pdf->Ln();

    $pdf->SetFont('Arial', null, $tamanoLetra);
    $pdf->Cell(98, $lineHeight, "TOTAL PAGOS Y ADELANTOS: ", 0, 0, "R");

    $pdf->SetFont('Arial', 'B', $tamanoLetra);
    $pdf->Cell(24, $lineHeight, $this->darFormatoMoneda($header["TOTAL"] - $header["X_COBRAR"]), 0, 0, "R");
    $pdf->Ln();

    $pdf->Line(72, $pdf->GetY(), 132, $pdf->GetY());

    $pdf->SetFont('Arial', null, $tamanoLetra);
    $pdf->Cell(98, 8 , "TOTAL POR COBRAR: ", 0, 0, "R");

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(24, 8, $this->darFormatoMoneda($header["X_COBRAR"]), 0, 0, "R");
  }

  function darFormatoMoneda($monto)
  {
    return number_format($monto, 2, '.', ',');
  }
}

?>