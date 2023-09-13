<?php
require_once PROJECT_ROOT_PATH . "/entities/ProductoReceta.php";

class ProductosRecetaDb extends Database
{
  public $class = ProductoReceta::class;
  public $idName = "id_receta";
  public $tableName = "productosreceta";

  public function obtenerProductoReceta($id)
  {
    $query = $this->prepareQuery("select-one");
    $params = $this->prepareParams(null, "select-one", $id);

    return $this->executeQuery($query, $params, "select-one");
  }

  public function listarProductosReceta()
  {
    $query = $this->prepareQuery("select");

    return $this->executeQuery($query, null, "select");
  }
  
  public function buscarInsumos($id) : array
  {
    $query = "SELECT * FROM productosreceta WHERE id_producto = ?";
    $params = array(array("nombre" => 1, "valor" => $id, "tipo" => PDO::PARAM_INT));

    return $this->executeQuery($query, $params, "select");
  }

  public function crearProductoReceta(ProductoReceta $productoReceta)
  {
    $productoRecetaArray = $this->prepareData((array) $productoReceta, "insert");
    $query = $this->prepareQuery("insert", $productoRecetaArray);
    $params = $this->prepareParams($productoRecetaArray);

    return $this->executeQuery($query, $params, "insert");
  }

  public function actualizarProductoReceta($id, ProductoReceta $productoReceta)
  {
    $productoRecetaArray = $this->prepareData((array) $productoReceta);
    $query = $this->prepareQuery("update", $productoRecetaArray);
    $params = $this->prepareParams($productoRecetaArray, "update", $id);

    return $this->executeQuery($query, $params, "update");
  }

  public function eliminarProductoReceta($id)
  {
    $query = $this->prepareQuery("delete");
    $params = $this->prepareParams(null, "delete", $id);

    return $this->executeQuery($query, $params, "delete");
  }
}
?>