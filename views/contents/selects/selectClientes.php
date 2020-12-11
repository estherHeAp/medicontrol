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
    for($i = 0; $i < sizeof($data); $i++) {
        $select .= '<option value="' . $data[$i]->getDni() . '"';
        if ($data[$i]->getDni() == $cliente) {
            $select .= ' selected';
        }
        $select .= '>' . $data[$i]->getNombre() . ' ' . $data[$i]->getApellido1() . ' ' . $data[$i]->getApellido2() . '</option>';
    }
    $select .= '</select>';

    return $select;
}
