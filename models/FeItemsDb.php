<?php
require_once PROJECT_ROOT_PATH . "/entities/FeItem.php";

class FeItemsDb extends Database
{
  public $class = FeItem::class;
  public $idName = "IdfeItem";
  public $tableName = "fe_items";

  public function obtenerFeItem($id)
  {
    $query = $this->prepareQuery("select-one");
    $params = $this->prepareParams(null, "select-one", $id);

    return $this->executeQuery($query, $params, "select-one");
  }

  public function listarFeItems()
  {
    $query = $this->prepareQuery("select");

    return $this->executeQuery($query, null, "select");
  }

  public function crearFeItem(FeItem $feItem)
  {
    $feItemArray = $this->prepareData((array) $feItem, "insert");
    $query = $this->prepareQuery("insert", $feItemArray);
    $params = $this->prepareParams($feItemArray);

    return $this->executeQuery($query, $params, "insert");
  }

  public function actualizarFeItem($id, FeItem $feItem)
  {
    $feItemArray = $this->prepareData((array) $feItem);
    $query = $this->prepareQuery("update", $feItemArray);
    $params = $this->prepareParams($feItemArray, "update", $id);

    return $this->executeQuery($query, $params, "update");
  }

  public function eliminarFeItem($id)
  {
    $query = $this->prepareQuery("delete");
    $params = $this->prepareParams(null, "delete", $id);

    return $this->executeQuery($query, $params, "delete");
  }
  
  public function eliminarFeItemsPorIdComprobante($idComprobante)
  {
    $query = "DELETE FROM $this->tableName WHERE NroMov = :id_comprobante";
    $params = array(["nombre" => "id_comprobante", "valor" => $idComprobante, "tipo" => PDO::PARAM_INT]);

    return $this->executeQuery($query, $params, "delete");
  }
}
?>