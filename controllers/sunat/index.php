<?php
require_once __DIR__ . "/../../inc/bootstrap.php";
require_once PROJECT_ROOT_PATH . "/controllers/BaseController.php";

class SunatController extends BaseController
{
  public function get()
  {
    $params = $this->getParams();
    
    $tipo = $params['tipo'] ?? null;
    $doc = $params['nro'] ?? null;

    if ($tipo == null || $doc == null) {
      $this->sendResponse(["mensaje" => "Falta alguno de los siguientes parámetros: tipo, nro"], 400);
      return;
    }

    $tipos = [
      'DNI' => '1',
      'RUC' => '2',
    ];

    $hash = "gPs39Bds648Kgh345fsdGjshjdsjeh73HF7T";
    $url = "http://perufacturo.com/hash/ver.php?hash=$hash&tipo=$tipos[$tipo]&doc=$doc";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = json_decode(curl_exec($ch));
    curl_close($ch);

    $this->sendResponse($result, 200);
  }
}

try {
  $controller = new SunatController();
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