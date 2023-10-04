<?php
require_once PROJECT_ROOT_PATH . "/entities/Terapista.php";

class TerapistasDb extends Database
{
  public $class = Terapista::class;
  public $idName = "id_profesional";
  public $tableName = "terapistas";

  public function obtenerTerapista($id)
  {
    $query = $this->prepareQuery("select-one");
    $params = $this->prepareParams(null, "select-one", $id);

    return $this->executeQuery($query, $params, "select-one");
  }

  public function listarTerapistas($conHabilidades)
  {
    if ($conHabilidades) {
      $query = "SELECT * FROM $this->tableName
                INNER JOIN terapistashabilidades AS th
                ON $this->tableName.id_persona = th.id_persona
                INNER JOIN habilidadesprofesionales AS hp
                ON th.id_habilidad = hp.id_habilidad";

      return $this->executeQuery($query, null);
    }


    $query = $this->prepareQuery("select");
    return $this->executeQuery($query, null, "select");
  }

  public function buscarPorNroDocumento($nroDocumento)
  {
    $query = "SELECT * FROM $this->tableName WHERE nro_documento = :nro_documento";
    $params = array(["nombre" => "nro_documento", "valor" => $nroDocumento, "tipo" => PDO::PARAM_STR]);

    return $this->executeQuery($query, $params);
  }

  public function crearTerapista(Terapista $terapista)
  {
    $terapistaArray = $this->prepareData((array) $terapista, "insert");
    $query = $this->prepareQuery("insert", $terapistaArray);
    $params = $this->prepareParams($terapistaArray);

    return $this->executeQuery($query, $params, "insert");
  }

  public function actualizarTerapista($id, Terapista $terapista)
  {
    $terapistaArray = $this->prepareData((array) $terapista);
    $query = $this->prepareQuery("update", $terapistaArray);
    $params = $this->prepareParams($terapistaArray, "update", $id);

    return $this->executeQuery($query, $params, "update");
  }

  public function eliminarTerapista($id)
  {
    $query = $this->prepareQuery("delete");
    $params = $this->prepareParams(null, "delete", $id);

    return $this->executeQuery($query, $params, "delete");
  }
}
?>