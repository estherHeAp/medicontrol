<?php

/**
 * Controlador para a vista perfil
 *
 * @author Esther
 */
class PerfilController {

    /**
     * Cargamos la vista que muestra el perfil del usuario
     */
    public function index() {
        require_once 'views/modules/header.php';
        require_once 'views/modules/navPerfil.php';
        require_once 'views/modules/nav.php';
        require_once 'views/modules/msg.php';
        require_once 'views/perfil.php';
        require_once 'views/modules/footer.php';
    }

    // UPDATE ----------------------------------------------------------------------------------------------------------
    /**
     * Modificación de los datos del usuario
     */
    public function update() {
        $usuario = filterSession('usuario');
        $msg = msg();

        // Datos del formulario
        $js = filterPost('js');
        $dni = mb_strtoupper(filterPost('dni'));
        $nombre = mb_strtoupper(filterPost('nombreUpdate'));
        $apellido1 = mb_strtoupper(filterPost('apellido1Update'));
        $apellido2 = mb_strtoupper(filterPost('apellido2Update'));
        $sexo = mb_strtoupper(filterPost('sexoUpdate'));
        $fechaNacimiento = filterPost('fechaNacimientoUpdate');
        $email = mb_strtolower(filterPost('emailUpdate'));
        $telf = filterPost('telfUpdate');
        $aseguradora = mb_strtoupper(filterPost('aseguradoraUpdate'));
        $fechaAlta = filterPost('fechaAltaUpdate');
        $fechaBaja = filterPost('fechaBajaUpdate');
        $extension = filterPost('extensionUpdate');
        $especialidad = mb_strtoupper(filterPost('especialidadUpdate'));

        $btn = filterPost('btn');

        $btnUpdate = filterPost('btnUpdate');
        $btnClear = filterPost('btnClearUpdate');

        if ($btn === 'Actualizar datos' || isset($btnUpdate)) {
            // Datos obligatorios
            if ($dni !== '' && $nombre !== '' && $apellido1 !== '' && $email !== '') {
                $usuario->set($dni, $nombre, $apellido1, $apellido2, $sexo, $fechaNacimiento, $email, $telf, $aseguradora, $fechaAlta, $fechaBaja, $especialidad, $extension);

                // ¿Email válido?
                if (!$usuario->check() && $usuario->checkByEmail()) {
                    $msg['msg']['error'] = 'La dirección de correo electrónico indicada ya se encuentra en uso, introduzca una diferente.';
                } else {
                    // Actualizamos los datos del usuario
                    if ($usuario->update()) {
                        $msg['msg']['conf'] = 'Proceso realizado con éxito.';
                        
                        // Actualizamos el usuario almacenado en sesión
                        $_SESSION['usuario'] = serialize($usuario);
                    } else {
                        $msg['msg']['error'] = 'Error en la actualización de los datos.';
                    }
                }
            } else {
                $msg['msg']['error'] = 'Datos sin introducir.';
            }
        } elseif ($btn === 'Resetear datos' || isset($btnClear)) {
            $msg['msg']['conf'] = 'Datos del formulario reseteados.';
            $msg['form'] = formUpdatePerfil($usuario);
        } // else $msg['msg']['error'] = 'No se ha seleccionado ninguna acción.';

        // Redireccionamiento (según JS activado o no)
        redirect($js, $msg);
    }

    // CHANGE ----------------------------------------------------------------------------------------------------------
    
    /**
     * Modificación de la contraseña de acceso del usuario
     */
    public function change() {
        $usuario = filterSession('usuario');

        $msg = msg();

        // Datos del formulario
        $js = filterPost('js');
        $actual = filterPost('actualChange');
        $pass = filterPost('passChange');
        $repass = filterPost('repassChange');

        // Validaciones previas
        if (isset($pass) && isset($repass) && $pass === $repass) {
            // Datos obligatorios
            if (isset($actual)) {
                // Comprobamos que la contraseña introducida es la actual
                $cuenta = $usuario->getAccount();

                if (password_verify($actual, $cuenta->getPass())) {
                    // Comprobamos que la nueva contraseña sea distinta a la actual
                    if ($actual !== $pass) {
                        // Actualizamos la contraseña de la cuenta
                        if ($cuenta->update($pass)) {
                            $msg['msg']['conf'] = 'Proceso realizado con éxito.';
                            $msg['otros']['action'] = 'change';
                        } else {
                            $msg['msg']['error'] = 'Ha ocurrido un error durante el proceso.';
                        }
                    } else {
                        $msg['msg']['error'] = 'La nueva contraseña debe ser diferente de la actual.';
                    }
                } else {
                    $msg['msg']['error'] = 'Contraseña actual incorrecta.';
                }
            } else {
                $msg['msg']['error'] = 'Datos sin introducir.';
            }
        } else {
            $msg['msg']['error'] = 'Las contraseñas no coinciden.';
        }

        // Redireccionamiento (según JS activado o no)
        redirect($js, $msg);
    }

    // DELETE ----------------------------------------------------------------------------------------------------------
    
    /**
     * Eliminación de la cuenta del usuario
     */
    public function delete() {
        $usuario = filterSession('usuario');

        $msg = msg();

        // Datos del formulario
        $js = filterPost('js');
        $pass = filterPost('passDelete');

        // Datos obligatorios
        if (isset($pass)) {
            // Comprobamos que la contraseña introducida es la actual
            $cuenta = $usuario->getAccount();

            if ($cuenta->getPass() === $pass) {
                // Comprobamos si el usuario tiene consultas asociadas
                // No se puede eliminar un cliente con consultas asociadas
                $consultas = $usuario->getConsultas(null);

                if ($consultas->rowCount() > 0) {
                    $msg['msg']['error'] = 'El usuario dispone de consultas asociadas que no pueden ser eliminadas.';
                } else {
                    // Eliminamos el usuario
                    if ($usuario->delete()) {
                        unset($_SESSION['usuario']);

                        // SUCCES -> RELOAD -> ?c=login
                    } else {
                        $msg['msg']['error'] = 'Ha ocurrido un error durante el proceso.';
                    }
                }
            } else {
                $msg['msg']['error'] = 'Contraseña incorrecta.';
            }
        } else {
            $msg['msg']['error'] = 'Datos sin introducir.';
        }

        // Redireccionamiento (según JS activado o no)
        redirect($js, $msg);
    }

    // -----------------------------------------------------------------------------------------------------------------

    public function logout() {
        unset($_SESSION['usuario']);

        // RELOAD -> ?c=login
        header('refresh: 0; url= ?c=login');
    }

}
