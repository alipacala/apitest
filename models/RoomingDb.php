<?php
require_once PROJECT_ROOT_PATH . "/entities/Rooming.php";

class RoomingDb extends Database
{
  public $class = Rooming::class;
  public $idName = "id_rooming";
  public $tableName = "rooming";

  public function obtenerRooming($id)
  {
    $query = $this->prepareQuery("select-one");
    $params = $this->prepareParams(null, "select-one", $id);

    return $this->executeQuery($query, $params, "select-one");
  }

  public function listarRooming()
  {
    $query = $this->prepareQuery("select");

    return $this->executeQuery($query, null, "select");
  }

  public function buscarUnoPorNroRegistroMaestro($nroRegistroMaestro)
  {
    $query = "SELECT * FROM $this->tableName WHERE nro_registro_maestro = :nro_registro_maestro ORDER BY id_checkin DESC LIMIT 1";
    $params = array(["nombre" => "nro_registro_maestro", "valor" => $nroRegistroMaestro, "tipo" => PDO::PARAM_STR]);

    return $this->executeQuery($query, $params, "select-one");
  }

  public function buscarVariosPorNroRegistroMaestro($nroRegistroMaestro) {
    $query = "SELECT * FROM $this->tableName WHERE nro_registro_maestro = :nro_registro_maestro";
    $params = array(["nombre" => "nro_registro_maestro", "valor" => $nroRegistroMaestro, "tipo" => PDO::PARAM_STR]);

    return $this->executeQuery($query, $params, "select");
  }

  public function buscarPorNroRegistroMaestroConFechaINOUT($nroRegistroMaestro)
  {
    $query = "SELECT
    fechas_minmax.nro_habitacion,
    MIN(fechas_minmax.fecha_in) AS fecha_in,
    fechas_minmax.hora AS hora_in,
    MAX(DATE_ADD(fechas_minmax.fecha_out, INTERVAL 1 DAY)) AS fecha_out,
    fechas_minmax.hora AS hora_out,
    r.tarifa
    FROM rooming r
    INNER JOIN (
          SELECT ro.nro_registro_maestro, ro.nro_habitacion, MIN(ro.fecha) AS fecha_in, MAX(ro.fecha) AS fecha_out, ro.hora AS hora
          FROM rooming ro
          INNER JOIN cheking ch ON ch.id_checkin = ro.id_checkin
          GROUP BY nro_registro_maestro, nro_habitacion
      ) AS fechas_minmax ON fechas_minmax.nro_registro_maestro = r.nro_registro_maestro AND fechas_minmax.nro_habitacion = r.nro_habitacion
    WHERE r.nro_registro_maestro = :nro_registro_maestro 
    GROUP BY nro_habitacion ORDER BY nro_habitacion";
    $params = array(["nombre" => "nro_registro_maestro", "valor" => $nroRegistroMaestro, "tipo" => PDO::PARAM_STR]);

    return $this->executeQuery($query, $params);
  }


  public function buscarPorIdCheckin($idCheckin)
  {
    $query = "SELECT nro_habitacion FROM $this->tableName WHERE id_checkin = :id_checkin GROUP BY nro_habitacion";
    $params = array(["nombre" => "id_checkin", "valor" => $idCheckin, "tipo" => PDO::PARAM_INT]);

    return $this->executeQuery($query, $params, "select-one");
  }

  public function buscarVariosPorIdChecking($idChecking, $nroHabitacion)
  {
    $query = "SELECT * FROM $this->tableName WHERE id_checkin = :id_checkin AND nro_habitacion = :nro_habitacion";
    $params = array(
      ["nombre" => "id_checkin", "valor" => $idChecking, "tipo" => PDO::PARAM_INT]
      ,
      ["nombre" => "nro_habitacion", "valor" => $nroHabitacion, "tipo" => PDO::PARAM_STR]
    );

    return $this->executeQuery($query, $params, "select");
  }

