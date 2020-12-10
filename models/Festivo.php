<?php

/**
 * Clase para la gestión de días festivos
 *
 * @author Esther
 */
class Festivo extends Calendario {

    // Atributos -------------------------------------------------------------------------------------------------------
    public $fecha;

    // Constructor -----------------------------------------------------------------------------------------------------
    function __construct() {
        
    }

    // Getters ---------------------------------------------------------------------------------------------------------
    function getFecha() {
        return $this->fecha;
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
        $this->setFecha($fecha);
    }

    function setFecha($fecha) {
        $this->fecha = $fecha;
    }

    // Métodos =========================================================================================================
    // CREATE, UPDATE, DELETE ------------------------------------------------------------------------------------------

    /**
     * Adición de un día festivo al calendario de disponibilidad para citas
     * 
     * @return boolean - Creación con éxito (true) o no (false)
     */
    public function create() {
        // Si el horario y la disponibilidad ya existen en la base de datos, utilizamos sus ids para asignarlos a la fecha correspondiente
        // Si no existen, los creamos y utilizamos los nuevos ids
        $idDisponibilidad = parent::getIdDisponibilidad();
        $idHorarios = parent::getIdHorarios();

        $sql = 'insert into fechas (id_disponibilidad, id_horarios, tipo, dia) values (?, ?, "F", ?);';
        $param = [$idDisponibilidad, $idHorarios, $this->fecha];

        return DB::getQueryStmt($sql, $param);
    }

    /**
     * Actualización de los datos de un día festivo
     * 
     * @param string $original - Fecha (día festivo) original
     * @return boolean - Actualización con éxito (true) o no (false)
     */
    public function update($original) {
        // Si el horario y la disponibilidad ya existen en la base de datos, utilizamos sus ids para asignarlos a la fecha correspondiente
        // Si no existen, los creamos y utilizamos los nuevos ids
        $idDisponibilidad = parent::getIdDisponibilidad();
        $idHorarios = parent::getIdHorarios();

        $sql = 'update fechas set id_disponibilidad = ?, id_horarios = ? where tipo = "F" and dia = ?';
        $param = [$idDisponibilidad, $idHorarios, $original];

        $sqlFecha = 'update fechas set dia = ? where dia = ?';
        $paramFecha = [$this->fecha, $original];

        return (DB::getQueryStmt($sql, $param) && DB::getQueryStmt($sqlFecha, $paramFecha));
    }

    /**
     * Actualización del año de los días festivos al año actual
     * 
     * @return boolean - Actualización con éxito (true) o no (false)
     */
    public static function updateYear() {
        $data = self::get(null);

        if ($data) {
            while ($row = $data->fetch()) {
                // Extraemos la fecha y la actualizamos con el año actual
                $fecha = date('Y') . '-' . date('m', strtotime($row['dia'])) . '-' . date('d', strtotime($row['dia']));
                $sql = 'update fechas set dia = ? where dia = ?;';
                $param = [$fecha, $row['dia']];
                $stmt = DB::getQueryStmt($sql, $param);

                if (!$stmt) {
                    break;
                }
            }

            return $stmt;
        } else {
            return false;
        }
    }

    /**
     * Eliminación de un día festivo
     * 
     * @return boolean - Eliminación con éxito (true) o no (false)
     */
    public function delete() {
        $sql = 'delete from fechas where dia = ?;';
        $param = [$this->fecha];

        return DB::getQueryStmt($sql, $param);
    }

    // GET -------------------------------------------------------------------------------------------------------------

    /**
     * Obtención de los días festivos
     * 
     * @param array search - Parámetros de búsqueda
     * @return PDOStatement - Calendarios obtenidos de la base de datos
     */
    public static function getFestivos($search) {
        $fecha1 = $search !== null ? $search['fecha1'] : null;
        $fecha2 = $search !== null ? $search['fecha2'] : null;
        $open = $search !== null ? $search['open'] : null;  // '<>' abiertos, '=' cerrados, 'like' null o ''

        $sql = 'select dia, manana1, manana2, tarde1, tarde2, duracion_cita, max_clientes from fechas f, disponibilidad d, horarios h where f.id_disponibilidad = d.id and f.id_horarios = h.id and f.tipo = "F" and';
        if ($open === '<>') {
            $sql .= ' (manana1 ' . $open . ' 0 or tarde1' . $open . ' 0)';
        } elseif ($open === '=') {
            $sql .= ' (manana1 ' . $open . ' 0 and tarde1' . $open . ' 0)';
        } else {
            $sql .= ' manana1 like "%%"';   // Se muestran todos los registros de días festivos
        }

        $param = [];

        if (!empty($fecha1)) {
            $sql .= ' and dia > ?';
            array_push($param, $fecha1);
        }

        if (!empty($fecha2)) {
            $sql .= ' and dia < ?';
            array_push($param, $fecha2);
        }
        
        $sql .= ' order by dia';

        return DB::getQueryStmt($sql, $param);
    }

    /**
     * Obtención de los datos para un día dado
     * 
     * @return Festivo - Día obtenido de la base de datos
     */
    public function getByFecha() {
        $sql = 'select * from fechas f, disponibilidad d, horarios h where f.id_disponibilidad = d.id and f.id_horarios = h.id and f.tipo = "F" and dia = ?;';
        $param = [$this->fecha];
        
        $stmt = DB::getQueryStmt($sql, $param);
        $row = $stmt->fetch();
        
        $festivo = new Festivo();
        $festivo->set($row['manana1'], $row['manana2'], $row['tarde1'], $row['tarde2'], $row['duracion_cita'], $row['max_clientes'], null, $this->fecha);

        return $festivo;
    }

    // CHECK -----------------------------------------------------------------------------------------------------------

    /**
     * Comprobación de la existencia del día festivo
     * 
     * @return boolean - Existencia de registros (true) o no (false)
     */
    public function check() {
        $sql = 'select * from fechas where tipo = "F" and dia = ?;';
        $param = [$this->fecha];

        return DB::getQueryCountBool($sql, $param);
    }

    // OTHERS ----------------------------------------------------------------------------------------------------------

    /**
     * Comprabación del tipo de día (laboral o festivo)
     * 
     * @param int $date - strtotime de una fecha
     * @return boolean - Existencia de registros (true) o no (false)
     */
    public static function is($date) {
        $fecha = date('Y-m-d', $date);

        $sql = 'select dia from fechas where tipo = "F" and dia = ?;';
        $param = [$fecha];

        return DB::getQueryCountBool($sql, $param);
    }

}
