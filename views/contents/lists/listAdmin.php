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
    } elseif (sizeof($data) == 0) {
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

        for($i = 0; $i < sizeof($data); $i++) {
            if (!isset($_SESSION['dni'])) {
                $_SESSION['dni'] = $data[$i]->getDni();
            }
            
            $list .= '<tr>';
            if ($usuario instanceof Admin) {
                $list .= '<td scope="row">' . $data[$i]->getDni() . '</td>';
            }
            $list .= '<td>' . $data[$i]->getNombre() . '</td>'
                    . '<td>' . $data[$i]->getApellido1() . '</td>'
                    . '<td>' . $data[$i]->getApellido2() . '</td>';
            if ($usuario instanceof Admin) {
                $list .= '<td>' . $data[$i]->getSexo() . '</td>'
                        . '<td>';
                if ($data[$i]->getFechaNacimiento() != '0000-00-00') {
                    $list .= $data[$i]->getFechaNacimiento();
                }
                $list .= '</td>'
                        . '<td>' . $data[$i]->getEmail() . '</td>'
                        . '<td>';
                if ($data[$i]->getTelf() != '0') {
                    $list .= $data[$i]->getTelf();
                }
                $list .= '</td>'
                        . '<td>' . $data[$i]->getFechaAlta() . '</td>'
                        . '<td>' . $data[$i]->getFechaBaja() . '</td>';
            }
            $list .= '<td>' . $data[$i]->getEspecialidad() . '</td>'
                    . '<td>' . $data[$i]->getExtension() . '</td>';
            if ($usuario instanceof Admin) {
                $list .= '<td>'
                        . '<form action="?c=admin&a=action" method="post" id="formAction">'
                        . '<input type="hidden" name="js" class="js" value="0">'
                        . '<input type="hidden" name="dniAdmin" class="dniAdmin" value="' . $data[$i]->getDni() . '">'
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
