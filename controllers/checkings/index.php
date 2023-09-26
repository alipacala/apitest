<?php
require_once __DIR__ . "/../../inc/bootstrap.php";
require_once PROJECT_ROOT_PATH . "/controllers/BaseController.php";

require_once PROJECT_ROOT_PATH . "/models/CheckingsDb.php";
require_once PROJECT_ROOT_PATH . "/models/PersonasDb.php";
require_once PROJECT_ROOT_PATH . "/models/ConfigDb.php";
require_once PROJECT_ROOT_PATH . "/models/AcompanantesDb.php";
require_once PROJECT_ROOT_PATH . "/models/DocumentosDetallesDb.php";
require_once PROJECT_ROOT_PATH . "/models/ComprobantesVentasDb.php";

class CheckingsController extends BaseController
{
  public function get()
  {
    $params = $this->getParams();
    $nroRegistroMaestro = $params['nro_registro_maestro'] ?? null;
    $cerrados = $params['cerrados'] ?? null;
    $abiertos = $params['abiertos'] ?? null;

    $checkingsDb = new CheckingsDb();
    $result = $checkingsDb->listarCheckings($nroRegistroMaestro, $cerrados, $abiertos);

    $this->sendResponse($result, 200);
  }

  public function getOne($id)
  {
    $checkingsDb = new CheckingsDb();
    $checking = $checkingsDb->obtenerChecking($id);

    $response = $checking ? $checking : ["mensaje" => "Checking no encontrado"];
    $code = $checking ? 200 : 404;

    $this->sendResponse($response, $code);
  }

  public function create()
  {
    $checkingDelBody = $this->getBody();
    $checking = $this->mapJsonToClass($checkingDelBody, Checking::class);

    $checkingsDb = new CheckingsDb();
    $id = $checkingsDb->crearChecking($checking);

    $response = $id ? [
      "mensaje" => "Checking creado correctamente",
      "resultado" => array_merge([$checkingsDb->idName => intval($id)], (array) $checkingDelBody)
    ] : ["mensaje" => "Error al crear el Checking"];
    $code = $id ? 201 : 400;

    $this->sendResponse($response, $code);
  }

