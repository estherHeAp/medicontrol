<?php

/**
 * 
 * @param Usuario $usuario - Utilizado para el select de empleados
 * @param PDOStatement $datos - Datos a mostrar en los campos obtenidos de la BD
 * @param array $nuevaConsulta - Datos de una consulta sin guardar (conservamos sus datos para mostrar)
 */
function formUpdateConsulta($usuario, $datos, $nuevaConsulta) {
    $form = '';

    if ($datos) {
        $row = !isset($nuevaConsulta) ? $datos->fetch() : $nuevaConsulta;
        $form .= '<form action="?c=consultas&a=update" method="post" id="formUpdate">'
                . '<fieldset>'
                . '<legend>Modificar consulta</legend>'
                . '<input type="hidden" name="js" class="js" value="0">'
                . '<input type="hidden" name="idConsulta" id="idConsulta" value="' . $row['consulta'] . '">'
                . '<input type="hidden" name="idCita" id="idCita" value="' . $row['id_cita'] . '">'
                
                . '<div class="form-row align-items-end">'
                . '<div class="form-group col-12 col-sm-6">'
                . selectEmpleados($usuario, $row['dni_empleado'])
                . '</div>'
                
                . '<div class="form-group col-12 col-sm-6">'
                . '<label for="asuntoUpdate">Asunto * :</label>'
                . '<input type="text" name="asuntoUpdate" id="asuntoUpdate" class="form-control" value="' . $row['asunto'] . '" required>'
                . '</div>'
                
                . '<div class="form-group col-12">'
                . '<label for="descripcionUpdate">Descripción:</label>'
                . '<textarea name="descripcionUpdate" id="descripcionUpdate" class="form-control">' . $row['descripcion'] . '</textarea>'
                . '</div>'
                
                . '<div class="col-12 col-md-6">'
                . '<div class="row">'
                . '<div class="col-12 form-check mr-0 mb-4 px-0 form-check-inline">'
                . '<input type="checkbox" name="pruebasUpdate" id="pruebasUpdate" class="form-check-input" value="pruebas"';
        if ($row['pruebas'] == 1) {
            $form .= ' checked';
        }
        $form .= '>'
                . '<label for="pruebasUpdate" class="form-check-label">Pruebas</label>'
                . '</div>'
                
                . '<div class="form-group col-12 px-0">'
                . '<label for="pruebasDetUpdate">Pruebas Det:</label>'
                . '<textarea name="pruebasDetUpdate" id="pruebasDetUpdate" class="form-control">' . $row['pruebas_detalles'] . '</textarea>'
                . '</div>'
                . '</div>'
                . '</div>'
                
                . '<div class="col-12 col-md-6">'
                . '<div class="row">'
                . '<div class="col-12 form-check mr-0 mb-4 px-0 form-check-inline">'
                . '<input type="checkbox" name="tratamientosUpdate" id="tratamientosUpdate" class="form-check-input" value="tratamientos"';
        if ($row['tratamientos'] == 1) {
            $form .= ' checked';
        }
        $form .= '>'
                . '<label for="tratamientosUpdate" class="form-check-label">Tratamientos</label>'
                . '</div>'
                
                . '<div class="form-group col-12 px-0">'
                . '<label for="tratamientosDetUpdate">Tratemientos Det:</label>'
                . '<textarea name="tratamientosDetUpdate" id="tratamientosDetUpdate" class="form-control">' . $row['tratamientos_detalles'] . '</textarea>'
                . '</div>'
                . '</div>'
                . '</div>'
                
                . '<div class="form-group col-12">'
                . '<label for="otrosUpdate">Otros:</label>'
                . '<textarea name="otrosUpdate" id="otrosUpdate" class="form-control">' . $row['otros_detalles'] . '</textarea>'
                . '</div>'
                
                . '<div class="form-group col-12 col-sm-6">'
                . '<label for="importeUpdate">Importe:</label>'
                . '<input type="number" name="importeUpdate" id="importeUpdate" class="form-control" value="' . $row['importe'] . '">'
                . '</div>'
                
                . '<div class="col-12 col-sm-6 mr-0 mb-4 pl-sm-5 form-check form-check-inline">'
                . '<input type="checkbox" name="pagoUpdate" id="pagoUpdate" class="form-check-input" value="pago"';
        if ($row['pago'] == 1) {
            $form .= ' checked';
        }
        $form .= '>'
                . '<label for="pagoUpdate" class="form-check-label">Pago</label>'
                . '</div>'
                
                . '<div class="col-12">'
                . '<input type="submit" name="btnUpdate" id="btnUpdate" class="btn btn-primary mr-1" value="Actualizar consulta">'
                . '<input type="submit" name="btnClearUpdate" id="btnClearUpdate" class="btn btn-info mr-1" value="Resetear datos">'
                . '<input type="submit" name="btnCancelUpdate" id="btnCancelUpdate" class="btn btn-secondary" value="Cancelar">'
                . '</div>'
                . '</div>'
                . '</fieldset>'
                . '</form>';
    } else {
        $form .= '<p class="msg-error">Error en la obtención de datos.</p>';
    }

    return $form;
}
