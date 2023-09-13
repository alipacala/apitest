<?php
require_once PROJECT_ROOT_PATH . "/entities/Producto.php";

class ProductosDb extends Database
{
  public $class = Producto::class;
  public $idName = "id_producto";
  public $tableName = "productos";

  public function obtenerProducto($id)
  {
    $query = $this->prepareQuery("select-one");
    $params = $this->prepareParams(null, "select-one", $id);

    return $this->executeQuery($query, $params, "select-one");
  }

  public function listarProductos($grupos = null)
  {
    if ($grupos) {
      $grupos = explode(",", $grupos);

      $query = "SELECT * FROM productos WHERE id_grupo IN (";
      $query .= implode(",", array_fill(0, count($grupos), "?"));
      $query .= ")";

      $params = array_map(function ($key, $value) {
        return array("nombre" => $key + 1, "valor" => $value, "tipo" => PDO::PARAM_INT);
      }, array_keys($grupos), $grupos);

      return $this->executeQuery($query, $params, "select");
    }

    $query = $this->prepareQuery("select");
    return $this->executeQuery($query, null, "select");
  }

  public function crearProducto(Producto $producto)
  {
    $productoArray = $this->prepareData((array) $producto, "insert");
    $query = $this->prepareQuery("insert", $productoArray);
    $params = $this->prepareParams($productoArray);

    return $this->executeQuery($query, $params, "insert");
  }

  public function actualizarProducto($id, Producto $producto)
  {
    $productoArray = $this->prepareData((array) $producto);
    $query = $this->prepareQuery("update", $productoArray);
    $params = $this->prepareParams($productoArray, "update", $id);

    return $this->executeQuery($query, $params, "update");
  }

  public function eliminarProducto($id)
  {
    $query = $this->prepareQuery("delete");
    $params = $this->prepareParams(null, "delete", $id);

    return $this->executeQuery($query, $params, "delete");
  }
}
?>