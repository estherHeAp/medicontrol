<?php

/**
 * Se mostrará un formulario para la actualización de los datos personales del usuario
 * 
 * @param Usuario $usuario
 * @return string
 */
function formUpdatePerfil($usuario) {
    $form='';

    $form .= '<form action="?c=perfil&a=update" method="post" id="formUpdate">'
            . '<fieldset>'
            . '<legend>Datos personales</legend>'
            . '<input type="hidden" name="js" class="js" value="0">'
            . '<input type="hidden" name="dni" class="dni" value ="' . $usuario->getDni() . '">'
            . '<div class="form-row">'
            . '<div class="form-group col-12 col-sm-12 col-md-4">'
            . '<label for="dniUpdate">DNI:</label>'
            . '<input type="text" name="dniUpdate" id="dniUpdate" class="form-control" value="' . $usuario->getDni() . '" disabled required>'
            . '</div>'
            . '<div class="form-group col-12 col-sm-6 col-md-4">'
            . '<label for="nombreUpdate">Nombre * :</label>'
            . '<input type="text" name="nombreUpdate" id="nombreUpdate" class="form-control" value="' . $usuario->getNombre() . '" required>'
            . '</div>'
            . '<div class="form-group col-12 col-sm-6 col-md-4">'
            . '<label for="apellido1Update">Primer apellido * :</label>'
            . '<input type="text" name="apellido1Update" id="apellido1Update" class="form-control" value="' . $usuario->getApellido1() . '" required>'
            . '</div>'
            . '<div class="form-group col-12 col-sm-6 col-md-4">'
            . '<label for="apellido2Update">Segundo apellido:</label>'
            . '<input type="text" name="apellido2Update" id="apellido2Update" class="form-control" value="' . $usuario->getApellido2() . '">'
            . '</div>'
            . '<div class="form-group col-12 col-sm-6 col-md-4">'
            . '<label for="sexoUpdate">Sexo:</label>'
            . '<select name="sexoUpdate" id="sexoUpdate" class="form-control">'
            . '<option value=""></option>'
            . '<option value="F"';
    if ($usuario->getSexo() === 'F') {
        $form .= ' selected';
    }
    $form .= '>MUJER</option>'
            . '<option value="M"';
    if ($usuario->getSexo() === 'M') {
        $form .= ' selected';
    }
    $form .= '>HOMBRE</option>'
            . '</select>'
            . '</div>'
            . '<div class="form-group col-12 col-sm-6 col-md-4">'
            . '<label for="fechaNacimientoUpdate">Fecha de nacimiento:</label>'
            . '<input type="date" name="fechaNacimientoUpdate" id="fechaNacimientoUpdate" class="form-control" max="' . date('Y-m-d') . '" value="' . $usuario->getFechaNacimiento() . '">'
            . '</div>'
            . '<div class="form-group col-12 col-sm-6 col-md-4">'
            . '<label for="emailUpdate">Correo electrónico * :</label>'
            . '<input type="email" name="emailUpdate" id="emailUpdate" class="form-control" value="' . $usuario->getEmail() . '" required>'
            . '</div>'
            . '<div class="form-group col-12 col-sm-6 col-md-4">'
            . '<label for="telfUpdate">Teléfono:</label>'
            . '<input type="tel" name="telfUpdate" id="telfUpdate" class="form-control" value="' . $usuario->getTelf() . '">'
            . '</div>';

    if ($usuario instanceof Cliente) {
        $form .= '<div class="form-group col-12 col-sm-6 col-md-4">'
                . '<label for="aseguradoraUpdate">Aseguradora:</label>'
                . '<input type="text" name="aseguradoraUpdate" id="aseguradoraUpdate" class="form-control" value="' . $usuario->getAseguradora() . '">'
                . '</div>';
    } elseif ($usuario instanceof Empleado) {
        $form .= '<div class="form-group col-12 col-sm-6 col-md-4">'
                . '<label for="fechaAltaUpdate">Fecha de alta:</label>'
                . '<input type="date" name="fechaAltaUpdate" id="fechaAltaUpdate" class="form-control" value="' . $usuario->getFechaAlta() . '" disabled>'
                . '</div>'
                . '<div class="form-group col-12 col-sm-6 col-md-4">'
                . '<label for="fechaBajaUpdate">Fecha de baja:</label>'
                . '<input type="date" name="fechaBajaUpdate" id="fechaBajaUpdate" class="form-control" value="' . $usuario->getFechaBaja() . '" disabled>'
                . '</div>'
                . '<div class="form-group col-12 col-sm-6 col-md-4">'
                . '<label for="extensionUpdate">Extension:</label>'
                . '<input type="number" name="extensionUpdate" id="extensionUpdate" class="form-control" value="' . $usuario->getExtension() . '" disabled>'
                . '</div>'
                . '<div class="form-group col-12 col-sm-12 col-md-4">'
                . '<label for="especialidadUpdate">Especialidad:</label>'
                . '<input type="text" name="especialidadUpdate" id="especialidadUpdate" class="form-control" value="' . $usuario->getEspecialidad() . '" disabled>'
                . '</div>';
    }

    $form .= '<div class="col-12">'
            . '<input type="submit" name="btnUpdate" id="btnUpdate" class="btn btn-primary mr-1" value="Actualizar datos">'
            . '<input type="submit" name="btnClearUpdate" id="btnClearUpdate" class="btn btn-info" value="Resetear datos">'
            . '</div>'
            . '</div>'
            . '</fieldset>'
            . '</form>';

    return $form;
}
