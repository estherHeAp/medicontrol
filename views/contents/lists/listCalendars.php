<?php

/**
 * Se mostrará un listado de días laborales con sus respectivos horarios y datos de disponibilidad
 * 
 * @return string
 */
function listCalendars() {
    $data = Laboral::getLaborales();

    $list = '';

    if ($data === false || $data->rowCount() === 0) {
        $list .= '<p class="msg-error">Error en la obtención de calendarios.</p>';
    } else {
        $list .= '<table class="table table-striped table-borderless">'
                . '<thead class="thead-light">'
                . '<tr>'
                . '<th scope="col">Día</th>'
                . '<th scope="col">Apertura mañana</th>'
                . '<th scope="col">Cierre mañana</th>'
                . '<th scope="col">Apertura tarde</th>'
                . '<th scope="col">Cierre tarde</th>'
                . '<th scope="col">Duración máx.</th>'
                . '<th scope="col">Núm. máx. clientes</th>'
                . '<th scope="col">Acción</th>'
                . '</tr>'
                . '</thead>'
                . '<tbody>';

        while ($row = $data->fetch()) {
            $list .= '<tr>'
                    . '<td scope="row">' . $row['dia'] . '</td>'
                    . '<td>' . $row['manana1'] . '</td>'
                    . '<td>' . $row['manana2'] . '</td>'
                    . '<td>' . $row['tarde1'] . '</td>'
                    . '<td>' . $row['tarde2'] . '</td>'
                    . '<td>' . $row['duracion_cita'] . '</td>'
                    . '<td>' . $row['max_clientes'] . '</td>'
                    . '<td>'
                    . '<form action="?c=calendarConfig&a=action" method="post" id="formAction">'
                    . '<input type="hidden" name="js" class="js" value="0">'
                    . '<input type="hidden" name="dia" class="dia" value="' . $row['dia'] . '">'
                    . '<input type="submit" name="btnMod" class="btn btn-primary btnMod fa fa-input" value="&#xf044">'
                    . '</form>'
                    . '</td>'
                    . '</tr>';
        }

        $list .= '</tbody>'
                . '</table>';
    }

    return $list;
}
