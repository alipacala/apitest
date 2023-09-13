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