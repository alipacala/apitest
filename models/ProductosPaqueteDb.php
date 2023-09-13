<?php
require_once PROJECT_ROOT_PATH . "/entities/ProductoPaquete.php";

class ProductosPaqueteDb extends Database
{
  public $class = ProductoPaquete::class;
  public $idName = "id_paquete";
  public $tableName = "productospaquete";

  public function obtenerProductoPaquete($id)
  {
    $query = $this->prepareQuery("select-one");
    $params = $this->prepareParams(null, "select-one", $id);

    return $this->executeQuery($query, $params, "select-one");
  }

  public function listarProductosPaquete()
  {
    $query = $this->prepareQuery("select");

    return $this->executeQuery($query, null, "select");
  }

  public function buscarSubproductos($id): array
  {
    $query = "SELECT * FROM productospaquete WHERE id_producto = ?";
    $params = array(array("nombre" => 1, "valor" => $id, "tipo" => PDO::PARAM_INT));

    return $this->executeQuery($query, $params, "select");
  }

  public function crearProductoPaquete(ProductoPaquete $productoPaquete)
  {
    $productoPaqueteArray = $this->prepareData((array) $productoPaquete, "insert");
    $query = $this->prepareQuery("insert", $productoPaqueteArray);
    $params = $this->prepareParams($productoPaqueteArray);

    return $this->executeQuery($query, $params, "insert");
  }

  public function actualizarProductoPaquete($id, ProductoPaquete $productoPaquete)
  {
    $productoPaqueteArray = $this->prepareData((array) $productoPaquete);
    $query = $this->prepareQuery("update", $productoPaqueteArray);
    $params = $this->prepareParams($productoPaqueteArray, "update", $id);

    return $this->executeQuery($query, $params, "update");
  }

  public function eliminarProductoPaquete($id)
  {
    $query = $this->prepareQuery("delete");
    $params = $this->prepareParams(null, "delete", $id);

    return $this->executeQuery($query, $params, "delete");
  }
}
?>