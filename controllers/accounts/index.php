<?php
require_once __DIR__ . "/../../inc/bootstrap.php";
require_once PROJECT_ROOT_PATH . "/controllers/BaseController.php";

require_once PROJECT_ROOT_PATH . "/models/AccountsDb.php";
require_once PROJECT_ROOT_PATH . "/models/UsersDb.php";

class AccountsController extends BaseController
{
  public function get()
  {
    $params = $this->getParams();

    $balanceActivo = boolval(($params['balance_activo'] ?? null) === "");
    $cantidad = $params['cantidad'] ?? null;
    $pagina = $params['pagina'] ?? null;

    $accountsDb = new AccountsDb();
    $result = $accountsDb->getAccounts($balanceActivo, $cantidad, $pagina);

    $this->sendResponse($result, 200);
  }

  public function getOne($id)
  {
    $accountsDb = new AccountsDb();
    $account = $accountsDb->getAccount($id);

    $response = $account ? $account : ["message" => "Cuenta no encontrada"];
    $code = $account ? 200 : 404;

    $this->sendResponse($response, $code);
  }

  public function create()
  {
    $accountFromBody = (array) $this->getBody();

    $accountsDb = new AccountsDb();
    $id = $accountsDb->createaccount(...array_values($accountFromBody));

    $response = $id ? [
      "message" => "Cuenta creada correctamente",
      "account" => array_merge(["id" => intval($id)], $accountFromBody)
    ] : ["message" => "Error al crear la cuenta"];
    $code = $id ? 201 : 400;

    $this->sendResponse($response, $code);
  }

  public function createCustom($action)
  {
    throw new Exception("No implementado");
  }

  public function update($id)
  {
    $accountFromBody = get_object_vars($this->getBody());

    // comprobar que se han enviado todos los datos
    if (!isset($accountFromBody['user_id']) || !isset($accountFromBody['account_number']) || !isset($accountFromBody['balance'])) {
      $this->sendResponse(["message" => "Faltan datos"], 400);
      return;
    }

    $accountsDb = new AccountsDb();
    $previousAccount = $accountsDb->getAccount($id);

    // comprobar que el usuario existe
    if (!$previousAccount) {
      $this->sendResponse(["message" => "Cuenta no encontrada"], 404);
      return;
    }

    // si los datos son iguales, no se hace nada
    if ($previousAccount['user_id'] == $accountFromBody['user_id'] && $previousAccount['account_number'] == $accountFromBody['account_number'] && $previousAccount['balance'] == $accountFromBody['balance']) {
      $this->sendResponse(["message" => "No se han realizado cambios"], 400);
      return;
    }

    $result = $accountsDb->updateAccount($id, ...array_values($accountFromBody));

    $response = $result ? [
      "message" => "Cuenta actualizada correctamente",
      "account" => $accountsDb->getAccount($id)
    ] : ["message" => "Error al actualizar la cuenta"];
    $code = $result ? 200 : 400;

    $this->sendResponse($response, $code);
  }

  public function updatePartial($id, $action = null)
  {
    throw new Exception("No implementado");
  }

  public function delete($id)
  {
    $accountsDb = new AccountsDb();

    // comprobar que la cuenta existe
    $previousAccount = $accountsDb->getAccount($id);
    if (!$previousAccount) {
      $this->sendResponse(["message" => "Cuenta no encontrada"], 404);
      return;
    }

    $result = $accountsDb->deleteAccount($id);

    $response = $result ? [
      "message" => "Cuenta eliminada correctamente",
      "account" => $previousAccount
    ] : ["message" => "Error al eliminar la cuenta"];
    $code = $result ? 200 : 400;

    $this->sendResponse($response, $code);
  }

  public function deleteCustom($id, $action)
  {
    throw new Exception("No implementado");
  }
}

try {
  $controller = new AccountsController();
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