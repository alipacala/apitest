<?php
require_once __DIR__ . "/../../inc/bootstrap.php";
require_once PROJECT_ROOT_PATH . "/controllers/BaseController.php";

require_once PROJECT_ROOT_PATH . "/models/UsersDb.php";
require_once PROJECT_ROOT_PATH . "/models/AccountsDb.php";

class UsersController extends BaseController
{
  public function get()
  {
    $params = $this->getParams();

    $activo = $params['activo'] ?? null;
    $cantidad = $params['cantidad'] ?? null;
    $pagina = $params['pagina'] ?? null;

    $usersDb = new UsersDb();
    $result = $usersDb->getUsers($activo, $cantidad, $pagina);

    $this->sendResponse($result, 200);
  }

  public function getOne($id)
  {
    $usersDb = new UsersDb();
    $user = $usersDb->getUser($id);

    $response = $user ? $user : ["message" => "Usuario no encontrado"];
    $code = $user ? 200 : 404;

    $this->sendResponse($response, $code);
  }

  public function getOneWith($id, $table)
  {
    if ($table !== 'accounts') {
      $this->sendResponse(["message" => "No se pueden obtener los '$table' de los usuarios"], 400);
      return;
    }

    $usersDb = new UsersDb();
    $user = $usersDb->getUser($id);

    if (!$user) {
      $this->sendResponse(["message" => "Usuario no encontrado"], 404);
      return;
    }

    $accountsDb = new AccountsDb();
    $user['accounts'] = $accountsDb->getAccountsByUser($id);

    $this->sendResponse($user, 200);
  }

  public function create()
  {
    $userFromBody = (array) $this->getBody();

    $usersDb = new UsersDb();
    $id = $usersDb->createUser(...array_values($userFromBody));

    $response = $id ? [
      "message" => "Usuario creado correctamente",
      "user" => array_merge(["id" => intval($id)], $userFromBody)
    ] : ["message" => "Error al crear el usuario"];
    $code = $id ? 201 : 400;

    $this->sendResponse($response, $code);
  }

  public function createCustom($action)
  {
    if ($action == 'basic') {
      $userFromBody = (array) $this->getBody();

      $usersDb = new UsersDb();
      $id = $usersDb->createUser($userFromBody['username'], $userFromBody['email']);

      $response = $id ? [
        "message" => "Usuario básico creado correctamente",
        "user" => array_merge(["id" => intval($id)], $userFromBody)
      ] : ["message" => "Error al crear el usuario básico"];
      $code = $id ? 201 : 400;

      $this->sendResponse($response, $code);

    } else if ($action == 'withAccounts') {

      $userFromBody = (array) $this->getBody();

      $usersDb = new UsersDb();
      $userId = intval($usersDb->createUser($userFromBody['username'], $userFromBody['email'], $userFromBody['age']));

      $accountsDb = new AccountsDb();
      $accounts = $userFromBody['accounts'] ?? [];

      if (!$userId || !$accounts) {
        $this->sendResponse(["message" => "No se puede crear el usuario con cuentas"], 400);
        return;
      }

      $accountsResponse = [];

      foreach ($accounts as $account) {
        $account->user_id = $userId;
        $account->id = intval($accountsDb->createAccount($account->user_id, $account->account_number, $account->balance));

        $accountsResponse[] = $account;
      }

      $userAndAccountsCreated = $userId && count($accounts) === count($accountsResponse);

      $response = $userAndAccountsCreated ? [
        "message" => "Usuario con cuentas creado correctamente",
        "user" => array_merge(["id" => $userId], $userFromBody)
      ] : ["message" => "Error al crear el usuario con cuentas"];
      $code = $userId ? 201 : 400;

      $this->sendResponse($response, $code);

    } else {
      $this->sendResponse(["message" => "No existe la acción $action"], 400);
    }
  }

