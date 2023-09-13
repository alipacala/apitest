<?php
require_once PROJECT_ROOT_PATH . "/entities/TerapistaHabilidad.php";

class TerapistasHabilidadesDb extends Database
{
  public $class = TerapistaHabilidad::class;
  public $idName = "id_terapistas_habilidad";
  public $tableName = "terapistashabilidades";

  public function obtenerTerapistaHabilidad($id)
  {
    $query = $this->prepareQuery("select-one");
    $params = $this->prepareParams(null, "select-one", $id);

    return $this->executeQuery($query, $params, "select-one");
  }

  public function listarTerapistasHabilidades()
  {
    $query = $this->prepareQuery("select");

    return $this->executeQuery($query, null, "select");
  }

  public function crearTerapistaHabilidad(TerapistaHabilidad $habilidad)
  {
    $habilidadArray = $this->prepareData((array) $habilidad, "insert");
    $query = $this->prepareQuery("insert", $habilidadArray);
    $params = $this->prepareParams($habilidadArray);

    return $this->executeQuery($query, $params, "insert");
  }

  public function actualizarTerapistaHabilidad($id, TerapistaHabilidad $habilidad)
  {
    $habilidadArray = $this->prepareData((array) $habilidad);
    $query = $this->prepareQuery("update", $habilidadArray);
    $params = $this->prepareParams($habilidadArray, "update", $id);

    return $this->executeQuery($query, $params, "update");
  }

  public function eliminarTerapistaHabilidad($id)
  {
    $query = $this->prepareQuery("delete");
    $params = $this->prepareParams(null, "delete", $id);

    return $this->executeQuery($query, $params, "delete");
  }
}
?>