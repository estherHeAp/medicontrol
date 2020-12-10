<?php

/**
 * Se mostrará un listado de días laborales con sus respectivos horarios y datos de disponibilidad
 * 
 * @return string
 */
function listCalendars() {
    $data = Laboral::getLaborales();

    $list = '';

    if (sizeof($data) == 0) {
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

        for ($i = 0; $i < sizeof($data); $i++) {
            $list .= '<tr>'
                    . '<td scope="row">' . $data[$i]->getDia() . '</td>'
                    . '<td>' . $data[$i]->getManana1() . '</td>'
                    . '<td>' . $data[$i]->getManana2() . '</td>'
                    . '<td>' . $data[$i]->getTarde1() . '</td>'
                    . '<td>' . $data[$i]->getTarde2() . '</td>'
                    . '<td>' . $data[$i]->getDuracion() . '</td>'
                    . '<td>' . $data[$i]->getMax() . '</td>'
                    . '<td>'
                    . '<form action="?c=calendarConfig&a=action" method="post" id="formAction">'
                    . '<input type="hidden" name="js" class="js" value="0">'
                    . '<input type="hidden" name="dia" class="dia" value="' . $data[$i]->getDia() . '">'
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
