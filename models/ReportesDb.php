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
    dd.cantidad,
    dd.precio_unitario,
    dd.precio_total,
    
    pr.id_grupo,
    pr.tipo
    
    FROM cheking AS ch
    INNER JOIN documento_detalle AS dd ON ch.nro_registro_maestro = dd.nro_registro_maestro
    INNER JOIN productos AS pr ON dd.id_producto = pr.id_producto
    INNER JOIN acompanantes AS ac ON dd.id_acompanate = ac.id_acompanante
    WHERE
      DATE(dd.fecha) = STR_TO_DATE(:fecha, '%Y-%m-%d')
      AND pr.id_tipo_de_producto != 10
      AND pr.id_tipo_de_producto != 11";
    $params = array(
      ["nombre" => "fecha", "valor" => $fecha, "tipo" => PDO::PARAM_STR]
    );

    return $this->executeQuery($query, $params);
  }

  public function obtenerReporteEstadoCuenta($consumos = null, $nroRegistroMaestro = null)
  {
    $query = "SELECT
    ch.tipo_de_servicio,
    ch.nro_habitacion,
    ch.nombre AS titular,
    ch.fecha_in,

    pr.nombre_producto,
    
    DATE_FORMAT(dd.fecha, '%d/%m/%Y') AS fecha,
    dd.nro_comprobante,
    dd.cantidad,
    dd.precio_unitario,
    dd.precio_total,
  
    dd.fecha_servicio,

  	CASE
    	WHEN pr.tipo = 'SRV' THEN ac.apellidos_y_nombres
        END AS cliente,
  
    CASE
    	WHEN dd.nro_comprobante IS NOT NULL AND co.por_pagar <= 0 THEN 'CANCELADO'
        WHEN dd.nro_comprobante IS NOT NULL THEN 'X COBRAR'
        ELSE ''
        END AS estado,
        
    CASE
    	WHEN pr.tipo = 'SVH' THEN 'HOSPEDAJES'
        WHEN pr.tipo = 'PRD' OR pr.tipo = 'RST' OR pr.tipo = 'PAQ' THEN 'PRODUCTOS Y PAQUETES'
        WHEN pr.tipo = 'SRV' THEN 'SERVICIOS'
        END AS grupo
    
    FROM documento_detalle AS dd
    INNER JOIN cheking AS ch ON dd.nro_registro_maestro = ch.nro_registro_maestro
    LEFT JOIN comprobante_ventas AS co ON dd.nro_comprobante = co.nro_comprobante
    INNER JOIN productos AS pr ON dd.id_producto = pr.id_producto
    LEFT JOIN acompanantes AS ac ON dd.id_acompanate = ac.id_acompanante
    WHERE
      dd.nro_registro_maestro = :nro_registro_maestro
      AND pr.id_tipo_de_producto != 10
      AND pr.id_tipo_de_producto != 11"
      . ($consumos == 'por-cobrar' ? " AND (co.por_pagar > 0 OR estado IS NULL)" : "");
    $params = array(
      ["nombre" => "nro_registro_maestro", "valor" => $nroRegistroMaestro, "tipo" => PDO::PARAM_INT]
    );

    return $this->executeQuery($query, $params);
  }
}
?>