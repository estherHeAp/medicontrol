<?php

/**
 * Atributos y métodos para usuarios con el rol de empleados
 *
 * @author Esther
 */
class Empleado extends Usuario {

    // Atributos -------------------------------------------------------------------------------------------------------
    private $fechaAlta,
            $fechaBaja,
            $especialidad,
            $extension;

    // Constructor -----------------------------------------------------------------------------------------------------
    function __construct() {
        
    }

    // Getters ---------------------------------------------------------------------------------------------------------
    function getFechaAlta() {
        return $this->fechaAlta;
    }

    function getFechaBaja() {
        return $this->fechaBaja;
    }

    function getEspecialidad() {
        return $this->especialidad;
    }

    function getExtension() {
        return $this->extension;
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
        parent::set($dni, $nombre, $apellido1, $apellido2, $sexo, $fechaNacimiento, $email, $telf, $aseguradora, $fechaAlta, $fechaBaja, $especialidad, $extension);
        $this->setFechaAlta($fechaAlta);
        $this->setFechaBaja($fechaBaja);
        $this->setEspecialidad($especialidad);
        $this->setExtension($extension);
    }

    function setFechaAlta($fechaAlta) {
        $this->fechaAlta = $fechaAlta;
    }

    function setFechaBaja($fechaBaja) {
        $this->fechaBaja = $fechaBaja;
    }

    function setEspecialidad($especialidad) {
        $this->especialidad = $especialidad;
    }

    function setExtension($extension) {
        $this->extension = $extension;
    }

    // Métodos =========================================================================================================
    // CREATE, UPDATE, DELETE ------------------------------------------------------------------------------------------

