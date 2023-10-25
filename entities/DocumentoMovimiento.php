<?php
class DocumentoMovimiento
{
  public ?int $id_documento_movimiento;
  public ?int $id_unidad_de_negocio;
  public ?int $id_unidad_de_negocio_secundaria;
  public ?int $id_personajuridica;
  public ?string $tipo_movimiento;
  public ?string $tipo_documento;
  public ?string $nro_documento;
  public ?string $nro_registro_maestro;
  public ?string $fecha_movimiento;
  public ?string $fecha_documento;
  public ?string $hora_movimiento;
  public ?string $nro_de_comanda;
  public ?string $nro_orden_compra;
  public ?string $fecha_recepcion;
  public ?string $motivo;
  public ?string $observaciones;
  public ?float $total;
  public ?int $id_usuario;
  public ?string $fecha_hora_registro;
}
?>