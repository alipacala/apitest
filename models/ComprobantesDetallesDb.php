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

  public function buscarComprobantesDetallesPorIdComprobante($idComprobante)
  {
    $query = "SELECT cd.*, dd.fecha_servicio FROM comprobante_detalle AS cd INNER JOIN documento_detalle AS dd ON cd.id_documentos_detalle = dd.id_documentos_detalle WHERE id_comprobante_ventas = :id_comprobante";
    $params = array(["nombre" => "id_comprobante", "valor" => $idComprobante, "tipo" => PDO::PARAM_INT]);

    return $this->executeQuery($query, $params, "select");
  }

  public function buscarDetallesCompraPorIdComprobante($idComprobante)
  {
    $query = "SELECT * FROM comprobante_detalle WHERE id_comprobante_ventas = :id_comprobante;";
    $params = array(
      ["nombre" => "id_comprobante", "valor" => $idComprobante, "tipo" => PDO::PARAM_INT],
    );

    return $this->executeQuery($query, $params, "select");
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

  public function eliminarComprobanteDetallePorIdComprobante($idComprobante)
  {
    $query = "DELETE FROM comprobante_detalle WHERE id_comprobante_ventas = :id_comprobante";
    $params = array(["nombre" => "id_comprobante", "valor" => $idComprobante, "tipo" => PDO::PARAM_INT]);

    return $this->executeQuery($query, $params, "delete");
  }
}
?>