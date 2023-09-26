<?php
require_once PROJECT_ROOT_PATH . "/fpdf/fpdf.php";

class ComprobanteImprimible
{
  function generarReporte($result = null, $porcIGV = null)
  {
    [$result, $header] = $this->prepararDatos($result, $porcIGV);

    $pdf = new FPDF('P', 'mm', array(100, 270));
    $pdf->AddPage();

    $lineHeight = 4;
    $tamanoLetra = 6;

    $pdf->SetFont('Arial', null, $tamanoLetra);

    $this->imprimirCabecera($pdf, $lineHeight, $tamanoLetra, $header);
    $this->imprimirCabeceraTabla($pdf, $lineHeight, $tamanoLetra);
    $this->imprimirComprobantesTabla($pdf, $lineHeight, $tamanoLetra, $result);
    $this->imprimirTotales($pdf, $lineHeight, $tamanoLetra, $header);

    $pdf->Output();
  }

  function prepararDatos($result = null, $porcIGV = null)
  {
    $tiposDoc = [
      "00" => "ORDEN DE PEDIDO",
      "01" => "FACTURA ELECTRONICA",
      "03" => "BOLETA DE VENTA ELECTRONICA"
    ];

    $tiposDocCliente = [
      "0" => "Sin Documento",
      "1" => "DNI",
      "6" => "RUC",
      "7" => "Pasaporte"
    ];

    $header = [];
    $header["FECHA"] = $result[0]["fecha"];
    $header["HORA"] = $result[0]["hora"];
    $header["NRO_COMPROBANTE"] = $result[0]["nro_comprobante"];
    $header["TIPO_DOC"] = $tiposDoc[$result[0]["tipo_doc"]];
    $header["TIPO_DOC_CLIENTE"] = $tiposDocCliente[$result[0]["tipo_documento_cliente"]];
    $header["RUC"] = $result[0]["ruc"];
    $header["NOMBRE"] = $result[0]["nombre"];
    $header["DIRECCION"] = $result[0]["direccion"];
    $header["LUGAR"] = $result[0]["ciudad"]; // TODO: TEMPORAL
    $header["FORMA_PAGO"] = "CONTADO"; // TODO: TEMPORAL

    $header["IGV"] = $porcIGV;
    $header["MONTO_IGV"] = $result[0]["igv"];
    $header["OPE_GRAVADA"] = $result[0]["subtotal"];
    $header["OPE_NO_GRAVADA"] = $this->darFormatoMoneda(0); // TODO: TEMPORAL
    $header["TOTAL"] = $result[0]["total"];

    $formatterES = new \NumberFormatter("es", \NumberFormatter::SPELLOUT);
    $centimos = $header["TOTAL"] - floor($header["TOTAL"]);
    $header["TOTAL_LITERAL"] = strtoupper($formatterES->format($header["TOTAL"])) . " Y " . str_pad(round($centimos * 100), 2, "0") . "/100";

    $result = array_map(function ($comprobante) {
      $comprobante["nombre_producto"] = mb_convert_encoding($comprobante["nombre_producto"], "ISO-8859-1", "UTF-8");
      $comprobante["cantidad"] = intval($comprobante["cantidad"]);
      $comprobante["precio_unitario"] = $this->darFormatoMoneda($comprobante["precio_unitario"]);
      $comprobante["precio_total"] = $this->darFormatoMoneda($comprobante["precio_total"]);
      return $comprobante;
    }, $result);

    return [$result, $header];
  }

  function imprimirCabecera(FPDF $pdf, $lineHeight, $tamanoLetra, $header)
  {
    $pdf->Cell(0, $lineHeight, "DAFFS S.A.C.", 0, 0, "C");
    $pdf->Ln();
    $pdf->Cell(0, $lineHeight, "DOM. FISCAL: AV. Tarapaca Nro. 379", 0, 0, "C");
    $pdf->Ln();
    $pdf->Cell(0, $lineHeight, mb_convert_encoding("DIRECCIÓN: CALLE LAS VILCAS B1 - URB. LOS OLIVOS", "ISO-8859-1", "UTF-8"), 0, 0, "C");
    $pdf->Ln();
    $pdf->Cell(0, $lineHeight, "TACNA - TACNA - TACNA", 0, 0, "C");
    $pdf->Ln();
    $pdf->Cell(0, $lineHeight, "RUC: " . "20601666767", 0, 0, "C");
    $pdf->Ln();
    $pdf->Ln();
    $pdf->Cell(0, $lineHeight, $header["TIPO_DOC"], 0, 0, "C");
    $pdf->Ln();
    $pdf->Cell(0, $lineHeight, $header["NRO_COMPROBANTE"], 0, 0, "C");
    $pdf->Ln();
    $pdf->Ln();

    $pdf->Cell(20, $lineHeight, "Fecha: " . $header["FECHA"]);
    $pdf->Cell(20, $lineHeight, "Hora: " . $header["HORA"]);
    $pdf->Ln();

    if ($header["TIPO_DOC"] != "NOTA DE PEDIDO") {
      if ($header["TIPO_DOC"] == "FACTURA ELECTRONICA") {
        $pdf->Cell(0, $lineHeight, "Forma de pago: " . $header["FORMA_PAGO"]);
        $pdf->Ln();
      }

      $pdf->Line(10, $pdf->GetY(), 90, $pdf->GetY());

      $pdf->Cell(14, $lineHeight, $header["TIPO_DOC_CLIENTE"] . ":");
      $pdf->Cell(14, $lineHeight, $header["RUC"]);
      $pdf->Ln();
      $pdf->Cell(0, $lineHeight, "NOMBRE:");
      $pdf->Ln();
      $pdf->Cell(0, $lineHeight, strtoupper($header["NOMBRE"]));
      $pdf->Ln();

      if ($header["TIPO_DOC_CLIENTE"] == "RUC") {
        $pdf->Cell(0, $lineHeight, "DIRECCION:");
        $pdf->Ln();
        $pdf->Cell(0, $lineHeight, strtoupper($header["DIRECCION"]));
        $pdf->Ln();
        $pdf->Cell(0, $lineHeight, strtoupper($header["LUGAR"]));
        $pdf->Ln();
      }
    }

    $pdf->Line(10, $pdf->GetY(), 90, $pdf->GetY());
  }

