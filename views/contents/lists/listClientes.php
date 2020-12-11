<?php

/**
 * Se mostrará un listado de clientes
 * 
 * @param Empleado $usuario
 * @param array $search - Criterios de búsqueda para mostrar solo los clientes buscados
 * @return string
 */
function listClientes($usuario, $search) {
    $data = $usuario->getClientes($search);
    
    $list = '';

    if ($data === false) {
        $list .= '<p class="msg-error">Error en la obtención de clientes.</p>';
    } elseif (sizeof($data) == 0) {
        if (isset($search)) {
            $list .= '<p class="msg-info">No se han encontrado resultados</p>';
        } else {
            $list .= '<p class="msg-info">No hay clientes registrados</p>';
        }
    } else {
        $list .= '<table class="table table-striped table-borderless">'
                . '<thead class="thead-light">'
                . '<tr>'
                . '<th scope="col">DNI</th>'
                . '<th scope="col">Nombre</th>'
                . '<th scope="col">Primer apellido</th>'
                . '<th scope="col">Segundo apellido</th>'
                . '<th scope="col">Sexo</th>'
                . '<th scope="col">Fecha de nacimiento</th>'
                . '<th scope="col">Email</th>'
                . '<th scope="col">Teléfono</th>'
                . '<th scope="col">Aseguradora</th>'
                . '<th scope="col">Acción</th>'
                . '</tr>'
                . '</thead>'
                . '<tbody>';

        for($i = 0; $i < sizeof($data); $i++) {
            $list .= '<tr>'
                    . '<td scope="row">' . $data[$i]->getDni() . '</td>'
                    . '<td>' . $data[$i]->getNombre() . '</td>'
                    . '<td>' . $data[$i]->getApellido1() . '</td>'
                    . '<td>' . $data[$i]->getApellido2() . '</td>'
                    . '<td>' . $data[$i]->getSexo() . '</td>'
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
                    . '<td>' . $data[$i]->getAseguradora() . '</td>'
                    . '<td>'
                    . '<form action="?c=clientes&a=action" method="post" id="formAction">'
                    . '<input type="hidden" name="js" class="js" value="0">'
                    . '<input type="hidden" name="dniCliente" class="dniCliente" value="' . $data[$i]->getDni() . '">'
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