  public function listarRoomingConDatos($fecha)
  {
    $query = "SELECT
      COALESCE(p.nombre_producto, '') AS nombre_producto,
      COALESCE(p.descripcion_del_producto, '') AS abreviatura_producto,
      h.nro_habitacion AS nro_habitacion,
      '' AS id_checkin,
      '' AS nro_registro_maestro,
      COALESCE(re.nro_reserva, '') AS nro_reserva,
      COALESCE(re.nombre, '') AS nombre,
      COALESCE(rh.nro_personas, '') AS nro_personas,
      COALESCE(rh.fecha_ingreso, '') AS fecha_in,
      COALESCE(re.estado_pago, '') AS estado_pago,
      '' AS estado,
      '' AS estado_cheking,
      COALESCE(rh.fecha_salida, '') AS fecha_out,
  
      CASE WHEN re.nro_registro_maestro IS NOT NULL THEN 1 ELSE 0 END AS ocupado,
      CASE WHEN re.nro_reserva IS NOT NULL THEN 1 ELSE 0 END AS reservado,
      CASE WHEN re.nro_registro_maestro IS NULL AND re.nro_reserva IS NULL THEN 1 ELSE 0 END AS libre,
      CASE WHEN re.nro_registro_maestro IS NOT NULL AND re.nro_reserva IS NOT NULL THEN 1 ELSE 0 END AS reservado_pero_ocupado,
      CASE WHEN rh.fecha_salida = :fecha1 THEN 1 ELSE 0 END AS de_salida
  
      FROM habitaciones h
  
      LEFT JOIN reservahabitaciones rh ON rh.nro_habitacion = h.nro_habitacion AND rh.fecha_ingreso <= :fecha2 AND rh.fecha_salida >= :fecha3
      LEFT JOIN reservas re ON re.nro_reserva = rh.nro_reserva
      LEFT JOIN productos p ON p.id_producto = h.id_producto
  
    WHERE h.id_unidad_de_negocio = 3
      
      /*-----------------------------------------------------------------------------------------------------------------------------------------*/
      UNION ALL
      /*-----------------------------------------------------------------------------------------------------------------------------------------*/
  
    SELECT
      COALESCE(p.nombre_producto, '') AS nombre_producto,
      COALESCE(p.descripcion_del_producto, '') AS abreviatura_producto,
      h.nro_habitacion AS nro_habitacion,
      COALESCE(ch.id_checkin, '') AS id_checkin,
      COALESCE(ch.nro_registro_maestro, '') AS nro_registro_maestro,
      COALESCE(ch.nro_reserva, '') AS nro_reserva,
      COALESCE(ch.nombre, '') AS nombre,
      COALESCE(ch.nro_personas, '') AS nro_personas,
      COALESCE(fechas_minmax.fecha_in, '') AS fecha_in,
      '' AS estado_pago,
      COALESCE(r.estado, '') AS estado,
      CASE WHEN ch.estado_cheking = 0 THEN 'S/DATOS'
        WHEN ch.estado_cheking = 1 THEN 'ESCANEO'
        WHEN ch.estado_cheking = 2 THEN 'EN PROCESO'
        WHEN ch.estado_cheking = 3 THEN 'GUARDADO'
        WHEN ch.estado_cheking = 4 THEN 'VISTO BUENO TERMINADO'
        ELSE ''
        END AS estado_cheking,
      COALESCE(CASE
        WHEN r.cambiado IS NULL THEN DATE_ADD(fechas_minmax.fecha_out, INTERVAL 1 DAY)
          ELSE fechas_minmax.fecha_out
        END, '') AS fecha_out,
  
      CASE WHEN ch.id_checkin IS NOT NULL THEN 1 ELSE 0 END AS ocupado,
      0 AS reservado,
      CASE WHEN ch.id_checkin IS NULL THEN 1 ELSE 0 END AS libre,
      0 AS reservado_pero_ocupado,
      CASE WHEN DATE_ADD(fechas_minmax.fecha_out, INTERVAL 1 DAY) = :fecha4 THEN 1 ELSE 0 END AS de_salida
  
      FROM habitaciones h
      LEFT JOIN rooming r ON r.nro_habitacion = h.nro_habitacion AND (:fecha5 = r.fecha OR (
        r.cambiado IS NULL AND :fecha6 = DATE_ADD(r.fecha, INTERVAL 1 DAY)
        )
      )
      
      
      LEFT JOIN (
          SELECT ro.nro_registro_maestro, ro.nro_habitacion, MIN(ro.fecha) AS fecha_in, MAX(ro.fecha) AS fecha_out
          FROM rooming ro
          INNER JOIN cheking ch ON ch.id_checkin = ro.id_checkin
          GROUP BY nro_registro_maestro, nro_habitacion
      ) AS fechas_minmax ON fechas_minmax.nro_registro_maestro = r.nro_registro_maestro AND fechas_minmax.nro_habitacion = r.nro_habitacion
  
      LEFT JOIN cheking ch ON ch.nro_registro_maestro = fechas_minmax.nro_registro_maestro
      LEFT JOIN productos p ON p.id_producto = h.id_producto
  
      
      WHERE h.id_unidad_de_negocio = 3
  
      ORDER BY nro_habitacion ASC;";
    $params = array(
      ["nombre" => "fecha1", "valor" => $fecha, "tipo" => PDO::PARAM_STR],
      ["nombre" => "fecha2", "valor" => $fecha, "tipo" => PDO::PARAM_STR],
      ["nombre" => "fecha3", "valor" => $fecha, "tipo" => PDO::PARAM_STR],
      ["nombre" => "fecha4", "valor" => $fecha, "tipo" => PDO::PARAM_STR],
      ["nombre" => "fecha5", "valor" => $fecha, "tipo" => PDO::PARAM_STR],
      ["nombre" => "fecha6", "valor" => $fecha, "tipo" => PDO::PARAM_STR]
    );

    return $this->executeQuery($query, $params);
  }

