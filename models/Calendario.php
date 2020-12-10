<?php

/**
 * Disponibilidad y horarios de los diferentes días del año
 *
 * @author Esther
 */
class Calendario {

    // Atributos -------------------------------------------------------------------------------------------------------
    protected $manana1,
            $manana2,
            $tarde1,
            $tarde2,
            $duracion,
            $max;

    // Constructor -----------------------------------------------------------------------------------------------------
    function __construct() {
        
    }

    // Getters ---------------------------------------------------------------------------------------------------------
    function getManana1() {
        return $this->manana1;
    }

    function getManana2() {
        return $this->manana2;
    }

    function getTarde1() {
        return $this->tarde1;
    }

    function getTarde2() {
        return $this->tarde2;
    }

    function getDuracion() {
        return $this->duracion;
    }

    function getMax() {
        return $this->max;
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
        $this->setManana1($manana1);
        $this->setManana2($manana2);
        $this->setTarde1($tarde1);
        $this->setTarde2($tarde2);
        $this->setDuracion($duracion);
        $this->setMax($max);
    }

    function setManana1($manana1) {
        $this->manana1 = $manana1;
    }

    function setManana2($manana2) {
        $this->manana2 = $manana2;
    }

    function setTarde1($tarde1) {
        $this->tarde1 = $tarde1;
    }

    function setTarde2($tarde2) {
        $this->tarde2 = $tarde2;
    }

    function setDuracion($duracion) {
        $this->duracion = $duracion;
    }

    function setMax($max) {
        $this->max = $max;
    }

    // Métodos =========================================================================================================
    // CREATE, UPDATE, DELETE ------------------------------------------------------------------------------------------

    /**
     * Creación del calendario de citas
     * 
     * @param string $all - 0 o 1 según se desee mostrar todas las citas o solo las pendientes respectivamente
     * @param string $action - Calendario a que afecta (add o update)
     * @param mixed $mes
     * @param mixed $anio
     * @param array $marcas - Fecha con la que se va a trabajar
     * @return string
     */
    public static function createCalendar($all, $action, $mes, $anio, $marcas) {
        // Fecha seleccionada en el calendario correspondiente
        $seleccion = !empty($marcas[$action]['seleccion']) ? $marcas[$action]['seleccion'] : null;

        $marca = isset($seleccion) ? $seleccion : null;

        $original = null;
        if ($action === 'update') {
            // Fecha original de una cita ya creada
            $cita = !empty($marcas[$action]['original']) ? $marcas[$action]['original'] : null;
            $original = isset($cita) ? $cita->getFecha() : null;

            // Uso de la fecha original solo cuando no se ha seleccionado otra y conservación del nuevo calendario
            $marca = isset($seleccion) ? $seleccion : $original;
            $active = isset($marcas[$action]['active']) ? $marcas[$action]['active'] : null;

            if (isset($active)) {
                if ($active == true) {
                    if (!empty($original) && empty($seleccion)) {
                        $mes = date('m', strtotime($marca));
                        $anio = date('Y', strtotime($marca));
                    }
                }
            }
        }

        $calendar = '';

        $meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
        $diasSemana = ['L', 'M', 'X', 'J', 'V', 'S', 'D'];

        $totalDiasMes = cal_days_in_month(CAL_GREGORIAN, $mes, $anio);
        $primerDiaMes = date('N', mktime(0, 0, 0, $mes, 1, $anio));

        // CALENDARIO
        $calendar .= '<table class="calendar">';
        $calendar .= '<thead>';

        // Navegación
        $calendar .= '<tr>';
        $calendar .= '<th><a href="?c=calendar&a=mesAnterior&js=0&all=' . $all . '&action=' . $action . '&mes=' . $mes . '&anio=' . $anio . '" class="mesAnterior" tabindex="0"><<</a></th>';
        $calendar .= '<th class="mes">' . $meses[$mes - 1] . '</th>';
        $calendar .= '<th><a href="?c=calendar&a=mesPosterior&js=0&all=' . $all . '&action=' . $action . '&mes=' . $mes . '&anio=' . $anio . '" class="mesPosterior" tabindex="0">>></a></th>';
        $calendar .= '<th></th>';
        $calendar .= '<th><a href="?c=calendar&a=anioAnterior&js=0&all=' . $all . '&action=' . $action . '&mes=' . $mes . '&anio=' . $anio . '" class="anioAnterior" tabindex="0"><<</a></th>';
        $calendar .= '<th class="anio">' . $anio . '</th>';
        $calendar .= '<th><a href="?c=calendar&a=anioPosterior&js=0&all=' . $all . '&action=' . $action . '&mes=' . $mes . '&anio=' . $anio . '" class="anioPosterior" tabindex="0">>></a></th>';
        $calendar .= '</tr>';

        // Días de la semana
        $calendar .= '<tr>';
        foreach ($diasSemana as $dia) {
            $calendar .= '<th>' . $dia . '</th>';
        }
        $calendar .= '</tr>';

        $calendar .= '</thead>';

        //

        $calendar .= '<tbody>';
        $calendar .= '<tr>';

        // Días en blanco hasta el primer día del mes
        for ($i = 0; $i < $primerDiaMes - 1; $i++) {
            $calendar .= '<td></td>';
        }

        // Días del mes
        for ($i = 1; $i <= $totalDiasMes; $i++) {
            $fecha = $anio . '-' . $mes . '-' . $i;

            $class = 'dia';
            $link = '<a href="?c=calendar&a=getHorario&dia=' . $i . '&all=' . $all . '&action=' . $action . '&mes=' . $mes . '&anio=' . $anio . '" tabindex="0">' . $i . '</a>';

            // ¿Día $i deshabilitado?
            if (!self::checkDay($i, $mes, $anio, $original)) {
                $class .= ' disabled';
                $link = $i;
            }

            // ¿Día marcado?
            if (strtotime($marca) === strtotime($fecha)) {
                $class .= ' mark';
            }

            // Mostramos el día
            $calendar .= '<td class="' . $class . '">' . $link . '</td>';

            // División por semana (añadimos una fila cada 7 días)
            if (($i + $primerDiaMes - 1) % 7 == 0) {
                $calendar .= '</tr><tr>';
            }
        }
        $calendar .= '</tr>';
        $calendar .= '</tbody>';
        $calendar .= '</table>';

        return $calendar;
    }

