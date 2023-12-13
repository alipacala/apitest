<?php
class BaseController
{
  public function __construct()
  {
    header("Content-Type: application/json");
  }

  public function route()
  {
    $method = $_SERVER['REQUEST_METHOD'];

    $action = $this->getUriAction();
    $actionParts = explode('/', $action);

    switch ($method) {
      case 'GET':
        if (is_numeric($actionParts[0]))
          if (!empty($actionParts[1]))
            $this->getOneCustom($actionParts[0], $actionParts[1]);
          else
            $this->getOne($action);
        else
          $this->get();
        break;
      case 'POST':
        if (!empty($action))
          $this->createCustom($action);
        else
          $this->create();
        break;
      case 'PUT':
        if (is_numeric($action))
          $this->update($action);
        break;
      case 'PATCH':
        if (is_numeric($actionParts[0]))
          if (!empty($actionParts[1]))
            $this->updatePartial($actionParts[0], $actionParts[1]);
          else
            $this->updatePartial($action);
        break;
      case 'DELETE':
        if (is_numeric($actionParts[0]))
          if (!empty($actionParts[1]))
            $this->deleteCustom($actionParts[0], $actionParts[1]);
          else
            $this->delete($action);
        break;
      default:
        echo 'Method not supported';
        break;
    }
  }

  public function get()
  {
    throw new Exception("Método no implementado");
  }

  public function getOne($id)
  {
    throw new Exception("Método no implementado");
  }

  public function getOneCustom($id, $action)
  {
    throw new Exception("Método no implementado");
  }

  public function create()
  {
    throw new Exception("Método no implementado");
  }

  public function createCustom($action)
  {
    throw new Exception("Método no implementado");
  }

  public function update($id)
  {
    throw new Exception("Método no implementado");
  }

  public function updatePartial($id, $action = null)
  {
    throw new Exception("Método no implementado");
  }

  public function delete($id)
  {
    throw new Exception("Método no implementado");
  }

  public function deleteCustom($id, $action)
  {
    throw new Exception("Método no implementado");
  }

  /**
   * Obtiene la tercera parte de la URL, la cual contiene la acción a ejecutar o el ID del recurso.
   * @return string
   */
  public static function getUriAction()
  {
    // require_once __DIR__ . '.\..\inc\bootstrap.php';
    require_once realpath(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'bootstrap.php');

    $uri = $_SERVER['REQUEST_URI'];
    $uri = explode('/', $uri);
    $uri = array_slice($uri, ENV === 'server' ? 4 : 3);
    $uri = implode('/', $uri);
    return $uri;
  }

  /**
   * Obtiene los parámetros de la URL.
   * @return array
   */
  public static function getParams()
  {
    parse_str($_SERVER['QUERY_STRING'], $params);
    return $params;
  }

  /**
   * Obtiene el contenido de la petición y lo convierte a JSON.
   * @return mixed
   */
  public function getBody()
  {
    $body = file_get_contents("php://input");
    return json_decode($body);
  }

  /**
   * Convierte el contenido de la respuesta a JSON y la envía.
   * @param $data
   */
  public function sendResponse($data, $code = 500)
  {
    http_response_code($code);
    echo json_encode($data);
  }

  /**
   * Mapea un objeto JSON a un objeto. Los nombres de las propiedades del objeto JSON deben coincidir con los de la clase.
   * @param $json Objeto JSON
   * @param $obj Objeto a mapear
   */
  public function mapJsonToObj($json, &$obj)
  {
    foreach ($json as $key => $value) {
      if (property_exists($obj, $key)) {
        if ($value === "")
          $value = null;
        $obj->$key = $value;
      }
    }
  }

  /**
   * Comprueba que los campos requeridos estén presentes en el objeto.
   * @param $camposRequeridos
   * @param $obj
   * @return array
   */
  public function comprobarCamposRequeridos($camposRequeridos, $obj)
  {
    $camposFaltantes = [];
    foreach ($camposRequeridos as $campo) {
      if (!isset($obj->$campo)) {
        $camposFaltantes[] = $campo;
      }
    }
    return $camposFaltantes;
  }

  /**
   * Compara dos objetos y determina si al menos un campo es diferente.
   * @param $objToUpdate Objeto con menos campos
   * @param $prevObj Objeto con más campos
   * @return bool Devuelve false si al menos un campo es diferente, true si todos los campos son iguales
   */
  function compararObjetoActualizar($objToUpdate, $prevObj)
  {
    $arrayToUpdate = (array) $objToUpdate;
    $arrayPrev = (array) $prevObj;

    if (count($arrayToUpdate) < 1) {
      return true;
    }

    foreach ($arrayToUpdate as $campo => $valor) {
      if (array_key_exists($campo, $arrayPrev) && $arrayPrev[$campo] != $valor) {
        return false;
      }
    }

    return true;
  }

  /**
   * Devuelve un array con los datos del error.
   * @param $e Excepción
   * @return array
   */
  function errorResponse($e)
  {
    return [
      "mensaje" => $e->getMessage(),
      "archivo" => $e->getFile(),
      "linea" => $e->getLine(),
      "trace" => $e->getTrace()
    ];
  }
}
?>