  public function update($id)
  {
    $userFromBody = (array) $this->getBody();

    // comprobar que se han enviado todos los datos
    if (!isset($userFromBody['username']) || !isset($userFromBody['email']) || !isset($userFromBody['age']) || !isset($userFromBody['is_active'])) {
      $this->sendResponse(["message" => "Faltan datos"], 400);
      return;
    }

    $usersDb = new UsersDb();
    $previousUser = $usersDb->getUser($id);

    // comprobar que el usuario existe
    if (!$previousUser) {
      $this->sendResponse(["message" => "Usuario no encontrado"], 404);
      return;
    }

    // si los datos son iguales, no se hace nada
    if ($previousUser['username'] === $userFromBody['username'] && $previousUser['email'] === $userFromBody['email'] && $previousUser['age'] === $userFromBody['age'] && $previousUser['is_active'] === $userFromBody['is_active']) {
      $this->sendResponse(["message" => "No se han realizado cambios"], 400);
      return;
    }

    $result = $usersDb->updateUser($id, ...array_values($userFromBody));

    $response = $result ? [
      "message" => "Usuario actualizado correctamente",
      "user" => $usersDb->getUser($id)
    ] : ["message" => "Error al actualizar el usuario"];
    $code = $result ? 200 : 400;

    $this->sendResponse($response, $code);
  }

  public function updatePartial($id, $action = null)
  {
    if ($action == 'enable') {

      $usersDb = new UsersDb();
      $previousUser = $usersDb->getUser($id);

      // comprobar que el usuario existe
      if (!$previousUser) {
        $this->sendResponse(["message" => "Usuario no encontrado"], 404);
        return;
      }

      // si el usuario ya está habilitado, no se hace nada
      $user = $usersDb->getUser($id);
      if ($user['is_active']) {
        $this->sendResponse(["message" => "El usuario ya está habilitado"], 400);
        return;
      }

      $result = $usersDb->updateUser($id, null, null, null, 1);

      $response = $result ? ["message" => "Usuario habilitado correctamente"] : ["message" => "Error al habilitar el usuario"];
      $code = $result ? 200 : 400;

      $this->sendResponse($response, $code);

    } else if ($action == null) { // actualizar datos parcialmente

      $userFromBody = (array) $this->getBody();

      // comprobar que se han enviado datos
      if (!isset($userFromBody['username']) && !isset($userFromBody['email']) && !isset($userFromBody['age'])) {
        $this->sendResponse(["message" => "No se especificaron datos a actualizar"], 400);
        return;
      }

      $usersDb = new UsersDb();
      $previousUser = $usersDb->getUser($id);

      // comprobar que el usuario existe
      if (!$previousUser) {
        $this->sendResponse(["message" => "Usuario no encontrado"], 404);
        return;
      }

      $username = $userFromBody['username'] ?? null;
      $email = $userFromBody['email'] ?? null;
      $age = $userFromBody['age'] ?? null;

      // TODO: si los datos son iguales, no se hace nada

      $result = $usersDb->updateUser($id, $username, $email, $age);

      $response = $result ? [
        "message" => "Usuario actualizado correctamente",
        "user" => $usersDb->getUser($id)
      ] : ["message" => "Error al actualizar el usuario"];
      $code = $result ? 200 : 400;

      $this->sendResponse($response, $code);

    } else {
      $this->sendResponse(["message" => "No existe la acción $action"], 400);
    }
  }

  public function delete($id)
  {
    $usersDb = new UsersDb();
    $previousUser = $usersDb->getUser($id);

    // comprobar que el usuario existe
    if (!$previousUser) {
      $this->sendResponse(["message" => "Usuario no encontrado"], 404);
      return;
    }

    $result = $usersDb->deleteUser($id);

    $response = $result ? [
      "message" => "Usuario eliminado correctamente",
      "user" => $previousUser
    ] : ["message" => "Error al eliminar el usuario"];
    $code = $result ? 200 : 400;

    $this->sendResponse($response, $code);
  }

  public function deleteCustom($id, $action)
  {
    if ($action == 'disable') {

      $usersDb = new UsersDb();
      $previousUser = $usersDb->getUser($id);

      // comprobar que el usuario existe
      if (!$previousUser) {
        $this->sendResponse(["message" => "Usuario no encontrado"], 404);
        return;
      }

      // si el usuario ya está deshabilitado, no se hace nada
      $user = $usersDb->getUser($id);
      if (!$user['is_active']) {
        $this->sendResponse(["message" => "El usuario ya está deshabilitado"], 400);
        return;
      }

      $result = $usersDb->updateUser($id, null, null, null, 0);

      $response = $result ? [
        "message" => "Usuario deshabilitado correctamente",
        "user" => $usersDb->getUser($id)
      ] : ["message" => "Error al deshabilitar el usuario"];
      $code = $result ? 200 : 400;

      $this->sendResponse($response, $code);

    } else {
      $this->sendResponse(["message" => "No existe la acción $action"], 400);
    }
  }
}

try {
  $controller = new UsersController();
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