<?php

/**
 * Se mostrará un listado de citas
 * 
 * @param Usuario $usuario
 * @param array $search - Criterios de búsqueda para mostrar solo las citas buscadas
 * @param string $all - Indicador para determinar si mostrar todas las citas o solo las pendientes
 * @return string
 */
function listCitas($usuario, $search, $all) {
    if ($usuario instanceof Cliente) {
        $data = $usuario->getCitasPendientes();
    } elseif ($usuario instanceof Empleado) {
        if ($all == 0) {
            $data = $usuario->getCitasPendientes($search);
        } else {
            $data = $usuario->getCitas($search);
        }
    }

    $list = '';

    if ($data === false) {
        $list .= '<p class="msg-error">Error en la obtención de citas.</p>';
    } elseif (sizeof($data) == 0) {
        if (isset($search)) {
            $list .= '<p class="msg-info">No se han encontrado resultados</p>';
        } else {
            $list .= '<p class="msg-info">No hay citas registradas</p>';
        }
    } else {
        $list .= '<table class="table table-striped table-borderless">'
                . '<thead class="thead-light">'
                . '<tr>'
                . '<th scope="col">Fecha</th>'
                . '<th scope="col">Hora</th>'
                . '<th scope="col">Asunto</th>';
        if ($usuario instanceof Empleado) {
            $list .= '<th scope="col">Cliente</th>';
        }
        $list .= '<th scope="col">Encargado</th>'
                . '<th scope="col">Acción</th>'
                . '</tr>'
                . '</thead>'
                . '<tbody>';

        for($i = 0; $i < sizeof($data); $i += 3) {
            $list .= '<tr>'
                    . '<td scope="row">' . $data[$i]->getFecha() . '</td>'
                    . '<td>' . $data[$i]->getHora() . '</td>'
                    . '<td>' . $data[$i]->getAsunto() . '</td>';
            if ($usuario instanceof Empleado) {
                $list .= '<td>' . $data[$i+1] . '</td>';
            }
            $list .= '<td>' . $data[$i+2] . '</td>'
                    . '<td>'
                    . '<form action="?c=citas&a=action" method="post" id="formAction">'
                    . '<input type="hidden" name="js" class="js" value="0">'
                    . '<input type="hidden" name="all" id="all" value="' . $all . '">'
                    . '<input type="hidden" name="idCita" class="idCita" value="' . $data[$i]->getId() . '">'
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
