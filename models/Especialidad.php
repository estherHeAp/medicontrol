<?php

/**
 * Description of Especialidad
 *
 * @author Esther
 */
class Especialidad {

    // Atributos -------------------------------------------------------------------------------------------------------
    private $nombre;

    // Constructor -----------------------------------------------------------------------------------------------------
    function __construct() {
        
    }

    // Getters ---------------------------------------------------------------------------------------------------------
    function getNombre() {
        return $this->nombre;
    }

    // Setters ---------------------------------------------------------------------------------------------------------
    function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    // Métodos =========================================================================================================
    // CREATE, UPDATE, DELETE ------------------------------------------------------------------------------------------
    public function create() {
        $sql = 'insert into especialidades (nombre) values (?);';
        $param = [$this->nombre];
        
        return DB::getQueryStmt($sql, $param);
    }
    
    public function delete() {
        $sql = 'delete from especialidades where nombre = ?;';
        $param = [$this->nombre];
        
        return DB::getQueryStmt($sql, $param);
    }
    
    // GET -------------------------------------------------------------------------------------------------------------
    // CHECK -----------------------------------------------------------------------------------------------------------
    /**
     * Comprobamos si la especialidad está siendo utilizada por algún usuario
     * 
     * return boolean - Si hay resultados para la consuta SQL se devolverá true
     */
    public function check() {
        $sql = 'select * from empleados where especialidad = ?;';
        $param = [$this->nombre];
        
        return DB::getQueryCountBool($sql, $param);
    }
    
    // OTHERS ----------------------------------------------------------------------------------------------------------
}