    /**
     * Creación de los horarios de disponibilidad para cita
     * 
     * @param string $dia
     * @param string $mes
     * @param string $anio
     * @param array $marcas - Fecha con la que se va a trabajar
     * @return string
     */
    public static function createHorario($dia, $mes, $anio, $marcas) {
        // Solo necesitamos marcar la hora original de la cita
        // No utilizaremos la nueva fecha seleccionada
        // Datos de la cita original (Object(Cita))
        $cita = !empty($marcas['update']['original']) ? $marcas['update']['original'] : null;

        // Extracción de la fecha y hora originiales de la cita
        $fechaOriginal = isset($cita) ? $cita->getFecha() : null;
        $horaOriginal = isset($cita) ? $cita->getHora() : null;

        // Formación del día (fecha + hora) original de la cita
        $original = isset($fechaOriginal) && isset($horaOriginal) ?
                mktime(date('H', strtotime($horaOriginal)), date('i', strtotime($horaOriginal)), date('s', strtotime($horaOriginal)), date('n', strtotime($fechaOriginal)), date('j', strtotime($fechaOriginal)), date('Y', strtotime($fechaOriginal))) :
                null;

        $horario = '';

        // Día de la nueva fecha de la cita
        $fecha = mktime(0, 0, 0, $mes, $dia, $anio);

        // Horario correspondiente a la nueva fecha de la cita
        $horas = self::getHours($fecha);
        $longHoras = sizeof($horas);

        $horario .= '<option value=""></option>';
        for ($i = 0; $i < $longHoras; $i++) {
            // Comprobamos la disponibilidad de la hora
            if (self::checkHour($horas[$i], $dia, $mes, $anio, $marcas)) {
                $horario .= '<option value="' . $horas[$i] . '"';

                // Hora para el día de la cita (fecha + hora) = Hora para el mismo día
                $hora = mktime(date('H', strtotime($horas[$i])), date('i', strtotime($horas[$i])), date('s', strtotime($horas[$i])), $mes, $dia, $anio);
                
                // Para el calendario de actualización de citas, debemos conservar la nueva hora seleccionada
                $nueva = !empty($marcas['update']['nuevaHora']) ? $marcas['update']['nuevaHora'] : null;
                $h = isset($nueva) ? $nueva : $horaOriginal;
                
                if ($original == $hora && $h == $horas[$i]) {
                    $horario .= ' selected';
                }

                $horario .= '>' . $horas[$i] . '</option>';
            }
        }

        return $horario;
    }

    // GET -------------------------------------------------------------------------------------------------------------

    /**
     * Obtención de la información sobre disponibilidad y horarios del día pasado como parámetro
     * 
     * @param int $date - Fecha a buscar en mktime
     * @return PDOStatement - Información del día obtenida de a base de datos
     */
    private static function get($date) {
        $param = [];
        $sql = 'select * from fechas f, disponibilidad d, horarios h where f.id_disponibilidad = d.id and f.id_horarios = h.id and f.tipo = "';
        if (Festivo::is($date)) {
            $sql .= 'F';
            array_push($param, date('Y-m-d', $date));
        } else {
            $sql .= 'L';
            array_push($param, date('N', $date));
        }
        $sql .= '" and dia = ?;';

        return DB::getQueryStmt($sql, $param);
    }

