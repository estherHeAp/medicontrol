<?php

/**
 * Atributos y métodos para la gestión de cuentas de usuario
 *
 * @author Esther
 */
class Cuenta {

    // Atributos -------------------------------------------------------------------------------------------------------
    private $dni,
            $pass;

    // Constructor -----------------------------------------------------------------------------------------------------
    function __construct() {
        
    }

    // Getters ---------------------------------------------------------------------------------------------------------
    function getDni() {
        return $this->dni;
    }

    function getPass() {
        return $this->pass;
    }

    // Setters ---------------------------------------------------------------------------------------------------------

    /**
     * Función que actúa como constructor de la clase con todos sus atributos como parámetros
     * 
     * @param string $dni
     * @param string $pass
     */
    function set($dni, $pass) {
        $this->setDni($dni);
        $this->setPass($pass);
    }

    function setDni($dni) {
        $this->dni = $dni;
    }

    function setPass($pass) {
        $this->pass = $pass;
    }

    // Métodos =========================================================================================================
    // CREATE, UPDATE, DELETE ------------------------------------------------------------------------------------------

    /**
     * Creación de una cuenta de usuario
     * 
     * @return boolean - Creación con éxito (true) o no (false)
     */
    public function create() {
        $cifrada = password_hash($this->pass, PASSWORD_DEFAULT);

        $sql = 'insert into cuentas (usuario, contrasena) values (?, ?);';
        $param = [$this->dni, $cifrada];

        return DB::getQueryStmt($sql, $param);
    }

    /**
     * Actualización de la contraseña del usuario por una nueva
     * 
     * @param string $pass - Nueva contrasña
     * @return boolean - Actualización con éxito (true) o no (false)
     */
    public function update($pass) {
        $cifrada = password_hash($pass, PASSWORD_DEFAULT);
        $this->pass = $pass;

        $sql = 'update cuentas set contrasena = ? where usuario = ?;';
        $param = [$cifrada, $this->dni];

        return DB::getQueryStmt($sql, $param);
    }

    /**
     * Eliminación de la cuenta de un usuario
     * 
     * @return boolean - Eliminación con éxito (true) o no (false)
     */
    public function delete() {
        $sql = 'delete from cuentas where usuario = ?;';
        $param = [$this->dni];

        return DB::getQueryStmt($sql, $param);
    }

    // GET -------------------------------------------------------------------------------------------------------------
    // CHECK -----------------------------------------------------------------------------------------------------------

    /**
     * Comprobación de la existencia de una cuenta de usuario con el usuario y contraseña indicados
     * 
     * @return boolean - Existencia de registros (true) o no (false)
     */
    public function check() {
        $sql = 'select contrasena from cuentas where usuario = ?;';
        $param = [$this->dni];

        $stmt = DB::getQueryStmt($sql, $param);
        $row = $stmt->fetch();

        return (password_verify($this->pass, $row['contrasena']));
    }

    // OTHERS ----------------------------------------------------------------------------------------------------------

    /**
     * Reseteo de contrasea y envío por correo electrónico al usuario
     * 
     * @param string $email - Email al que enviar la nueva contraseña
     * @return boolean - Envío de email exitoso (true) o no (false)
     */
    public function resetPass($email) {
        // 1- Crear contraseña aleatoria
        $pass = newPass();

        // 2- Actualizar contrseña de la cuenta
        if ($this->update($pass)) {
            // 3- Enviar email con la nueva contraseña
            return sendMail($pass, $email);
        } else {
            return false;
        }
    }

}
