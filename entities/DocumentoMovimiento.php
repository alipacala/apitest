<?php
class DocumentoMovimiento
{
  public ?int $id_documento_movimiento;
  public ?int $id_unidad_de_negocio;
  public ?string $tipo_movimiento;
  public ?string $tipo_documento;
  public ?string $nro_documento;
  public ?string $nro_registro_maestro;
  public ?string $fecha_movimiento;
  public ?string $fecha_documento;
  public ?string $hora_movimiento;
  public ?string $nro_de_comanda;
  public ?float $total;
  public ?int $id_usuario;
  public ?string $fecha_hora_registro;
}
?>