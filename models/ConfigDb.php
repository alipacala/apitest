<?php
require_once PROJECT_ROOT_PATH . "/entities/Config.php";

class ConfigDb extends Database
{
  public $class = Config::class;
  public $idName = "id_config";
  public $tableName = "config";

  public function obtenerConfig($id)
  {
    $query = $this->prepareQuery("select-one");
    $params = $this->prepareParams(null, "select-one", $id);

    return $this->executeQuery($query, $params, "select-one");
  }

  public function obtenerCodigo($id)
  {
    $query = $this->prepareQuery("select-one");
    $params = $this->prepareParams(null, "select-one", $id);

    $result = $this->executeQuery($query, $params, "select-one");

    switch ($id) {
      case 5:
        $fechaYHora = $this->obtenerFechaYHora();
        $date = DateTime::createFromFormat("Y-m-d", $fechaYHora['fecha']);
        $año = $date->format("y");

        $result = array(
          "codigo" => substr($año, -2) . str_pad($result->numero_correlativo, 6, "0", STR_PAD_LEFT)
        );
        break;
      default:
        $result = array(
          "codigo" => $result->codigo .
          str_pad($result->numero_correlativo, 6, "0", STR_PAD_LEFT)
        );
        break;
    }
    return $result;
  }

  public function listarConfig()
  {
    $query = $this->prepareQuery("select");

    return $this->executeQuery($query, null, "select");
  }

  public function crearConfig(Config $config)
  {
    $configArray = $this->prepareData((array) $config, "insert");
    $query = $this->prepareQuery("insert", $configArray);
    $params = $this->prepareParams($configArray);

    return $this->executeQuery($query, $params, "insert");
  }

  public function actualizarConfig($id, Config $config)
  {
    $configArray = $this->prepareData((array) $config);
    $query = $this->prepareQuery("update", $configArray);
    $params = $this->prepareParams($configArray, "update", $id);

    return $this->executeQuery($query, $params, "update");
  }

  public function incrementarCorrelativo($id)
  {
    $query = "UPDATE config SET numero_correlativo = numero_correlativo + 1 WHERE id_config = :id_config";
    $params = array(["nombre" => "id_config", "valor" => $id, "tipo" => PDO::PARAM_INT]);

    return $this->executeQuery($query, $params, "update");
  }

  public function eliminarConfig($id)
  {
    $query = $this->prepareQuery("delete");
    $params = $this->prepareParams(null, "delete", $id);

    return $this->executeQuery($query, $params, "delete");
  }
}
?>