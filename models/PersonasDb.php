<?php
require_once PROJECT_ROOT_PATH . "/entities/Persona.php";

class PersonasDb extends Database
{
  public $class = Persona::class;
  public $idName = "id_persona";
  public $tableName = "personanaturaljuridica";

  public function obtenerPersona($id)
  {
    $query = $this->prepareQuery("select-one");
    $params = $this->prepareParams(null, "select-one", $id);

    return $this->executeQuery($query, $params, "select-one");
  }

  public function listarPersonas()
  {
    $query = $this->prepareQuery("select");

    return $this->executeQuery($query, null, "select");
  }

  public function buscarPorNroDocumento($nroDocumento)
  {
    $query = "SELECT * FROM $this->tableName WHERE nro_documento = :nro_documento";
    $params = array(["nombre" => "nro_documento", "valor" => $nroDocumento, "tipo" => PDO::PARAM_STR]);

    return $this->executeQuery($query, $params, "select-one");
  }

  public function crearPersona(Persona $persona)
  {
    $personaArray = $this->prepareData((array) $persona, "insert");
    $query = $this->prepareQuery("insert", $personaArray);
    $params = $this->prepareParams($personaArray);

    return $this->executeQuery($query, $params, "insert");
  }

  public function actualizarPersona($id, Persona $persona)
  {
    $personaArray = $this->prepareData((array) $persona);
    $query = $this->prepareQuery("update", $personaArray);
    $params = $this->prepareParams($personaArray, "update", $id);

    return $this->executeQuery($query, $params, "update");
  }

  public function actualizarEdadPersona($id, $edad)
  {
    $query = "UPDATE $this->tableName SET edad = :edad WHERE $this->idName = :id";
    $params = array(
      ["nombre" => "edad", "valor" => $edad, "tipo" => PDO::PARAM_INT],
      ["nombre" => "id", "valor" => $id, "tipo" => PDO::PARAM_INT]
    );

    return $this->executeQuery($query, $params, "update");
  }

  public function eliminarPersona($id)
  {
    $query = $this->prepareQuery("delete");
    $params = $this->prepareParams(null, "delete", $id);

    return $this->executeQuery($query, $params, "delete");
  }
}
?>