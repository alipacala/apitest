<?php
class UsersDb extends Database
{
  // funcion que devuelve la cantidad de usuarios
  public function getUsersCount($activo)
  {
    $query = "SELECT COUNT(*) AS total FROM users";
    $params = [];

    if ($activo !== null) {
      $query .= " WHERE is_active = :activo";
      $params = array(
        array("nombre" => "activo", "valor" => $activo, "tipo" => PDO::PARAM_INT)
      );
    }

    return $this->executeQuery($query, $params, "select");
  }

  public function getUser($id)
  {
    $query = "SELECT * FROM users WHERE id = :id";
    $params = array(
      array("nombre" => "id", "valor" => $id, "tipo" => PDO::PARAM_INT)
    );

    return $this->executeQuery($query, $params, "select-one");
  }

  public function getUsers($activo = null, $cantidad = null, $pagina = null)
  {
    $query = "SELECT * FROM users";
    $params = [];

    if ($activo !== null) {
      $query .= " WHERE is_active = :activo";
      $params[] = array(
        "nombre" => "activo",
        "valor" => $activo,
        "tipo" => PDO::PARAM_INT
      );
    }

    $query .= " ORDER BY id ASC";

    $paginacion = is_numeric($cantidad) && is_numeric($pagina);

    if ($paginacion) {
      $count = $this->getUsersCount($activo);

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

  public function createUser($username, $email, $age = null)
  {
    $query = "INSERT INTO users (username, email, age) 
              VALUES (:username, :email, :age)";

    $params = array(
      array("nombre" => "username", "valor" => $username, "tipo" => PDO::PARAM_STR),
      array("nombre" => "email", "valor" => $email, "tipo" => PDO::PARAM_STR),
      array("nombre" => "age", "valor" => $age, "tipo" => PDO::PARAM_INT),
    );

    return $this->executeQuery($query, $params, "insert");
  }

  public function updateUser($id, $username = null, $email = null, $age = null, $isActive = null)
  {
    $query = "UPDATE users SET ";
    $params = [];

    if ($username !== null) {
      $query .= "username = :username";
      $params[] = array(
        "nombre" => "username",
        "valor" => $username,
        "tipo" => PDO::PARAM_STR
      );
    }

    if ($email !== null) {
      $query .= (count($params) > 0 ? ", " : "") . "email = :email";
      $params[] = array(
        "nombre" => "email",
        "valor" => $email,
        "tipo" => PDO::PARAM_STR
      );
    }

    if ($age !== null) {
      $query .= (count($params) > 0 ? ", " : "") . "age = :age";
      $params[] = array(
        "nombre" => "age",
        "valor" => $age,
        "tipo" => PDO::PARAM_INT
      );
    }

    if ($isActive !== null) {
      $query .= (count($params) > 0 ? ", " : "") . "is_active = :is_active";
      $params[] = array(
        "nombre" => "is_active",
        "valor" => $isActive,
        "tipo" => PDO::PARAM_INT
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

  public function deleteUser($id)
  {
    $query = "DELETE FROM users WHERE id = :id";
    $params = array(
      array("nombre" => "id", "valor" => $id, "tipo" => PDO::PARAM_INT)
    );

    return $this->executeQuery($query, $params, "delete");
  }
}
?>