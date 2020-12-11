<?php

/**
 * Atributos y métodos para usuarios con el rol de clientes
 *
 * @author Esther
 */
class Cliente extends Usuario {

    // Atributos -------------------------------------------------------------------------------------------------------
    private $aseguradora;

    // Constructor -----------------------------------------------------------------------------------------------------
    function __construct() {
        
    }

    // Getters ---------------------------------------------------------------------------------------------------------
    function getAseguradora() {
        return $this->aseguradora;
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
        $this->setAseguradora($aseguradora);
    }

    function setAseguradora($aseguradora) {
        $this->aseguradora = $aseguradora;
    }

    // Métodos =========================================================================================================
    // CREATE, UPDATE, DELETE ------------------------------------------------------------------------------------------

    /**
     * Creación de un cliente
     * 
     * @return boolean - Creación con éxito (true) o no (false)
     */
    public function create() {
        if (parent::create()) {
            $sql = 'insert into clientes (dni, aseguradora) values (?, ?);';
            $param = [$this->dni, $this->aseguradora];

            return DB::getQueryStmt($sql, $param);
        } else {
            return false;
        }
    }

    /**
     * Actualización de los datos de un cliente
     * 
     * @return boolean - Creación con éxito (true) o no (false)
     */
    public function update() {
        if (parent::update()) {
            $sql = 'update clientes set aseguradora = ? where dni = ?;';
            $param = [$this->aseguradora, $this->dni];

            return DB::getQueryStmt($sql, $param);
        } else {
            return false;
        }
    }

    /**
     * Eliminación de un cliente
     * 
     * @return boolean - Eliminación con éxito (true) o no (false)
     */
    public function delete() {
        $cuenta = $this->getAccount();

        $sql = 'delete from clientes where dni = ?;';
        $param = [$this->dni];

        if ($cuenta->delete() && DB::getQueryStmt($sql, $param) && parent::delete()) {
            return true;
        } else {
            return false;
        }
    }

    // GET -------------------------------------------------------------------------------------------------------------

    /**
     * Obtención de las citas pendientes de cliente guardadas en la base de datos
     * 
     * @param array $search - Parámetros de búsqueda
     * @return array - Citas obtenidas de la base de datos con una fecha igual o posterior a la actual
     */
    public function getCitasPendientes() {
        $currentDate = date('Y-m-d');

        $sql = 'select c.id, c.fecha, c.hora, c.asunto, cl.dni dni_cliente, uc.nombre nombre_cliente, '
                . 'uc.apellido1 apellido1_cliente, e.dni dni_empleado, ue.nombre nombre_empleado, ue.apellido1 apellido1_empleado '
                . 'from citas c '
                . 'left join clientes cl on cl.dni = c.dni_cliente '
                . 'left join empleados e on e.dni = c.dni_empleado '
                . 'left join usuarios uc on uc.dni = cl.dni '
                . 'left join usuarios ue on ue.dni = e.dni '
                . 'where c.dni_cliente = ? and fecha >= ? order by fecha, hora;';
        $param = [$this->dni, $currentDate];

        $stmt = DB::getQueryStmt($sql, $param);
        
        if($stmt) {
            $citas = [];
            
            while($row = $stmt->fetch()) {
                $cita = new Cita();
                $cita->set($row['id'], $row['dni_cliente'], $row['dni_empleado'], $row['fecha'], $row['hora'], $row['asunto']);
                
                // Se desean añadir los nombres del cliente y el empleado
                array_push($citas, $cita, 
                        $row['nombre_cliente'] . ' ' . $row['apellido1_cliente'],
                        $row['nombre_empleado'] . ' ' . $row['apellido1_empleado']);
            }
            
            return $citas;
        } else {
            return false;
        }
    }

    /**
     * Obtención de todas las consultas del cliente guardadas en la base de datos (todas o las que correspondan a un criterio de búsqueda dado)
     * 
     * @param array $search - Parámetros de búsqueda
     * @return array - Consultas obtenidas de la base de datos
     */
    public function getConsultas($search) {
        $asunto = $search !== null ? $search['asunto'] : '';
        $pago = $search !== null ? $search['pago'] : '';

        $sql = 'select co.id consulta, c.id id, c.fecha, c.hora, c.asunto, co.descripcion, co.pruebas, co.pruebas_detalles, '
                . 'co.tratamientos, co.tratamientos_detalles, co.otros_detalles, co.importe, co.pago, cl.dni dni_cliente, '
                . 'uc.nombre nombre_cliente, uc.apellido1 apellido1_cliente, e.dni dni_empleado, ue.nombre nombre_empleado, '
                . 'ue.apellido1 apellido1_empleado '
                . 'from consultas co '
                . 'left join citas c on c.id = co.id_cita '
                . 'left join clientes cl on cl.dni = c.dni_cliente '
                . 'left join empleados e on e.dni = c.dni_empleado '
                . 'left join usuarios uc on uc.dni = cl.dni '
                . 'left join usuarios ue on ue.dni = e.dni '
                . 'where cl.dni = ? and c.asunto like ? and co.pago like ? '
                . 'order by fecha desc, hora desc';
        $param = [$this->dni, "%$asunto%", "%$pago%"];

        $stmt = DB::getQueryStmt($sql, $param);
        
        if($stmt) {
            $consultas = [];
            
            while($row = $stmt->fetch()) {
                $consulta = new Consulta();
                $consulta->set($row['consulta'], $row['id'], $row['descripcion'], $row['pruebas'], $row['pruebas_detalles'], $row['tratamientos'], $row['tratamientos_detalles'], $row['otros_detalles'], $row['importe'], $row['pago']);
                
                $cita = new Cita();
                $cita->set($row['id'], $row['dni_cliente'], $row['dni_empleado'], $row['fecha'], $row['hora'], $row['asunto']);
                
                // Se mostrará información de la cita, la consulta y los nombres de los clientes y los empleados encargados
                array_push($consultas,
                        array($consulta, $cita, $row['nombre_cliente'].' '.$row['apellido1_cliente'], $row['nombre_empleado'].' '.$row['apellido1_empleado']));
            }
            
            return $consultas;
        } else {
            return false;
        }
    }

    // CHECK -----------------------------------------------------------------------------------------------------------
    // OTHERS ----------------------------------------------------------------------------------------------------------
}
