<?php
require_once __DIR__ . "/../../inc/bootstrap.php";
require_once PROJECT_ROOT_PATH . "/controllers/BaseController.php";

require_once PROJECT_ROOT_PATH . "/models/TerapistasDb.php";
require_once PROJECT_ROOT_PATH . "/models/TerapistasHabilidadesDb.php";
require_once PROJECT_ROOT_PATH . "/models/HabilidadesProfesionalesDb.php";

class TerapistasController extends BaseController
{
  public function get()
  {
    $params = $this->getParams();
    $conHabilidades = isset($params['con_habilidades']);
    $nroDocumento = $params['nro_documento'] ?? null;

    $terapistasDb = new TerapistasDb();

    $result = $terapistasDb->listarTerapistas($conHabilidades);

    // si se pide que se incluyan las habilidades, se hace el mapeo para agregar las habilidades como propiedad de cada terapista
    if ($conHabilidades) {
      $result = array_reduce($result, function ($carry, $terapista) {
        $idProfesional = $terapista['id_profesional'];

        if (!isset($carry[$idProfesional])) {
          $carry[$idProfesional] = [
            'id_profesional' => $idProfesional,
            'nombre' => "$terapista[apellidos], $terapista[nombres]",
            'sexo' => $terapista['sexo'],
            'habilidades' => [],
          ];
        }

        $carry[$idProfesional]['habilidades'][] = [
          'id_habilidad' => $terapista['id_habilidad'],
          'codigo_habilidad' => $terapista['codigo_habilidad'],
          'descripcion' => $terapista['descripcion'],
        ];

        return $carry;
      }, []);

      $result = array_values($result);
    }

    if ($nroDocumento) {
      $result = $terapistasDb->buscarPorNroDocumento($nroDocumento);

      if (!$result) {
        $this->sendResponse(["mensaje" => "No se encontraron personas naturales o jurídicas con el código proporcionado."], 404);
        return;
      }
    }

    $this->sendResponse($result, 200);
  }

  public function getOne($id)
  {
    $terapistasDb = new TerapistasDb();
    $terapista = $terapistasDb->obtenerTerapista($id);

    $response = $terapista ? $terapista : ["mensaje" => "Terapista no encontrada"];
    $code = $terapista ? 200 : 404;

    $this->sendResponse($response, $code);
  }

  public function obtenerEdadSegunFecha($fechaNacimiento)
  {
    $nacimiento = new DateTime($fechaNacimiento);
    $ahora = new DateTime(date("Y-m-d"));
    $diferencia = $ahora->diff($nacimiento);
    return intval($diferencia->format("%y"));
  }

  public function create()
  {
    $terapistaDelBody = $this->getBody();

    $habilidades = $terapistaDelBody['habilidades'];
    unset($terapistaDelBody['habilidades']);

    $terapista = new Terapista();
    $this->mapJsonToObj($terapistaDelBody, $terapista);

    $terapista->tipo_documento = 0;
    $terapista->fecha_ingreso = date("Y-m-d");
    $terapista->baja = 0;
    $terapista->fecha_de_baja = null;

    $persona = new Persona();

    $persona->tipo_persona = "NATU";
    $persona->fecha = date("Y-m-d");
    $persona->id_usuario_creacion = 0; // TODO
    $persona->nacionalidad = "PER";
    $persona->pais = "PER";
    $persona->email = $terapista->Email;
    $persona->ciudad = $terapista->provincia;
    $persona->ocupacion = $terapista->cargo;
    $persona->fecha_creacion = date("Y-m-d H:i:s");
    $persona->edad = $this->obtenerEdadSegunFecha($terapista->fecha_de_nacimiento);

    $terapistasDb = new TerapistasDb();
    $personasDb = new PersonasDb();
    $terapistasHabilidadesDb = new TerapistasHabilidadesDb();

    try {
      $terapistasDb->empezarTransaccion();
      
      $idPersona = $personasDb->crearPersona($persona);

      $terapista->id_persona = $idPersona;
      $idTerapista = $terapistasDb->crearTerapista($terapista);

      foreach($habilidades as $habilidad) {
        $terapistaHabilidad = new TerapistaHabilidad();
        $terapistaHabilidad->id_persona = $idPersona;
        $terapistaHabilidad->id_habilidad = $habilidad['id_habilidad'];

        $terapistasHabilidadesDb->crearTerapistaHabilidad($terapistaHabilidad);
      }

      $terapistasDb->terminarTransaccion();
    }
    catch (Exception $e) {
      $terapistasDb->cancelarTransaccion();
      $newException = new Exception("Error al crear el terapista, la persona o sus habilidades", 0, $e);
      throw $newException;
    }

    $seHaCreado = $idPersona && $idTerapista;
    $response = $seHaCreado ? [
      "mensaje" => "Terapista creada correctamente",
      "resultado" => array_merge([$terapistasDb->idName => intval($idTerapista)], (array) $terapistaDelBody)
    ] : ["mensaje" => "Error al crear la Terapista"];
    $code = $seHaCreado ? 201 : 400;

    $this->sendResponse($response, $code);
  }

  public function update($id)
  {
    $terapistaDelBody = $this->getBody();
    $terapista = new Terapista();
    $this->mapJsonToObj($terapistaDelBody, $terapista);

    $terapistasDb = new TerapistasDb();

    $prevTerapista = $terapistasDb->obtenerTerapista($id);
    unset($prevTerapista->id_terapista);

    // comprobar que la terapista exista
    if (!$prevTerapista) {
      $this->sendResponse(["mensaje" => "Terapista no encontrada"], 404);
      return;
    }

    // si los datos son iguales, no se hace nada
    if ($this->compararObjetoActualizar($terapista, $prevTerapista)) {
      $this->sendResponse(["mensaje" => "No se realizaron cambios"], 200);
      return;
    }

    $result = $terapistasDb->actualizarTerapista($id, $terapista);

    $response = $result ? [
      "mensaje" => "Terapista actualizada correctamente",
      "resultado" => $terapistasDb->obtenerTerapista($id)
    ] : ["mensaje" => "Error al actualizar la Terapista"];
    $code = $result ? 200 : 400;

    $this->sendResponse($response, $code);
  }

  public function delete($id)
  {
    $terapistasDb = new TerapistasDb();
    $prevTerapista = $terapistasDb->obtenerTerapista($id);

    // comprobar que la terapista exista
    if (!$prevTerapista) {
      $this->sendResponse(["mensaje" => "Terapista no encontrada"], 404);
      return;
    }

    $result = $terapistasDb->eliminarTerapista($id);

    $response = $result ? [
      "mensaje" => "Terapista eliminada correctamente",
      "resultado" => $prevTerapista
    ] : ["mensaje" => "Error al eliminar la Terapista"];
    $code = $result ? 200 : 400;

    $this->sendResponse($response, $code);
  }
}

try {
  $controller = new TerapistasController();
  $controller->route();
} catch (Exception $e) {
  $controller->sendResponse($controller->errorResponse($e), 500);
}
?>