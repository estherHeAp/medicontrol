<?php

/**
 * Atributos y métodos para la gestión de consultas
 *
 * @author Esther
 */
class Consulta {

    // Atributos -------------------------------------------------------------------------------------------------------
    private $id,
            $cita,
            $descripcion,
            $pruebas,
            $pruebasDet,
            $tratamientos,
            $tratamientosDet,
            $otros,
            $importe,
            $pago;

    // Constructor -----------------------------------------------------------------------------------------------------
    function __construct() {
        
    }

    // Getters ---------------------------------------------------------------------------------------------------------
    function getId() {
        return $this->id;
    }

    function getCita() {
        return $this->cita;
    }

    function getDescripcion() {
        return $this->descripcion;
    }

    function getPruebas() {
        return $this->pruebas;
    }

    function getPruebasDet() {
        return $this->pruebasDet;
    }

    function getTratamientos() {
        return $this->tratamientos;
    }

    function getTratamientosDet() {
        return $this->tratamientosDet;
    }

    function getOtros() {
        return $this->otros;
    }

    function getImporte() {
        return $this->importe;
    }

    function getPago() {
        return $this->pago;
    }

    // Setters ---------------------------------------------------------------------------------------------------------

    /**
     * Función que actúa como constructor de la clase con todos sus atributos como parámetros
     * 
     * @param string $id
     * @param string $cita
     * @param string $descripcion
     * @param string $pruebas
     * @param string $pruebasDet
     * @param string $tratamientos
     * @param string $tratamientosDet
     * @param string $otros
     * @param string $importe
     * @param string $pago
     */
    function set($id, $cita, $descripcion, $pruebas, $pruebasDet, $tratamientos, $tratamientosDet, $otros, $importe, $pago) {
        $this->setId($id);
        $this->setCita($cita);
        $this->setDescripcion($descripcion);
        $this->setPruebas($pruebas);
        $this->setPruebasDet($pruebasDet);
        $this->setTratamientos($tratamientos);
        $this->setTratamientosDet($tratamientosDet);
        $this->setOtros($otros);
        $this->setImporte($importe);
        $this->setPago($pago);
    }

    function setId($id) {
        $this->id = $id;
    }

    function setCita($cita) {
        $this->cita = $cita;
    }

    function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    function setPruebas($pruebas) {
        $this->pruebas = $pruebas;
    }

    function setPruebasDet($pruebasDet) {
        $this->pruebasDet = $pruebasDet;
    }

    function setTratamientos($tratamientos) {
        $this->tratamientos = $tratamientos;
    }

    function setTratamientosDet($tratamientosDet) {
        $this->tratamientosDet = $tratamientosDet;
    }

    function setOtros($otros) {
        $this->otros = $otros;
    }

    function setImporte($importe) {
        $this->importe = $importe;
    }

    function setPago($pago) {
        $this->pago = $pago;
    }

    // Métodos =========================================================================================================
    // CREATE, UPDATE, DELETE ------------------------------------------------------------------------------------------

    /**
     * Creación de una consulta
     * 
     * @return boolean - Creación con éxito (true) o no (false)
     */
    public function create() {
        $sql = 'insert into consultas (id, id_cita, descripcion, pruebas, pruebas_detalles, tratamientos, tratamientos_detalles, otros_detalles, importe, pago) '
                . 'values (null, ?, null, 0, null, 0, null, null, null, 0);';
        $param = [$this->cita];

        return DB::getQueryStmt($sql, $param);
    }

    /**
     * Actualización de los datos de una consulta
     * 
     * @return boolean - Creación con éxito (true) o no (false)
     */
    public function update() {
        $sql = 'update consultas set descripcion = ?, pruebas = ?, pruebas_detalles = ?, tratamientos = ?, tratamientos_detalles = ?, '
                . 'otros_detalles = ?, importe = ?, pago = ? where id = ?;';
        $param = [$this->descripcion, $this->pruebas, $this->pruebasDet, $this->tratamientos, $this->tratamientosDet, $this->otros, $this->importe, $this->pago, $this->id];

        return DB::getQueryStmt($sql, $param);
    }

    /**
     * Eliminación de una consulta
     * 
     * @return boolean - Eliminación con éxito (true) o no (false)
     */
    public function delete() {
        $sql = 'delete from consultas where id = ?;';
        $param = [$this->id];

        return DB::getQueryStmt($sql, $param);
    }

    // GET -------------------------------------------------------------------------------------------------------------

    /**
     * Obtención de una consulta a través de su ID
     * 
     * @return PDOStatement - Consulta obtenida de la base de datos
     */
    public function get() {
        $sql = 'select co.id consulta, id_cita, descripcion, pruebas, pruebas_detalles, tratamientos, tratamientos_detalles, otros_detalles, importe, pago, dni_empleado, asunto from consultas co, citas ci where co.id_cita = ci.id and co.id = ?;';
        $param = [$this->id];

        return DB::getQueryStmt($sql, $param);
    }

    // CHECK -----------------------------------------------------------------------------------------------------------

    /**
     * Comprobación de la existencia de una consulta dado el ID de la cita (solo encontraremos una consulta asociada a un ID de cita determinado)
     * 
     * @return boolean - Existencia de registros (true) o no (false)
     */
    public function check() {
        $sql = 'select * from consultas where id_cita = ?;';
        $param = [$this->cita];

        return DB::getQueryCountBool($sql, $param);
    }

    // OTHERS ----------------------------------------------------------------------------------------------------------
}
