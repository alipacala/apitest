<?php
require_once PROJECT_ROOT_PATH . "/entities/Acompanante.php";

class AcompanantesDb extends Database
{
  public $class = Acompanante::class;
  public $idName = "id_acompanante";
  public $tableName = "acompanantes";

  public function obtenerAcompanante($id)
  {
    $query = $this->prepareQuery("select-one");
    $params = $this->prepareParams(null, "select-one", $id);

    return $this->executeQuery($query, $params, "select-one");
  }

  public function listarAcompanantes()
  {
    $query = $this->prepareQuery("select");
    
    return $this->executeQuery($query, null, "select");
  }

  public function buscarPorNroRegistroMaestro($nroRegistroMaestro)
  {
    $query = "SELECT * FROM $this->tableName WHERE nro_registro_maestro = :nro_registro_maestro";
    $params = array(["nombre" => "nro_registro_maestro", "valor" => $nroRegistroMaestro, "tipo" => PDO::PARAM_STR]);

    return $this->executeQuery($query, $params, "select");
  }

  public function crearAcompanante(Acompanante $acompanante)
  {
    $acompananteArray = $this->prepareData((array) $acompanante, "insert");
    $query = $this->prepareQuery("insert", $acompananteArray);
    $params = $this->prepareParams($acompananteArray);

    return $this->executeQuery($query, $params, "insert");
  }

  public function actualizarAcompanante($id, Acompanante $acompanante)
  {
    $acompananteArray = $this->prepareData((array) $acompanante);
    $query = $this->prepareQuery("update", $acompananteArray);
    $params = $this->prepareParams($acompananteArray, "update", $id);

    return $this->executeQuery($query, $params, "update");
  }

  public function eliminarAcompanante($id)
  {
    $query = $this->prepareQuery("delete");
    $params = $this->prepareParams(null, "delete", $id);

    return $this->executeQuery($query, $params, "delete");
  }
}
?>