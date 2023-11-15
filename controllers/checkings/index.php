<?php
require_once __DIR__ . "/../../inc/bootstrap.php";
require_once PROJECT_ROOT_PATH . "/controllers/BaseController.php";

require_once PROJECT_ROOT_PATH . "/models/CheckingsDb.php";
require_once PROJECT_ROOT_PATH . "/models/PersonasDb.php";
require_once PROJECT_ROOT_PATH . "/models/ConfigDb.php";
require_once PROJECT_ROOT_PATH . "/models/AcompanantesDb.php";
require_once PROJECT_ROOT_PATH . "/models/DocumentosDetallesDb.php";
require_once PROJECT_ROOT_PATH . "/models/ComprobantesVentasDb.php";
require_once PROJECT_ROOT_PATH . "/models/ReservasDb.php";
require_once PROJECT_ROOT_PATH . "/models/ReservasHabitacionesDb.php";
require_once PROJECT_ROOT_PATH . "/models/RoomingDb.php";

class CheckingsController extends BaseController
{
  public function get()
  {
    $params = $this->getParams();
    $nroRegistroMaestro = $params['nro_registro_maestro'] ?? null;
    $nroHabitacion = $params['nro_habitacion'] ?? null;
    $idChecking = $params['id_checking'] ?? null;

    $cerrados = isset($params['cerrados']);
    $abiertos = isset($params['abiertos']);
    $conTipoPrecio = isset($params['con_tipo_precio']);

    $nroReserva = $params['nro_reserva'] ?? null;

    $checkingsDb = new CheckingsDb();

    if ($conTipoPrecio) {
      $result = $checkingsDb->buscarPorNroRegistroMaestroNroHabitacionIdChecking($nroRegistroMaestro, $idChecking, $nroHabitacion);
    }
    if ($nroRegistroMaestro) {
      $result = $checkingsDb->buscarPorNroRegistroMaestro($nroRegistroMaestro);
    }
    if ($cerrados) {
      $result = $checkingsDb->listarCerrados();
    }
    if ($abiertos) {
      $result = $checkingsDb->listarAbiertos();
    }
    if ($nroReserva) {
      $result = $checkingsDb->buscarPorNroReserva($nroReserva);
    }
    if (count($params) === 0) {
      $result = $checkingsDb->listarCheckings();
    }

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
    $checking = new Checking();
    $this->mapJsonToObj($checkingDelBody, $checking);

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
    if ($action == 'spa') {
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
        $titular = $personasDb->buscarPorNroDocumento($dni);

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

        $acompananteTemp = $acompanante;
        $acompanante = new Acompanante();
        $this->mapJsonToObj($acompananteTemp, $acompanante);

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

    } else if ($action == 'hotel') {

      $checkingDelBody = $this->getBody();
      $checking = new Checking();
      $this->mapJsonToObj($checkingDelBody, $checking);

      $codigo = "HT" . date("y");

      $configDb = new ConfigDb();
      $nroRegistroMaestro = $configDb->obtenerCodigo(11)['codigo'];
      $configDb->actualizarNumeroCorrelativo($codigo);

      // actualizar la reserva
      $reservasDb = new ReservasDb();
      $reservasDb->asignarNroRegistroMaestroPorNroReserva($checkingDelBody->nro_reserva, $nroRegistroMaestro);

      // consultar datos de la reserva
      $reserva = $reservasDb->buscarConPrecioPorNroReserva($checkingDelBody->nro_reserva)[0];

      // mapear los datos de la reserva al checking
      $checking->id_unidad_de_negocio = $reserva["id_unidad_de_negocio"];
      $checking->nro_registro_maestro = $nroRegistroMaestro;
      $checking->tipo_de_servicio = "HOTEL";
      $checking->nombre = $reserva["nombre"];
      $checking->lugar_procedencia = $reserva["lugar_procedencia"];
      $checking->id_modalidad = $reserva["id_modalidad"];
      $checking->nro_personas = $reserva["nro_personas"];
      $checking->fecha_in = $reserva["fecha_llegada"];
      $checking->hora_in = $reserva["hora_llegada"];
      $checking->fecha_out = $reserva["fecha_salida"];

      $precioUnitario = $reserva["precio_unitario"];

      // crear el checking
      $checkingsDb = new CheckingsDb();
      $idChecking = $checkingsDb->crearChecking($checking);

      // consultar la reserva con sus habitaciones
      $reservasHabitacionesDb = new ReservasHabitacionesDb();
      $reservasHabitaciones = $reservasHabitacionesDb->buscarReservaConHabitacionesPorNroHabitacion($checkingDelBody->nro_reserva);

      // crear los roomings
      $roomingDb = new RoomingDb();

      foreach ($reservasHabitaciones as $reservaHabitacion) {
        for ($fecha = clone new DateTime($reservaHabitacion["fecha_llegada"]); $fecha < new DateTime($reservaHabitacion["fecha_salida"]); $fecha->modify('+1 day')) {
          $rooming = new Rooming();

          $rooming->id_checkin = $idChecking;
          $rooming->nro_registro_maestro = $nroRegistroMaestro;
          $rooming->fecha = $fecha->format('Y-m-d');

          $rooming->tarifa = $precioUnitario;
          $rooming->estado = "NA";

          $rooming->nro_habitacion = $reservaHabitacion["nro_habitacion"];
          $rooming->id_producto = $reservaHabitacion["id_producto"];
          $rooming->hora = $reservaHabitacion["hora_llegada"];
          $rooming->nro_personas = $reservaHabitacion["nro_personas"];

          $roomingDb->crearRooming($rooming);

          $documentoDetalle = new DocumentoDetalle();
          $documentoDetalle->tipo_movimiento = "SA";
          $documentoDetalle->nro_registro_maestro = $nroRegistroMaestro;
          $documentoDetalle->fecha = $fecha->format('Y-m-d');
          $documentoDetalle->id_producto = $reservaHabitacion["id_producto"];
          $documentoDetalle->nivel_descargo = 1;
          $documentoDetalle->cantidad = 1;
          $documentoDetalle->tipo_de_unidad = "UND";
          $documentoDetalle->precio_unitario = $precioUnitario;
          $documentoDetalle->precio_total = $precioUnitario;
          // TODO: falta asignar el id_usuario
          $documentoDetalle->id_usuario = 12;
          $documentoDetalle->fecha_hora_registro = $roomingDb->obtenerFechaYHora()['fecha_y_hora'];
        }
      }

    } else if ($action == 'normal') {

      $body = $this->getBody();

      $checking = new Checking();
      $this->mapJsonToObj($body["checking"], $checking);

      $persona = new Persona();
      $this->mapJsonToObj($body["persona"], $checking);

      $acompanantes = $body["acompanantes"];
      $precioUnitario = $body["precio_unitario"];

      // buscar la persona por nro_documento
      $personasDb = new PersonasDb();
      $personaBuscada = $personasDb->buscarPorNroDocumento($persona->nro_documento);

      if ($personaBuscada) {
        // actualizar la persona
        $personasDb->actualizarPersona($personaBuscada->id_persona, $persona);
        $persona->id_persona = $personaBuscada->id_persona;
      } else {
        // crear la persona
        $persona->tipo_persona = 0;
        $persona->sexo = 'N';
        $persona->nacionalidad = 'NA';
        $persona->pais = 'NA';
        $persona->id_usuario_creacion = 12;

        $persona->fecha_creacion = $personasDb->obtenerFechaYHora()['fecha_y_hora'];

        $persona->id_persona = $personasDb->crearPersona($persona);
      }

      $configDb = new ConfigDb();
      $nroRegistroMaestro = $configDb->obtenerCodigo(11)['codigo'];

      // crear el checking
      $checkingsDb = new CheckingsDb();
      $checking->nro_registro_maestro = $nroRegistroMaestro;
      $idChecking = $checkingsDb->crearChecking($checking);

      // actualizar los roomings
      $roomingDb = new RoomingDb();
      $roomingDb->actualizarIdCheckingEnRoomings($checking->nro_registro_maestro, $checking->nro_habitacion, $idChecking);

      // crear los acompañantes
      $acompanantesDb = new AcompanantesDb();

      foreach ($acompanantes as $index => $acompananteTemp) {
        $acompanante = new Acompanante();
        $this->mapJsonToObj($acompananteTemp, $acompanante);

        $acompanante->nro_registro_maestro = $checking->nro_registro_maestro;
        $acompanante->tipo_de_servicio = $checking->tipo_de_servicio;
        $acompanante->nro_de_orden_unico = $index;
        $acompanante->nro_habitacion = $checking->nro_habitacion;

        if ($index == 0) {
          $acompanante->nro_documento = $persona->nro_documento;
          $acompanante->apellidos_y_nombres = $persona->apellidos . ", " . $persona->nombres;
        }

        $acompanantesDb->crearAcompanante($acompanante);
      }

      // crear los roomings
      for ($fecha = clone new DateTime($checking->fecha_in); $fecha < new DateTime($checking->fecha_out); $fecha->modify('+1 day')) {
        $rooming = new Rooming();

        $rooming->id_checkin = $idChecking;
        $rooming->nro_registro_maestro = $nroRegistroMaestro;
        $rooming->fecha = $fecha->format('Y-m-d');

        $rooming->tarifa = $precioUnitario;
        $rooming->estado = "NA";

        $rooming->nro_habitacion = $checking->nro_habitacion;
        // TODO: falta asignar el id_producto
        $rooming->hora = $checking->hora_in;
        $rooming->nro_personas = $checking->nro_personas;

        $roomingDb->crearRooming($rooming);

        // crear el detalle del documento
        $documentoDetalle = new DocumentoDetalle();

        $documentoDetalle->tipo_movimiento = "SA";
        $documentoDetalle->nro_registro_maestro = $nroRegistroMaestro;
        $documentoDetalle->fecha = $fecha->format('Y-m-d');
        // TODO: falta asignar el id_producto
        $documentoDetalle->nivel_descargo = 1;
        $documentoDetalle->cantidad = 1;
        $documentoDetalle->tipo_de_unidad = "UND";
        $documentoDetalle->precio_unitario = $precioUnitario;
        $documentoDetalle->precio_total = $precioUnitario;
        $documentoDetalle->fecha_hora_registro = $roomingDb->obtenerFechaYHora()['fecha_y_hora'];

        $documentosDetallesDb = new DocumentosDetallesDb();
        $documentosDetallesDb->crearDocumentoDetalle($documentoDetalle);
      }

      // incrementar el correlativo
      $configDb->incrementarCorrelativo(11);

    } else {
      $this->sendResponse(["mensaje" => "Acción no válida"], 404);
    }
  }

