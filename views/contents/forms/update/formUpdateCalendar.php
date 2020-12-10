<?php

/**
 * Se mostrará el formuario de modficiación de un día laboral
 * 
 * @param PDOStatement $datos - Datos a mostrar en los campos obtenidos de la BD
 * @return string
 */
function formUpdateCalendar($datos) {
    $form = '';

    if (isset($datos)) {
        $form .= '<form action="?c=calendarConfig&a=update" method="post" id="formUpdate">'
                . '<fieldset>'
                . '<legend>Modificar calendario</legend>'
                
                . '<input type="hidden" name="js" class="js" value="0">'
                . '<input type="hidden" name="dia" class="dia" value="' . $datos->getDia() . '">'
                
                . '<div class="form-row">'
                . '<div class="form-group col-12">'
                . '<label for="diaUpdate">Día:</label>'
                . '<input type="text" name="diaUpdate" id="diaUpdate" class="form-control" value="' . $datos->getDia() . '" disabled>'
                . '</div>'
                
                . '<div class="form-group col-12 col-sm-6 col-md-4">'
                . '<label for="manana1Update">Apertura (mañanas):</label>'
                . '<input type="time" name="manana1Update" id="manana1Update" class="form-control" value="' . $datos->getManana1() . '" required>'
                . '</div>'
                
                . '<div class="form-group col-12 col-sm-6 col-md-4">'
                . '<label for="manana2Update">Cierre (mañanas):</label>'
                . '<input type="time" name="manana2Update" id="manana2Update" class="form-control" value="' . $datos->getManana2() . '" required>'
                . '</div>'
                
                . '<div class="form-group col-12 col-sm-6 col-md-4">'
                . '<label for="tarde1Update">Apertura (tardes):</label>'
                . '<input type="time" name="tarde1Update" id="tarde1Update" class="form-control" value="' . $datos->getTarde1() . '" required>'
                . '</div>'
                
                . '<div class="form-group col-12 col-sm-6 col-md-4">'
                . '<label for="tarde2Update">Cierre (tardes):</label>'
                . '<input type="time" name="tarde2Update" id="tarde2Update" class="form-control" value="' . $datos->getTarde2() . '" required>'
                . '</div>'
                
                . '<div class="form-group col-12 col-sm-6 col-md-4">'
                . '<label for="duracionUpdate">Duración máxima:</label>'
                . '<input type="number" name="duracionUpdate" id="duracionUpdate" class="form-control" value="' . $datos->getDuracion() . '" required>'
                . '</div>'
                
                . '<div class="form-group col-12 col-sm-6 col-md-4">'
                . '<label for="maxUpdate">Número máx. clientes:</label>'
                . '<input type="number" name="maxUpdate" id="maxUpdate" class="form-control" value="' . $datos->getMax() . '">'
                . '</div>'
                
                . '<div class="col-12">'
                . '<input type="submit" name="btnUpdate" id="btnUpdate" class="btn btn-primary mr-1" value="Actualizar datos">'
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