    /**
     * Adición del empleado a la tabla de administradores, relacionando dicho empleado con el admin correspondiente
     * 
     * @return boolean - Adición con éxito (true) o no (false)
     */
    private static function add($dni) {
        $sql = 'select distinct dni_admin from administradores where dni_admin <> "ADMIN";';
        $param = [];

        $stmt = DB::getQueryStmt($sql, $param);

        if ($stmt) {
            if ($stmt->rowCount() > 0) {
                $admin = [];
                while ($row = $stmt->fetch()) {
                    array_push($admin, $row['dni_admin']);
                }

                foreach ($admin as $dni_admin) {
                    $sql = 'insert into administradores (dni_admin, dni_empleado) values (?, ?);';
                    $param = [$dni_admin, $dni];

                    $stmt = DB::getQueryStmt($sql, $param);

                    if (!$stmt) {
                        break;
                    }
                }

                return $stmt;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    /**
     * Creación de un empleado
     * 
     * @return boolean - Creación con éxito (true) o no (false)
     */
    public function create() {
        // ¿Empleado en la DB (tabla "empleados")?
        if ($this->checkByDni()) {
            // ¿Empleado con cuenta (tabla "cuentas")?
            // Si ya existe una cuenta el controlador mostrará un mensaje de error
            // En caso de no existir una cuenta...
            return self::add($this->dni);
        } else {
            if (parent::create()) {
                $sql = 'insert into empleados (dni, fecha_alta, fecha_baja, especialidad, extension) values (?, ?, ?, ?, ?);';
                $param = [$this->dni, $this->fechaAlta, $this->fechaBaja, $this->especialidad, $this->extension];

                if (DB::getQueryStmt($sql, $param)) {
                    return self::add($this->dni);
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }

    /**
     * Actualización de los datos de un empleado
     * 
     * @return boolean - Actualización con éxito (true) o no (false)
     */
    public function update() {
        if (parent::update()) {
            $sql = 'update empleados set fecha_alta = ?, fecha_baja = ?, especialidad = ?, extension = ? where dni = ?;';
            $param = [$this->fechaAlta, $this->fechaBaja, $this->especialidad, $this->extension, $this->dni];

            return DB::getQueryStmt($sql, $param);
        } else {
            return false;
        }
    }

    /**
     * Eliminación de un empleado
     * 
     * @return boolean - Eliminación con éxito (true) o no (false)
     */
    public function delete() {
        // Se conservan los datos del usuario, pero se elimina su cuenta y como admin
        $cuenta = $this->getAccount();

        if ($cuenta->delete()) {
            $sql = 'delete from administradores where dni_admin = ? or dni_empleado = ?;';
            $param = [$this->dni, $this->dni];

            return DB::getQueryStmt($sql, $param);
        } else {
            return false;
        }
    }

    // GET -------------------------------------------------------------------------------------------------------------

    /**
     * Obtención de todas las citas guardadas en la base de datos (todas o las que correspondan a un criterio de búsqueda dado)
     * 
     * @param array $search - Parámetros de búsqueda
     * @return PDOStatement - Citas obtenidas de la base de datos
     */
    public function getCitas($search) {
        $dni = $search !== null ? $search['dni'] : '';
        $asunto = $search !== null ? $search['asunto'] : '';

        $sql = 'select c.id, c.fecha, c.hora, c.asunto, cl.dni dni_cliente, uc.nombre nombre_cliente, '
                . 'uc.apellido1 apellido1_cliente, e.dni dni_empleado, ue.nombre nombre_empleado, ue.apellido1 apellido1_empleado '
                . 'from citas c '
                . 'left join clientes cl on cl.dni = c.dni_cliente '
                . 'left join empleados e on e.dni = c.dni_empleado '
                . 'left join usuarios uc on uc.dni = cl.dni '
                . 'left join usuarios ue on ue.dni = e.dni '
                . 'where cl.dni like ? and c.asunto like ? '
                . 'order by fecha, hora;';
        $param = ["%$dni%", "%$asunto%"];

        return DB::getQueryStmt($sql, $param);
    }

    /**
     * Obtención de las citas guardadas en la base de datos que no constan de una consuta relacionada
     * 
     * @return PDOStatement - Citas obtenidas de la base de datos sin consultas relacionadas
     */
    public function getCitasNoConsulta() {
        $sql = 'select c.id, c.fecha, c.hora, c.asunto, uc.nombre nombre_cliente, uc.apellido1 apellido1_cliente '
                . 'from citas c '
                . 'left join clientes cl on cl.dni = c.dni_cliente '
                . 'left join usuarios uc on uc.dni = cl.dni '
                . 'left join consultas co on co.id_cita = c.id '
                . 'where c.id not in(select id_cita from consultas) '
                . 'order by fecha, hora;';
        $param = [];

        return DB::getQueryStmt($sql, $param);
    }

    /**
     * Obtención de las citas pendientes guardadas en la base de datos (todas o las que correspondan a un criterio de búsqueda dado)
     * 
     * @param array $search - Parámetros de búsqueda
     * @return PDOStatemente - Citas obtenidas de la base de datos con una fecha igual o posterior a la actual
     */
    public function getCitasPendientes($search) {
        $currentDate = date('Y-m-d');

        $dni = $search !== null ? $search['dni'] : '';
        $asunto = $search !== null ? $search['asunto'] : '';

        $sql = 'select c.id, c.fecha, c.hora, c.asunto, cl.dni dni_cliente, uc.nombre nombre_cliente, '
                . 'uc.apellido1 apellido1_cliente, e.dni dni_empleado, ue.nombre nombre_empleado, ue.apellido1 apellido1_empleado '
                . 'from citas c '
                . 'left join clientes cl on cl.dni = c.dni_cliente '
                . 'left join empleados e on e.dni = c.dni_empleado '
                . 'left join usuarios uc on uc.dni = cl.dni '
                . 'left join usuarios ue on ue.dni = e.dni '
                . 'where fecha >= ? and cl.dni like ? and c.asunto like ? '
                . 'order by fecha, hora;';
        $param = [$currentDate, "%$dni%", "%$asunto%"];

        return DB::getQueryStmt($sql, $param);
    }

    /**
     * Obtención de todas las consultas guardadas en la base de datos (todas o las que correspondan a un criterio de búsqueda dado)
     * 
     * @param array $search - Parámetros de búsqueda
     * @return PDOStatement - Consultas obtenidas de la base de datos
     */
    public function getConsultas($search) {
        $dni = $search !== null ? $search['dni'] : '';
        $asunto = $search !== null ? $search['asunto'] : '';
        $pago = $search !== null ? $search['pago'] : '';

        $sql = 'select co.id consulta, c.id id, c.fecha, c.hora, c.asunto, co.descripcion, co.pruebas, co.pruebas_detalles, co.tratamientos, '
                . 'co.tratamientos_detalles, co.otros_detalles, co.importe, co.pago, cl.dni dni_cliente, uc.nombre nombre_cliente, '
                . 'uc.apellido1 apellido1_cliente, e.dni dni_empleado, ue.nombre nombre_empleado, ue.apellido1 apellido1_empleado '
                . 'from consultas co '
                . 'left join citas c on c.id = co.id_cita '
                . 'left join clientes cl on cl.dni = c.dni_cliente '
                . 'left join empleados e on e.dni = c.dni_empleado '
                . 'left join usuarios uc on uc.dni = cl.dni '
                . 'left join usuarios ue on ue.dni = e.dni '
                . 'where cl.dni like ? and c.asunto like ? and co.pago like ? '
                . 'order by fecha desc, hora desc;';
        $param = ["%$dni%", "%$asunto%", "%$pago%"];

        return DB::getQueryStmt($sql, $param);
    }

    /**
     * Obtención de los clientes guardados en la base de datos (todos o los que correspondan a un criterio de búsqueda dado)
     * 
     * @param array $search - Parámetros de búsqueda
     * @return PDOStatement - Clientes obtenidos de la base de datos
     */
    public function getClientes($search) {
        $dni = is_array($search) ? $search['dni'] : '';

        $sql = 'select * from clientes c, usuarios u where c.dni = u.dni and c.dni like ?;';
        $param = ["%$dni%"];

        return DB::getQueryStmt($sql, $param);
    }

    /**
     * Obtención de los empleados guardados en la base de datos (todos o los que correspondan a un criterio de búsqueda dado)
     * <br/>
     * Si nos encontramos en la página de administradores, para añadir empleados como administradores, excluimos a éstos de los resultados obtenidos
     * 
     * @param array $search - Parámetros de búsqueda
     * @return PDOStatement - Empleados obtenidos de la base de datos
     */
    public function getEmpleados($search) {
        $c = filterGet('c');

        $dni = is_array($search) ? $search['dni'] : '';
        $nombre = is_array($search) ? $search['nombre'] : '';
        $especialidad = is_array($search) ? $search['especialidad'] : '';

        $sql = 'select * from empleados e, usuarios u, cuentas c '
                . 'where e.dni = u.dni and c.usuario = u.dni '
                . 'and e.dni in (select usuario from cuentas) '
                . 'and e.dni <> "ADMIN" '
                . 'and e.dni like ? and nombre like ? and especialidad like ? ';

        if ($c === 'admin') {
            $sql .= 'and e.dni not in (select distinct dni_admin from administradores)';
        }

        $param = ["%$dni%", "%$nombre%", "%$especialidad%"];

        return DB::getQueryStmt($sql, $param);
    }

    /**
     * Obtención de los administradores guardados en la base de datos con excepción del usuario ADMIN (todos o los que correspondan a un criterio de búsqueda dado)
     * 
     * @param array $search - Parámetros de búsqueda
     * @return PDOStatement - Administradores obtenidos de la base de datos
     */
    public function getAdmin($search) {
        $nombre = is_array($search) ? $search['nombre'] : '';
        $especialidad = is_array($search) ? $search['especialidad'] : '';

        $sql = 'select distinct * from administradores a, empleados e, usuarios u '
                . 'where a.dni_admin = u.dni and e.dni = a.dni_admin '
                . 'and e.dni <> "ADMIN" '
                . 'and nombre like ? and especialidad like ? '
                . 'group by a.dni_admin;';
        $param = ["%$nombre%", "%$especialidad%"];

        return DB::getQueryStmt($sql, $param);
    }

    /**
     * Obtención de las especialidades guardadas en la base de datos (todas o las que correspondan a un criterio de búsqueda dado)
     * 
     * @param array $search - Parámetros de búsqueda
     * @return PDOStatement - Especialidades obtenidas de la base de datos
     */
    public function getEspecialidades() {
        $sql = 'select * from especialidades where nombre <> "ROOT";';
        $param = [];

        return DB::getQueryStmt($sql, $param);
    }

    /**
     * Obtención de las especialidades guardadas en la base de datos (todas o las que correspondan a un criterio de búsqueda dado)
     * 
     * @param array $search - Parámetros de búsqueda
     * @return PDOStatement - Especialidades obtenidas de la base de datos
     */
    public function getEspecialidadByNombre($nombre) {
        $sql = 'select * from especialidades where nombre = ?;';
        $param = [$nombre];

        return DB::getQueryCountBool($sql, $param);
    }

    // CHECK -----------------------------------------------------------------------------------------------------------
    // OTHERS ----------------------------------------------------------------------------------------------------------
}
