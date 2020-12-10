<?php

/**
 * Se mostrará un menú desplegable con los clientes registrados
 * 
 * @param Empleado $usuario
 * @param string $cliente - DNI del cliente
 * @return string
 */
function selectClientes($usuario, $cliente) {
    $data = $usuario->getClientes(null);

    $select = '<label for="clienteSelect">Cliente:</label>'
            . '<select name="clienteSelect" class="clienteSelect form-control">'
            . '<option value=""></option>';
    while ($row = $data->fetch()) {
        $select .= '<option value="' . $row['dni'] . '"';
        if ($row['dni'] == $cliente) {
            $select .= ' selected';
        }
        $select .= '>' . $row['nombre'] . ' ' . $row['apellido1'] . ' ' . $row['apellido2'] . '</option>';
    }
    $select .= '</select>';

    return $select;
}
