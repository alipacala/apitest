<?php
require_once PROJECT_ROOT_PATH . "/entities/FeComprobante.php";

class FeComprobantesDb extends Database
{
  public $class = FeComprobante::class;
  public $idName = "IdFeC";
  public $tableName = "fe_comprobante";

  public function obtenerFeComprobante($id)
  {
    $query = $this->prepareQuery("select-one");
    $params = $this->prepareParams(null, "select-one", $id);

    return $this->executeQuery($query, $params, "select-one");
  }

  public function listarFeComprobantes()
  {
    $query = $this->prepareQuery("select");

    return $this->executeQuery($query, null, "select");
  }

  public function crearFeComprobante(FeComprobante $feComprobante)
  {
    $feComprobanteArray = $this->prepareData((array) $feComprobante, "insert");
    $query = $this->prepareQuery("insert", $feComprobanteArray);
    $params = $this->prepareParams($feComprobanteArray);

    return $this->executeQuery($query, $params, "insert");
  }

  public function actualizarFeComprobante($id, FeComprobante $feComprobante)
  {
    $feComprobanteArray = $this->prepareData((array) $feComprobante);
    $query = $this->prepareQuery("update", $feComprobanteArray);
    $params = $this->prepareParams($feComprobanteArray, "update", $id);

    return $this->executeQuery($query, $params, "update");
  }

  public function anularFeComprobante($id) {
    $query = "UPDATE fe_comprobante SET xestado = 0 WHERE NroMov = :id_comprobante";
    $params = array(["nombre" => "id_comprobante", "valor" => $id, "tipo" => PDO::PARAM_INT]);

    return $this->executeQuery($query, $params, "update");
  }

  public function eliminarFeComprobante($id)
  {
    $query = $this->prepareQuery("delete");
    $params = $this->prepareParams(null, "delete", $id);

    return $this->executeQuery($query, $params, "delete");
  }
}
?>