  public function update($id)
  {
    $checkingDelBody = $this->getBody();
    $checking = new Checking();
    $this->mapJsonToObj($checkingDelBody, $checking);

    $checkingsDb = new CheckingsDb();

    $prevChecking = $checkingsDb->obtenerChecking($id);
    unset($prevChecking->id_checkin);

    // comprobar que el checking exista
    if (!$prevChecking) {
      $this->sendResponse(["mensaje" => "Checking no encontrado"], 404);
      return;
    }

    // si los datos son iguales, no se hace nada
    if ($this->compararObjetoActualizar($checking, $prevChecking)) {
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
    if ($action == 'cerrar') {
      $checkingsDb = new CheckingsDb();
      $checking = $checkingsDb->obtenerChecking($id);

      $documentosDetallesDb = new DocumentosDetallesDb();
      $detalles = $documentosDetallesDb->buscarPorNroRegistroMaestro($checking->nro_registro_maestro);

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

    } else if ($action == 'normal') {

      $body = $this->getBody();

      $checking = new Checking();
      $this->mapJsonToObj($body->checking, $checking);

      $persona = new Persona();
      $this->mapJsonToObj($body->persona, $persona);

      $acompanantes = $body->acompanantes;
      $precioUnitario = $body->precio_unitario;

      // buscar roomings por id_checking
      $roomingDb = new RoomingDb();

      $roomings = $roomingDb->buscarVariosPorIdChecking($checking->id_checkin, $checking->nro_habitacion);

      // borrar los roomings de la db de los cuales la fecha no esté en el rango de fechas del checking
      foreach ($roomings as $rooming) {
        $fechaRooming = new DateTime($rooming->fecha);
        if ($fechaRooming < new DateTime($checking->fecha_in) || $fechaRooming >= new DateTime($checking->fecha_out)) {
          $roomingDb->eliminarRooming($rooming->id_rooming);

          // elimninar el documento detalle
          $documentosDetallesDb = new DocumentosDetallesDb();
          $documentosDetallesDb->eliminarPorNroRegistroMaestroNroHabYFecha($rooming->nro_registro_maestro, $rooming->nro_habitacion, $rooming->fecha);
        }
      }

      // buscar la persona por nro_documento
      $personasDb = new PersonasDb();
      $personaBuscada = $personasDb->buscarPorNroDocumento($persona->nro_documento);

      if ($personaBuscada) {
        // actualizar la persona
        unset($persona->id_persona);
        $personasDb->actualizarPersona($personaBuscada->id_persona, $persona);
        $persona->id_persona = $personaBuscada->id_persona;
      } else {
        // crear la persona
        $persona->tipo_persona = 0;
        $persona->sexo = 'N';
        $persona->nacionalidad = 'NA';
        $persona->pais = 'NA';
        $persona->id_usuario_creacion = 12;

        $persona->fecha_creacion = $personasDb->obtenerFechaYHora()['fecha_y_hora'];

        $persona->id_persona = $personasDb->crearPersona($persona);
      }

      // actualizar el checking
      $checkingsDb = new CheckingsDb();

      // obtener el checkin anterior
      $prevChecking = $checkingsDb->obtenerChecking($id);

      // si las fechas in out del checkin anterior están fuera de las fechas in out del checking, se reemplazan en el objeto
      $checking->fecha_in = $prevChecking->fecha_in < $checking->fecha_in ? $prevChecking->fecha_in : $checking->fecha_in;
      $checking->fecha_out = $prevChecking->fecha_out > $checking->fecha_out ? $prevChecking->fecha_out : $checking->fecha_out;

      $checking->lugar_procedencia = $persona->ciudad;
      // TODO: falta el tipo_transporte

      $checking->tipo_de_servicio = "HOTEL";
      $checking->id_persona = $persona->id_persona;
      $checking->nombre = $persona->apellidos . ", " . $persona->nombres;

      $checking->nro_adultos = $checking->nro_adultos ?? 0;
      $checking->nro_ninos = $checking->nro_ninos ?? 0;
      $checking->nro_infantes = $checking->nro_infantes ?? 0;
      $checking->nro_personas = $checking->nro_adultos + $checking->nro_ninos + $checking->nro_infantes;
      unset($checking->id_checkin);

      $checkingsDb->actualizarChecking($id, $checking);

      // actualizar los roomings
      $roomingDb = new RoomingDb();
      $roomingDb->actualizarIdCheckingEnRoomings($checking->nro_registro_maestro, $checking->nro_habitacion, $id);

      // actualizar el acompañante titular
      $acompanantesDb = new AcompanantesDb();
      $prevAcompananteTitular = $acompanantesDb->buscarTitularPorNroRegistroMaestro($checking->nro_registro_maestro);

      $acompananteTitular = new Acompanante();

      $acompananteTitular->nro_registro_maestro = $checking->nro_registro_maestro;
      $acompananteTitular->tipo_de_servicio = $checking->tipo_de_servicio;
      $acompananteTitular->nro_de_orden_unico = 0;
      $acompananteTitular->nro_documento = $persona->nro_documento;
      $acompananteTitular->apellidos_y_nombres = $persona->apellidos . ", " . $persona->nombres;
      $acompananteTitular->sexo = $persona->sexo;
      $acompananteTitular->edad = $persona->edad;

      $acompanantesDb->actualizarAcompanante($prevAcompananteTitular->id_acompanante, $acompananteTitular);

      // obtener el último nro_de_orden_unico de los acompañantes
      $ultimoNro = array_reduce($acompanantes, function ($max, $acompanante) {
        return $acompanante->nro_de_orden_unico > $max ? $acompanante->nro_de_orden_unico : $max;
      }, 0);

      // crear los acompañantes
      foreach ($acompanantes as $index => $acompananteTemp) {
        if ($acompananteTemp->id_acompanante) {
          continue;
        }

        $acompanante = new Acompanante();
        $this->mapJsonToObj($acompananteTemp, $acompanante);

        $acompanante->nro_registro_maestro = $checking->nro_registro_maestro;
        $acompanante->tipo_de_servicio = "HOTEL";
        $acompanante->nro_de_orden_unico = $ultimoNro + $index + 1;
        $acompanante->nro_habitacion = $checking->nro_habitacion;

        $acompanantesDb->crearAcompanante($acompanante);
      }

      // crear los roomings que estén en el rango de fechas del checking y que las fechas
      for ($fecha = clone new DateTime($checking->fecha_in); $fecha < new DateTime($checking->fecha_out); $fecha->modify('+1 day')) {

        // si la fecha está en los roomings, no se hace nada
        if (
          array_filter($roomings, function ($rooming) use ($fecha) {
            return $rooming->fecha == $fecha->format('Y-m-d');
          })
        ) {
          continue;
        }

        // obtener el id_producto del primer rooming en roomings
        $roomingAnterior = $roomings[0];
        $idProducto = $roomingAnterior->id_producto;

        $rooming = new Rooming();

        $rooming->id_checkin = $id;
        $rooming->nro_registro_maestro = $checking->nro_registro_maestro;
        $rooming->fecha = $fecha->format('Y-m-d');

        $rooming->tarifa = $precioUnitario;
        $rooming->estado = "NA";

        $rooming->nro_habitacion = $checking->nro_habitacion;
        $rooming->id_producto = $idProducto;
        $rooming->hora = $checking->hora_in;
        $rooming->nro_personas = $checking->nro_personas;

        $roomingDb->crearRooming($rooming);

        // crear el detalle del documento
        $documentoDetalle = new DocumentoDetalle();

        $documentoDetalle->tipo_movimiento = "SA";
        $documentoDetalle->nro_registro_maestro = $checking->nro_registro_maestro;
        $documentoDetalle->fecha = $fecha->format('Y-m-d');
        $documentoDetalle->id_producto = $idProducto;
        $documentoDetalle->nivel_descargo = 1;
        $documentoDetalle->cantidad = 1;
        $documentoDetalle->tipo_de_unidad = "UND";
        $documentoDetalle->precio_unitario = $precioUnitario;
        $documentoDetalle->precio_total = $precioUnitario;

        $documentoDetalle->nro_habitacion = $checking->nro_habitacion;
        $documentoDetalle->fecha_servicio = $fecha->format('Y-m-d');
        $documentoDetalle->id_usuario = 12;

        $documentoDetalle->fecha_hora_registro = $roomingDb->obtenerFechaYHora()['fecha_y_hora'];

        $documentosDetallesDb = new DocumentosDetallesDb();
        $documentosDetallesDb->crearDocumentoDetalle($documentoDetalle);
      }

      $this->sendResponse(["mensaje" => "Checking actualizado correctamente"], 200);

    } else if ($action == 'habitacion') {

      $body = $this->getBody();
      $nroHabitacion = $body->nro_habitacion;
      $prevNroHabitacion = $body->prev_nro_habitacion;
      $fecha = $body->fecha;

      $checkingsDb = new CheckingsDb();
      $checking = $checkingsDb->obtenerChecking($id);
      if ($checking) {
        $roomingDb = new RoomingDb();
        $roomingDb->cambiarNroHabitacion($checking->nro_registro_maestro, $prevNroHabitacion, $nroHabitacion, $fecha);

        // actualizar cerrada de habitación
        $roomingDb = new RoomingDb();
        $roomingDb->cambiarRoomings($checking->nro_registro_maestro, $prevNroHabitacion, $fecha);

        // actualizar los documentos detalles
        $documentosDetallesDb = new DocumentosDetallesDb();
        $documentosDetallesDb->cambiarNroHabitacion($checking->nro_registro_maestro, $prevNroHabitacion, $nroHabitacion, $fecha);

        $this->sendResponse(["mensaje" => "Nro de habitación actualizada correctamente"], 200);
      } else {
        $this->sendResponse(["mensaje" => "Checking no encontrado"], 404);
      }

    } else if ($action == "checkout") {

      $body = $this->getBody();
      $nroHabitacion = $body->nro_habitacion;

      $roomingDb = new RoomingDb();
      $roomingDb->checkout($id, $nroHabitacion);

      $this->sendResponse(["mensaje" => "Checkout realizado correctamente"], 200);

    } else {
      $this->sendResponse(["mensaje" => "Acción no válida"], 404);
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
  $controller->sendResponse($controller->errorResponse($e), 500);
}
?>