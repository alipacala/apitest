<?php
require_once PROJECT_ROOT_PATH . "/entities/ComprobanteVentas.php";

class ComprobantesVentasDb extends Database
{
  public $class = ComprobanteVentas::class;
  public $idName = "id_comprobante_ventas";
  public $tableName = "comprobante_ventas";

  public function obtenerComprobanteVentas($id)
  {
    $query = $this->prepareQuery("select-one");
    $params = $this->prepareParams(null, "select-one", $id);

    return $this->executeQuery($query, $params, "select-one");
  }

  public function listarComprobantsVenta($nroRegistroMaestro = null)
  {
    if ($nroRegistroMaestro) {
      $query = "SELECT
       co.fecha_documento,
       co.nro_comprobante,
       co.nro_documento_cliente,
       fec.rznSocialUsuario,
       co.total AS total_comprobante,
       co.por_pagar,
       co.id_comprobante_ventas,
       co.tipo_comprobante,
       co.forma_de_pago,
       re.medio_pago,
       re.total AS total_recibo
       FROM $this->tableName AS co
       INNER JOIN fe_comprobante AS fec ON co.id_comprobante_ventas = fec.NroMov
       LEFT JOIN recibo_de_pago AS re ON co.id_comprobante_ventas = re.id_comprobante_ventas
       WHERE nro_registro_maestro = :nro_registro_maestro";
      $params = array(["nombre" => "nro_registro_maestro", "valor" => $nroRegistroMaestro, "tipo" => PDO::PARAM_STR]);

      return $this->executeQuery($query, $params); // no se especifica el tipo de operación porque no se debe parsear el resultado
    }

    $query = $this->prepareQuery("select");

    return $this->executeQuery($query, null, "select");
  }

  public function crearComprobanteVentas(ComprobanteVentas $comprobanteVentas)
  {
    $comprobanteVentasArray = $this->prepareData((array) $comprobanteVentas, "insert");
    $query = $this->prepareQuery("insert", $comprobanteVentasArray);
    $params = $this->prepareParams($comprobanteVentasArray);

    return $this->executeQuery($query, $params, "insert");
  }

  public function actualizarComprobanteVentas($id, ComprobanteVentas $comprobanteVentas)
  {
    $comprobanteVentasArray = $this->prepareData((array) $comprobanteVentas);
    $query = $this->prepareQuery("update", $comprobanteVentasArray);
    $params = $this->prepareParams($comprobanteVentasArray, "update", $id);

    return $this->executeQuery($query, $params, "update");
  }

  public function pagar($id, $monto)
  {
    $query = "UPDATE $this->tableName SET por_pagar = por_pagar - :monto WHERE $this->idName = :id";
    $params = array(
      ["nombre" => "monto", "valor" => $monto, "tipo" => PDO::PARAM_STR],
      ["nombre" => "id", "valor" => $id, "tipo" => PDO::PARAM_INT]
    );

    return $this->executeQuery($query, $params, "update");
  }

  public function eliminarComprobanteVentas($id)
  {
    $query = $this->prepareQuery("delete");
    $params = $this->prepareParams(null, "delete", $id);

    return $this->executeQuery($query, $params, "delete");
  }
}
?>