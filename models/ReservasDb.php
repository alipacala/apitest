<?php
require_once PROJECT_ROOT_PATH . "/entities/Reserva.php";

class ReservasDb extends Database
{
  public $class = Reserva::class;
  public $idName = "id_reserva";
  public $tableName = "reservas";

  public function obtenerReserva($id)
  {
    $query = $this->prepareQuery("select-one");
    $params = $this->prepareParams(null, "select-one", $id);

    return $this->executeQuery($query, $params, "select-one");
  }

  public function buscarPorNroReserva($nroReserva)
  {
    $query = "SELECT * FROM $this->tableName WHERE nro_reserva = :nro_reserva";
    $params = array(["nombre" => "nro_reserva", "valor" => $nroReserva, "tipo" => PDO::PARAM_STR]);

    return $this->executeQuery($query, $params, "select-one");
  }
  
  public function listarReservas()
  {
    $query = $this->prepareQuery("select");

    return $this->executeQuery($query, null, "select");
  }

  public function listarConCantidadHabitaciones()
  {
    $query = "SELECT
        r.id_reserva,
        r.nro_reserva,
        r.fecha_llegada,
        r.fecha_salida,
        rh.nro_noches,
        r.nombre,
        r.nro_personas,
        r.lugar_procedencia,
        r.estado_pago,
        r.nro_registro_maestro,
        r.observaciones_hospedaje,
        r.observaciones_pago,
         COUNT(rh.nro_habitacion) AS cantidad_habitaciones,
        GROUP_CONCAT(rh.nro_habitacion ORDER BY rh.nro_habitacion) AS nro_habitacion
      FROM $this->tableName r
      INNER JOIN reservahabitaciones rh ON r.nro_reserva = rh.nro_reserva
      WHERE r.nro_reserva = rh.nro_reserva
      GROUP BY r.id_reserva, r.nro_reserva, r.fecha_llegada, r.fecha_salida, rh.nro_noches, r.nombre, r.nro_personas, r.lugar_procedencia, r.estado_pago, r.nro_registro_maestro, r.observaciones_hospedaje, r.observaciones_pago";

    return $this->executeQuery($query);
  }

  public function buscarConPrecioPorNroReserva($nroReserva)
  {
    $query = "SELECT  r.nombre, r.lugar_procedencia, r.id_modalidad, r.id_unidad_de_negocio, r.nro_registro_maestro, r.nro_reserva, rh.precio_unitario, r.nro_personas, r.fecha_llegada, r.hora_llegada, r.fecha_salida FROM reservas r
    INNER JOIN reservahabitaciones rh ON rh.nro_reserva = r.nro_reserva
    WHERE r.nro_reserva = :nro_reserva";
    $params = array(["nombre" => "nro_reserva", "valor" => $nroReserva, "tipo" => PDO::PARAM_STR]);

    return $this->executeQuery($query, $params);
  }

  public function crearReserva(Reserva $reserva)
  {
    $reservaArray = $this->prepareData((array) $reserva, "insert");
    $query = $this->prepareQuery("insert", $reservaArray);
    $params = $this->prepareParams($reservaArray);

    return $this->executeQuery($query, $params, "insert");
  }

  public function actualizarReserva($id, Reserva $reserva)
  {
    $reservaArray = $this->prepareData((array) $reserva);
    $query = $this->prepareQuery("update", $reservaArray);
    $params = $this->prepareParams($reservaArray, "update", $id);

    return $this->executeQuery($query, $params, "update");
  }

  public function asignarNroRegistroMaestroPorNroReserva($nroReserva, $nroRegistroMaestro)
  {
    $query = "UPDATE $this->tableName SET nro_registro_maestro = :nro_registro_maestro, estado_pago = 1 WHERE nro_reserva = :nro_reserva";
    $params = array(
      ["nombre" => "nro_registro_maestro", "valor" => $nroRegistroMaestro, "tipo" => PDO::PARAM_STR],
      ["nombre" => "nro_reserva", "valor" => $nroReserva, "tipo" => PDO::PARAM_STR]
    );

    return $this->executeQuery($query, $params, "update");
  }

  public function actualizarEstado($idReserva, $fechaPago) {
    $query = "UPDATE $this->tableName SET estado_pago = 1, fecha_pago = :fecha_pago WHERE id_reserva = :id_reserva";
    $params = array(
      ["nombre" => "fecha_pago", "valor" => $fechaPago, "tipo" => PDO::PARAM_STR],
      ["nombre" => "id_reserva", "valor" => $idReserva, "tipo" => PDO::PARAM_INT]
    );

    return $this->executeQuery($query, $params, "update");
  }

  public function eliminarReserva($id)
  {
    $query = $this->prepareQuery("delete");
    $params = $this->prepareParams(null, "delete", $id);

    return $this->executeQuery($query, $params, "delete");
  }
}
?>