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

  public function listarConDisponibilidad($fechaIngreso, $fechaSalida)
  {
    $query = "SELECT h.id_habitacion, h.nro_habitacion, h.id_producto
    FROM $this->tableName h
    WHERE h.id_habitacion NOT IN (
      SELECT h.id_habitacion
      FROM $this->tableName h
      INNER JOIN rooming r ON h.nro_habitacion = r.nro_habitacion
      INNER JOIN reservahabitaciones rh ON h.nro_habitacion = rh.nro_habitacion
      WHERE
        LENGTH(h.nro_habitacion) > 3 /* para que las habitaciones adicionales siempre se muestren */
        OR (
          r.fecha BETWEEN :fecha_ingreso1
            AND DATE_ADD(:fecha_salida1, INTERVAL -1 DAY)
        )
        OR (
          rh.fecha_ingreso > DATE_ADD(:fecha_salida2, INTERVAL -1 DAY)
          AND rh.fecha_salida < :fecha_ingreso2
        )
    ) AND h.id_unidad_de_negocio = 3";

    $params = array(
      ["nombre" => "fecha_ingreso1", "valor" => $fechaIngreso, "tipo" => PDO::PARAM_STR],
      ["nombre" => "fecha_salida1", "valor" => $fechaSalida, "tipo" => PDO::PARAM_STR],
      ["nombre" => "fecha_ingreso2", "valor" => $fechaIngreso, "tipo" => PDO::PARAM_STR],
      ["nombre" => "fecha_salida2", "valor" => $fechaSalida, "tipo" => PDO::PARAM_STR]
    );

    return $this->executeQuery($query, $params);
  }

  public function buscarPorNroHabitacion($nroHabitacion)
  {
    $query = "SELECT p.id_producto, p.nombre_producto, p.codigo, p.precio_venta_01, p.precio_venta_02, p.precio_venta_03 FROM $this->tableName h
    INNER JOIN productos p ON h.id_producto = p.id_producto WHERE h.nro_habitacion = :nro_habitacion";
    $params = array(["nombre" => "nro_habitacion", "valor" => $nroHabitacion, "tipo" => PDO::PARAM_STR]);

    return $this->executeQuery($query, $params, "select");
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

  public function cerrarHabitacion($prevNroHabitacion)
  {
    $query = "UPDATE $this->tableName SET cerrada = 1 WHERE nro_habitacion = :nro_habitacion";
    $params = array(
      ["nombre" => "nro_habitacion", "valor" => $prevNroHabitacion, "tipo" => PDO::PARAM_STR]
    );

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