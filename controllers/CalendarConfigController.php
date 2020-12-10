<?php

/**
 * Controlador para la gestión del calendario laboral
 *
 * @author Esther
 */
class CalendarConfigController {

    /**
     * Cargamos la vista del calendario laboral
     */
    public function index() {
        require_once 'views/modules/header.php';
        require_once 'views/modules/navPerfil.php';
        require_once 'views/modules/nav.php';
        require_once 'views/modules/msg.php';
        require_once 'views/calendarConfig.php';
        require_once 'views/modules/footer.php';
    }

    // UPDATE ----------------------------------------------------------------------------------------------------------

    /**
     * Actualización de los datos de un día laboral
     */
    public function update() {
        $msg = msg();

        // Datos del formulario
        $js = filterPost('js');
        $dia = mb_strtoupper(filterPost('dia'));
        $manana1 = filterPost('manana1Update');
        $manana2 = filterPost('manana2Update');
        $tarde1 = filterPost('tarde1Update');
        $tarde2 = filterPost('tarde2Update');
        $maxDuracion = filterPost('duracionUpdate');
        $maxClientes = filterPost('maxUpdate');

        $btn = filterPost('btn');

        $btnUpdate = filterPost('btnUpdate');
        $btnClear = filterPost('btnClearUpdate');
        $btnCancel = filterPost('btnCancelUpdate');

        $calendar = new Laboral();

        if ($btn === 'Actualizar datos' || isset($btnUpdate)) {
            // Datos obligatorios
            if (isset($dia) && isset($manana1) && isset($manana2) && isset($tarde1) && isset($tarde2) && isset($maxDuracion) && isset($maxClientes)) {
                $calendar->set($manana1, $manana2, $tarde1, $tarde2, $maxDuracion, $maxClientes, $dia, null);

                if ($calendar->update()) {
                    $msg['msg']['conf'] = 'Calendario modificado.';
                    $msg['list'] = listCalendars();
                } else {
                    $msg['msg']['error'] = 'Ha ocurrido un error durante la actualización del calendario.';
                }
            } else {
                $msg['msg']['error'] = 'Error en la obtención de datos o datos sin cumplimentar.';
            }
        } elseif ($btn === 'Resetear datos' || isset($btnClear)) {
            $calendar->setDia($dia);
            $data = $calendar->getByDia();

            $msg['msg']['conf'] = 'Datos del formulario reseteados.';
            $msg['form'] = formUpdateCalendar($data);
        } elseif ($btn === 'Cancelar' || isset($btnCancel)) {
            $msg['msg']['conf'] = 'Formulario cancelado.';
        } // else $msg['msg']['error'] = 'No se ha seleccionado ninguna acción.';
        // Redireccionamiento (según JS activado o no)
        redirect($js, $msg);
    }

    // ACTION ----------------------------------------------------------------------------------------------------------

    /**
     * Acción solicitada desde el listado de días laborales (modificación de los datos del día laboral)
     */
    public function action() {
        $msg = msg();

        // Datos del formulario
        $js = filterPost('js');
        $dia = mb_strtoupper(filterPost('dia'));

        if (isset($dia)) {
            // Obtenemos los datos de la consulta
            $calendar = new Laboral();
            $calendar->setDia($dia);

            $data = $calendar->getByDia();

            // Mostramos el formulario con los datos de la consulta
            // Establecemos el mensaje de error a través del propio formulario 
            $msg['form'] = formUpdateCalendar($data);
        } else {
            $msg['msg']['error'] = 'La acción no ha podido ser realizada.';
        }

        // Redireccionamiento (según JS activado o no)
        redirect($js, $msg);
    }

}
