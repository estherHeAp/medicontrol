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
    for($i = 0; $i < sizeof($data); $i++) {
        $select .= '<option value="' . $data[$i]->getDni() . '"';
        if ($data[$i]->getDni() == $empleado) {
            $select .= ' selected';
        }
        $select .= '>' . $data[$i]->getNombre() . ' ' . $data[$i]->getApellido1() . ' ' . $data[$i]->getApellido2() . '</option>';
    }
    $select .= '</select>';

    return $select;
}