    /**
     * Obtención del número de citas concertadas en un día y una hora determiandos
     * 
     * @param int $date - Fecha a buscar en mktime
     * @param string $hour - Hora
     * @return int - Número de citas concertadas
     */
    private function getCitas($date, $hour) {
        $fecha = date('Y-m-d', $date);
        $hora = isset($hour) ? $hour : '';

        $sql = 'select * from citas where fecha = ? and hora like ?;';
        $param = [$fecha, "%$hora%"];

        return DB::getQueryCount($sql, $param);
    }

    /**
     * Obtenemos el id de la fecha a partir del día pasado como parámetro
     * 
     * @param string $date
     * @return string - ID de la fecha
     */
    public static function getIdFecha($date) {
        $idFecha = '';

        $dia = '';

        // ¿Día festivo?
        if (Festivo::is(strtotime($date))) {
            $dia = $date;
        } else {
            $dia = date('N', strtotime($date));
        }

        $sql = 'select id from fechas where dia = ?;';
        $param = [$dia];

        $stmt = DB::getQueryStmt($sql, $param);

        if ($stmt) {
            $row = $stmt->fetch();
            $idFecha = $row['id'];
        }

        return $idFecha;
    }

    /**
     * Comprobamos si es una disponibilidad nueva para crearla, si ya existe en la BD solo asociaremos el id a la tabla fechas
     * 
     * @return string - ID de la tabla disponibilidad
     */
    protected function getIdDisponibilidad() {
        $idDisponibilidad = '';

        $sqlId = 'select id from disponibilidad where duracion_cita = ? and max_clientes = ?;';
        $paramId = [$this->duracion, $this->max];

        $stmt = DB::getQueryStmt($sqlId, $paramId);

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch();
            $idDisponibilidad = $row['id'];
        } else {
            $sql = 'insert into disponibilidad (id, duracion_cita, max_clientes) values (null, ?, ?);';
            $param = [$this->duracion, $this->max];

            if (DB::getQueryStmt($sql, $param)) {
                $stmt = DB::getQueryStmt($sqlId, $paramId);
                $row = $stmt->fetch();
                $idDisponibilidad = $row['id'];
            }
        }
        
