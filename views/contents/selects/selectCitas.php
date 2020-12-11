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
    for($i = 0; $i < sizeof($data); $i += 2) {
        $select .= '<option value="' . $data[$i]->getId() . '">' . $data[$i]->getFecha() . ' ' . $data[$i]->getHora() . ' ' . $data[$i]->getAsunto() . ' - ' . $data[$i+1] . '</option>';
    }
    $select .= '</select>';
    
    return $select;
}
