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

  public function listarReservasHabitaciones()
  {
    $query = $this->prepareQuery("select");

    return $this->executeQuery($query, null, "select");
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

  public function eliminarReservaHabitacion($id)
  {
    $query = $this->prepareQuery("delete");
    $params = $this->prepareParams(null, "delete", $id);

    return $this->executeQuery($query, $params, "delete");
  }
}
?>