<?php

/**
 * Atributos y métodos para la gestión de usuarios
 *
 * @author Esther
 */
class Usuario {

    // Atributos -------------------------------------------------------------------------------------------------------
    protected $dni,
            $nombre,
            $apellido1,
            $apellido2,
            $sexo,
            $fechaNacimiento,
            $email,
            $telf;

    // Constructor -----------------------------------------------------------------------------------------------------
    function __construct() {
        
    }

    // Getters ---------------------------------------------------------------------------------------------------------
    function getDni() {
        return $this->dni;
    }

    function getNombre() {
        return $this->nombre;
    }

    function getApellido1() {
        return $this->apellido1;
    }

    function getApellido2() {
        return $this->apellido2;
    }

    function getSexo() {
        return $this->sexo;
    }

    function getFechaNacimiento() {
        return $this->fechaNacimiento;
    }

    function getEmail() {
        return $this->email;
    }

    function getTelf() {
        return $this->telf;
    }

    // Setters ---------------------------------------------------------------------------------------------------------

    /**
     * Función que actúa como constructor de la clase con todos sus atributos como parámetros
     * 
     * @param string $dni
     * @param string $nombre
     * @param string $apellido1
     * @param string $apellido2
     * @param string $sexo
     * @param string $fechaNacimiento
     * @param string $email
     * @param string $telf
     * @param string $aseguradora
     * @param string $fechaAlta
     * @param string $fechaBaja
     * @param string $especialidad
     * @param string $extension
     */
    function set($dni, $nombre, $apellido1, $apellido2, $sexo, $fechaNacimiento, $email, $telf, $aseguradora, $fechaAlta, $fechaBaja, $especialidad, $extension) {
        $this->setDni($dni);
        $this->setNombre($nombre);
        $this->setApellido1($apellido1);
        $this->setApellido2($apellido2);
        $this->setSexo($sexo);
        $this->setFechaNacimiento($fechaNacimiento);
        $this->setEmail($email);
        $this->setTelf($telf);
    }

    function setDni($dni) {
        $this->dni = $dni;
    }

    function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    function setApellido1($apellido1) {
        $this->apellido1 = $apellido1;
    }

    function setApellido2($apellido2) {
        $this->apellido2 = $apellido2;
    }

    function setSexo($sexo) {
        $this->sexo = $sexo;
    }

