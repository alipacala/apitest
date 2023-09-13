<?php
class Database
{
    /**
     * Instancia compartida de la conexión PDO
     */
    private static $pdo;

    /**
     * Nombre de la clase que se utilizará para devolver los resultados de las consultas
     */
    public $class = null;

    /**
     * Nombre del campo que se utilizará como id de la tabla
     */
    public $idName = null;

    /**
     * Nombre de la tabla
     */
    public $tableName = null;
    
    public function __construct()
    {
        if (!isset(self::$pdo)) {
            try {
                // Configuración de la conexión PDO
                $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_DATABASE_NAME . ';port=' . DB_PORT . ';charset=utf8';
                self::$pdo = new PDO($dsn, DB_USERNAME, DB_PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                self::$pdo->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, false);
                self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                self::$pdo->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
            } catch (PDOException $e) {
                throw $e;
            }
        }
    }

    /**
     * Llama a la función executeQuery con los parámetros recibidos y devuelve el resultado
     * @param string $query Consulta SQL. Debe contener parámetros con el formato :nombre
     * @param array $params Parámetros de la consulta SQL. Debe contener un array con el formato array("nombre" => "nombre", "valor" => "valor", "tipo" => PDO::PARAM_STR)
     * @param string $operation Operación a realizar. Puede ser select-one, select, insert, update o delete. Permite devolver el resultado en un formato específico
     * @return mixed Resultado de la ejecución de la consulta SQL
     **/
    public function executeQuery($query = "", $params = null, $operation = null)
    {
        try {
            $stmt = $this->executeStatement($query, $params);

            switch (strtolower($operation)) {
                case 'select-one':
                    $result = new $this->class();
                    $stmt->setFetchMode(PDO::FETCH_INTO, $result);
                    $result = $stmt->fetch(PDO::FETCH_INTO);
                    break;
                case 'select':
                    $result = $stmt->fetchAll(PDO::FETCH_CLASS, $this->class);
                    break;
                case 'insert':
                    $result = self::$pdo->lastInsertId();
                    break;
                case 'update':
                case 'delete':
                    $result = $stmt->rowCount();
                    break;
                default:
                    $result = $stmt->fetchAll();
            }
            return $result;
        } catch (PDOException $e) {
            throw $e;
        }
    }

    /**
     * Ejecuta una consulta SQL y devuelve el resultado
     * @param string $query Consulta SQL. Debe contener parámetros con el formato :nombre
     * @param array $params Parámetros de la consulta SQL. Debe contener un array con el formato array("nombre" => "nombre", "valor" => "valor", "tipo" => PDO::PARAM_STR)
     * @return PDOStatement Resultado de la ejecución de la consulta SQL
     **/
    private function executeStatement($query = "", $params = null)
    {
        $stmt = self::$pdo->prepare($query);

        if (!$stmt)
            throw new Exception("No se pudo preparar la consulta " . $query);
        if ($params) {
            foreach ($params as $param) {
                $stmt->bindValue($param['nombre'], $param['valor'], $param['tipo']);
            }
        }
        $stmt->execute();
        return $stmt;
    }

    protected $transactionCounter = 0;

    public function empezarTransaccion()
    {
        self::$pdo->beginTransaction();
    }

    public function terminarTransaccion()
    {
        self::$pdo->commit();
    }

    public function cancelarTransaccion()
    {
        self::$pdo->rollback();
    }

    /**
     * Prepara los datos para realizar una consulta SQL, filtrando los valores nulos
     * @param array $array Datos a preparar
     * @param string $operation Operación a realizar. Si es insert, elimina el id de los datos
     * @return array Datos preparados
     */
    public function prepareData($array, $operation = null)
    {
        if ($operation == "insert") {
            unset($array[$this->idName]);
        }

        // eliminar los valores nulos
        return array_filter($array, function ($value) {
            return $value !== null;
        });
    }

    /**
     * Prepara la consulta SQL a partir de la operación a realizar
     * @param string $operation Operación a realizar. Puede ser select-one, select, insert, update o delete
     * @param array $array Datos a preparar
     * @return string Consulta SQL
     */
    public function prepareQuery($operation, $array = null)
    {
        $query = "";

        switch ($operation) {
            case "select-one":
                $query = "SELECT * FROM $this->tableName WHERE $this->idName = :id";
                break;
            case "select":
                $query = "SELECT * FROM $this->tableName ORDER BY $this->idName ASC";
                break;
            case "insert":
                $query = "INSERT INTO $this->tableName (";
                $query .= implode(", ", array_keys($array));
                $query .= ") VALUES (";
                $query .= implode(", ", array_map(function ($value) {
                    return ":$value";
                }, array_keys($array)));
                $query .= ")";
                break;
            case "update":
                $query = "UPDATE $this->tableName SET ";
                $query .= implode(", ", array_map(function ($value) {
                    return "$value = :$value";
                }, array_keys($array)));
                $query .= " WHERE $this->idName = :$this->idName";
                break;
            case "delete":
                $query = "DELETE FROM $this->tableName WHERE $this->idName = :id";
                break;
        }

        return $query;
    }

    /**
     * Prepara los parámetros de la consulta SQL a partir de la operación a realizar
     * @param array $array Datos a preparar
     * @param string $operation Operación a realizar. Puede ser select-one, select, insert, update o delete
     * @param int $id Id del registro a seleccionar o eliminar
     * @return array Parámetros de la consulta SQL
     */
    public function prepareParams($array = null, $operation = null, $id = null)
    {
        if ($operation == "select-one" || $operation == "delete") {
            return array(array("nombre" => "id", "valor" => $id, "tipo" => PDO::PARAM_INT));
        }

        if ($operation == "update") {
            $array = array_merge($array, [$this->idName => $id]);
        }

        return array_map(function ($key) use ($array) {
            return array(
                "nombre" => $key,
                "valor" => $array[$key],
                "tipo" => is_int($array[$key]) ? PDO::PARAM_INT : PDO::PARAM_STR
            );
        }, array_keys($array));
    }

    /**
     * Obtiene la fecha y hora actual del servidor de base de datos.
     * @return array Array con los valores de fecha, hora y fecha y hora
     */
    public function obtenerFechaYHora()
    {
        $query = "SELECT CURDATE() AS fecha, CURTIME() AS hora, NOW() AS fecha_y_hora";
        $stmt = self::$pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>