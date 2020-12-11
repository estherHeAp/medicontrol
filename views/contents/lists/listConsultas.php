<?php

/**
 * Se mostrará un listado de consultas
 * 
 * @param Usuario $usuario
 * @param array $search - Criterios de búsqueda para mostrar solo las consultas buscadas
 * @return string
 */
function listConsultas($usuario, $search) {
    // Se utilizan los datos relativos a la consulta, la cita, el cliente y el empleado a través de un array multidimensional con los datos en este orden
    if ($usuario instanceof Cliente) {
        $data = $usuario->getConsultas($search);
    } elseif ($usuario instanceof Empleado) {
        $data = $usuario->getConsultas($search);
    }

    $list = '';

    if ($data === false) {
        $list .= '<p class="msg-error">Error en la obtención de consultas.</p>';
    } elseif (sizeof($data) == 0) {
        if (isset($search)) {
            $list .= '<p class="msg-info">No se han encontrado resultados</p>';
        } else {
            $list .= '<p class="msg-info">No hay consultas registradas</p>';
        }
    } else {
        $list .= '<table class="table table-striped table-borderless">'
                . '<thead class="thead-light">'
                . '<tr>'
                . '<th scope="col">Fecha</th>'
                . '<th scope="col">Hora</th>'
                . '<th scope="col">Asunto</th>'
                . '<th scope="col">Descripción</th>'
                . '<th scope="col">Pruebas</th>'
                . '<th scope="col">Pruebas detalles</th>'
                . '<th scope="col">Tratamientos</th>'
                . '<th scope="col">Tratamientos detalles</th>'
                . '<th scope="col">Otros</th>'
                . '<th scope="col">Importe</th>'
                . '<th scope="col">Pago</th>';

        if ($usuario instanceof Empleado) {
            $list .= '<th scope="col">Cliente</th>';
        }

        $list .= '<th scope="col">Encargado</th>';

        if ($usuario instanceof Empleado) {
            $list .= '<th scope="col">Acción</th>';
        }

        $list .= '</tr>'
                . '</thead>'
                . '<tbody>';

        for($i = 0; $i < sizeof($data); $i++) {
            $list .= '<tr>'
                    . '<td scope="row">' . $data[$i][1]->getFecha() . '</td>'
                    . '<td>' . $data[$i][1]->getHora() . '</td>'
                    . '<td>' . $data[$i][1]->getAsunto() . '</td>'
                    . '<td>' . $data[$i][0]->getDescripcion() . '</td>'
                    . '<td>';
            if ($data[$i][0]->getPruebas() == 0 || $data[$i][0]->getPruebas() == null) {
                $list .= 'N';
            } elseif ($data[$i][0]->getPruebas() == 1) {
                $list .= 'S';
            }
            $list .= '</td>'
                    . '<td>' . $data[$i][0]->getPruebasDet() . '</td>'
                    . '<td>';
            if ($data[$i][0]->getTratamientos() == 0 || $data[$i][0]->getTratamientos() == null) {
                $list .= 'N';
            } elseif ($data[$i][0]->getTratamientos() == 1) {
                $list .= 'S';
            }
            $list .= '</td>'
                    . '<td>' . $data[$i][0]->getTratamientosDet() . '</td>'
                    . '<td>' . $data[$i][0]->getOtros() . '</td>'
                    . '<td>' . $data[$i][0]->getImporte() . '</td>'
                    . '<td>';
            if ($data[$i][0]->getPago() == 0 || $data[$i][0]->getPago() == null) {
                $list .= 'N';
            } elseif ($data[$i][0]->getPago() == 1) {
                $list .= 'S';
            }
            $list .= '</td>';
            if ($usuario instanceof Empleado) {
                $list .= '<td>' . $data[$i][2] . '</td>';
            }
            $list .= '<td>' . $data[$i][3] . '</td>';
            if ($usuario instanceof Empleado) {
                $list .= '<td>'
                        . '<form action="?c=consultas&a=action" method="post" id="formAction">'
                        . '<input type="hidden" name="js" class="js" value="0">'
                        . '<input type="hidden" name="idConsulta" class="idConsulta" value="' . $data[$i][0]->getId() . '">'
                        . '<input type="submit" name="btnMod" class="btn btn-primary btnMod fa fa-input" value="&#xf044">'
                        . '<input type="submit" name="btnDel" class="btn btn-danger btnDel fa fa-input" value="&#xf2ed">'
                        . '</form>'
                        . '</td>';
            }
            $list .= '</tr>';
        }

        $list .= '</tbody>'
                . '</table>';
    }

    return $list;
}
