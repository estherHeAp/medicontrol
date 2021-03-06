<?php

/**
 * Se mostrará un menú desplegable con las especialidades registradas
 * 
 * @param Empleado $usuario
 * @param string $empleado - Nombre del empleado
 * @return string
 */
function selectEspecialidades($usuario, $empleado) {
    $data = $usuario->getEspecialidades();

    $select = '<label for="especialidadSelect">Especialidad * :</label>'
            . '<select name="especialidadSelect" class="especialidadSelect form-control" required>'
            . '<option value=""></option>';
    for($i = 0; $i < sizeof($data); $i++) {
        $select .= '<option value="' . $data[$i]->getNombre() . '"';
        if ($data[$i]->getNombre() == $empleado) {
            $select .= ' selected';
        }
        $select .= '>' . $data[$i]->getNombre() . '</option>';
    }
    $select .= '</select>';

    return $select;
}
