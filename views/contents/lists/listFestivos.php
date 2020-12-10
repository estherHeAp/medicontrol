<?php

/**
 * Se mostrará un listado de días festivos con sus respectivos horarios y datos de disponibilidad
 * 
 * @param array $search - Criterios de búsqueda para mostrar solo los días festivos buscados
 * @return string
 */
function listFestivos($search) {
    $data = Festivo::getFestivos($search);

    $list = '';

    if ($data === false) {
        $list .= '<p class="msg-error">Error en la obtención de días festivos.</p>';
    } elseif ($data->rowCount() === 0) {
        if (isset($search)) {
            $list .= '<p class="msg-info">No se han encontrado resultados</p>';
        } else {
            $list .= '<p class="msg-info">No existen días festivos registrados</p>';
        }
    } else {
        $list .= '<table class="table table-striped table-borderless">'
                . '<thead class="thead-light">'
                . '<tr>'
                . '<td colspan="8">'
                . '<form action="?c=festivos&a=updateYear" method="post" id="formUpdateYear" class="text-left">'
                . '<input type="hidden" name="js" class="js" value="0">'
                . '<input type="submit" name="btnUpdateYear" id="btnUpdateYear" class="btn btn-info" value="Actualizar fechas al año actual">'
                . '</form>'
                . '</td>'
                . '</tr>'
                . '<tr>'
                . '<th scope="col">Fecha</th>'
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
                    . '<form action="?c=festivos&a=action" method="post" id="formAction">'
                    . '<input type="hidden" name="js" class="js" value="0">'
                    . '<input type="hidden" name="fecha" class="fecha" value="' . $row['dia'] . '">'
                    . '<input type="submit" name="btnMod" class="btn btn-primary btnMod fa fa-input" value="&#xf044">'
                    . '<input type="submit" name="btnDel" class="btn btn-danger btnDel fa fa-input" value="&#xf2ed">'
                    . '</form>'
                    . '</td>'
                    . '</tr>';
        }

        $list .= '</tbody>'
                . '</table>';
    }

    return $list;
}