  function imprimirCabeceraTabla(FPDF $pdf, $lineHeight, $tamanoLetra)
  {
    $pdf->Cell(44, $lineHeight, "DESCRIPCION", 0, 0, "C");
    $pdf->Cell(12, $lineHeight, "CANT.", 0, 0, "C");
    $pdf->Cell(12, $lineHeight, "P.UNIT.", 0, 0, "C");
    $pdf->Cell(12, $lineHeight, "IMPORTE", 0, 0, "C");
    $pdf->Ln();
    $pdf->Line(10, $pdf->GetY(), 90, $pdf->GetY());
  }

  function imprimirComprobantesTabla(FPDF $pdf, $lineHeight, $tamanoLetra, $result)
  {
    foreach ($result as $comprobante) {
      $pdf->Cell(0, $lineHeight, $comprobante["nombre_producto"]);
      $pdf->Ln();
      $pdf->Cell(44, $lineHeight, "");
      $pdf->Cell(12, $lineHeight, $comprobante["cantidad"], 0, 0, "C");
      $pdf->Cell(12, $lineHeight, $comprobante["precio_unitario"], 0, 0, "R");
      $pdf->Cell(12, $lineHeight, $comprobante["precio_total"], 0, 0, "R");
      $pdf->Ln();
    }
  }

  function imprimirTotales(FPDF $pdf, $lineHeight, $tamanoLetra, $header)
  {
    $pdf->Line(10, $pdf->GetY(), 90, $pdf->GetY());

    if ($header["TIPO_DOC"] != "NOTA DE PEDIDO") {
      $pdf->Cell(38, $lineHeight, "", 0, 0, "R");
      $pdf->Cell(24, $lineHeight, "OPE. GRAVADA");
      $pdf->Cell(4, $lineHeight, ": S/");
      $pdf->Cell(14, $lineHeight, $header["OPE_GRAVADA"], 0, 0, "R");
      $pdf->Ln();

      $pdf->Cell(38, $lineHeight, "", 0, 0, "R");
      $pdf->Cell(24, $lineHeight, "OPE. NO GRAVADA");
      $pdf->Cell(4, $lineHeight, ": S/");
      $pdf->Cell(14, $lineHeight, $header["OPE_NO_GRAVADA"], 0, 0, "R");
      $pdf->Ln();

      $pdf->Cell(38, $lineHeight, "", 0, 0, "R");
      $pdf->Cell(24, $lineHeight, "MONTO IGV " . $header["IGV"] . "%");
      $pdf->Cell(4, $lineHeight, ": S/");
      $pdf->Cell(14, $lineHeight, $header["MONTO_IGV"], 0, 0, "R");
      $pdf->Ln();
      $pdf->Ln();
    }

    $pdf->Cell(38, $lineHeight, "", 0, 0, "R");
    $pdf->Cell(24, $lineHeight, "TOTAL");
    $pdf->Cell(4, $lineHeight, ": S/");
    $pdf->Cell(14, $lineHeight, $header["TOTAL"], 0, 0, "R");
    $pdf->Ln();

    if ($header["TIPO_DOC"] != "NOTA DE PEDIDO") {
      $pdf->Line(10, $pdf->GetY(), 90, $pdf->GetY());
      $pdf->Cell(0, $lineHeight, "SON: " . $header["TOTAL_LITERAL"] . " SOLES");
      $pdf->Ln();
      $pdf->Line(10, $pdf->GetY(), 90, $pdf->GetY());

      $pdf->Ln();
      $pdf->Cell(0, $lineHeight, "REPRESENTACION IMPRESA DEL COMPROBANTE DE VENTA ELECTRONICA");
      $pdf->Ln();
      $pdf->Cell(0, $lineHeight, "SLOGAN...");
    }
  }

  function darFormatoMoneda($monto)
  {
    return number_format($monto, 2, '.', ',');
  }
}

?>