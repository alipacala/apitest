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
  public ?float $por_pagar;
  public ?string $nro_orden_pedido;
  public ?int $id_usuario_responsable ;
  public ?int $id_tipo_de_gasto ;
  public ?float $gran_total;
  public ?float $afecto_percepcion;
  public ?float $porcentaje_percepcion;
  public ?float $valor_percepcion;
  public ?int $estado;
  public ?int $id_usuario;
  public ?string $fecha_hora_registro;
}
?>