        return $idDisponibilidad;
    }

    /**
     * Comprobamos si son horarios nuevos para crearlos, si ya existen en la BD solo asociaremos el id a la tabla de fechas
     * 
     * @return string - ID de la tabla horarios
     */
    protected function getIdHorarios() {
        $idHorario = '';

        $sqlId = 'select id from horarios where manana1 = ? and manana2 = ? and tarde1 = ? and tarde2 = ?;';
        $paramId = [$this->manana1, $this->manana2, $this->tarde1, $this->tarde2];

        $stmt = DB::getQueryStmt($sqlId, $paramId);

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch();
            $idHorario = $row['id'];
        } else {
            $sql = 'insert into horarios (id, manana1, manana2, tarde1, tarde2) values (null, ?, ?, ?, ?);';
            $param = [$this->manana1, $this->manana2, $this->tarde1, $this->tarde2];

            if (DB::getQueryStmt($sql, $param)) {
                $stmt = DB::getQueryStmt($sqlId, $paramId);
                $row = $stmt->fetch();
                $idHorario = $row['id'];
            }
        }
        
        return $idHorario;
    }

    /**
     * Obtención de los horarios en un día dado
     * 
     * @param int $fecha
     * @return array
     */
    private static function getHours($fecha) {
        $stmt = self::get($fecha);

        $row = $stmt->fetch();

        $manana1 = strtotime($row['manana1']);
        $manana2 = strtotime($row['manana2']);
        $tarde1 = strtotime($row['tarde1']);
        $tarde2 = strtotime($row['tarde2']);
        $maxDuracion = 60 * $row['duracion_cita'];
        $maxClientes = $row['max_clientes'];

        $horas = [];

        // Horarios de mañana
        if ($manana1 !== $manana2) {
            for ($i = $manana1; $i < $manana2; $i += $maxDuracion) {
                $hora = date('H:i:s', $i);
                array_push($horas, $hora);
            }
        }

        // Horarios de tarde
        if ($tarde1 !== $tarde2) {
            for ($i = $tarde1; $i < $tarde2; $i += $maxDuracion) {
                $hora = date('H:i:s', $i);
                array_push($horas, $hora);
            }
        }

        return $horas;
    }

    // CHECK -----------------------------------------------------------------------------------------------------------

    /**
     * Comprobación de disponibiliad para un día dado
     * 
     * @param string $dia
     * @param string $mes
     * @param string $anio
     * @param string $marca - Fecha original de la cita
     * @return boolean
     */
    private static function checkDay($dia, $mes, $anio, $marca) {
        $original = mktime(0, 0, 0, date('m', strtotime($marca)), date('j', strtotime($marca)), date('Y', strtotime($marca)));

        $fecha = mktime(0, 0, 0, $mes, $dia, $anio);
        $actual = mktime(0, 0, 0, date('m'), date('j'), date('Y'));

        // Habilitamos la fecha original de la cita para volver a seleccionarla
        if ($original == $fecha) {
            return true;
        } else {
            // Deshabilitamos días festivos sin posibiliad de cita y fechas anteriores a la actual
            // ¿Día festivo?
            if (Festivo::is($fecha)) {
                $maxCitas = self::maxCitas($fecha);
                if ($fecha >= $actual && $maxCitas > 0) {
                    $citas = self::getCitas($fecha, null);
                    if ($citas < $maxCitas) {
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            } else {
                // ¿Fecha posterior a la actual?
                if ($fecha >= $actual) {
                    $maxCitas = self::maxCitas($fecha);
                    if ($maxCitas > 0) {
                        $citas = self::getCitas($fecha, null);
                        if ($citas < $maxCitas) {
                            return true;
                        } else {
                            return false;
                        }
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            }
        }
    }

    /**
     * Comprobamos la disponibilidad de una hora dada
     * 
     * @param string $hora
     * @param string $dia
     * @param string $mes
     * @param string $anio
     * @param array $marcas - Fecha con la que se va a trabajar
     * @return boolean
     */
    private static function checkHour($hora, $dia, $mes, $anio, $marcas) {
        // Solo necesitamos marcar la hora original de la cita
        // No utilizaremos la nueva fecha seleccionada
        // Datos de la cita original (Object(Cita))
        $cita = !empty($marcas['update']['original']) ? $marcas['update']['original'] : null;

        $fechaOriginal = isset($cita) ? $cita->getFecha() : null;
        $horaOriginal = isset($cita) ? $cita->getHora() : null;

        $original = isset($fechaOriginal) && isset($horaOriginal) ?
                mktime(date('H', strtotime($horaOriginal)), date('i', strtotime($horaOriginal)), date('s', strtotime($horaOriginal)), date('n', strtotime($fechaOriginal)), date('j', strtotime($fechaOriginal)), date('Y', strtotime($fechaOriginal))) :
                null;

        $fecha = mktime(0, 0, 0, $mes, $dia, $anio);

        $nueva = mktime(date('H', strtotime($hora)), date('i', strtotime($hora)), date('s', strtotime($hora)), $mes, $dia, $anio);
        $actual = mktime(date('H'), date('i'), date('s'), date('n'), date('j'), date('Y'));

        // ¿Coinciden las fechas?
        if ($original === $nueva) {
            return true;
        } else {
            // ¿Hora pasada?
            if ($nueva > $actual) {
                $stmt = self::get($fecha);
                $row = $stmt->fetch();

                $maxCitas = $row['max_clientes'];
                $numCitas = self::getCitas($fecha, $hora);

                if ($numCitas < $maxCitas) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }

    // OTHERS ----------------------------------------------------------------------------------------------------------    

    /**
     * Obtención del número máximo de citas posibles a realizar en un día, según el horario, la duración máxima por cita y el máximo de citas por hora (o duración establecida)
     * 
     * @param int $date - Fecha a buscar en mktime
     * @return int - Máximo de citas permitidas en un día
     */
    private function maxCitas($date) {
        $param = [];
        $sql = 'select * from fechas f, disponibilidad d, horarios h where f.id_disponibilidad = d.id and f.id_horarios = h.id and f.tipo = "';
        if (Festivo::is($date)) {
            $sql .= 'F';
            array_push($param, date('Y-m-d', $date));
        } else {
            $sql .= 'L';
            array_push($param, date('N', $date));
        }
        $sql .= '" and dia = ?;';

        $stmt = DB::getQueryStmt($sql, $param);

        $row = $stmt->fetch();

        $manana1 = strtotime($row['manana1']);
        $manana2 = strtotime($row['manana2']);
        $tarde1 = strtotime($row['tarde1']);
        $tarde2 = strtotime($row['tarde2']);
        $maxDuracion = 60 * $row['duracion_cita'];
        $maxClientes = $row['max_clientes'];

        $manana = $manana2 - $manana1;
        $tarde = $tarde2 - $tarde1;
        $total = $manana + $tarde;

        $max = 0;
        if ($maxDuracion > 0) {
            $max = ($total / $maxDuracion) * $maxClientes;
        }

        return $max;
    }

}
