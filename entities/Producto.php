<?php
class Producto
{
	public ?int $id_producto;
  public ?string $nombre_producto;
  public ?string $descripcion_del_producto;
  public ?string $codigo;
  public ?string $tipo;
  public ?string $tipo_de_unidad;
  public ?int $id_grupo;
  public ?int $id_central_de_costos;
  public ?int $id_tipo_de_producto;
  public ?float $cantidad_de_fracciones;
  public ?string $tipo_de_unidad_de_fracciones;
  public ?string $fecha_de_vigencia;
  public ?int $stock_min_temporada_baja;
  public ?int $stock_max_temporada_baja;
  public ?int $stock_min_temporada_alta;
  public ?int $stock_max_temporada_alta;
  public ?float $cantidad_pedido;
  public ?int $requiere_programacion;
  public ?string $tiempo_estimado;
  public ?string $codigo_habilidad;
  public ?string $tipo_comision;
  public ?float $costo_unitario;
  public ?float $costo_mano_de_obra;
  public ?float $costo_adicional;
  public ?float $porcentaje_margen;
  public ?float $precio_venta_01;
  public ?float $precio_venta_02;
  public ?float $precio_venta_03;
  public ?string $preparacion;
  public ?int $id_impresora;
  public ?int $activo;
}
?>