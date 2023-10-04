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

  public function listarRooming($nroRegistroMaestro = null, $idCheckin = null)
  {
    if ($nroRegistroMaestro) {
      $query = "SELECT * FROM $this->tableName WHERE nro_registro_maestro = :nro_registro_maestro ORDER BY id_checkin DESC LIMIT 1";
      $params = array(["nombre" => "nro_registro_maestro", "valor" => $nroRegistroMaestro, "tipo" => PDO::PARAM_STR]);

      return $this->executeQuery($query, $params, "select-one");
    }

    if ($idCheckin) {
      $query = "SELECT * FROM $this->tableName WHERE id_checkin = :id_checkin ORDER BY id_rooming DESC LIMIT 1";
      $params = array(["nombre" => "id_checkin", "valor" => $idCheckin, "tipo" => PDO::PARAM_INT]);

      return $this->executeQuery($query, $params, "select-one");
    }

    $query = $this->prepareQuery("select");

    return $this->executeQuery($query, null, "select");
  }

  public function listarRoomingConDatos1()
  {
    $query = "SELECT 
    p.nombre_producto, 
    h.nro_habitacion, 
    ch.nro_registro_maestro, 
    ch.nro_reserva, 
    ch.nombre, 
    ch.nro_personas, 
    ch.fecha_in, 
    ch.fecha_out
    FROM habitaciones h
    LEFT JOIN rooming r ON r.nro_habitacion = h.nro_habitacion
    LEFT JOIN cheking ch ON ch.id_checkin = r.id_checkin AND CURDATE() BETWEEN ch.fecha_in AND ch.fecha_out
    LEFT JOIN reservas re ON ch.nro_registro_maestro = re.nro_registro_maestro 
    LEFT JOIN productos p ON p.id_producto = h.id_producto
    WHERE h.id_unidad_de_negocio = 3 
    GROUP BY h.nro_habitacion
    ORDER BY h.nro_habitacion ASC";

    return $this->executeQuery($query, null, "select");
  }

  public function listarRoomingConDatos2($fecha)
  {
    $query = "SELECT
    COALESCE(p.nombre_producto, '') AS nombre_producto,
    COALESCE(h.nro_habitacion, '') AS nro_habitacion,
    COALESCE(ch.nro_registro_maestro, '') AS nro_registro_maestro,
    COALESCE(ch.nro_reserva, '') AS nro_reserva,
    COALESCE(ch.nombre, '') AS nombre,
    COALESCE(ch.nro_personas, '') AS nro_personas,
    COALESCE(ch.fecha_in, '') AS fecha_in,
    COALESCE(ch.fecha_out, '') AS fecha_out
    FROM habitaciones h
    LEFT JOIN rooming r ON r.nro_habitacion = h.nro_habitacion
    LEFT JOIN cheking ch ON ch.id_checkin = r.id_checkin AND :fecha BETWEEN ch.fecha_in AND ch.fecha_out
    LEFT JOIN reservas re ON ch.nro_registro_maestro = re.nro_registro_maestro 
    LEFT JOIN productos p ON p.id_producto = h.id_producto
    WHERE h.id_unidad_de_negocio = 3
    GROUP BY h.nro_habitacion
    ORDER BY h.nro_habitacion ASC";
    $params = array(["nombre" => "fecha", "valor" => $fecha, "tipo" => PDO::PARAM_STR]);

    return $this->executeQuery($query, $params, "select");
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

  public function eliminarRooming($id)
  {
    $query = $this->prepareQuery("delete");
    $params = $this->prepareParams(null, "delete", $id);

    return $this->executeQuery($query, $params, "delete");
  }
}
?>