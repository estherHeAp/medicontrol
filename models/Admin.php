<?php

/**
 * Atributos y métodos propios de un usuario tipo administrador
 *
 * @author Esther
 */
class Admin extends Empleado {

    // Atributos -------------------------------------------------------------------------------------------------------
    // Constructor -----------------------------------------------------------------------------------------------------
    /**
     * Constructor sin parámetros
     */
    function __construct() {
        
    }

    // Getters ---------------------------------------------------------------------------------------------------------
    // Setters ---------------------------------------------------------------------------------------------------------
    // Métodos =========================================================================================================
    // CREATE, UPDATE, DELETE ------------------------------------------------------------------------------------------

    /**
     * Creación de administradores
     * 
     * @return boolean
     */
    public function create() {
        // En principio solo hay una sucursal por lo que el admin lo será de todos los empleados registrados
        $data = parent::getEmpleados(null);
        $empleados = [];
        while ($row = $data->fetch()) {
            if ($row['dni'] != 'admin') {
                array_push($empleados, $row['dni']);
            }
        }

        for ($i = 0; $i < sizeof($empleados); $i++) {
            $sql = 'insert into administradores (dni_admin, dni_empleado) values (?, ?);';
            $param = [$this->dni, $empleados[$i]];

            $stmt = DB::getQueryStmt($sql, $param);

            if (!$stmt) {
                break;
            }
        }

        return $stmt;
    }

    /**
     * Eliminación de administradores
     * 
     * @return boolean
     */
    public function delete() {
        $sql = 'delete from administradores where dni_admin = ?;';
        $param = [$this->dni];

        return DB::getQueryStmt($sql, $param);
    }

    // GET -------------------------------------------------------------------------------------------------------------
    // CHECK -----------------------------------------------------------------------------------------------------------
    // OTHERS ----------------------------------------------------------------------------------------------------------
}
