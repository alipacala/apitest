<?php
require_once PROJECT_ROOT_PATH . "/entities/GrupoDeLaCarta.php";

class GruposDeLaCartaDb extends Database
{
  public $class = GrupoDeLaCarta::class;
  public $idName = "id_grupo";
  public $tableName = "gruposdelacarta";

  public function obtenerGrupoDeLaCarta($id)
  {
    $query = $this->prepareQuery("select-one");
    $params = $this->prepareParams(null, "select-one", $id);

    return $this->executeQuery($query, $params, "select-one");
  }

  public function listarGruposDeLaCarta()
  {
    $query = $this->prepareQuery("select");

    return $this->executeQuery($query, null, "select");
  }

  public function crearGrupoDeLaCarta(GrupoDeLaCarta $grupoDeLaCarta)
  {
    $gruposDeLaCartaArray = $this->prepareData((array) $grupoDeLaCarta, "insert");
    $query = $this->prepareQuery("insert", $gruposDeLaCartaArray);
    $params = $this->prepareParams($gruposDeLaCartaArray);

    return $this->executeQuery($query, $params, "insert");
  }

  public function actualizarGrupoDeLaCarta($id, GrupoDeLaCarta $grupoDeLaCarta)
  {
    $gruposDeLaCartaArray = $this->prepareData((array) $grupoDeLaCarta);
    $query = $this->prepareQuery("update", $gruposDeLaCartaArray);
    $params = $this->prepareParams($gruposDeLaCartaArray, "update", $id);

    return $this->executeQuery($query, $params, "update");
  }

  public function eliminarGrupoDeLaCarta($id)
  {
    $query = $this->prepareQuery("delete");
    $params = $this->prepareParams(null, "delete", $id);

    return $this->executeQuery($query, $params, "delete");
  }
}
?>