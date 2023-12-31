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
      $result = array(
        "codigo" => $result->codigo . str_pad($result->numero_correlativo, 6, "0", STR_PAD_LEFT)
      );

    } else if ($id == 20) {
      $result = array(
        "codigo" => "P001-" .
          str_pad($result->numero_correlativo, 8, "0", STR_PAD_LEFT)
      );

    } else if ($id == 2 || $id == 11 || $id == 27 || $id == 28) {
      // id de la config de reservas y de hotel
      $result = array(
        "codigo" => $result->codigo . str_pad($result->numero_correlativo, 6, "0", STR_PAD_LEFT)
      );

    } else if ($id == 25) {

      $result = array(
        "codigo" => $result->codigo . "2301" .
          str_pad($result->numero_correlativo, 4, "0", STR_PAD_LEFT)
      );

    } else {
      $result = array(
        "codigo" => $result->codigo .
          str_pad($result->numero_correlativo, 6, "0", STR_PAD_LEFT)
      );
    }
    return $result;
  }

  public function obtenerCodigoOGenerar($codigo) {
    $nombresCodigos = [
      "RESERVA" => "RE",
      "MA" => "MA",
      "SPA" => "SP",
      "COMANDA" => "CM",
      "HOTEL" => "HT",
    ];

    $cantidadDigitos = 6;

    if ($codigo == 'GUIA_INTERNA') {
      $codigo = date("y") . "01";
      $cantidadDigitos = 4;
    }
    if ($codigo == 'PEDIDO') {
      $codigo = "P001-";
      $cantidadDigitos = 8;
    }

    $tienePrefijo = array_key_exists($codigo, $nombresCodigos);

    if ($tienePrefijo) {
      $codigo = $nombresCodigos[$codigo] . date("y");
    }

    $result = $this->obtenerCorrelativoDeCodigo($codigo);

    if ($tienePrefijo && !$result) {
      $nuevoConfig = new Config();
      $nuevoConfig->codigo = $codigo;
      $nuevoConfig->numero_correlativo = 0;
      $this->crearConfig($nuevoConfig);
    }

    return $codigo . str_pad($result[0]["codigo"], $cantidadDigitos, "0", STR_PAD_LEFT);
  }

  public function obtenerCorrelativoDeCodigo($codigo) {
    $query = "SELECT numero_correlativo + 1 AS codigo FROM config WHERE codigo = :codigo";
    $params = array(["nombre" => "codigo", "valor" => $codigo, "tipo" => PDO::PARAM_STR]);

    return $this->executeQuery($query, $params);
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