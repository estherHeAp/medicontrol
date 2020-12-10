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
    while ($row = $data->fetch()) {
        $select .= '<option value="' . $row['nombre'] . '"';
        if ($row['nombre'] == $empleado) {
            $select .= ' selected';
        }
        $select .= '>' . $row['nombre'] . '</option>';
    }
    $select .= '</select>';

    return $select;
}
