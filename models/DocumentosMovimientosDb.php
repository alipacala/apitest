<?php
require_once PROJECT_ROOT_PATH . "/entities/DocumentoMovimiento.php";

class DocumentosMovimientosDb extends Database
{
  public $class = DocumentoMovimiento::class;
  public $idName = "id_documento_movimiento";
  public $tableName = "documento_movimiento";

  public function obtenerDocumentoMovimiento($id)
  {
    $query = $this->prepareQuery("select-one");
    $params = $this->prepareParams(null, "select-one", $id);

    return $this->executeQuery($query, $params, "select-one");
  }

  public function listarDocumentosMovimientos()
  {
    $query = $this->prepareQuery("select");

    return $this->executeQuery($query, null, "select");
  }

  public function listarDocumentosMovimientosEnRangoFechas($fechaInicio, $fechaFin, $unidadNegocio)
  {
    $query = "SELECT 
      dm.id_documento_movimiento,
      dm.fecha_movimiento AS fecha,
      dm.tipo_movimiento,
      dm.nro_documento,
      un1.nombre_unidad_de_negocio AS origen,
      un2.nombre_unidad_de_negocio AS destino,
      pe.nombres,
      pe.apellidos,
      dm.motivo,
      dm.observaciones
      FROM $this->tableName dm
      LEFT JOIN personanaturaljuridica pe ON pe.id_persona = dm.id_personajuridica
      LEFT JOIN unidaddenegocio un1 ON un1.id_unidad_de_negocio = dm.id_unidad_de_negocio
      LEFT JOIN unidaddenegocio un2 ON un2.id_unidad_de_negocio = dm.id_unidad_de_negocio_secundaria
      WHERE dm.fecha_movimiento BETWEEN :fecha_inicio AND :fecha_fin
      AND (dm.tipo_documento = 'GI' OR dm.tipo_documento = 'GR')
      AND (dm.id_unidad_de_negocio = :unidad_negocio1 OR dm.id_unidad_de_negocio_secundaria = :unidad_negocio2)
      ORDER BY dm.fecha_movimiento, dm.nro_documento DESC";

    $params = array(
      array("nombre" => "fecha_inicio", "valor" => $fechaInicio, "tipo" => PDO::PARAM_STR),
      array("nombre" => "fecha_fin", "valor" => $fechaFin, "tipo" => PDO::PARAM_STR),
      array("nombre" => "unidad_negocio1", "valor" => $unidadNegocio, "tipo" => PDO::PARAM_INT),
      array("nombre" => "unidad_negocio2", "valor" => $unidadNegocio, "tipo" => PDO::PARAM_INT)
    );

    return $this->executeQuery($query, $params, "select");
  }

  public function buscarPorNroDocumento($nroDocumento, $idDocumentoMovimiento = null)
  {
    $query = "SELECT * FROM $this->tableName WHERE nro_documento = :nro_documento AND id_documento_movimiento != :id_documento_movimiento";
    $params = array(
      ["nombre" => "nro_documento", "valor" => $nroDocumento, "tipo" => PDO::PARAM_STR],
      ["nombre" => "id_documento_movimiento", "valor" => $idDocumentoMovimiento, "tipo" => PDO::PARAM_INT]
    );

    return $this->executeQuery($query, $params, "select-one");
  }

  public function crearDocumentoMovimiento(DocumentoMovimiento $grupoDeLaCarta)
  {
    $documentoMovimientoArray = $this->prepareData((array) $grupoDeLaCarta, "insert");
    $query = $this->prepareQuery("insert", $documentoMovimientoArray);
    $params = $this->prepareParams($documentoMovimientoArray);

    return $this->executeQuery($query, $params, "insert");
  }

  public function actualizarDocumentoMovimiento($id, DocumentoMovimiento $grupoDeLaCarta)
  {
    $documentoMovimientoArray = $this->prepareData((array) $grupoDeLaCarta);
    $query = $this->prepareQuery("update", $documentoMovimientoArray);
    $params = $this->prepareParams($documentoMovimientoArray, "update", $id);

    return $this->executeQuery($query, $params, "update");
  }

  public function eliminarDocumentoMovimiento($id)
  {
    $query = $this->prepareQuery("delete");
    $params = $this->prepareParams(null, "delete", $id);

    return $this->executeQuery($query, $params, "delete");
  }
}
?>