<?php

/**
 * Se mostrará un formulario de actualización para empleados
 * 
 * @param Festivo $datos - Datos a mostrar en los campos obtenidos de la BD
 * @return string
 */
function formUpdateFestivo($datos) {
    $form = '';

    if (isset($datos)) {
        $form .= '<form action="?c=festivos&a=update" method="post" id="formUpdate">'
                . '<fieldset>'
                . '<legend>Modificar día festivo</legend>'
                
                . '<input type="hidden" name="js" class="js" value="0">'
                
                . '<div class="form-row">'
                . '<div class="form-group col-12">'
                . '<label for="fechaUpdate">Fecha:</label>'
                . '<input type="date" name="fechaUpdate" id="fechaUpdate" class="form-control" value="' . $datos->getFecha() . '" required>'
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
                . '<input type="number" name="maxUpdate" id="maxUpdate" class="form-control" value="' . $datos->getMax() . '" required>'
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
        $form .= 'Error en la obtención de datos.';
    }

    return $form;
}