  public function crearRooming(Rooming $rooming)
  {
    $roomingArray = $this->prepareData((array) $rooming, "insert");
    $query = $this->prepareQuery("insert", $roomingArray);
    $params = $this->prepareParams($roomingArray);

    return $this->executeQuery($query, $params, "insert");
  }

  public function actualizarRooming($id, Rooming $rooming)
  {
    $roomingArray = $this->prepareData((array) $rooming);
    $query = $this->prepareQuery("update", $roomingArray);
    $params = $this->prepareParams($roomingArray, "update", $id);

    return $this->executeQuery($query, $params, "update");
  }

  public function cambiarRoomings($nroRegistroMaestro, $prevNroHabitacion, $fecha)
  {
    $query = "UPDATE $this->tableName SET cambiado = 1 WHERE nro_registro_maestro = :nro_registro_maestro AND nro_habitacion = :prev_nro_habitacion
    AND fecha < :fecha";
    $params = array(
      ["nombre" => "nro_registro_maestro", "valor" => $nroRegistroMaestro, "tipo" => PDO::PARAM_STR],
      ["nombre" => "prev_nro_habitacion", "valor" => $prevNroHabitacion, "tipo" => PDO::PARAM_STR],
      ["nombre" => "fecha", "valor" => $fecha, "tipo" => PDO::PARAM_STR]
    );

    return $this->executeQuery($query, $params, "update");
  }

  public function cambiarNroHabitacion($nroRegistroMaestro, $prevNroHabitacion, $nroHabitacion, $fecha)
  {
    $query = "UPDATE $this->tableName SET nro_habitacion = :nro_habitacion WHERE nro_registro_maestro = :nro_registro_maestro AND nro_habitacion = :prev_nro_habitacion
    AND fecha >= :fecha";
    $params = array(
      ["nombre" => "nro_habitacion", "valor" => $nroHabitacion, "tipo" => PDO::PARAM_STR],
      ["nombre" => "nro_registro_maestro", "valor" => $nroRegistroMaestro, "tipo" => PDO::PARAM_STR],
      ["nombre" => "prev_nro_habitacion", "valor" => $prevNroHabitacion, "tipo" => PDO::PARAM_STR],
      ["nombre" => "fecha", "valor" => $fecha, "tipo" => PDO::PARAM_STR]
    );

    return $this->executeQuery($query, $params, "update");
  }

  public function checkout($idChecking, $nroHabitacion, $fechaCheckout)
  {
    $query = "UPDATE $this->tableName SET estado = 'OU' AND cambiado = 1 WHERE id_checkin = :id_checkin AND nro_habitacion = :nro_habitacion AND fecha < :fecha";
    $params = array(
      ["nombre" => "id_checkin", "valor" => $idChecking, "tipo" => PDO::PARAM_INT]
      ,
      ["nombre" => "nro_habitacion", "valor" => $nroHabitacion, "tipo" => PDO::PARAM_STR],
      ["nombre" => "fecha", "valor" => $fechaCheckout, "tipo" => PDO::PARAM_STR]
    );

    return $this->executeQuery($query, $params, "update");
  }

  public function borrarRestantesCheckout($idChecking, $nroHabitacion, $fechaCheckout)
  {
    $query = "DELETE FROM $this->tableName WHERE id_checkin = :id_checkin AND nro_habitacion = :nro_habitacion AND fecha >= :fecha";
    $params = array(
      ["nombre" => "id_checkin", "valor" => $idChecking, "tipo" => PDO::PARAM_INT]
      ,
      ["nombre" => "nro_habitacion", "valor" => $nroHabitacion, "tipo" => PDO::PARAM_STR],
      ["nombre" => "fecha", "valor" => $fechaCheckout, "tipo" => PDO::PARAM_STR]
    );

    return $this->executeQuery($query, $params, "delete");
  }

  public function actualizarIdCheckingEnRoomings($nroRegistroMaestro, $nroHabitacion, $idChecking)
  {
    $query = "UPDATE $this->tableName SET id_checkin = :id_checkin WHERE nro_registro_maestro = :nro_registro_maestro AND nro_habitacion = :nro_habitacion";
    $params = array(
      ["nombre" => "id_checkin", "valor" => $idChecking, "tipo" => PDO::PARAM_INT],
      ["nombre" => "nro_registro_maestro", "valor" => $nroRegistroMaestro, "tipo" => PDO::PARAM_STR],
      ["nombre" => "nro_habitacion", "valor" => $nroHabitacion, "tipo" => PDO::PARAM_STR],
    );

    return $this->executeQuery($query, $params, "update");
  }

  public function eliminarRooming($id)
  {
    $query = $this->prepareQuery("delete");
    $params = $this->prepareParams(null, "delete", $id);

    return $this->executeQuery($query, $params, "delete");
  }
}
?>