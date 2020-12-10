<?php

/**
 * Acciones que se podrán realizar desde la vista de login
 *
 * @author Esther
 */
class LoginController {

    /**
     * Mostramos la vista de login
     */
    public function index() {
        require_once 'views/contents/msg.php';
        require_once 'views/modules/header.php';
        require_once 'views/modules/msg.php';
        require_once 'views/login.php';
        require_once 'views/modules/footer.php';
    }

    // -----------------------------------------------------------------------------------------------------------------

    /**
     * Acceso a la cuenta de un usuario
     */
    public function login() {
        $msg = msg();

        // Datos del formulario
        $js = filterPost('js');
        $dni = mb_strtoupper(filterPost('dniLogin'));
        $pass = filterPost('passLogin');

        // Datos obligatorios
        if (isset($dni) && isset($pass)) {
            // Comprobar que el usuario existe en la BD
            $usuario = new Usuario();
            $usuario->setDni($dni);
            
            // En el caso de empleados con cuentas inactivas...
            $cuenta = $usuario->getAccount();

            if ($usuario->checkByDni() && isset($cuenta)) {
                // Comprobar que las credenciales de acceso son válidas
                $cuenta = new Cuenta();
                $cuenta->set($dni, $pass);

                if ($cuenta->check()) {
                    $_SESSION['usuario'] = serialize($usuario->getByDni());

                    // SUCCES -> RELOAD -> ?c=main
                } else {
                    $msg['msg']['error'] = 'Contraseña incorrecta.';
                }
            } else {
                $msg['msg']['error'] = 'El usuario no se encuentra registrado.';
            }
        } else {
            $msg['msg']['error'] = 'Datos sin introducir.';
        }

        // Redireccionamiento (según JS activado o no)
        redirect($js, $msg);
    }

    // -----------------------------------------------------------------------------------------------------------------

    /**
     * Recuperación de la contraseña para el acceso a una cuenta de usuario
     */
    public function reset() {
        $msg = msg();

        // Datos del formulario
        $js = filterPost('js');
        $email = mb_strtolower(filterPost('emailReset'));

        // Datos obligatorios
        if (isset($email)) {
            // Obtener datos del usuario
            $usuario = new Usuario();
            $usuario->setEmail($email);

            $usuario = $usuario->getByEmail();

            if (isset($usuario)) {
                // Comprobar si el usuario es cliente o empleado
                if ($usuario instanceof Cliente) {
                    // Obtener datos de la cuenta del usuario
                    $cuenta = $usuario->getAccount();

                    if (isset($cuenta)) {
                        // Actualizar contraseña por una nueva
                        
                        // if($cuenta->resetPass($email)) { ... } else { ... }
                        
                        $pass = newPass();

                        if ($cuenta->update($pass)) {
                            // Enviar email con la nueva contraseña
                            if (sendMail($pass, $email)) {
                                $msg['msg']['conf'] = 'Proceso realizado con éxito.';
                            } else {
                                $msg['msg']['error'] = 'Ha ocurrido un error en el envío del correo electrónico.';
                            }
                        } else {
                            $msg['msg']['error'] = 'Ha ocurrido un error al actualizar la contraseña.';
                        }
                    } else {
                        $msg['msg']['error'] = 'No se ha encontrado una cuenta asociada al usuario.';
                    }
                } else {
                    $msg['msg']['error'] = 'Esta opción solo se encuentra disponible para clientes. Como empleado debe contactar con su administrador.';
                }
            } else {
                $msg['msg']['error'] = 'La dirección de correo electrónico indicada no se encuentra asociada a ningún usuario.';
            }
        } else {
            $msg['msg']['error'] = 'Datos sin introducir.';
        }

        // Redireccionamiento (según JS activado o no)
        redirect($js, $msg);
    }

    // -----------------------------------------------------------------------------------------------------------------

    /**
     * Creación de un nuevo usuario cliente
     */
    public function add() {
        $msg = msg();

        // Datos del formulario
        $js = filterPost('js');
        $dni = mb_strtoupper(filterPost('dniAdd'));
        $nombre = mb_strtoupper(filterPost('nombreAdd'));
        $apellido1 = mb_strtoupper(filterPost('apellido1Add'));
        $email = mb_strtolower(filterPost('emailAdd'));
        $pass = filterPost('passAdd');
        $repass = filterPost('repassAdd');

        // Validaciones previas
        if (isset($pass) && isset($repass) && $pass === $repass) {
            // Datos obligatorios
            if (isset($dni) && isset($nombre) && isset($apellido1) && isset($email)) {
                // Comprobar si el usuario ya se encuentra registrado
                $usuario = new Cliente();
                $usuario->set($dni, $nombre, $apellido1, null, null, null, $email, null, null, null, null, null, null);

                if ($usuario->getByDni() !== null) {
                    $msg['msg']['error'] = 'El usuario ya se encuentra registrado. Si no recuerda la contraseña utilice la opción "¿Ha olvidado su contraseña?".';
                } else {
                    // Comprobar si el email ya se encuentra en uso por otro usuario
                    if ($usuario->getByEmail() !== null) {
                        $msg['msg']['error'] = 'La dirección de correo electrónico ya se encuentra en uso, introduzca una diferente.';
                    } else {
                        // Crear usuario y cuenta
                        $cuenta = new Cuenta();
                        $cuenta->set($dni, $pass);

                        if ($usuario->create() && $cuenta->create()) {
                            $msg['msg']['conf'] = 'Proceso realizado con éxito.';
                            $msg['otros']['action'] = 'add';
                        } else {
                            $msg['msg']['error'] = 'Ha ocurrido un error durante el registro.';
                        }
                    }
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

}
