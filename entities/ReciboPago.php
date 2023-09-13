<?php
class ReciboPago
{
	public ?int $Id_recibo_pago;
  public ?int $id_comprobante_ventas;
  public ?int $id_unidad_de_negocio;
  public ?string $tipo_movimiento;
  public ?string $nro_recibo;
  public ?int $nro_de_caja;
  public ?string $medio_pago;
  public ?string $nro_voucher;
  public ?string $moneda;
  public ?string $fecha;
  public ?float $total;
  public ?string $nro_cierre_turno;
  public ?int $id_usuario;
  public ?string $fecha_hora_registro;
}
?>