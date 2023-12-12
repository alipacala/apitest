<?php
require_once PROJECT_ROOT_PATH."/fpdf/fpdf.php";

class ReporteFichaChecking {
  private $pdf = null;
  private $lineHeight = 5;

  public function __construct() {
    $this->pdf = new FPDF('P', 'mm', [105, 148]);
  }

  function generarReporte($checking = null, $persona = null, $roomings = null, $acompanantes = null) {

    $this->imprimirDatosPersonales($checking, $persona);
    $this->imprimirDatosHabitaciones($roomings, $checking);
    $this->imprimirDatosAcompanantes();
    $this->imprimirDatosComprobante();

    $this->pdf->Output();
  }

  function imprimirDatosPersonales($checking = null, $persona = null) {
    $this->pdf->AddPage();
    $this->pdf->Image(PROJECT_ROOT_PATH.DIRECTORY_SEPARATOR."assets".DIRECTORY_SEPARATOR."img".DIRECTORY_SEPARATOR."logo.png", null, null, 15, 0, "PNG");

    $this->pdf->SetFont('Arial', 'B', 10);

    $this->pdf->Cell(null, $this->lineHeight, "REGISTRO DE HUESPEDES", 0, 0, "C");
    $this->pdf->Ln();
    $this->pdf->Cell(null, $this->lineHeight, "DATOS PERSONALES - TITULAR DE HABITACION", 0, 0, "C");
    $this->pdf->Ln();
    $this->pdf->Ln();

    $tiposDoc = [
      "0" => "Sin Documento",
      "1" => "DNI",
      "6" => "RUC",
      "7" => "Pasaporte"
    ];

    $this->pdf->SetFont('Arial', null, 8.5);

    $this->pdf->Cell(22, $this->lineHeight, "APELLIDOS:");
    $this->pdf->Cell(null, $this->lineHeight, $this->aUTF8($persona->apellidos));
    $this->pdf->Ln();
    $this->pdf->Cell(22, $this->lineHeight, "NOMBRES:");
    $this->pdf->Cell(null, $this->lineHeight, $this->aUTF8($persona->nombres));
    $this->pdf->Ln();
    $this->pdf->Cell(22, $this->lineHeight, "TIPO DOC:");
    $this->pdf->Cell(34, $this->lineHeight, $tiposDoc[$persona->tipo_documento]);
    $this->pdf->Cell(6, $this->lineHeight, $this->aUTF8("N°:"));
    $this->pdf->Cell(24, $this->lineHeight, $persona->nro_documento);
    $this->pdf->Ln();

    $this->pdf->Cell(26, $this->lineHeight, "NACI. LUGAR:");
    $this->pdf->Cell(30, $this->lineHeight, $this->aUTF8($persona->lugar_de_nacimiento));
    $this->pdf->Cell(14, $this->lineHeight, "FECHA:");
    // darle formato a la fecha
    $this->pdf->Cell(30, $this->lineHeight, date_format(date_create($persona->fecha), "d/m/Y"));
    $this->pdf->Ln();

    $this->pdf->Cell(29, $this->lineHeight, "NACIONALIDAD:");
    $this->pdf->Cell(27, $this->lineHeight, $this->aUTF8($persona->nacionalidad));
    $this->pdf->Cell(14, $this->lineHeight, "EDAD:");
    $this->pdf->Cell(30, $this->lineHeight, $persona->edad);
    $this->pdf->Ln();

    $this->pdf->Cell(46, $this->lineHeight, "PROFESION/OCUPACION:");
    $this->pdf->Cell(null, $this->lineHeight, $this->aUTF8($persona->ocupacion));
    $this->pdf->Ln();

    $this->pdf->Cell(22, $this->lineHeight, "DIRECCION:");
    $this->pdf->Cell(null, $this->lineHeight, $this->aUTF8($persona->direccion));
    $this->pdf->Ln();

    $this->pdf->Cell(22, $this->lineHeight, "CIUDAD:");
    $this->pdf->Cell(34, $this->lineHeight, $this->aUTF8($persona->ciudad));
    $this->pdf->Cell(14, $this->lineHeight, "PAIS:");
    $this->pdf->Cell(30, $this->lineHeight, $this->aUTF8($persona->pais));
    $this->pdf->Ln();

    $this->pdf->Cell(22, $this->lineHeight, "CELULAR:");
    $this->pdf->Cell(null, $this->lineHeight, $persona->celular);
    $this->pdf->Ln();

    $this->pdf->Cell(14, $this->lineHeight, "E-MAIL:");
    $this->pdf->Cell(null, $this->lineHeight, $persona->email);
    $this->pdf->Ln();

    $this->pdf->Cell(56, $this->lineHeight, "REQUIERE ESTACIONAMIENTO? ".($checking->estacionamiento == 1 ? "SI" : "NO"));
    $this->pdf->Cell(44, $this->lineHeight, $this->aUTF8("N° PLACA: ").$checking->nro_placa);
    $this->pdf->Ln();
  }

  function imprimirDatosHabitaciones($roomings = null, $checking = null) {

    // imprimir cabecera

    $this->pdf->Cell(14, $this->lineHeight, "HAB No.", 1, 0, "C");
    $this->pdf->Cell(28, $this->lineHeight, "Fecha/Hora IN", 1, 0, "C");
    $this->pdf->Cell(28, $this->lineHeight, "Fecha/Hora OUT", 1, 0, "C");
    $this->pdf->Cell(14, $this->lineHeight, "Valor", 1, 0, "C");
    $this->pdf->Ln();

    // imprimir datos

    foreach($roomings as $rooming) {
      $this->pdf->Cell(14, $this->lineHeight, $rooming['nro_habitacion'], 1, 0, "C");
      $this->pdf->Cell(28, $this->lineHeight, date_format(date_create($rooming['fecha_in'].' '.$rooming['hora_in']), "d/m/Y H:i"
      ), 1, 0, "C");
      $this->pdf->Cell(28, $this->lineHeight, date_format(date_create($rooming['fecha_out'].' '.$rooming['hora_out']), "d/m/Y H:i"
      ), 1, 0, "C");
      $this->pdf->Cell(14, $this->lineHeight, $rooming['tarifa'], 1, 0, "C");
      $this->pdf->Ln();
    }
    $this->pdf->Ln();

    // imprimir observaciones
    $this->pdf->MultiCell(null, 4, "OBSERVACIONES: " . $checking->observaciones_hospedaje, 1);

  }

  function imprimirDatosAcompanantes() {
    $this->pdf->AddPage();

    $this->pdf->Cell(null, 7, $this->aUTF8("DATOS ACOMPAÑANTES"), 0, 0, "C");
    $this->pdf->Ln();
  }

  function imprimirDatosComprobante() {

  }

  function aUTF8($string) {
    return mb_convert_encoding($string, "ISO-8859-1", "UTF-8");
  }
}
?>