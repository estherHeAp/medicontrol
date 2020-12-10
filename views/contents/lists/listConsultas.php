<?php

/**
 * Se mostrará un listado de consultas
 * 
 * @param Usuario $usuario
 * @param array $search - Criterios de búsqueda para mostrar solo las consultas buscadas
 * @return string
 */
function listConsultas($usuario, $search) {
    if ($usuario instanceof Cliente) {
        $data = $usuario->getConsultas($search);
    } elseif ($usuario instanceof Empleado) {
        $data = $usuario->getConsultas($search);
    }

    $list = '';

    if ($data === false) {
        $list .= '<p class="msg-error">Error en la obtención de consultas.</p>';
    } elseif ($data->rowCount() === 0) {
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

        while ($row = $data->fetch()) {
            $list .= '<tr>'
                    . '<td scope="row">' . $row['fecha'] . '</td>'
                    . '<td>' . $row['hora'] . '</td>'
                    . '<td>' . $row['asunto'] . '</td>'
                    . '<td>' . $row['descripcion'] . '</td>'
                    . '<td>';
            if ($row['pruebas'] == 0 || $row['pruebas'] == null) {
                $list .= 'N';
            } elseif ($row['pruebas'] == 1) {
                $list .= 'S';
            }
            $list .= '</td>'
                    . '<td>' . $row['pruebas_detalles'] . '</td>'
                    . '<td>';
            if ($row['tratamientos'] == 0 || $row['tratamientos'] == null) {
                $list .= 'N';
            } elseif ($row['tratamientos'] == 1) {
                $list .= 'S';
            }
            $list .= '</td>'
                    . '<td>' . $row['tratamientos_detalles'] . '</td>'
                    . '<td>' . $row['otros_detalles'] . '</td>'
                    . '<td>' . $row['importe'] . '</td>'
                    . '<td>';
            if ($row['pago'] == 0 || $row['pago'] == null) {
                $list .= 'N';
            } elseif ($row['pago'] == 1) {
                $list .= 'S';
            }
            $list .= '</td>';
            if ($usuario instanceof Empleado) {
                $list .= '<td>' . $row['nombre_cliente'] . ' ' . $row['apellido1_cliente'] . '</td>';
            }
            $list .= '<td>' . $row['nombre_empleado'] . ' ' . $row['apellido1_empleado'] . '</td>';
            if ($usuario instanceof Empleado) {
                $list .= '<td>'
                        . '<form action="?c=consultas&a=action" method="post" id="formAction">'
                        . '<input type="hidden" name="js" class="js" value="0">'
                        . '<input type="hidden" name="idConsulta" class="idConsulta" value="' . $row['consulta'] . '">'
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
