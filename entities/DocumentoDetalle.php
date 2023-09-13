<?php
class DocumentoDetalle
{
  public function __construct()
  {
  }
  
  public ?int $id_documentos_detalle;
  public ?string $tipo_movimiento;
  public ?string $nro_registro_maestro;
  public ?int $id_documento_movimiento;
  public ?string $fecha;
  public ?int $id_producto;
  public ?int $nivel_descargo;
  public ?float $cantidad;
  public ?string $tipo_de_unidad;
  public ?float $precio_unitario;
  public ?float $precio_total;
  public ?int $id_acompanate;
  public ?int $id_profesional;
  public ?string $fecha_servicio;
  public ?string $hora_servicio;
  public ?string $fecha_termino;
  public ?string $hora_termino;
  public ?string $nro_comprobante;
  public ?string $observaciones;
  public ?int $id_recibo_de_pago;
  public ?int $anulado;
  public ?int $id_usuario;
  public ?string $fecha_hora_registro;
  public ?int $id_item;
}
?>