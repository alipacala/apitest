<?php
require_once PROJECT_ROOT_PATH . "/entities/ComprobanteVentas.php";

class ReportesDb extends Database
{
  public function obtenerReporte($fecha = null)
  {
    $query = "SELECT
    co.fecha_documento,
    co.nro_comprobante,
    co.nro_documento_cliente,
    fec.rznSocialUsuario,
    co.total AS total_comprobante,
    co.id_comprobante_ventas,
    co.tipo_comprobante,
    co.forma_de_pago,
    re.nro_cierre_turno,
    re.medio_pago,
    re.total AS total_recibo
    FROM comprobante_ventas AS co
    INNER JOIN fe_comprobante AS fec ON co.id_comprobante_ventas = fec.NroMov
    LEFT JOIN recibo_de_pago AS re ON co.id_comprobante_ventas = re.id_comprobante_ventas
    WHERE DATE(fecha_documento) = STR_TO_DATE(:fecha, '%Y-%m-%d')";
    $params = array(
      ["nombre" => "fecha", "valor" => $fecha, "tipo" => PDO::PARAM_STR]
    );

    return $this->executeQuery($query, $params);
  }
}
?>