<?php

/**
 * Se mostrará un listado de empleados
 * 
 * @param Empleado $usuario
 * @param array $search - Criterios de búsqueda para mostrar solo los empleados buscados
 * @return string
 */
function listEmpleados($usuario, $search) {
    $data = $usuario->getEmpleados($search);

    $list = '';

    if ($data === false) {
        $list .= '<p class="msg-error">Error en la obtención de citas.</p>';
    } elseif ($data->rowCount() === 0) {
        if (isset($search)) {
            $list .= '<p class="msg-info">No se han encontrado resultados</p>';
        } else {
            $list .= '<p class="msg-info">No hay empleados registrados</p>';
        }
    } else {
        $list .= '<table class="table table-striped table-borderless">'
                . '<thead class="thead-light">'
                . '<tr>'
                . '<th scope="col">DNI</th>'
                . '<th scope="col">Nombre</th>'
                . '<th scope="col">Primer apellido</th>'
                . '<th scope="col">Sengundo apellido</th>'
                . '<th scope="col">Sexo</th>'
                . '<th scope="col">Fecha de nacimiento</th>'
                . '<th scope="col">Email</th>'
                . '<th scope="col">Teléfono</th>'
                . '<th scope="col">Fecha de alta</th>'
                . '<th scope="col">Fecha de baja</th>'
                . '<th scope="col">Especialidad</th>'
                . '<th scope="col">Extensión</th>'
                . '<th scope="col">Acción</th>'
                . '</tr>'
                . '</thead>'
                . '<tbody>';

        while ($row = $data->fetch()) {
            if (!isset($_SESSION['dni'])) {
                $_SESSION['dni'] = $row['dni'];
            }
            
            $list .= '<tr>'
                    . '<td scope="row">' . $row['dni'] . '</td>'
                    . '<td>' . $row['nombre'] . '</td>'
                    . '<td>' . $row['apellido1'] . '</td>'
                    . '<td>' . $row['apellido2'] . '</td>'
                    . '<td>' . $row['sexo'] . '</td>'
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
                    . '<td>' . $row['fecha_baja'] . '</td>'
                    . '<td>' . $row['especialidad'] . '</td>'
                    . '<td>' . $row['extension'] . '</td>'
                    . '<td>'
                    . '<form action="?c=empleados&a=action" method="post" id="formAction">'
                    . '<input type="hidden" name="js" class="js" value="0">'
                    . '<input type="hidden" name="dniEmpleado" class="dniEmpleado" value="' . $row['dni'] . '">'
                    . '<input type="submit" name="btnMod" class="btn btn-primary btnMod fa fa-input" value="&#xf044">'
                    . '<input type="submit" name="btnDel" class="btn btn-danger btnDel fa fa-input" value="&#xf2ed">'
                    . '<input type="submit" name="btnRes" class="btn btn-warning btnRes fa fa-input" value="&#xf2ea">'
                    . '</form>'
                    . '</td>'
                    . '</tr>';
        }

        $list .= '</tbody>'
                . '</table>';
    }

    return $list;
}