  public function createCustom($action)
  {
    switch ($action) {
      case 'spa':
        $checkingDelBody = $this->getBody();

        $personasDb = new PersonasDb();

        $titularDelBody = $checkingDelBody->titular;
        $nuevaPersona = $checkingDelBody->titular->es_nuevo;

        unset($titularDelBody->es_nuevo);

        if ($nuevaPersona) {

          $camposRequeridos = ["nro_documento", "apellidos_y_nombres", "sexo", "edad"];
          $camposFaltantes = $this->comprobarCamposRequeridos($camposRequeridos, $titularDelBody);

          if (count($camposFaltantes) > 0) {
            $this->sendResponse(["mensaje" => "Faltan los siguientes campos en el titular: " . implode(", ", $camposFaltantes)], 400);
            return;
          }

          $titular = new Persona();
          $titular->tipo_persona = "NATU";
          $titular->tipo_documento = "0";
          $titular->nro_documento = $titularDelBody->nro_documento;

          // buscar la última coma
          $posicionUltimaComa = strrpos($titularDelBody->apellidos_y_nombres, ",");

          if ($posicionUltimaComa !== false) {
            $apellidos = trim(substr($titularDelBody->apellidos_y_nombres, 0, $posicionUltimaComa));
            $nombres = trim(substr($titularDelBody->apellidos_y_nombres, $posicionUltimaComa + 1));
          } else {
            // buscar el último espacio en blanco
            $posicionUltimoEspacio = strrpos($titularDelBody->apellidos_y_nombres, " ");
            if ($posicionUltimoEspacio !== false) {
              $apellidos = trim(substr($titularDelBody->apellidos_y_nombres, 0, $posicionUltimoEspacio));
              $nombres = trim(substr($titularDelBody->apellidos_y_nombres, $posicionUltimoEspacio + 1));
            } else {
              $apellidos = $titularDelBody->apellidos_y_nombres;
              $nombres = "";
            }
          }

          $titular->apellidos = $apellidos;
          $titular->nombres = $nombres;
                    
          $titular->sexo = $titularDelBody->sexo;
          $titular->edad = $titularDelBody->edad;
          $titular->fecha_creacion = $personasDb->obtenerFechaYHora()['fecha_y_hora'];

          $idTitular = $personasDb->crearPersona($titular);
          $titular->id_persona = $idTitular;

        } else {

          $camposRequeridos = ["nro_documento"];
          $camposFaltantes = $this->comprobarCamposRequeridos($camposRequeridos, $titularDelBody);

          if (count($camposFaltantes) > 0) {
            $this->sendResponse(["mensaje" => "Faltan los siguientes campos en el titular: " . implode(", ", $camposFaltantes)], 400);
            return;
          }

          $dni = $titularDelBody->nro_documento;
          $titular = $personasDb->listarPersonas($dni)[0];

          $edad = $titularDelBody->edad ?? null;

          if ($edad && $titular->edad != $edad) {
            $personasDb->actualizarEdadPersona($titular->id_persona, $edad);
          }
          $idTitular = $titular->id_persona;
        }

        $titularCreado = $personasDb->obtenerPersona($idTitular);

        $checking = new Checking();

        $configDb = new ConfigDb();
        $checking->nro_registro_maestro = $configDb->obtenerCodigo(4)['codigo'];

        $checking->id_unidad_de_negocio = 3;
        $checking->tipo_de_servicio = "SPA";
        $checking->nombre = $titular->apellidos . ", " . $titular->nombres;
        $checking->id_persona = $idTitular;
        $checking->fecha_in = $personasDb->obtenerFechaYHora()['fecha'];
        $checking->hora_in = $personasDb->obtenerFechaYHora()['hora'];

        $checking->nro_personas = count($checkingDelBody->acompanantes) + 1;
        $checking->nro_adultos = 1;
        $checking->nro_ninos = 0;
        $checking->nro_infantes = 0;

        foreach ($checkingDelBody->acompanantes as $acompanante) {
          if ($acompanante->edad < 3) {
            $checking->nro_infantes++;
          } else if ($acompanante->edad < 12) {
            $checking->nro_ninos++;
          } else {
            $checking->nro_adultos++;
          }
        }

        $checkingsDb = new CheckingsDb();
        $idChecking = $checkingsDb->crearChecking($checking);

        $configDb->incrementarCorrelativo(4);

        $checkingCreado = $checkingsDb->obtenerChecking($idChecking);

        $acompanantesDb = new AcompanantesDb();

        // crear el acompañante titular
        $acompananteTitular = new Acompanante();
        $acompananteTitular->nro_registro_maestro = $checking->nro_registro_maestro;
        $acompananteTitular->tipo_de_servicio = $checking->tipo_de_servicio;
        $acompananteTitular->nro_de_orden_unico = 0;
        $acompananteTitular->nro_documento = $titularCreado->nro_documento;
        $acompananteTitular->apellidos_y_nombres = $titularCreado->apellidos . ", " . $titularCreado->nombres;
        $acompananteTitular->sexo = $titularCreado->sexo;
        $acompananteTitular->edad = $titularCreado->edad;

        $idAcompananteTitular = $acompanantesDb->crearAcompanante($acompananteTitular);

        $acompanantesCreados = [];
        $acompanantesCreados[] = $acompanantesDb->obtenerAcompanante($idAcompananteTitular);

        $acompanantes = $checkingDelBody->acompanantes;

        foreach ($acompanantes as $index => $acompanante) {

          $acompanante = $this->mapJsonToClass($acompanante, Acompanante::class);

          $camposRequeridos = ["apellidos_y_nombres", "sexo", "edad", "parentesco"];
          $camposFaltantes = $this->comprobarCamposRequeridos($camposRequeridos, $acompanante);

          if (count($camposFaltantes) > 0) {
            $this->sendResponse(["mensaje" => "Faltan los siguientes campos en un acompañante: " . implode(", ", $camposFaltantes)], 400);
            return;
          }

          $acompanante->nro_registro_maestro = $checking->nro_registro_maestro;
          $acompanante->tipo_de_servicio = $checking->tipo_de_servicio;
          $acompanante->nro_de_orden_unico = $index + 1;

          $idAcompanante = $acompanantesDb->crearAcompanante($acompanante);

          $acompananteCreado = $acompanantesDb->obtenerAcompanante($idAcompanante);
          $acompanantesCreados[] = $acompananteCreado;
        }

        $checkingYAcompanantesCreados = $checkingCreado && $titularCreado && count($acompanantesCreados) == count($acompanantes) + 1;

        $response = $checkingYAcompanantesCreados ? [
          "mensaje" => "Checking creado correctamente",
          "resultado" => array_merge((array) $checkingCreado, ["titular" => $titularCreado], ["acompanantes" => $acompanantesCreados])
        ] : ["mensaje" => "Error al crear el Checking"];
        $code = $checkingYAcompanantesCreados ? 201 : 400;

        $this->sendResponse($response, $code);

        break;
      default:
        $this->sendResponse(["mensaje" => "Acción no válida"], 404);
        break;
    }
  }

