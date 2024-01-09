<?php
require_once PROJECT_ROOT_PATH . "/fpdf/fpdf.php";

class ComprobanteImprimible
{
  function generarReporte($result = null, $porcIGV = null)
  {
    [$result, $header] = $this->prepararDatos($result, $porcIGV);

    $pdf = new FPDF('P', 'mm', array(100, 270));
    $pdf->AddPage();
    $pdf->SetMargins(0, 0, 0);

    $lineHeight = 4;
    $tamanoLetra = 10;

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
    $header["FECHA"] = date("d/m/Y", strtotime($result[0]["fecha"]));
    $header["HORA"] = $result[0]["hora"];
    $header["NRO_COMPROBANTE"] = $result[0]["nro_comprobante"];
    $header["TIPO_DOC"] = $tiposDoc[$result[0]["tipo_doc"]];
    $header["TIPO_DOC_CLIENTE"] = $tiposDocCliente[$result[0]["tipo_documento_cliente"]];
    $header["RUC"] = $result[0]["ruc"];
    $header["NOMBRE"] = $this->aUTF8($result[0]["nombre"]);
    $header["DIRECCION"] = $this->aUTF8($result[0]["direccion"]);
    $header["LUGAR"] = $result[0]["ciudad"];
    $header["FORMA_PAGO"] = "CONTADO"; // TODO: TEMPORAL

    $header["IGV"] = $porcIGV;
    $header["MONTO_IGV"] = $result[0]["igv"];
    $header["OPE_GRAVADA"] = $result[0]["subtotal"];
    $header["OPE_NO_GRAVADA"] = $this->darFormatoMoneda(0);
    $header["TOTAL"] = $result[0]["total"];

    $soles = floor($header["TOTAL"]);
    $centimos = $header["TOTAL"] - $soles;

    $formatterES = new \NumberFormatter("es", \NumberFormatter::SPELLOUT);
    $header["TOTAL_LITERAL"] = $this->aUTF8(strtoupper($this->quitarTildes($formatterES->format($soles)))) . " Y " . str_pad(round($centimos * 100), 2, "0") . "/100 SOLES";

    $result = array_map(function ($comprobante) {
      $comprobante["nombre_producto"] = $this->aUTF8($comprobante["nombre_producto"]);
      $comprobante["cantidad"] = intval($comprobante["cantidad"]);
      $comprobante["precio_unitario"] = $this->darFormatoMoneda($comprobante["precio_unitario"]);
      $comprobante["precio_total"] = $this->darFormatoMoneda($comprobante["precio_total"]);
      return $comprobante;
    }, $result);

    return [$result, $header];
  }

  function imprimirCabecera(FPDF $pdf, $lineHeight, $tamanoLetra, $header)
  {
    $pdf->Image(PROJECT_ROOT_PATH . DIRECTORY_SEPARATOR . "assets" . DIRECTORY_SEPARATOR . "img" . DIRECTORY_SEPARATOR . "logo.png", 25, null, 50, 0, "PNG");

    $pdf->Cell(0, $lineHeight);
    $pdf->Ln();

    $pdf->Cell(0, $lineHeight, "DAFFS S.A.C.", 0, 0, "C");
    $pdf->Ln();
    $pdf->Cell(0, $lineHeight, "DOM. FISCAL: AV. Tarapaca Nro. 379", 0, 0, "C");
    $pdf->Ln();
    $pdf->Cell(0, $lineHeight, $this->aUTF8("DIRECCIÓN: CALLE LAS VILCAS B1 - URB. LOS OLIVOS"), 0, 0, "C");
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

    $pdf->Cell(40, $lineHeight, "Fecha: " . $header["FECHA"]);
    $pdf->Cell(40, $lineHeight, "Hora: " . $header["HORA"]);
    $pdf->Ln();

    if ($header["TIPO_DOC"] != "NOTA DE PEDIDO") {
      if ($header["TIPO_DOC"] == "FACTURA ELECTRONICA") {
        $pdf->Cell(0, $lineHeight, "Forma de pago: " . $header["FORMA_PAGO"]);
        $pdf->Ln();
      }

      $pdf->Ln(2);
      $pdf->Line(0, $pdf->GetY(), 100, $pdf->GetY());
      $pdf->Ln(2);

      $pdf->Cell(14, $lineHeight, $header["TIPO_DOC_CLIENTE"] . ":");
      $pdf->Cell(14, $lineHeight, $header["RUC"]);
      $pdf->Ln();
      $pdf->Cell(0, $lineHeight, "NOMBRE:");
      $pdf->Ln();
      $pdf->MultiCell(0, $lineHeight, strtoupper($header["NOMBRE"]));

      if ($header["TIPO_DOC_CLIENTE"] == "RUC") {
        $pdf->Cell(0, $lineHeight, "DIRECCION:");
        $pdf->Ln();
        $pdf->MultiCell(0, $lineHeight, strtoupper($header["DIRECCION"]));
        $pdf->MultiCell(0, $lineHeight, strtoupper($header["LUGAR"]));
      }
    }

    $pdf->Ln(2);
    $pdf->Line(0, $pdf->GetY(), 100, $pdf->GetY());
    $pdf->Ln(2);
  }

