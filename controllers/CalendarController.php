<?php

/**
 * Métodos para la navegación por el calendario de citas y la obtención de los horarios disponibles para cita
 *
 * @author Esther
 */
class CalendarController {

    /**
     * Navegación del calendario de citas: mes anterior
     */
    public function mesAnterior() {
        $usuario = filterSession('usuario');
        $cita = filterSession('cita');
        $fecha = filterSession('fecha');    // A qué formulario pertenece la fecha guardada

        $msg = msg();

        // Datos del formulario
        $js = filterGet('js');
        $all = filterGet('all');
        $action = filterGet('action');
        $mes = filterGet('mes');
        $anio = filterGet('anio');

        $msg['marcas'] = $fecha;

        if ($action === 'update') {
            $msg['marcas'][$action]['original'] = isset($cita) ? $cita : '';
            $msg['marcas'][$action]['active'] = false;   // No conservar marca de la fecha original, ya que conserva éste calendario y no podemos cambiarlo
        }

        // Datos necesarios
        if (isset($mes) && isset($anio)) {
            // Si el mes ha llegado a enero, no seguiremos bajando
            if (($mes - 1) >= 1) {
                $msg['calendar'][$action] = Calendario::createCalendar($all, $action, $mes - 1, $anio, $msg['marcas']);
            } else {
                $msg['calendar'][$action] = Calendario::createCalendar($all, $action, $mes, $anio, $msg['marcas']);
            }
        } else {
            $msg['msg']['error'] = 'Datos sin introducir.';
        }

        // Conservamos formulario y listado de citas
        $msg['list'] = listCitas($usuario, null, $all);
        if ($action === 'update') {
            $msg['form'] = formUpdateCita($msg, $usuario, $cita, $all);
        }

        // Redireccionamiento (según JS activado o no)
        redirect($js, $msg);
    }

    /**
     * Navegación del calendario de citas: mes siguiente
     */
    public function mesPosterior() {
        $usuario = filterSession('usuario');
        $cita = filterSession('cita');
        $fecha = filterSession('fecha');

        $msg = msg();

        // Datos del formulario
        $js = filterGet('js');
        $all = filterGet('all');
        $action = filterGet('action');
        $mes = filterGet('mes');
        $anio = filterGet('anio');

        $msg['marcas'] = $fecha;

        if ($action === 'update') {
            $msg['marcas'][$action]['original'] = isset($cita) ? $cita : '';
            $msg['marcas'][$action]['active'] = false;   // No conservar marca de la fecha original, ya que conserva éste calendario y no podemos cambiarlo
        }

        // Datos necesarios
        if (isset($mes) && isset($anio)) {
            // Si el mes ha llegado a diciembre, no seguiremos subiendo
            if (($mes + 1) <= 12) {
                $msg['calendar'][$action] = Calendario::createCalendar($all, $action, $mes + 1, $anio, $msg['marcas']);
            } else {
                $msg['calendar'][$action] = Calendario::createCalendar($all, $action, $mes, $anio, $msg['marcas']);
            }
        } else {
            $msg['msg']['error'] = 'Datos sin introducir.';
        }

        // Conservamos formulario y listado de citas
        $msg['list'] = listCitas($usuario, null, $all);
        if ($action === 'update') {
            $msg['form'] = formUpdateCita($msg, $usuario, $cita, $all);
        }

        // Redireccionamiento (según JS activado o no)
        redirect($js, $msg);
    }

    // -----------------------------------------------------------------------------------------------------------------