  public function update($id)
  {
    $checkingDelBody = $this->getBody();
    $checking = $this->mapJsonToClass($checkingDelBody, Checking::class);

    $checkingsDb = new CheckingsDb();

    $prevChecking = $checkingsDb->obtenerChecking($id);
    unset($prevChecking->id_checkin);

    // comprobar que el checking exista
    if (!$prevChecking) {
      $this->sendResponse(["mensaje" => "Checking no encontrado"], 404);
      return;
    }

    // si los datos son iguales, no se hace nada
    if ($prevChecking == $checking) {
      $this->sendResponse(["mensaje" => "No se realizaron cambios"], 200);
      return;
    }

    $result = $checkingsDb->actualizarChecking($id, $checking);

    $response = $result ? [
      "mensaje" => "Checking actualizado correctamente",
      "resultado" => $checkingsDb->obtenerChecking($id)
    ] : ["mensaje" => "Error al actualizar el Checking"];
    $code = $result ? 200 : 400;

    $this->sendResponse($response, $code);
  }

  public function updatePartial($id, $action = null)
  {
    switch ($action) {
      case 'cerrar':
        $checkingsDb = new CheckingsDb();
        $checking = $checkingsDb->obtenerChecking($id);

        $documentosDetallesDb = new DocumentosDetallesDb();
        $detalles = $documentosDetallesDb->listarDocumentosDetalles($checking->nro_registro_maestro);

        // filtrar los que tengan nivel_descargo = 1
        $detalles = array_filter($detalles, function ($detalle) {
          return $detalle->nivel_descargo == 1;
        });

        // comprobar que todos los detalles tengan nro_comprobante
        foreach ($detalles as $detalle) {
          if (!$detalle->nro_comprobante) {
            $this->sendResponse(["mensaje" => "No se puede cerrar el checking porque hay detalles sin nro_comprobante"], 400);
            return;
          }
        }

        // comprobar que los comprobantes tengan por_pagar = 0
        $comprobantesVentasDb = new ComprobantesVentasDb();
        $comprobantes = $comprobantesVentasDb->listarComprobantesVentas($checking->nro_registro_maestro);

        // filtrar anulados
        $comprobantes = array_filter($comprobantes, function ($comprobante) {
          return $comprobante["estado"] == 1;
        });

        foreach ($comprobantes as $comprobante) {
          if (floatval($comprobante["por_pagar"]) > 0) {
            $this->sendResponse(["mensaje" => "No se puede cerrar el checking porque hay comprobantes por pagar"], 400);
            return;
          }
        }

        // actualizar el checking con el campo cerrado
        $checkingAActualizar = new Checking();
        $checkingAActualizar->cerrada = 1;
        $checkingAActualizar->fecha_cerrada = $checkingsDb->obtenerFechaYHora()['fecha'];
        $checkingAActualizar->hora_cerrada = $checkingsDb->obtenerFechaYHora()['hora'];

        $result = $checkingsDb->actualizarChecking($id, $checkingAActualizar);

        $response = $result ? [
          "mensaje" => "Checking cerrado correctamente",
          "resultado" => $checkingsDb->obtenerChecking($id)
        ] : ["mensaje" => "Error al cerrar el Checking"];
        $code = $result ? 200 : 400;

        $this->sendResponse($response, $code);
        break;
      default:
        $this->sendResponse(["mensaje" => "Acción no válida"], 404);
        break;
    }
  }

  public function delete($id)
  {
    $checkingsDb = new CheckingsDb();
    $prevChecking = $checkingsDb->obtenerChecking($id);

    // comprobar que el checking exista
    if (!$prevChecking) {
      $this->sendResponse(["mensaje" => "Checking no encontrado"], 404);
      return;
    }

    $result = $checkingsDb->eliminarChecking($id);

    $response = $result ? [
      "mensaje" => "Checking eliminado correctamente",
      "resultado" => $prevChecking
    ] : ["mensaje" => "Error al eliminar el Checking"];
    $code = $result ? 200 : 400;

    $this->sendResponse($response, $code);
  }
}

try {
  $controller = new CheckingsController();
  $controller->route();
} catch (Exception $e) {
  $controller->sendResponse([
    "mensaje" => $e->getMessage(),
    "archivo" => $e->getPrevious()?->getFile() ?? $e->getFile(),
    "linea" => $e->getPrevious()?->getLine() ?? $e->getLine(),
    "trace" => $e->getPrevious()?->getTrace() ?? $e->getTrace()
  ], 500);
}
?>