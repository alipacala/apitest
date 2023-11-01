<?php
class Reserva
{
  public ?int $id_reserva;
  public ?int $id_unidad_de_negocio;
  public ?string $nro_reserva;
  public ?string $nro_registro_maestro;
  public ?string $nombre;
  public ?string $lugar_procedencia;
  public ?int $id_modalidad;
  public ?string $fecha_llegada;
  public ?string $hora_llegada;
  public ?string $fecha_salida;
  public ?string $tipo_transporte;
  public ?string $telefono;
  public ?string $observaciones_hospedaje;
  public ?string $observaciones_pago;
  public ?int $nro_personas;
  public ?int $nro_adultos;
  public ?int $nro_ninos;
  public ?int $nro_infantes;
  public ?float $monto_total;
  public ?float $adelanto;
  public ?int $porcentaje_pago;
  public ?string $fecha_pago;
  public ?string $forma_pago;
  public ?int $estado_pago;
}
?>