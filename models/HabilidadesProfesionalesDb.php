<?php
require_once PROJECT_ROOT_PATH . "/entities/HabilidadProfesional.php";

class HabilidadesProfesionalesDb extends Database
{
  public $class = HabilidadProfesional::class;
  public $idName = "id_habilidad";
  public $tableName = "habilidadesprofesionales";

  public function obtenerHabilidadProfesional($id)
  {
    $query = $this->prepareQuery("select-one");
    $params = $this->prepareParams(null, "select-one", $id);

    return $this->executeQuery($query, $params, "select-one");
  }

  public function listarHabilidadesProfesionales()
  {
    $query = $this->prepareQuery("select");

    return $this->executeQuery($query, null, "select");
  }

  public function crearHabilidadProfesional(HabilidadProfesional $habilidad)
  {
    $habilidadArray = $this->prepareData((array) $habilidad, "insert");
    $query = $this->prepareQuery("insert", $habilidadArray);
    $params = $this->prepareParams($habilidadArray);

    return $this->executeQuery($query, $params, "insert");
  }

  public function actualizarHabilidadProfesional($id, HabilidadProfesional $habilidad)
  {
    $habilidadArray = $this->prepareData((array) $habilidad);
    $query = $this->prepareQuery("update", $habilidadArray);
    $params = $this->prepareParams($habilidadArray, "update", $id);

    return $this->executeQuery($query, $params, "update");
  }

  public function eliminarHabilidadProfesional($id)
  {
    $query = $this->prepareQuery("delete");
    $params = $this->prepareParams(null, "delete", $id);

    return $this->executeQuery($query, $params, "delete");
  }
}
?>