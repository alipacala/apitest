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

  public function create()
  {
    $terapistaDelBody = $this->getBody();
    $terapista = $this->mapJsonToClass($terapistaDelBody, Terapista::class);

    $terapistasDb = new TerapistasDb();
    $id = $terapistasDb->crearTerapista($terapista);

    $response = $id ? [
      "mensaje" => "Terapista creada correctamente",
      "resultado" => array_merge([$terapistasDb->idName => intval($id)], (array) $terapistaDelBody)
    ] : ["mensaje" => "Error al crear la Terapista"];
    $code = $id ? 201 : 400;

    $this->sendResponse($response, $code);
  }

  public function update($id)
  {
    $terapistaDelBody = $this->getBody();
    $terapista = $this->mapJsonToClass($terapistaDelBody, Terapista::class);

    $terapistasDb = new TerapistasDb();

    $prevTerapista = $terapistasDb->obtenerTerapista($id);
    unset($prevTerapista->id_terapista);

    // comprobar que la terapista exista
    if (!$prevTerapista) {
      $this->sendResponse(["mensaje" => "Terapista no encontrada"], 404);
      return;
    }

    // si los datos son iguales, no se hace nada
    if ($prevTerapista == $terapista) {
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
  $controller->sendResponse([
    "mensaje" => $e->getMessage(),
    "archivo" => $e->getPrevious()?->getFile() ?? $e->getFile(),
    "linea" => $e->getPrevious()?->getLine() ?? $e->getLine(),
    "trace" => $e->getPrevious()?->getTrace() ?? $e->getTrace()
  ], 500);
}
?>