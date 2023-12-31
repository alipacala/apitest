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

  public function buscarPorEscaneo($idUnidadDeNegocio) {
    $query = "SELECT ch.*, ro.nro_habitacion AS habitacion, DATEDIFF(ch.fecha_out, ch.fecha_in) AS nro_dias FROM $this->tableName ch INNER JOIN rooming ro ON ch.nro_registro_maestro = ro.nro_registro_maestro WHERE id_unidad_de_negocio = :id_unidad_de_negocio AND estado_cheking = 1 AND ro.estado != 'OU' GROUP BY ro.nro_habitacion";
    $params = array(["nombre" => "id_unidad_de_negocio", "valor" => $idUnidadDeNegocio, "tipo" => PDO::PARAM_STR]);

    return $this->executeQuery($query, $params);
  }

  public function listarCerrados()
  {
    $query = "SELECT ch.tipo_de_servicio, ch.id_checkin, ch.nombre, ro.nro_habitacion, ch.nro_personas, ch.nro_adultos, ch.nro_ninos, ch.nro_infantes, ch.fecha_in, ch.hora_in, ch.fecha_out, ch.nro_registro_maestro, ro.nro_habitacion
    FROM cheking ch
    INNER JOIN rooming ro ON ch.nro_registro_maestro = ro.nro_registro_maestro
    WHERE ch.cerrada = 1
    GROUP BY nro_registro_maestro, nro_habitacion";

    return $this->executeQuery($query, null, "select");
  }

  public function listarAbiertos()
  {
    $query = "SELECT ch.tipo_de_servicio, ch.id_checkin, ch.nombre, ro.nro_habitacion, ch.nro_personas, ch.nro_adultos, ch.nro_ninos, ch.nro_infantes, ch.fecha_in, ch.hora_in, ch.fecha_out, ch.nro_registro_maestro, ro.nro_habitacion
    FROM cheking ch
    LEFT JOIN rooming ro ON ch.nro_registro_maestro = ro.nro_registro_maestro
    WHERE ch.cerrada IS NULL OR ch.cerrada = 0
    GROUP BY nro_registro_maestro, nro_habitacion";

    return $this->executeQuery($query);
  }

  public function buscarPorNroReserva($nroReserva)
  {
    $query = "SELECT * FROM $this->tableName WHERE nro_reserva = :nro_reserva";
    $params = array(["nombre" => "nro_reserva", "valor" => $nroReserva, "tipo" => PDO::PARAM_STR]);

    return $this->executeQuery($query, $params, "select-one");
  }

  public function buscarPorNroRegistroMaestroNroHabitacionIdChecking($nroRegistroMaestro = null, $idChecking = null, $nroHabitacion = null, $idUnidadDeNegocio = null)
  {
    $query = "SELECT ch.tipo_documento,
            ch.nro_registro_maestro,
            ch.nro_reserva,
            p.id_persona,
            p.tipo_documento,
            p.nro_documento,
            p.apellidos,
            p.nombres,
            p.lugar_de_nacimiento,
            p.fecha,
            p.edad,
            p.sexo,
            p.celular,
            p.ocupacion,
            p.direccion,
            p.email,
            p.ciudad AS lugar_procedencia,

            ch.estacionamiento,
            ch.nro_placa,
            pr.nombre_producto,
            ro.nro_habitacion,
            ro.tarifa,

            fechas_minmax.fecha_in,
            DATE_ADD(fechas_minmax.fecha_out, INTERVAL 1 DAY) AS fecha_out,
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
            LEFT JOIN personanaturaljuridica p ON ch.id_persona = p.id_persona
            LEFT JOIN rooming ro ON ro.nro_registro_maestro = ch.nro_registro_maestro
            LEFT JOIN productos pr ON ro.id_producto = pr.id_producto

            LEFT JOIN (
                SELECT nro_registro_maestro, nro_habitacion, MIN(fecha) AS fecha_in, MAX(fecha) AS fecha_out
                FROM rooming
                GROUP BY nro_registro_maestro, nro_habitacion
            ) AS fechas_minmax ON fechas_minmax.nro_registro_maestro = ro.nro_registro_maestro AND fechas_minmax.nro_habitacion = ro.nro_habitacion

            WHERE 
              (:nro_registro_maestro1 IS NULL OR ch.nro_registro_maestro = :nro_registro_maestro2)
              AND (:nro_habitacion1 IS NULL OR ro.nro_habitacion = :nro_habitacion2)
              AND (:id_checkin1 IS NULL OR ch.id_checkin = :id_checkin2)
              AND (:id_unidad_de_negocio1 IS NULL OR (ch.id_unidad_de_negocio = :id_unidad_de_negocio2 AND ch.estado_cheking = 1))
            GROUP BY ch.nro_registro_maestro";

    $params = array(
      ["nombre" => "nro_registro_maestro1", "valor" => $nroRegistroMaestro, "tipo" => PDO::PARAM_STR],
      ["nombre" => "nro_registro_maestro2", "valor" => $nroRegistroMaestro, "tipo" => PDO::PARAM_STR],
      ["nombre" => "nro_habitacion1", "valor" => $nroHabitacion, "tipo" => PDO::PARAM_STR],
      ["nombre" => "nro_habitacion2", "valor" => $nroHabitacion, "tipo" => PDO::PARAM_STR],
      ["nombre" => "id_checkin1", "valor" => $idChecking, "tipo" => PDO::PARAM_INT],
      ["nombre" => "id_checkin2", "valor" => $idChecking, "tipo" => PDO::PARAM_INT],
      ["nombre" => "id_unidad_de_negocio1", "valor" => $idUnidadDeNegocio, "tipo" => PDO::PARAM_INT],
      ["nombre" => "id_unidad_de_negocio2", "valor" => $idUnidadDeNegocio, "tipo" => PDO::PARAM_INT],
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

  public function cambiarEstadoChecking($idChecking, $estadoChecking)
  {
    $query = "UPDATE $this->tableName
      SET estado_cheking = :estado_cheking
      WHERE id_checkin = :id_checkin";
    $params = array(
      ["nombre" => "estado_cheking", "valor" => $estadoChecking, "tipo" => PDO::PARAM_INT],
      ["nombre" => "id_checkin", "valor" => $idChecking, "tipo" => PDO::PARAM_INT],
    );

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