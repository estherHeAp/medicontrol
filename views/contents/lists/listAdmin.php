<?php

/**
 * Se mostrará un listado de administradores
 * 
 * @param Empleado $usuario
 * @param array $search - Criterios de búsqueda para mostrar solo los administradores buscados
 * @return string
 */
function listAdmin($usuario, $search) {
    $data = $usuario->getAdmin($search);

    $list = '';

    if ($data === false) {
        $list .= '<p class="msg-error">Error en la obtención de administradores.</p>';
    } elseif ($data->rowCount() === 0) {
        if (isset($search)) {
            $list .= '<p class="msg-info">No se han encontrado resultados</p>';
        } else {
            $list .= '<p class="msg-info">No hay administradores registrados</p>';
        }
    } else {
        $list .= '<table class="table table-striped table-borderless">'
                . '<thead class="thead-light">'
                . '<tr>';
        if ($usuario instanceof Admin) {
            $list .= '<th scope="col">DNI</th>';
        }
        $list .= '<th scope="col">Nombre</th>'
                . '<th scope="col">Primer apellido</th>'
                . '<th scope="col">Segundo apellido</th>';
        if ($usuario instanceof Admin) {
            $list .= '<th scope="col">Sexo</th>'
                    . '<th scope="col">Fecha de nacimiento</th>'
                    . '<th scope="col">Email</th>'
                    . '<th scope="col">Teléfono</th>'
                    . '<th scope="col">Fecha de alta</th>'
                    . '<th scope="col">Fecha de baja</th>';
        }
        $list .= '<th scope="col">Especialidad</th>'
                . '<th scope="col">Extensión</th>';
        if ($usuario instanceof Admin) {
            $list .= '<th scope="col">Acción</th>';
        }
        $list .= '</tr>'
                . '</thead>'
                . '<tbody>';

        while ($row = $data->fetch()) {
            if (!isset($_SESSION['dni'])) {
                $_SESSION['dni'] = $row['dni'];
            }
            
            $list .= '<tr>';
            if ($usuario instanceof Admin) {
                $list .= '<td scope="row">' . $row['dni'] . '</td>';
            }
            $list .= '<td>' . $row['nombre'] . '</td>'
                    . '<td>' . $row['apellido1'] . '</td>'
                    . '<td>' . $row['apellido2'] . '</td>';
            if ($usuario instanceof Admin) {
                $list .= '<td>' . $row['sexo'] . '</td>'
                        . '<td>';
                if ($row['fecha_nacimiento'] != '0000-00-00') {
                    $list .= $row['fecha_nacimiento'];
                }
                $list .= '</td>'
                        . '<td>' . $row['email'] . '</td>'
                        . '<td>';
                if ($row['telf'] != '0') {
                    $list .= $row['telf'];
                }
                $list .= '</td>'
                        . '<td>' . $row['fecha_alta'] . '</td>'
                        . '<td>' . $row['fecha_baja'] . '</td>';
            }
            $list .= '<td>' . $row['especialidad'] . '</td>'
                    . '<td>' . $row['extension'] . '</td>';
            if ($usuario instanceof Admin) {
                $list .= '<td>'
                        . '<form action="?c=admin&a=action" method="post" id="formAction">'
                        . '<input type="hidden" name="js" class="js" value="0">'
                        . '<input type="hidden" name="dniAdmin" class="dniAdmin" value="' . $row['dni'] . '">'
                        . '<input type="submit" name="btnMod" class="btn btn-primary btnMod fa fa-input" value="&#xf044">'
                        . '<input type="submit" name="btnDel" class="btn btn-danger btnDel fa fa-input" value="&#xf2ed">'
                        . '<input type="submit" name="btnRes" class="btn btn-warning btnRes fa fa-input" value="&#xf2ea">'
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
