<?php
class ComprobanteDetalle
{
  public ?int $id_comprobante_detalle;
  public ?int $id_documentos_detalle;
  public ?string $tipo_movimiento;
  public ?string $nro_registro_maestro;
  public ?int $id_comprobante_ventas;
  public ?int $id_producto;
  public ?string $descripcion;
  public ?float $cantidad;
  public ?string $tipo_de_unidad;
  public ?float $precio_unitario;
  public ?float $precio_total;
  public ?int $id_usuario;
  public ?string $fecha_hora_registro;
}
?>