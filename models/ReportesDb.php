<?php
require_once PROJECT_ROOT_PATH . "/entities/ComprobanteVentas.php";

class ReportesDb extends Database
{
  public function obtenerReporteDiarioCaja($fecha = null)
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
    co.por_pagar,
    co.nro_registro_maestro,
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

  public function obtenerReporteDiarioDetalles($fecha = null)
  {
    $query = "SELECT
    ch.tipo_de_servicio,
    ch.nro_habitacion,

    pr.nombre_producto,
    ac.apellidos_y_nombres,
    dd.fecha,
    dd.nro_comprobante,
    
    cd.cantidad,
    cd.precio_unitario,
    cd.precio_total,
    pr.id_grupo
    
    FROM cheking AS ch

    LEFT JOIN comprobante_detalle AS cd ON ch.nro_registro_maestro = cd.nro_registro_maestro
    INNER JOIN documento_detalle AS dd ON cd.id_documentos_detalle = dd.id_documentos_detalle
    
    INNER JOIN productos AS pr ON cd.id_producto = pr.id_producto
    INNER JOIN acompanantes AS ac ON dd.id_acompanate = ac.id_acompanante
    WHERE DATE(fecha) = STR_TO_DATE(:fecha, '%Y-%m-%d')";
    $params = array(
      ["nombre" => "fecha", "valor" => $fecha, "tipo" => PDO::PARAM_STR]
    );

    return $this->executeQuery($query, $params);
  }
}
?>