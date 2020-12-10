<?php

/**
 * Clase para la comunicación con la base de datos: conexión y realización de consultas
 *
 * @author Esther
 */
class DB {

    /**
     * Estabecemos la conexión con la base de datos
     * 
     * @return \PDO - Conexión con la base de datos
     */
    public static function getConnection() {
        try {
            $pdo = new PDO(DNS, USER, PASS);

            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->exec('SET CHARACTER SET UTF8');

            return $pdo;
        } catch (Exception $ex) {
            echo 'ERROR - CONEXIÓN CON LA BASE DE DATOS: <br/>' . $ex->getMessage();
        }
    }

    // -----------------------------------------------------------------------------------------------------------------

    /**
     * Ejecutamos un SQL con los parámetros necesarios
     * 
     * @param string $sql - SQL a ejecutar
     * @param array $param - Parámetros utilizados en el SQL
     * @return boolean - Resultados de la consulta
     */
    public static function getQueryStmt($sql, $param) {
        $pdo = self::getConnection();
        $params = sizeof($param);

        try {
            $stmt = $pdo->prepare($sql);

            if ($params > 0) {
                for ($i = 0; $i < $params; $i++) {
                    $stmt->bindParam(($i + 1), $param[$i]);
                }
            }

            $stmt->execute();

            return $stmt;
        } catch (Exception $ex) {
            echo 'ERROR - LA CONSULTA NO HA PODIDO SER REALIZADA: <br/>' . $ex->getMessage();
        } finally {
            unset($pdo);
        }
    }

    /**
     * Obtenemos el número de resultados obtenidos a partir de la ejecución de un SQL
     * 
     * @param string $sql - SQL a ejecutar
     * @param array $param - Parámetros utilizados en el SQL
     * @return int - Número de resultados obtenidos
     */
    public static function getQueryCount($sql, $param) {
        $pdo = self::getConnection();
        $params = sizeof($param);

        try {
            $stmt = $pdo->prepare($sql);

            if ($params > 0) {
                for ($i = 0; $i < $params; $i++) {
                    $stmt->bindParam(($i + 1), $param[$i]);
                }
            }

            $stmt->execute();
            $count = $stmt->rowCount();

            return $count;
        } catch (Exception $ex) {
            echo 'ERROR - LA CONSULTA NO HA PODIDO SER REALIZADA: <br/>' . $ex->getMessage();
        } finally {
            unset($pdo);
        }
    }

    /**
     * Comprobamos que se devuelve algún resultados
     * 
     * @param string $sql - SQL a ejecutar
     * @param array $param - Parámetros utilizados en el SQL
     * @return boolean - true si hay resultados, false si el número de resultados es 0
     */
    public static function getQueryCountBool($sql, $param) {
        $pdo = self::getConnection();
        $params = sizeof($param);

        try {
            $stmt = $pdo->prepare($sql);

            if ($params > 0) {
                for ($i = 0; $i < $params; $i++) {
                    $stmt->bindParam(($i + 1), $param[$i]);
                }
            }

            $stmt->execute();
            $count = $stmt->rowCount();

            if ($count > 0) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            echo 'ERROR - LA CONSULTA NO HA PODIDO SER REALIZADA: <br/>' . $ex->getMessage();
        } finally {
            unset($pdo);
        }
    }

    // -----------------------------------------------------------------------------------------------------------------

    /**
     * Obtenemos el email del usuario administrador
     * 
     * @return string - Email del usuario administrador
     */
    public static function getAdminEmail() {
        $pdo = self::getConnection();

        try {
            $sql = 'select email from usuarios where dni = "admin";';
            $param = [];

            $stmt = self::getQueryStmt($sql, $param);
            $email = $stmt->fetch();

            return $email;
        } catch (Exception $ex) {
            echo 'ERROR - LA CONSULTA NO HA PODIDO SER REALIZADA: <br/>' . $ex->getMessage();
        } finally {
            unset($pdo);
        }
    }

}
