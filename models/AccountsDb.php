<?php
class AccountsDb extends Database
{
  // funcion que devuelve la cantidad de cuentas
  public function getAccountsCount($balanceActivo)
  {
    $query = "SELECT COUNT(*) AS total FROM accounts";
    $params = [];

    if ($balanceActivo) {
      $query .= " WHERE balance > 0";
    }

    return $this->executeQuery($query, $params, "select");
  }

  public function getAccount($id)
  {
    $query = "SELECT * FROM accounts WHERE id = :id";
    $params = array(
      array("nombre" => "id", "valor" => $id, "tipo" => PDO::PARAM_INT)
    );

    return $this->executeQuery($query, $params, "select-one");
  }

  public function getAccountsByUser($id)
  {
    $query = "SELECT a.id, a.account_number, a.balance FROM accounts a INNER JOIN users u ON a.user_id = u.id WHERE u.id = :id";
    $params = array(
      array("nombre" => "id", "valor" => $id, "tipo" => PDO::PARAM_INT)
    );

    return $this->executeQuery($query, $params, "select");
  }

  public function getAccounts($balanceActivo = false, $cantidad = null, $pagina = null)
  {
    $query = "SELECT * FROM accounts";
    $params = [];

    if ($balanceActivo) {
      $query .= " WHERE balance > 0";
    }

    $query .= " ORDER BY id ASC";

    $paginacion = is_numeric($cantidad) && is_numeric($pagina);

    if ($paginacion) {
      $count = $this->getAccountsCount($balanceActivo);

      $cantidad = intval($cantidad);
      $pagina = intval($pagina);

      $total = intval($count[0]['total']);

      if ($total == 0) {
        return array(
          "datos" => [],
          "pagina" => 0,
          "totalPaginas" => 0,
          "cantidadPorPagina" => 0,
          "total" => $total,
        );
      }

      $totalPaginas = ceil($total / $cantidad);
      $pagina = $pagina > $totalPaginas ? $totalPaginas : $pagina;
      $pagina = $pagina < 1 ? 1 : $pagina;
      $offset = ($pagina - 1) * $cantidad;

      $query .= " LIMIT :cantidad OFFSET :offset";
      $params[] = array(
        "nombre" => "cantidad",
        "valor" => $cantidad,
        "tipo" => PDO::PARAM_INT
      );
      $params[] = array(
        "nombre" => "offset",
        "valor" => $offset,
        "tipo" => PDO::PARAM_INT
      );
    }

    $params = $params ?: [];

    $result = $this->executeQuery($query, $params, "select");

    if ($paginacion) {
      return array(
        "datos" => $result,
        "pagina" => $pagina,
        "totalPaginas" => $totalPaginas,
        "cantidadPorPagina" => $cantidad,
        "total" => $total,
      );
    } else {
      return $result;
    }
  }

  public function createAccount($userId, $accountNumber, $balance)
  {
    $query = "INSERT INTO accounts (user_id, account_number, balance) VALUES (:user_id, :account_number, :balance)";

    $params = array(
      array("nombre" => "user_id", "valor" => $userId, "tipo" => PDO::PARAM_INT),
      array("nombre" => "account_number", "valor" => $accountNumber, "tipo" => PDO::PARAM_STR),
      array("nombre" => "balance", "valor" => $balance, "tipo" => PDO::PARAM_STR),
    );

    return $this->executeQuery($query, $params, "insert");
  }

  public function updateAccount($id, $userId = null, $accountNumber = null, $balance = null)
  {
    $query = "UPDATE accounts SET ";
    $params = [];

    if ($userId !== null) {
      $query .= "user_id = :user_id";
      $params[] = array(
        "nombre" => "user_id",
        "valor" => $userId,
        "tipo" => PDO::PARAM_INT
      );
    }

    if ($accountNumber !== null) {
      $query .= (count($params) > 0 ? ", " : "") . "account_number = :account_number";
      $params[] = array(
        "nombre" => "account_number",
        "valor" => $accountNumber,
        "tipo" => PDO::PARAM_STR
      );
    }

    if ($balance !== null) {
      $query .= (count($params) > 0 ? ", " : "") . "balance = :balance";
      $params[] = array(
        "nombre" => "balance",
        "valor" => $balance,
        "tipo" => PDO::PARAM_STR
      );
    }

    $query .= " WHERE id = :id";

    $params[] = array(
      "nombre" => "id",
      "valor" => $id,
      "tipo" => PDO::PARAM_INT
    );

    return $this->executeQuery($query, $params, "update");
  }

  public function deleteAccount($id) {
    $query = "DELETE FROM accounts WHERE id = :id";
    $params = array(
      array("nombre" => "id", "valor" => $id, "tipo" => PDO::PARAM_INT)
    );

    return $this->executeQuery($query, $params, "delete");
  }
}
?>