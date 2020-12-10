<?php

/**
 * Se mostrará un menú desplegable con los empleados registrados
 * 
 * @param Empleado $usuario
 * @param string $empleado - DNI del empleado
 * @return string
 */
function selectEmpleados($usuario, $empleado) {
    $data = $usuario->getEmpleados(null);

    $select = '<label for="empleadoSelect">Empleado:</label>'
            . '<select name="empleadoSelect" class="empleadoSelect form-control">'
            . '<option value=""></option>';
    while ($row = $data->fetch()) {
        $select .= '<option value="' . $row['dni'] . '"';
        if ($row['dni'] == $empleado) {
            $select .= ' selected';
        }
        $select .= '>' . $row['nombre'] . ' ' . $row['apellido1'] . ' ' . $row['apellido2'] . '</option>';
    }
    $select .= '</select>';

    return $select;
}