  function imprimirCabeceraTabla(FPDF $pdf, $lineHeight, $tamanoLetra)
  {
    $pdf->Cell(44, $lineHeight, "DESCRIPCION", 0, 0, "C");
    $pdf->Cell(18, $lineHeight, "CANT.", 0, 0, "C");
    $pdf->Cell(18, $lineHeight, "P.UNIT.", 0, 0, "C");
    $pdf->Cell(18, $lineHeight, "IMPORTE", 0, 0, "C");

    $pdf->Ln();
    $pdf->Ln(2);
    $pdf->Line(0, $pdf->GetY(), 100, $pdf->GetY());
    $pdf->Ln(2);
  }

  function imprimirComprobantesTabla(FPDF $pdf, $lineHeight, $tamanoLetra, $result)
  {
    foreach ($result as $comprobante) {
      $pdf->Cell(0, $lineHeight, $comprobante["nombre_producto"]);
      $pdf->Ln();
      $pdf->Cell(44, $lineHeight, "");
      $pdf->Cell(18, $lineHeight, $comprobante["cantidad"], 0, 0, "C");
      $pdf->Cell(18, $lineHeight, $comprobante["precio_unitario"], 0, 0, "R");
      $pdf->Cell(18, $lineHeight, $comprobante["precio_total"], 0, 0, "R");
      $pdf->Ln();
    }
  }

  function imprimirTotales(FPDF $pdf, $lineHeight, $tamanoLetra, $header)
  {
    $pdf->Ln(2);
    $pdf->Line(0, $pdf->GetY(), 100, $pdf->GetY());
    $pdf->Ln(2);

    if ($header["TIPO_DOC"] != "NOTA DE PEDIDO") {
      $pdf->Cell(40, $lineHeight);
      $pdf->Cell(34, $lineHeight, "OPE. GRAVADA");
      $pdf->Cell(4, $lineHeight, ": S/");
      $pdf->Cell(20, $lineHeight, $header["OPE_GRAVADA"], 0, 0, "R");
      $pdf->Ln();

      $pdf->Cell(40, $lineHeight);
      $pdf->Cell(34, $lineHeight, "OPE. NO GRAVADA");
      $pdf->Cell(4, $lineHeight, ": S/");
      $pdf->Cell(20, $lineHeight, $header["OPE_NO_GRAVADA"], 0, 0, "R");
      $pdf->Ln();

      $pdf->Cell(40, $lineHeight);
      $pdf->Cell(34, $lineHeight, "MONTO IGV " . $header["IGV"] . "%");
      $pdf->Cell(4, $lineHeight, ": S/");
      $pdf->Cell(20, $lineHeight, $header["MONTO_IGV"], 0, 0, "R");
      $pdf->Ln();
      $pdf->Ln();
    }

    $pdf->Cell(40, $lineHeight);
    $pdf->Cell(34, $lineHeight, "TOTAL");
    $pdf->Cell(4, $lineHeight, ": S/");
    $pdf->Cell(20, $lineHeight, $header["TOTAL"], 0, 0, "R");
    $pdf->Ln();

    if ($header["TIPO_DOC"] != "NOTA DE PEDIDO") {
      $pdf->Ln(2);
      $pdf->Line(0, $pdf->GetY(), 100, $pdf->GetY());
      $pdf->Ln(2);
      $pdf->MultiCell(0, $lineHeight, "SON: " . $header["TOTAL_LITERAL"]);

      $pdf->Ln(2);
      $pdf->Line(0, $pdf->GetY(), 100, $pdf->GetY());
      $pdf->Ln(2);

      $pdf->Cell(0, $lineHeight, "REPRESENTACION IMPRESA DEL COMPROBANTE");
      $pdf->Ln();
      $pdf->Cell(0, $lineHeight, "DE VENTA ELECTRONICA");
    }
  }

  function darFormatoMoneda($monto)
  {
    return number_format($monto, 2, '.', ',');
  }

  function aUTF8($string)
  {
    return mb_convert_encoding($string, "ISO-8859-1", "UTF-8");
  }

  // quita las tildes simples de las vocales
  function quitarTildes($string)
  {
    $string = str_replace(
      array('Á', 'á', 'É', 'é', 'Í', 'í', 'Ó', 'ó', 'Ú', 'ú'),
      array('A', 'a', 'E', 'e', 'I', 'i', 'O', 'o', 'U', 'u'),
      $string
    );

    return $string;
  }
}

?>