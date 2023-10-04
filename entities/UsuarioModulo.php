<?php
class UsuarioModulo
{
  public ?int $id_usuario_modulo;
  public ?int $id_usuario;
  public ?int $id_modulo;
  public ?int $tiene_acceso;
  public ?int $acceso_consulta;
  public ?int $acceso_modificacion;
  public ?int $acceso_creacion;
  public ?string $apertura_fecha_hora;
  public ?string $cese_fecha_hora;
}
?>