    /**
     * Navegación del calendario de citas: año anterior
     */
    public function anioAnterior() {
        $usuario = filterSession('usuario');
        $cita = filterSession('cita');
        $fecha = filterSession('fecha');

        $msg = msg();

        // Datos del formulario
        $js = filterGet('js');
        $all = filterGet('all');
        $action = filterGet('action');
        $mes = filterGet('mes');
        $anio = filterGet('anio');

        $msg['marcas'] = $fecha;

        if ($action === 'update') {
            $msg['marcas'][$action]['original'] = isset($cita) ? $cita : '';
            $msg['marcas'][$action]['active'] = false;   // No conservar marca de la fecha original, ya que conserva éste calendario y no podemos cambiarlo
        }

        // Datos necesarios
        if (isset($mes) && isset($anio)) {
            $msg['calendar'][$action] = Calendario::createCalendar($all, $action, $mes, $anio - 1, $msg['marcas']);
        } else {
            $msg['msg']['error'] = 'Datos sin introducir.';
        }

        // Conservamos formulario y listado de citas
        $msg['list'] = listCitas($usuario, null, $all);
        if ($action === 'update') {
            $msg['form'] = formUpdateCita($msg, $usuario, $cita, $all);
        }

        // Redireccionamiento (según JS activado o no)
        redirect($js, $msg);
    }

    /**
     * Navegación del calendario de citas: año siguiente
     */
    public function anioPosterior() {
        $usuario = filterSession('usuario');
        $cita = filterSession('cita');
        $fecha = filterSession('fecha');

        $msg = msg();

        // Datos del formulario
        $js = filterGet('js');
        $all = filterGet('all');
        $action = filterGet('action');
        $mes = filterGet('mes');
        $anio = filterGet('anio');

        $msg['marcas'] = $fecha;

        if ($action === 'update') {
            $msg['marcas'][$action]['original'] = isset($cita) ? $cita : '';
            $msg['marcas'][$action]['active'] = false;   // No conservar marca de la fecha original, ya que conserva éste calendario y no podemos cambiarlo
        }

        // Datos necesarios
        if (isset($mes) && isset($anio)) {
            $msg['calendar'][$action] = Calendario::createCalendar($all, $action, $mes, $anio + 1, $msg['marcas']);
        } else {
            $msg['msg']['error'] = 'Datos sin introducir.';
        }

        // Conservamos formulario y listado de citas
        $msg['list'] = listCitas($usuario, null, $all);
        if ($action === 'update') {
            $msg['form'] = formUpdateCita($msg, $usuario, $cita, $all);
        }

        // Redireccionamiento (según JS activado o no)
        redirect($js, $msg);

    }

    // -----------------------------------------------------------------------------------------------------------------

    /**
     * Obtención de los horarios disponibles para cita en un día dado
     */
    public function getHorario() {
        $usuario = filterSession('usuario');
        $cita = filterSession('cita');

        $msg = msg();

        // Datos del formulario
        $js = filterGet('js');
        $all = filterGet('all');
        $action = filterGet('action');
        $dia = filterGet('dia');
        $mes = filterGet('mes');
        $anio = filterGet('anio');
        
        if ($action === 'update') {
            $msg['marcas'][$action]['original'] = isset($cita) ? $cita : '';
            $msg['marcas'][$action]['active'] = true;
        }

        // Datos necesarios
        if (isset($dia) && isset($mes) && isset($anio)) {
            $fecha = $anio . '-' . $mes . '-' . $dia;   // Marca en el calendario

            $msg['marcas'][$action]['seleccion'] = $fecha;

            // Conservamos el calendario utilizado y obtenemos el horario solicitado
            $msg['calendar'][$action] = Calendario::createCalendar($all, $action, $mes, $anio, $msg['marcas']);
            $msg['horario'][$action] = Calendario::createHorario(date('j', strtotime($fecha)), $mes, $anio, $msg['marcas']);

            // Conservamos formulario y listado de citas
            $msg['list'] = listCitas($usuario, null, $all);
            if ($action === 'update') {
                $msg['form'] = formUpdateCita($msg, $usuario, $cita, $all);
            }

            // Almacenamos la fecha deseada para su posterior uso (string)
            $_SESSION['fecha'] = serialize($msg['marcas']);
        } else {
            $msg['msg']['error'] = 'Datos sin introducir.';
        }
        
        // Redireccionamiento (según JS activado o no)
        redirect($js, $msg);
    }

}
