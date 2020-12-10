<?php

/**
 * Clase para la gestión de días laborales
 *
 * @author Esther
 */
class Laboral extends Calendario {

    // Atributos -------------------------------------------------------------------------------------------------------
    public $dia;

    // Constructor -----------------------------------------------------------------------------------------------------
    function __construct() {
        
    }

    // Getters ---------------------------------------------------------------------------------------------------------
    function getDia() {
        return $this->dia;
    }

    // Setters ---------------------------------------------------------------------------------------------------------

    /**
     * Función que actúa como constructor de la clase con todos sus atributos como parámetros
     * 
     * @param string $manana1
     * @param string $manana2
     * @param string $tarde1
     * @param string $tarde2
     * @param string $duracion
     * @param string $max
     * @param string $dia
     * @param string $fecha
     */
    function set($manana1, $manana2, $tarde1, $tarde2, $duracion, $max, $dia, $fecha) {
        parent::set($manana1, $manana2, $tarde1, $tarde2, $duracion, $max, $dia, $fecha);
        $this->setDia($dia);
    }

    function setDia($dia) {
        $this->dia = $dia;
    }

    // Métodos =========================================================================================================
    // CREATE, UPDATE, DELETE ------------------------------------------------------------------------------------------

    /**
     * Actualización de los datos del calendario
     * 
     * @return boolean - Creación con éxito (true) o no (false)
     */
    public function update() {
        // Si el horario y a disponbilidad ya existen en la base de datos, utilizamos sus ids para asignarlos a la fecha correspondiente
        // Si no existen, los creamos y utilizamos los nuevos ids
        $idDisponibilidad = parent::getIdDisponibilidad();
        $idHorarios = parent::getIdHorarios();

        $sql = 'update fechas set id_disponibilidad = ?, id_horarios = ? where tipo = "L" and dia = ?';
        $param = [$idDisponibilidad, $idHorarios, $this->dia];

        return DB::getQueryStmt($sql, $param);
    }

    // GET -------------------------------------------------------------------------------------------------------------

    /**
     * Obtención de los calendarios
     * 
     * @return array - Calendarios obtenidos de la base de datos
     */
    public static function getLaborales() {
        $sql = 'select * from fechas f, disponibilidad d, horarios h where f.id_disponibilidad = d.id and f.id_horarios = h.id and f.tipo = "L" order by dia;';
        $param = [];

        $stmt = DB::getQueryStmt($sql, $param);

        $laborales = [];

        if ($stmt) {
            while ($row = $stmt->fetch()) {
                $laboral = new Laboral();
                $laboral->set($row['manana1'], $row['manana2'], $row['tarde1'], $row['tarde2'], $row['duracion_cita'], $row['max_clientes'], $row['dia'], null);
                array_push($laborales, $laboral);
            }
        }

        return $laborales;
    }

    /**
     * Obtención de los datos para un día dado
     * 
     * @return Laboral - Día obtenido de la base de datos
     */
    public function getByDia() {
        $sql = 'select * from fechas f, disponibilidad d, horarios h where f.id_disponibilidad = d.id and f.id_horarios = h.id and f.tipo = "L" and f.dia = ?;';
        $param = [$this->dia];

        $stmt = DB::getQueryStmt($sql, $param);

        $dia = null;

        if ($stmt) {
            $row = $stmt->fetch();
            
            $dia = new Laboral();
            $dia->set($row['manana1'], $row['manana2'], $row['tarde1'], $row['tarde2'], $row['duracion_cita'], $row['max_clientes'], $row['dia'], null);
        }

        return $dia;
    }

    // CHECK -----------------------------------------------------------------------------------------------------------
    // OTHERS ----------------------------------------------------------------------------------------------------------
}
