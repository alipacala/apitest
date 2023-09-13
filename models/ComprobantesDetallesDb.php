<?php
require_once PROJECT_ROOT_PATH . "/entities/ComprobanteDetalle.php";

class ComprobantesDetallesDb extends Database
{
  public $class = ComprobanteDetalle::class;
  public $idName = "id_comprobante_detalle";
  public $tableName = "comprobante_detalle";

  public function obtenerComprobanteDetalle($id)
  {
    $query = $this->prepareQuery("select-one");
    $params = $this->prepareParams(null, "select-one", $id);

    return $this->executeQuery($query, $params, "select-one");
  }

  public function listarComprobantesDetalles()
  {
    $query = $this->prepareQuery("select");

    return $this->executeQuery($query, null, "select");
  }

  public function crearComprobanteDetalle(ComprobanteDetalle $comprobanteDetalle)
  {
    $comprobanteDetalleArray = $this->prepareData((array) $comprobanteDetalle, "insert");
    $query = $this->prepareQuery("insert", $comprobanteDetalleArray);
    $params = $this->prepareParams($comprobanteDetalleArray);

    return $this->executeQuery($query, $params, "insert");
  }

  public function actualizarComprobanteDetalle($id, ComprobanteDetalle $comprobanteDetalle)
  {
    $comprobanteDetalleArray = $this->prepareData((array) $comprobanteDetalle);
    $query = $this->prepareQuery("update", $comprobanteDetalleArray);
    $params = $this->prepareParams($comprobanteDetalleArray, "update", $id);

    return $this->executeQuery($query, $params, "update");
  }

  public function eliminarComprobanteDetalle($id)
  {
    $query = $this->prepareQuery("delete");
    $params = $this->prepareParams(null, "delete", $id);

    return $this->executeQuery($query, $params, "delete");
  }
}
?>