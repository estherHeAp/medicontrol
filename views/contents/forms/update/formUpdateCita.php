<?php

/**
 * Se mostrará un formulario para la modificación de una cita mostrando los datos de esta
 * 
 * @param array $msg - Datos que permitirán la carga del calendario correspondiente a los datos de la cita
 * @param Empleado $usuario
 * @param Cita $cita
 * @param string $all - Conservación de listados completos o solo de citas pendientes
 * @return string
 */
function formUpdateCita($msg, $usuario, $cita, $all) {
    $form = '';

    if (isset($cita)) {
        $form .= '<form action="?c=citas&a=update" method="post" id="formUpdate">'
                . '<fieldset>'
                . '<legend>Modificar cita</legend>'
                
                . '<input type="hidden" name="js" class="js" value="0">'
                . '<input type="hidden" name="action" class="action" value="update">'
                . '<input type="hidden" name="all" class="all" value="' . $all . '">'
                
                . '<div class="form-row">';
        if ($usuario instanceof Empleado) {
            $form .= '<div class="form-group col-12 selectEmpleados">'
                    . selectEmpleados($usuario, $cita->getEmpleado())
                    . '</div>';
        }

        $form .= '<div class="col-12 col-sm-6 text-center calendario">';
        if (isset($msg)) {
            if ($msg['calendar']['update'] !== '') {
                $form .= $msg['calendar']['update'];
            } else {
                $form .= Calendario::createCalendar($all, 'update', date('n', strtotime($cita->getFecha())), date('Y', strtotime($cita->getFecha())), $msg['marcas']);
            }
        } else {
            $form .= Calendario::createCalendar($all, 'update', date('n', strtotime($cita->getFecha())), date('Y', strtotime($cita->getFecha())), $msg['marcas']);
        }
        $form .= '</div>'
                
                . '<div class="col-12 col-sm-6">'
                . '<div class="row">'
                . '<div class="form-group col-12 p-0 horario">'
                . '<label for="horarioUpdate">Seleccionar hora:</label>'
                . '<select name="horarioUpdate" id="horarioUpdate" class="form-control" required>';
        if (isset($msg)) {
            if ($msg['horario']['update'] !== '') {
                // Horario generado al seleccionar un nuevo día
                $form .= $msg['horario']['update'];
            } else {
                // Horario con la hora original seleccionada
                $form .= Calendario::createHorario(date('j', strtotime($cita->getFecha())), date('n', strtotime($cita->getFecha())), date('Y', strtotime($cita->getFecha())), $msg['marcas']);
            }
        } else {
            // Horario con la hora original seleccionada
            $form .= Calendario::createHorario(date('j', strtotime($cita->getFecha())), date('n', strtotime($cita->getFecha())), date('Y', strtotime($cita->getFecha())), $msg['marcas']);
        }
        $form .= '</select>'
                . '</div>'
                
                . '<div class="form-group col-12 p-0">'
                . '<label for="asuntoUpdate">Asunto:</label>'
                . '<input type="text" name="asuntoUpdate" id="asuntoUpdate" class="form-control" value="' . $cita->getAsunto() . '" required>'
                . '</div>'
                . '</div>'
                . '</div>'
                
                . '<div class="col-12">'
                . '<input type="submit" name="btnUpdate" id="btnUpdate" class="btn btn-primary mr-1" value="Actualizar cita">'
                . '<input type="submit" name="btnClearUpdate" id="btnClearUpdate" class="btn btn-info mr-1" value="Resetear datos">'
                . '<input type="submit" name="btnCancelUpdate" id="btnCancelUpdate" class="btn btn-secondary" value="Cancelar">'
                . '</div>'
                . '</div>'
                . '</fieldset>'
                . '</form>';
    } else {
        $form .= '<p class="msg-error">Error en la obtención de datos</p>';
    }

    return $form;
}
