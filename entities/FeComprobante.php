<?php
class FeComprobante
{
  public ?int $IdFeC;
  public ?string $NroMov;
  public ?string $serieComprobante;
  public ?string $nroComprobante;
  public ?string $tipOperacion;
  public ?string $fecEmision;
  public ?string $fecPago;
  public ?string $codLocalEmisor;
  public ?string $TipDocUsuario;
  public ?string $numDocUsuario;
  public ?string $rznSocialUsuario;
  public ?string $tipMoneda;
  public ?float $sumDsctoGlobal;
  public ?float $sumOtrosCargos;
  public ?float $mtoDescuentos;
  public ?float $mtoOperGravadas;
  public ?float $mtoOperInafectas;
  public ?float $mtoOperExoneradas;
  public ?float $mtoIGV;
  public ?float $mtoISC;
  public ?float $mtoOtrosTributos;
  public ?float $mtoImpVenta;
  public ?int $xestado;
  public ?string $xdocnro;
  public ?string $xfecha;
  public ?string $xhora;
}
?>