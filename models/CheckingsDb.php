<?php
require_once PROJECT_ROOT_PATH . "/entities/Checking.php";

class CheckingsDb extends Database
{
  public $class = Checking::class;
  public $idName = "id_checkin";
  public $tableName = "cheking";

  public function obtenerChecking($id)
  {
    $query = $this->prepareQuery("select-one");
    $params = $this->prepareParams(null, "select-one", $id);

    return $this->executeQuery($query, $params, "select-one");
  }

  public function listarCheckings()
  {
    $query = $this->prepareQuery("select");

    return $this->executeQuery($query, null, "select");
  }

  public function buscarPorNroRegistroMaestro($nroRegistroMaestro)
  {
    $query = "SELECT * FROM $this->tableName WHERE nro_registro_maestro = :nro_registro_maestro";
    $params = array(["nombre" => "nro_registro_maestro", "valor" => $nroRegistroMaestro, "tipo" => PDO::PARAM_STR]);

    return $this->executeQuery($query, $params, "select-one");
  }

  public function listarCerrados()
  {
    $query = "SELECT * FROM $this->tableName WHERE cerrada = 1";

    return $this->executeQuery($query, null, "select");
  }

  public function listarAbiertos()
  {
    $query = "SELECT * FROM $this->tableName WHERE cerrada IS NULL OR cerrada = 0";

    return $this->executeQuery($query, null, "select");
  }

  public function buscarPorNroRegistroMaestroYNroHabitacion($nroRegistroMaestro, $nroHabitacion)
  {
    $query = "SELECT ch.tipo_documento,
            ch.nro_documento,
            p.apellidos,
            p.nombres,
            p.lugar_de_nacimiento,
            p.fecha,
            p.edad,
            p.sexo,
            p.ocupacion,
            p.direccion,
            ch.lugar_procedencia,
            ch.telefono,
            p.email,
            ch.estacionamiento,
            ch.nro_placa,
            ro.nro_habitacion,
            pr.nombre_producto,
            ro.tarifa,
            ch.fecha_in,
            ch.fecha_out,
            ch.hora_in,
            ch.hora_out,
            ch.forma_pago,
            ch.tipo_comprobante,
            ch.tipo_documento_comprobante,
            ch.nro_documento_comprobante,
            ch.razon_social,
            ch.nro_ninos,
            ch.nro_adultos,
            ch.nro_infantes,
            ch.nro_personas,
            ch.direccion_comprobante,
            CASE
                WHEN pr.precio_venta_01 = ro.tarifa THEN 'Precio Normal'
                WHEN pr.precio_venta_02 = ro.tarifa THEN 'Precio Corporativo'
                WHEN pr.precio_venta_03 = ro.tarifa THEN 'Precio Cliente Premium'
                ELSE 'Precio Booking'
            END AS tipo_precio
            FROM cheking ch
            INNER JOIN personanaturaljuridica p ON ch.id_persona = p.id_persona
            LEFT JOIN rooming ro ON ro.nro_registro_maestro = ch.nro_registro_maestro
            LEFT JOIN productos pr ON ro.id_producto = pr.id_producto
            WHERE ch.nro_registro_maestro = :nro_registro_maestro AND ro.nro_habitacion = :nro_habitacion
            GROUP BY ch.nro_registro_maestro";

    $params = array(
      ["nombre" => "nro_registro_maestro", "valor" => $nroRegistroMaestro, "tipo" => PDO::PARAM_STR],
      ["nombre" => "nro_habitacion", "valor" => $nroHabitacion, "tipo" => PDO::PARAM_STR]
    );

    return $this->executeQuery($query, $params);

  }

  public function deshacerCerradoChecking($nroRegistroMaestro)
  {
    $query = "UPDATE $this->tableName
      SET cerrada = NULL, fecha_cerrada = NULL, hora_cerrada = NULL
      WHERE nro_registro_maestro = :nro_registro_maestro";
    $params = array(["nombre" => "nro_registro_maestro", "valor" => $nroRegistroMaestro, "tipo" => PDO::PARAM_STR]);

    return $this->executeQuery($query, $params, "update");
  }

  public function crearChecking(Checking $checking)
  {
    $checkingArray = $this->prepareData((array) $checking, "insert");
    $query = $this->prepareQuery("insert", $checkingArray);
    $params = $this->prepareParams($checkingArray);

    return $this->executeQuery($query, $params, "insert");
  }

  public function actualizarChecking($id, Checking $checking)
  {
    $checkingArray = $this->prepareData((array) $checking);
    $query = $this->prepareQuery("update", $checkingArray);
    $params = $this->prepareParams($checkingArray, "update", $id);

    return $this->executeQuery($query, $params, "update");
  }

  public function eliminarChecking($id)
  {
    $query = $this->prepareQuery("delete");
    $params = $this->prepareParams(null, "delete", $id);

    return $this->executeQuery($query, $params, "delete");
  }
}
?>