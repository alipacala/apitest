<?php
require_once PROJECT_ROOT_PATH . "/entities/Habitacion.php";

class HabitacionesDb extends Database
{
  public $class = Habitacion::class;
  public $idName = "id_habitacion";
  public $tableName = "habitaciones";

  public function obtenerHabitacion($id)
  {
    $query = $this->prepareQuery("select-one");
    $params = $this->prepareParams(null, "select-one", $id);

    return $this->executeQuery($query, $params, "select-one");
  }

  public function listarHabitaciones()
  {
    $query = $this->prepareQuery("select");

    return $this->executeQuery($query, null, "select");
  }

  public function listarDeHotelArenasSpa()
  {
    $query = "SELECT h.id_habitacion, h.nro_habitacion
    FROM $this->tableName h WHERE h.id_unidad_de_negocio = 3";

    return $this->executeQuery($query);
  }

  public function crearHabitacion(Habitacion $habitacion)
  {
    $habitacionArray = $this->prepareData((array) $habitacion, "insert");
    $query = $this->prepareQuery("insert", $habitacionArray);
    $params = $this->prepareParams($habitacionArray);

    return $this->executeQuery($query, $params, "insert");
  }

  public function actualizarHabitacion($id, Habitacion $habitacion)
  {
    $habitacionArray = $this->prepareData((array) $habitacion);
    $query = $this->prepareQuery("update", $habitacionArray);
    $params = $this->prepareParams($habitacionArray, "update", $id);

    return $this->executeQuery($query, $params, "update");
  }

  public function eliminarHabitacion($id)
  {
    $query = $this->prepareQuery("delete");
    $params = $this->prepareParams(null, "delete", $id);

    return $this->executeQuery($query, $params, "delete");
  }
}
?>