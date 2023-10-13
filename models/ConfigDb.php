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

    if ($id == 5) {
      /* $fechaYHora = $this->obtenerFechaYHora();
      $date = DateTime::createFromFormat("Y-m-d", $fechaYHora['fecha']);
      $año = $date->format("y");

      $result = array(
        "codigo" => substr($año, -2) . str_pad($result->numero_correlativo, 6, "0", STR_PAD_LEFT)
      ); */
      $result = array(
        "codigo" => $result->codigo . str_pad($result->numero_correlativo, 6, "0", STR_PAD_LEFT)
      );

    } else if ($id == 20) {
      $result = array(
        "codigo" => "P001-" .
          str_pad($result->numero_correlativo, 8, "0", STR_PAD_LEFT)
      );

    } else if ($id == 2 || $id == 11) {
      // id de la config de reservas y de hotel
      $result = array(
        "codigo" => $result->codigo . str_pad($result->numero_correlativo, 6, "0", STR_PAD_LEFT)
      );

    } else {
      $result = array(
        "codigo" => $result->codigo .
          str_pad($result->numero_correlativo, 6, "0", STR_PAD_LEFT)
      );
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

  function actualizarNumeroCorrelativo($codigo)
  {
    $query = "UPDATE config SET numero_correlativo = numero_correlativo + 1 WHERE codigo = :codigo";
    $params = array(["nombre" => "codigo", "valor" => $codigo, "tipo" => PDO::PARAM_STR]);

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