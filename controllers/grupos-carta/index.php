<?php
require_once __DIR__ . "/../../inc/bootstrap.php";
require_once PROJECT_ROOT_PATH . "/controllers/BaseController.php";

require_once PROJECT_ROOT_PATH . "/models/GruposDeLaCartaDb.php";
require_once PROJECT_ROOT_PATH . "/models/ConfigDb.php";

class GruposDeLaCartaController extends BaseController
{
  public function get()
  {
    $gruposDeLaCartaDb = new GruposDeLaCartaDb();
    $result = $gruposDeLaCartaDb->listarGruposDeLaCarta();

    $this->sendResponse($result, 200);
  }

  public function getOne($id)
  {
    $gruposDeLaCartaDb = new GruposDeLaCartaDb();
    $grupoDeLaCarta = $gruposDeLaCartaDb->obtenerGrupoDeLaCarta($id);

    $response = $grupoDeLaCarta ? $grupoDeLaCarta : ["mensaje" => "Grupo de la Carta no encontrado"];
    $code = $grupoDeLaCarta ? 200 : 404;

    $this->sendResponse($response, $code);
  }

  public function create()
  {
    $grupoDeLaCartaDelBody = $this->getBody();
    $grupoDeLaCarta = $this->mapJsonToClass($grupoDeLaCartaDelBody, GrupoDeLaCarta::class);

    // comprobar que el grupo de la carta tenga nombre
    if (!$grupoDeLaCarta->nombre_grupo) {
      $this->sendResponse(["mensaje" => "Falta el nombre del Grupo de la Carta"], 400);
      return;
    }

    // obtener el correlativo del grupo de la carta en config
    $configDb = new ConfigDb();
    $correlativo = $configDb->obtenerConfig(12)->numero_correlativo; // 12 = correlativo de grupos de la carta

    $grupoDeLaCarta->codigo_subgrupo = str_pad($correlativo, 3, "0", STR_PAD_LEFT);
    $grupoDeLaCarta->nro_orden = 1;

    // si no hay codigo_grupo, entonces el codigo_grupo es el correlativo
    if (!isset($grupoDeLaCarta->codigo_grupo)) {
      $grupoDeLaCarta->codigo_grupo = $grupoDeLaCarta->codigo_subgrupo;
    }

    $gruposDeLaCartaDb = new GruposDeLaCartaDb();
    $id = $gruposDeLaCartaDb->crearGrupoDeLaCarta($grupoDeLaCarta);

    // actualizar el correlativo del grupo de la carta en config
    $configDb->incrementarCorrelativo(12);

    $response = $id ? [
      "mensaje" => "Grupo de la Carta creado correctamente",
      "resultado" => array_merge([$gruposDeLaCartaDb->idName => intval($id)], (array) $grupoDeLaCartaDelBody)
    ] : ["mensaje" => "Error al crear el Grupo de la Carta"];
    $code = $id ? 201 : 400;

    $this->sendResponse($response, $code);
  }

  public function update($id)
  {
    $grupoDeLaCartaDelBody = $this->getBody();
    $grupoDeLaCarta = $this->mapJsonToClass($grupoDeLaCartaDelBody, GrupoDeLaCarta::class);

    $gruposDeLaCartaDb = new GruposDeLaCartaDb();

    $prevGrupoDeLaCarta = $gruposDeLaCartaDb->obtenerGrupoDeLaCarta($id);
    unset($prevGrupoDeLaCarta->id_grupo);

    // comprobar que el grupo de la carta exista
    if (!$prevGrupoDeLaCarta) {
      $this->sendResponse(["mensaje" => "Grupo de la Carta no encontrado"], 404);
      return;
    }

    // si los datos son iguales, no se hace nada
    if ($this->compararObjetoActualizar($grupoDeLaCarta, $prevGrupoDeLaCarta)) {
      $this->sendResponse(["mensaje" => "No se realizaron cambios"], 200);
      return;
    }

    // si se ha enviado solo el nombre, se actualiza el nombre y el codigo_grupo a un nuevo correlativo
    if (isset($grupoDeLaCarta->nombre_grupo) && !isset($grupoDeLaCarta->codigo_grupo)) {
      // obtener el correlativo del grupo de la carta en config
      $configDb = new ConfigDb();
      $correlativo = $configDb->obtenerConfig(12)->numero_correlativo; // 12 = correlativo de grupos de la carta

      $grupoDeLaCarta->codigo_grupo = str_pad($correlativo, 3, "0", STR_PAD_LEFT);
      $grupoDeLaCarta->codigo_subgrupo = $grupoDeLaCarta->codigo_grupo;

      // actualizar el correlativo del grupo de la carta en config
      $configDb->incrementarCorrelativo(12);
    }

    $result = $gruposDeLaCartaDb->actualizarGrupoDeLaCarta($id, $grupoDeLaCarta);

    $response = $result ? [
      "mensaje" => "Grupo de la Carta actualizado correctamente",
      "resultado" => $gruposDeLaCartaDb->obtenerGrupoDeLaCarta($id)
    ] : ["mensaje" => "Error al actualizar el Grupo de la Carta"];
    $code = $result ? 200 : 400;

    $this->sendResponse($response, $code);
  }

  public function delete($id)
  {
    $gruposDeLaCartaDb = new GruposDeLaCartaDb();
    $prevGrupoDeLaCarta = $gruposDeLaCartaDb->obtenerGrupoDeLaCarta($id);

    if ($prevGrupoDeLaCarta->codigo_grupo == $prevGrupoDeLaCarta->codigo_subgrupo) {
      $gruposDeLaCarta = $gruposDeLaCartaDb->listarGruposDeLaCarta();
      $gruposDeLaCarta = array_filter($gruposDeLaCarta, function ($grupoDeLaCarta) use ($prevGrupoDeLaCarta) {
        return $grupoDeLaCarta->codigo_grupo == $prevGrupoDeLaCarta->codigo_grupo && $grupoDeLaCarta->codigo_subgrupo != $prevGrupoDeLaCarta->codigo_subgrupo;
      });

      if (count($gruposDeLaCarta) > 0) {
        $this->sendResponse(["mensaje" => "No se puede eliminar el Grupo de la Carta porque tiene subgrupos"], 400);
        return;
      }
    }

    // comprobar que el grupo de la carta exista
    if (!$prevGrupoDeLaCarta) {
      $this->sendResponse(["mensaje" => "Grupo de la Carta no encontrado"], 404);
      return;
    }

    try {
      $result = $gruposDeLaCartaDb->eliminarGrupoDeLaCarta($id);
    } catch (PDOException $e) {
      if (str_contains($e->getMessage(), "23000")) {
        $this->sendResponse(["mensaje" => "No se puede eliminar el Grupo de la Carta porque tiene productos asociados"], 400);
        return;
      }
    }

    $response = $result ? [
      "mensaje" => "Grupo de la Carta eliminado correctamente",
      "resultado" => $prevGrupoDeLaCarta
    ] : ["mensaje" => "Error al eliminar el Grupo de la Carta"];
    $code = $result ? 200 : 400;

    $this->sendResponse($response, $code);
  }
}

try {
  $controller = new GruposDeLaCartaController();
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