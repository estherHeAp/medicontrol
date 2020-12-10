<?php

/**
 * Atributos y métodos para la gestión de citas
 *
 * @author Esther
 */
class Cita {

    // Atributos -------------------------------------------------------------------------------------------------------
    private $id,
            $cliente,
            $empleado,
            $fecha,
            $hora,
            $asunto;

    // Constructor -----------------------------------------------------------------------------------------------------
    function __construct() {
        
    }

    // Getters ---------------------------------------------------------------------------------------------------------
    function getId() {
        return $this->id;
    }

    function getCliente() {
        return $this->cliente;
    }

    function getEmpleado() {
        return $this->empleado;
    }

    function getFecha() {
        return $this->fecha;
    }

    function getHora() {
        return $this->hora;
    }

    function getAsunto() {
        return $this->asunto;
    }

    // Setters ---------------------------------------------------------------------------------------------------------

    /**
     * Función que actúa como constructor de la clase con todos sus atributos como parámetros
     * 
     * @param string $id
     * @param string $cliente
     * @param string $empleado
     * @param string $fecha
     * @param string $hora
     * @param string $asunto
     */
    function set($id, $cliente, $empleado, $fecha, $hora, $asunto) {
        $this->setId($id);
        $this->setCliente($cliente);
        $this->setEmpleado($empleado);
        $this->setFecha($fecha);
        $this->setHora($hora);
        $this->setAsunto($asunto);
    }

    function setId($id) {
        $this->id = $id;
    }

    function setCliente($cliente) {
        $this->cliente = $cliente;
    }

    function setEmpleado($empleado) {
        $this->empleado = $empleado;
    }

    function setFecha($fecha) {
        $this->fecha = $fecha;
    }

    function setHora($hora) {
        $this->hora = $hora;
    }

    function setAsunto($asunto) {
        $this->asunto = $asunto;
    }

    // Métodos =========================================================================================================
    // CREATE, UPDATE, DELETE ------------------------------------------------------------------------------------------

    /**
     * Creación de una cita
     * 
     * @return boolean - Creación con éxito (true) o no (false)
     */
    public function create() {
        $idFecha = Calendario::getIdFecha($this->fecha);

        $sql = 'insert into citas (id, dni_cliente, dni_empleado, id_fecha, fecha, hora, asunto) values (null, ?, ?, ?, ?, ?, ?);';
        $param = [$this->cliente, $this->empleado, $idFecha, $this->fecha, $this->hora, $this->asunto];

        return DB::getQueryStmt($sql, $param);
    }

    /**
     * Actualización de los datos de una cita
     * 
     * @return boolean - Creación con éxito (true) o no (false)
     */
    public function update() {
        $sql = 'update citas set dni_empleado = ?, fecha = ?, hora = ?, asunto = ? where id = ?';
        $param = [$this->empleado, $this->fecha, $this->hora, $this->asunto, $this->id];

        return DB::getQueryStmt($sql, $param);
    }

    /**
     * Eliminación de una cita
     * 
     * @return boolean - Eliminación con éxito (true) o no (false)
     */
    public function delete() {
        $sql = 'delete from citas where id = ?;';
        $param = [$this->id];

        return DB::getQueryStmt($sql, $param);
    }

    // GET -------------------------------------------------------------------------------------------------------------

    /**
     * Obtención de una cita a través de su ID
     * 
     * @return object - Cita buscada
     */
    public function get() {
        $sql = 'select * from citas where id = ?;';
        $param = [$this->id];

        $stmt = DB::getQueryStmt($sql, $param);

        $cita = null;
        if ($stmt) {
            $row = $stmt->fetch();

            $cita = new Cita();
            $cita->set($row['id'], $row['dni_cliente'], $row['dni_empleado'], $row['fecha'], $row['hora'], $row['asunto']);
        }

        return $cita;
    }

    // CHECK -----------------------------------------------------------------------------------------------------------

    /**
     * Comprobación de la existencia de una cita dado su ID
     * 
     * @return boolean - Existencia de registros (true) o no (false)
     */
    public function checkConsultas() {
        $sql = 'select * from consultas where id_cita = ?;';
        $param = [$this->id];

        return DB::getQueryCountBool($sql, $param);
    }

    // OTHERS ----------------------------------------------------------------------------------------------------------
}