    function setFechaNacimiento($fechaNacimiento) {
        $this->fechaNacimiento = $fechaNacimiento;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setTelf($telf) {
        $this->telf = $telf;
    }

    // Métodos =========================================================================================================
    // CREATE, UPDATE, DELETE ------------------------------------------------------------------------------------------

    /**
     * Creación de un usuario
     * 
     * @return boolean - Creación con éxito (true) o no (false)
     */
    protected function create() {
        $sql = 'insert into usuarios (dni, nombre, apellido1, apellido2, sexo, fecha_nacimiento, email, telf) values (?, ?, ?, ?, ?, ?, ?, ?);';
        $param = [$this->dni, $this->nombre, $this->apellido1, $this->apellido2, $this->sexo, $this->fechaNacimiento, $this->email, $this->telf];

        return DB::getQueryStmt($sql, $param);
    }

    /**
     * Actualización de los datos de un usuario
     * 
     * @return boolean - Actualización con éxito (true) o no (false)
     */
    protected function update() {
        $c = filterGet('c');
        $a = filterGet('a');
        
        if ($c !== 'perfil' && $a !== 'add') {
            // Formuario de actualización de un usuario desde la página clientes, empleados o administradores
            // En caso de modificar el DNI del usuario debemos referirnos a él por su DNI original (almacenado en sesión)
            $sql = 'update usuarios set dni = ?, nombre = ?, apellido1 = ?, apellido2 = ?, sexo = ?, fecha_nacimiento = ?, email = ?, telf = ? where dni = ?;';
            $param = [$this->dni, $this->nombre, $this->apellido1, $this->apellido2, $this->sexo, $this->fechaNacimiento, $this->email, $this->telf, $_SESSION['dni']];
        } else {
            // Formularo de actualización de un usuario desde la página perfil
            // Si el usuario se encuentra en el perfil, se utilizará siempre su DNI para referirnos al mismo sin posibilidad de actualizarlo
            $sql = 'update usuarios set nombre = ?, apellido1 = ?, apellido2 = ?, sexo = ?, fecha_nacimiento = ?, email = ?, telf = ? where dni = ?;';
            $param = [$this->nombre, $this->apellido1, $this->apellido2, $this->sexo, $this->fechaNacimiento, $this->email, $this->telf, $this->dni];
        }

        return DB::getQueryStmt($sql, $param);
    }

    /**
     * Eliminación de un usuario
     * 
     * @return boolean - Eliminación con éxito (true) o no (false)
     */
    protected function delete() {
        $sql = 'delete from usuarios where dni = ?;';
        $param = [$this->dni];

        return DB::getQueryStmt($sql, $param);
    }

    // GET -------------------------------------------------------------------------------------------------------------

    /**
     * Obtención de los datos de un usuario a partir de su DNI
     * 
     * @return object - Usuario tipo cliente, empleado o administrador
     */
    public function getByDni() {
        $sql = 'select u.dni, u.nombre, u.apellido1, u.apellido2, u.sexo, u.fecha_nacimiento, u.email, u.telf, '
                . 'c.aseguradora, e.fecha_alta, e.fecha_baja, e.especialidad, e.extension, '
                . 'count(c.dni) clientes, count(e.dni) empleados, count(a.dni_admin) admin '
                . 'from usuarios u '
                . 'left join clientes c on u.dni = c.dni '
                . 'left join empleados e on u.dni = e.dni '
                . 'left join administradores a on u.dni = a.dni_admin '
                . 'where u.dni = ?;';
        $param = [$this->dni];

        $stmt = DB::getQueryStmt($sql, $param);

        $row = $stmt->fetch();

        $usuario = null;
        if ($row['clientes'] > 0) {
            $usuario = new Cliente();
            $usuario->set($row['dni'], $row['nombre'], $row['apellido1'], $row['apellido2'], $row['sexo'], $row['fecha_nacimiento'],
                    $row['email'], $row['telf'], $row['aseguradora'], null, null, null, null);
        } elseif ($row['empleados'] > 0) {
            if ($row['admin'] > 0) {
                $usuario = new Admin();
            } else {
                $usuario = new Empleado();
            }

            $usuario->set($row['dni'], $row['nombre'], $row['apellido1'], $row['apellido2'], $row['sexo'], $row['fecha_nacimiento'],
                    $row['email'], $row['telf'], null, $row['fecha_alta'], $row['fecha_baja'], $row['especialidad'], $row['extension']);
        }

        return $usuario;
    }

    /**
     * Obtención de los datos de un usuario a partir de su email
     * 
     * @return object - Usuario tipo cliente, empleado o administrador
     */
    public function getByEmail() {
        // Aprovechamos la función anterior, por lo que solo necesitamos obtener el DNI del usuario a través de su email
        $sql = 'select dni from usuarios where email = ?;';
        $param = [$this->email];

        $stmt = DB::getQueryStmt($sql, $param);

        $usuario = null;
        if (DB::getQueryCountBool($sql, $param)) {
            $row = $stmt->fetch();
            $this->setDni($row['dni']);

            $usuario = $this->getByDni();
        }

        return $usuario;
    }

    /**
     * Obtención de la cuenta de un usuario a partir de su DNI (usuario de la cuenta)
     * 
     * @return object - Cuenta del usuario
     */
    public function getAccount() {
        $sql = 'select * from cuentas where usuario = ?;';
        $param = [$this->dni];

        $stmt = DB::getQueryStmt($sql, $param);

        $cuenta = null;
        if (DB::getQueryCountBool($sql, $param)) {
            $row = $stmt->fetch();

            $cuenta = new Cuenta();
            $cuenta->set($row['usuario'], $row['contrasena']);
        }

        return $cuenta;
    }

    // CHECK -----------------------------------------------------------------------------------------------------------

    /**
     * Comprobación de la existencia de un usuario con el DNI y el email del usuario
     * 
     * @return boolean - Existencia de registros (true) o no (false)
     */
    public function check() {
        $sql = 'select * from usuarios where dni = ? and email = ?;';
        $param = [$this->dni, $this->email];

        return DB::getQueryCountBool($sql, $param);
    }

    /**
     * Comprobación de la existencia de un usuario con el DNI y el email del usuario
     * 
     * @return boolean - Existencia de registros (true) o no (false)
     */
    public function checkEmailByDni($dni) {
        $sql = 'select * from usuarios where dni = ? and email = ?;';
        $param = [$dni, $this->email];

        return DB::getQueryCountBool($sql, $param);
    }

    /**
     * Comprobación de la existencia de un usuario con el DNI del usuario 
     * 
     * @return boolean - Existencia de registros (true) o no (false)
     */
    public function checkByDni() {
        $sql = 'select * from usuarios where dni = ?;';
        $param = [$this->dni];

        return DB::getQueryCountBool($sql, $param);
    }

    /**
     * Comprobación de la existencia de un usuario con el email del usuario 
     * 
     * @return boolean - Existencia de registros (true) o no (false)
     */
    public function checkByEmail() {
        $sql = 'select * from usuarios where email = ?;';
        $param = [$this->email];

        return DB::getQueryCountBool($sql, $param);
    }

    // OTHERS ----------------------------------------------------------------------------------------------------------
}
