<?php
require_once PROJECT_ROOT_PATH . "/entities/ModalidadCliente.php";

class ModalidadesClienteDb extends Database
{
  public $class = ModalidadCliente::class;
  public $idName = "id_modalidad";
  public $tableName = "modalidadcliente";

  public function obtenerModalidadCliente($id)
  {
    $query = $this->prepareQuery("select-one");
    $params = $this->prepareParams(null, "select-one", $id);

    return $this->executeQuery($query, $params, "select-one");
  }

  public function listarModalidadesCliente()
  {
    $query = $this->prepareQuery("select");

    return $this->executeQuery($query, null, "select");
  }

  public function crearModalidadCliente(ModalidadCliente $modalidadCliente)
  {
    $modalidadClienteArray = $this->prepareData((array) $modalidadCliente, "insert");
    $query = $this->prepareQuery("insert", $modalidadClienteArray);
    $params = $this->prepareParams($modalidadClienteArray);

    return $this->executeQuery($query, $params, "insert");
  }

  public function actualizarModalidadCliente($id, ModalidadCliente $modalidadCliente)
  {
    $modalidadClienteArray = $this->prepareData((array) $modalidadCliente);
    $query = $this->prepareQuery("update", $modalidadClienteArray);
    $params = $this->prepareParams($modalidadClienteArray, "update", $id);

    return $this->executeQuery($query, $params, "update");
  }

  public function eliminarModalidadCliente($id)
  {
    $query = $this->prepareQuery("delete");
    $params = $this->prepareParams(null, "delete", $id);

    return $this->executeQuery($query, $params, "delete");
  }
}
?>