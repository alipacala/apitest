<?php
require_once PROJECT_ROOT_PATH . "/entities/ReciboPago.php";

class RecibosPagoDb extends Database
{
  public $class = ReciboPago::class;
  public $idName = "Id_recibo_pago";
  public $tableName = "recibo_de_pago";

  public function obtenerReciboPago($id)
  {
    $query = $this->prepareQuery("select-one");
    $params = $this->prepareParams(null, "select-one", $id);

    return $this->executeQuery($query, $params, "select-one");
  }

  public function listarRecibosPago()
  {
    $query = $this->prepareQuery("select");

    return $this->executeQuery($query, null, "select");
  }

  public function crearReciboPago(ReciboPago $reciboPago)
  {
    $reciboPagoArray = $this->prepareData((array) $reciboPago, "insert");
    $query = $this->prepareQuery("insert", $reciboPagoArray);
    $params = $this->prepareParams($reciboPagoArray);

    return $this->executeQuery($query, $params, "insert");
  }

  public function actualizarReciboPago($id, ReciboPago $reciboPago)
  {
    $reciboPagoArray = $this->prepareData((array) $reciboPago);
    $query = $this->prepareQuery("update", $reciboPagoArray);
    $params = $this->prepareParams($reciboPagoArray, "update", $id);

    return $this->executeQuery($query, $params, "update");
  }

  public function cerrarTurno($nroCierreTurno)
  {
    $query = "UPDATE recibo_de_pago SET nro_cierre_turno = :nro_cierre_turno WHERE nro_cierre_turno IS NULL";
    $params = array(
      ["nombre" => "nro_cierre_turno", "valor" => $nroCierreTurno, "tipo" => PDO::PARAM_INT]
    );

    return $this->executeQuery($query, $params, "update");
  }

  public function comprobarTurnoCerrado()
  {
    $query = "SELECT COUNT(*) AS cantidad FROM recibo_de_pago WHERE nro_cierre_turno IS NULL";

    return $this->executeQuery($query);
  }

  public function eliminarReciboPago($id)
  {
    $query = $this->prepareQuery("delete");
    $params = $this->prepareParams(null, "delete", $id);

    return $this->executeQuery($query, $params, "delete");
  }
}
?>