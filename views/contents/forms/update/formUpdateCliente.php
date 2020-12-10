<?php

/**
 * Se mostrará el formulario de actualización para clientes
 * 
 * @param Cliente $datos - Datos a mostrar en los campos obtenidos de la BD
 * @return string
 */
function formUpdateCliente($datos) {
    $form = '';

    if (isset($datos)) {
        $form .= '<form action="?c=clientes&a=update" method="post" id="formUpdate">'
                . '<fieldset>'
                . '<legend>Modificar cliente</legend>'
                
                . '<input type="hidden" name="js" class="js" value="0">'
                
                . '<div class="form-row">'
                . '<div class="form-group col-12 col-md-4">'
                . '<label for="dniUpdate">DNI * :</label>'
                . '<input type="text" name="dniUpdate" id="dniUpdate" class="form-control" pattern="^[0-9]{8}[A-Z]$" value="' . $datos->getDni() . '" required>'
                . '</div>'
                
                . '<div class="form-group col-12 col-sm-6 col-md-4">'
                . '<label for="nombreUpdate">Nombre * :</label>'
                . '<input type="text" name="nombreUpdate" id="nombreUpdate" class="form-control" value="' . $datos->getNombre() . '" required>'
                . '</div>'
                
                . '<div class="form-group col-12 col-sm-6 col-md-4">'
                . '<label for="apellido1Update">Primer apellido * :</label>'
                . '<input type="text" name="apellido1Update" id="apellido1Update" class="form-control" value="' . $datos->getApellido1() . '" required>'
                . '</div>'
                
                . '<div class="form-group col-12 col-sm-6 col-md-4">'
                . '<label for="apellido2Update">Segundo apellido:</label>'
                . '<input type="text" name="apellido2Update" id="apellido2Update" class="form-control" value="' . $datos->getApellido2() . '">'
                . '</div>'
                
                . '<div class="form-group col-12 col-sm-6 col-md-4">'
                . '<label for="sexoUpdate">Sexo:</label>'
                . '<select name="sexoUpdate" id="sexoUpdate" class="form-control">'
                . '<option value=""></option>'
                . '<option value="F"';
        if ($datos->getSexo() === 'F') {
            $form .= ' selected';
        }
        $form .= '>Mujer</option>'
                . '<option value="M"';
        if ($datos->getSexo() === 'M') {
            $form .= ' selected';
        }
        $form .= '>Hombre</option>'
                . '</select>'
                . '</div>'
                
                . '<div class="form-group col-12 col-sm-6 col-md-4">'
                . '<label for="fechaNacimientoUpdate">Fecha de nacimiento:</label>'
                . '<input type="date" name="fechaNacimientoUpdate" id="fechaNacimientoUpdate" class="form-control" max="' . date('Y-m-d') . '" value="' . $datos->getFechaNacimiento() . '">'
                . '</div>'
                
                . '<div class="form-group col-12 col-sm-6 col-md-4">'
                . '<label for="emailUpdate">Correo electrónico * :</label>'
                . '<input type="email" name="emailUpdate" id="emailUpdate" class="form-control" value="' . $datos->getEmail() . '" required>'
                . '</div>'
                
                . '<div class="form-group col-12 col-sm-6 col-md-4">'
                . '<label for="telfUpdate">Teléfono:</label>'
                . '<input type="tel" name="telfUpdate" id="telfUpdate" class="form-control" value="' . $datos->getTelf() . '">'
                . '</div>'
                
                . '<div class="form-group col-12 col-sm-6 col-md-4">'
                . '<label for="aseguradoraUpdate">Aseguradora:</label>'
                . '<input type="text" name="aseguradoraUpdate" id="aseguradoraUpdate" class="form-control" value="' . $datos->getAseguradora() . '">'
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
