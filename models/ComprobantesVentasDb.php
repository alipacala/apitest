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

  public function buscarPorNroRegistroMaestro($nroRegistroMaestro)
  {
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
       re.total AS total_recibo,
       co.estado
       FROM $this->tableName AS co
       INNER JOIN fe_comprobante AS fec ON co.id_comprobante_ventas = fec.NroMov
       LEFT JOIN recibo_de_pago AS re ON co.id_comprobante_ventas = re.id_comprobante_ventas
       WHERE nro_registro_maestro = :nro_registro_maestro";
    $params = array(["nombre" => "nro_registro_maestro", "valor" => $nroRegistroMaestro, "tipo" => PDO::PARAM_STR]);

    return $this->executeQuery($query, $params);
  }

  public function listarComprobantesVentas($fecha = null, $mes = null, $anio = null, $soloBolFact = false)
  {
    $query = "";
    $params = null;

    if ($fecha) {
      $query = "SELECT co.*, fc.rznSocialUsuario, us.usuario
        FROM $this->tableName AS co
        INNER JOIN fe_comprobante AS fc ON co.id_comprobante_ventas = fc.NroMov
        INNER JOIN usuarios AS us ON co.id_usuario = us.id_usuario
        WHERE DATE(fecha_documento) = STR_TO_DATE(:fecha, '%Y-%m-%d')";
      $params = array(["nombre" => "fecha", "valor" => $fecha, "tipo" => PDO::PARAM_STR]);
    }

    if ($mes && $anio) {
      $query = "SELECT co.*, fc.rznSocialUsuario, us.usuario
        FROM $this->tableName AS co
        INNER JOIN fe_comprobante AS fc ON co.id_comprobante_ventas = fc.NroMov
        INNER JOIN usuarios AS us ON co.id_usuario = us.id_usuario
        WHERE MONTH(fecha_documento) = :mes AND YEAR(fecha_documento) = :anio";
      $params = array(
        ["nombre" => "mes", "valor" => $mes, "tipo" => PDO::PARAM_INT],
        ["nombre" => "anio", "valor" => $anio, "tipo" => PDO::PARAM_INT]
      );
    }

    if ($soloBolFact) {
      $query .= " AND (tipo_comprobante = '01' OR tipo_comprobante = '03')";
    }

    $query .= " ORDER BY co.fecha_documento DESC, co.nro_comprobante ASC";

    return $this->executeQuery($query, $params);
  }

  public function listarComprasEnRangoFechas($fechaInicio, $fechaFin)
  {
    $query = "SELECT co.*,
    pe.nombres, pe.apellidos,
    CASE WHEN co.por_pagar = 0 THEN '-' ELSE 'X PAGAR' END AS estado,
    CASE WHEN co.tipo_comprobante = '00' THEN 'PD' WHEN co.tipo_comprobante = '01' THEN 'FA' WHEN co.tipo_comprobante = '03' THEN 'BO' END AS tipo_comprobante,
    tg.nombre_gasto
    FROM $this->tableName co
    INNER JOIN personanaturaljuridica pe ON co.nro_documento_cliente = pe.nro_documento
    INNER JOIN tipo_de_gasto tg ON co.id_tipo_de_gasto = tg.id_tipo_de_gasto
    WHERE co.tipo_movimiento = 'IN' AND co.fecha_documento BETWEEN :fecha_inicio AND :fecha_fin
    ORDER BY co.fecha_documento DESC, co.nro_comprobante ASC";
    $params = array(
      ["nombre" => "fecha_inicio", "valor" => $fechaInicio, "tipo" => PDO::PARAM_STR],
      ["nombre" => "fecha_fin", "valor" => $fechaFin, "tipo" => PDO::PARAM_STR]
    );

    return $this->executeQuery($query, $params);
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

  public function resetearPagos($id)
  {
    $query = "UPDATE $this->tableName SET por_pagar = total WHERE $this->idName = :id";
    $params = array(["nombre" => "id", "valor" => $id, "tipo" => PDO::PARAM_INT]);

    return $this->executeQuery($query, $params, "update");
  }

  public function anularComprobanteVentas($id)
  {
    $query = "UPDATE $this->tableName SET estado = 0, por_pagar = total WHERE $this->idName = :id_comprobante";
    $params = array(["nombre" => "id_comprobante", "valor" => $id, "tipo" => PDO::PARAM_INT]);

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