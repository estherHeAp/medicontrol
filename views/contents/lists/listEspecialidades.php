<?php

/**
 * Se mostrará un listado de especialidades
 * 
 * @param Empleado $usuario
 * @return string
 */
function listEspecialidades($usuario) {
    $data = $usuario->getEspecialidades();

    $list = '';

    if ($data === false) {
        $list .= '<p class="msg-error">Error en la obtención de especialidades.</p>';
    } elseif (sizeof($data) == 0) {
        $list .= '<p class="msg-info">No hay especialidades registradas</p>';
    } else {
        $list .= '<table class="table table-striped table-borderless">'
                . '<thead class="thead-light">'
                . '<tr>'
                . '<th scope="col">Nombre</th>'
                . '<th scope="col">Acción</th>'
                . '</tr>'
                . '</thead>'
                . '<tbody>';

        for($i = 0; $i < sizeof($data); $i++) {
            $list .= '<tr>'
                    . '<td>' . $data[$i]->getNombre() . '</td>'
                    . '<td>'
                    . '<form action="?c=especialidades&a=delete" method="post" id="formDelete">'
                    . '<input type="hidden" name="js" class="js" value="0">'
                    . '<input type="hidden" name="especialidad" class="especialidad" value="' . $data[$i]->getNombre() . '">'
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
