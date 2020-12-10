<?php

/**
 * Se mostrará un menú desplegable con las citas que aún no han generado una consulta
 * 
 * @param Empleado $usuario
 * @return string
 */
function selectCitas($usuario) {
    $data = $usuario->getCitasNoConsulta();

    $select = '<label for="citaSelect">Cita:</label>'
            . '<select name="citaSelect" class = "citaSelect form-control">'
            . '<option value=""></option>';
    while ($row = $data->fetch()) {
        $select .= '<option value="' . $row['id'] . '">' . $row['fecha'] . ' ' . $row['hora'] . ' ' . $row['asunto'] . ' - ' . $row['nombre_cliente'] . ' ' . $row['apellido1_cliente'] . '</option>';
    }
    $select .= '</select>';
    
    return $select;
}
