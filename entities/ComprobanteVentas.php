<?php
class ComprobanteVentas
{
  public ?int $id_comprobante_ventas;
  public ?int $id_unidad_de_negocio;
  public ?string $tipo_movimiento;
  public ?string $tipo_comprobante;
  public ?string $nro_comprobante;
  public ?string $nro_registro_maestro;
  public ?string $tipo_documento_cliente;
  public ?string $nro_documento_cliente;
  public ?string $direccion_cliente;
  public ?string $fecha_documento;
  public ?string $hora_documento;
  public ?float $subtotal;
  public ?float $igv;
  public ?float $porcentaje_igv;
  public ?float $total;
  public ?string $forma_de_pago;
  public ?float $monto_inicial;
  public ?string $fecha_de_pago_credito;
  public ?float $monto_credito;
  public ?int $id_usuario;
  public ?string $fecha_hora_registro;
}
?>