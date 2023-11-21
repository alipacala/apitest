<?php
require_once PROJECT_ROOT_PATH . "/entities/ReservaHabitacion.php";

class ReservasHabitacionesDb extends Database
{
  public $class = ReservaHabitacion::class;
  public $idName = "id_reserva_habitaciones";
  public $tableName = "reservahabitaciones";

  public function obtenerReservaHabitacion($id)
  {
    $query = $this->prepareQuery("select-one");
    $params = $this->prepareParams(null, "select-one", $id);

    return $this->executeQuery($query, $params, "select-one");
  }

  public function buscarPorNroReserva($nroReserva)
  {
    $query = "SELECT * FROM $this->tableName WHERE nro_reserva = :nro_reserva";
    $params = array(["nombre" => "nro_reserva", "valor" => $nroReserva, "tipo" => PDO::PARAM_STR]);

    return $this->executeQuery($query, $params, "select");
  }

  public function buscarPorNroHabitacion($nroHabitacion)
  {
    $query = "SELECT * FROM $this->tableName WHERE nro_habitacion = :nro_habitacion AND id_producto IS NOT NULL ORDER BY fecha_ingreso DESC LIMIT 1";
    $params = array(["nombre" => "nro_habitacion", "valor" => $nroHabitacion, "tipo" => PDO::PARAM_STR]);

    return $this->executeQuery($query, $params, "select-one");
  }

  public function listarReservasHabitaciones()
  {
    $query = $this->prepareQuery("select");

    return $this->executeQuery($query, null, "select");
  }

  public function buscarReservaConHabitacionesPorNroHabitacion($nroReserva)
  {
    $query = "SELECT rh.nro_personas, rh.nro_habitacion, h.id_producto, rh.precio_unitario, r.fecha_llegada, r.fecha_salida, r.hora_llegada FROM reservahabitaciones rh
    INNER JOIN reservas r ON r.nro_reserva = rh.nro_reserva
    INNER JOIN habitaciones h ON rh.nro_habitacion = h.nro_habitacion
    WHERE r.nro_reserva = :nro_reserva";
    $params = array(["nombre" => "nro_reserva", "valor" => $nroReserva, "tipo" => PDO::PARAM_STR]);

    return $this->executeQuery($query, $params);
  }

  public function crearReservaHabitacion(ReservaHabitacion $reservaHabitacion)
  {
    $reservaHabitacionArray = $this->prepareData((array) $reservaHabitacion, "insert");
    $query = $this->prepareQuery("insert", $reservaHabitacionArray);
    $params = $this->prepareParams($reservaHabitacionArray);

    return $this->executeQuery($query, $params, "insert");
  }

  public function actualizarReservaHabitacion($id, ReservaHabitacion $reservaHabitacion)
  {
    $reservaHabitacionArray = $this->prepareData((array) $reservaHabitacion);
    $query = $this->prepareQuery("update", $reservaHabitacionArray);
    $params = $this->prepareParams($reservaHabitacionArray, "update", $id);

    return $this->executeQuery($query, $params, "update");
  }

  public function actualizarFechasPorReserva($nroReserva, $fechaIngreso, $fechaSalida)
  {
    $query = "UPDATE $this->tableName SET fecha_ingreso = :fecha_ingreso, fecha_salida = :fecha_salida WHERE nro_reserva = :nro_reserva";
    $params = array(
      ["nombre" => "fecha_ingreso", "valor" => $fechaIngreso, "tipo" => PDO::PARAM_STR],
      ["nombre" => "fecha_salida", "valor" => $fechaSalida, "tipo" => PDO::PARAM_STR],
      ["nombre" => "nro_reserva", "valor" => $nroReserva, "tipo" => PDO::PARAM_STR]
    );

    return $this->executeQuery($query, $params, "update");
  }

  public function eliminarReservaHabitacion($id)
  {
    $query = $this->prepareQuery("delete");
    $params = $this->prepareParams(null, "delete", $id);

    return $this->executeQuery($query, $params, "delete");
  }
}
?>