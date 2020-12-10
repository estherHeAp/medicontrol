<?php

/**
 * Controlador para la gestión de usuarios empleados
 *
 * @author Esther
 */
class EmpleadosController {

    /**
     * Cargamos la vista de gestión de empleados
     */
    public function index() {
        require_once 'views/modules/header.php';
        require_once 'views/modules/navPerfil.php';
        require_once 'views/modules/nav.php';
        require_once 'views/modules/msg.php';
        require_once 'views/empleados.php';
        require_once 'views/modules/footer.php';
    }

    // CREATE ----------------------------------------------------------------------------------------------------------
    
    /**
     * Generación de la contraseña y creación de la cuenta del empleado
     * 
     * @param Usuario $usuario
     * @param Empleado $empleado
     * @param array $msg
     * @return array - Mensajes a mostrar
     */
    private static function create($usuario, $empleado, $msg) {
        // Creamos de nuevo su cuenta (tablas "administradores" + "cuentas")
        $pass = newPass();
        $cuenta = new Cuenta();
        $cuenta->set($empleado->getDni(), $pass);

        if ($empleado->create() && $cuenta->create()) {
            if (sendMail($pass, $empleado->getEmail())) {
                $msg['msg']['conf'] = 'Proceso realizado con éxito.';
                $msg['list'] = listEmpleados($usuario, null);
                $msg['otros']['action'] = 'add';
            } else {
                $msg['msg']['error'] = 'Ha ocurrido un error durante el envío de la contraseña por email.';
            }
        } else {
            $msg['msg']['error'] = 'Ha ocurrido un error durante el proceso.';
        }

        return $msg;
    }

    /**
     * Adición de un empleado
     */
    public function add() {
        $usuario = filterSession('usuario');

        $msg = msg();

        // Datos del formulario
        $js = filterPost('js');
        $dni = mb_strtoupper(filterPost('dniAdd'));
        $nombre = mb_strtoupper(filterPost('nombreAdd'));
        $apellido1 = mb_strtoupper(filterPost('apellido1Add'));
        $apellido2 = mb_strtoupper(filterPost('apellido2Add'));
        $sexo = mb_strtoupper(filterPost('sexoAdd'));
        $fechaNacimiento = filterPost('fechaNacimientoAdd');
        $email = mb_strtolower(filterPost('emailAdd'));
        $telf = filterPost('telfAdd');
        $fechaAlta = filterPost('fechaAltaAdd');
        $fechaBaja = filterPost('fechaBajaAdd');
        $especialidadSelect = mb_strtoupper(filterPost('especialidadSelect'));
        $extension = filterPost('extension');

        // Datos obligatorios
        if (isset($dni) && isset($nombre) && isset($apellido1) && isset($email)) {
            // ¿Empleado en la DB (tabla "empleados")?
            $empleado = new Empleado();
            $empleado->set($dni, $nombre, $apellido1, $apellido2, $sexo, $fechaNacimiento, $email, $telf, null, $fechaAlta, $fechaBaja, $especialidadSelect, $extension);

            if ($empleado->checkByDni()) {
                // ¿Empleado con cuenta (tabla "cuentas")?
                $cuenta = $empleado->getAccount();
                if (isset($cuenta)) {
                    // Error: Usuario ya registrado
                    $msg['msg']['error'] = 'El usuario ya se encuentra registrado.';
                } else {
                    // Comprobar DNI y email
                    if ($empleado->check()) {
                        $msg = self::create($usuario, $empleado, $msg);
                    } elseif ($empleado->checkByEmail()) {
                        // Error: Email ya en uso por otro usuario
                        $msg['msg']['error'] = 'La dirección de correo electrónico indicada ya se encuentra en uso por otro usuario.';
                    } else {
                        // Actualizamos el email del empleado
                        if ($empleado->update()) {
                            $msg = self::create($usuario, $empleado, $msg);
                        } else {
                            $msg['msg']['error'] = 'Ha ocurrido un error durante el proceso.';
                        }
                    }
                }
            } else {
                // Comprobar email
                if ($empleado->checkByEmail()) {
                    // Error: Email ya en uso por otro usuario
                    $msg['msg']['error'] = 'La dirección de correo electrónico indicada ya se encuentra en uso por otro usuario.';
                } else {
                    $msg = self::create($usuario, $empleado, $msg);
                }
            }
        } else {
            $msg['msg']['error'] = 'No se ha seleccionado ninguna cita.';
        }

        // Redireccionamiento (según JS activado o no)
        redirect($js, $msg);
    }

    // UPDATE ----------------------------------------------------------------------------------------------------------

    /**
     * Actualización de los datos de un empleado
     */
    public function update() {
        $usuario = filterSession('usuario');
        $msg = msg();

        // Datos del formulario
        $js = filterPost('js');
        $dni = mb_strtoupper(filterPost('dniUpdate'));
        $nombre = mb_strtoupper(filterPost('nombreUpdate'));
        $apellido1 = mb_strtoupper(filterPost('apellido1Update'));
        $apellido2 = mb_strtoupper(filterPost('apellido2Update'));
        $sexo = mb_strtoupper(filterPost('sexoUpdate'));
        $fechaNacimiento = filterPost('fechaNacimientoUpdate');
        $email = mb_strtolower(filterPost('emailUpdate'));
        $telf = filterPost('telfUpdate');
        $fechaAlta = filterPost('fechaAltaUpdate');
        $fechaBaja = filterPost('fechaBajaUpdate');
        $especialidad = mb_strtoupper(filterPost('especialidadSelect'));
        $extension = filterPost('extensionUpdate');

        $btn = filterPost('btn');

        $btnUpdate = filterPost('btnUpdate');
        $btnClear = filterPost('btnClearUpdate');
        $btnCancel = filterPost('btnCancelUpdate');

        if ($btn === 'Actualizar datos' || isset($btnUpdate)) {
            $empleado = new Empleado();
            $empleado->setDni($dni);
            $data = $empleado->getByDni();

            // Datos obligatorios
            if ($dni !== '' && $nombre !== '' && $apellido1 !== '' && $email !== '') {
                // Actualizamos los datos del empleado
                $empleado->set($dni, $nombre, $apellido1, $apellido2, $sexo, $fechaNacimiento, $email, $telf, null, $fechaAlta, $fechaBaja, $especialidad, $extension);
                
                if ($empleado->checkByDni() && $empleado->getDni() !== $_SESSION['dni']) {
                    // ¿DNI válido?
                    // Los empleados pueden estar registrados pero no estar activos (sin una cuenta de acceso)
                    $cuenta = $empleado->getAccount();
                    
                    $msg['msg']['error'] = 'El usuario ya se encuentra registrado.';
                    
                    if (!isset($cuenta)) {
                        $msg['msg']['error'] .= ' (antiguo empleado)';
                    }

                    $msg['form'] = formUpdateEmpleado($usuario, $empleado);
                }elseif (!$empleado->check() && $empleado->checkByEmail()) {
                    // ¿Email válido?
                    // Si cambiamos el DNI del usuario, debemos buscar el email con el antiguo DNI (para el mismo usuario)
                    if ($empleado->checkEmailByDni($_SESSION['dni'])) {
                        if ($empleado->update()) {
                            $msg['msg']['conf'] = 'Empleado actualizado.';
                            $msg['list'] = listEmpleados($usuario, null);
                        } else {
                            $msg['msg']['error'] = 'Ha ocurrido un error durante la actualización del empleado.';
                        }
                    } else {
                        $msg['msg']['error'] = 'La dirección de correo electrónico indicada ya se encuentra en uso por otro usuario.';
                        $msg['form'] = formUpdateEmpleado($usuario, $empleado);
                    }
                } else {
                    if ($empleado->update()) {
                        $msg['msg']['conf'] = 'Empleado actualizado.';
                        $msg['list'] = listEmpleados($usuario, null);
                    } else {
                        $msg['msg']['error'] = 'Ha ocurrido un error durante la actualización del empleado.';
                    }
                }
            } else {
                $empleado->set($dni, $nombre, $apellido1, $apellido2, $sexo, $fechaNacimiento, $email, $telf, null, $fechaAlta, $fechaBaja, $especialidad, $extension);
                $msg['msg']['error'] = 'Error en la obtención de datos o datos sin introducir.';
                $msg['form'] = formUpdateEmpleado($usuario, $empleado);
            }
        } elseif ($btn === 'Resetear datos' || isset($btnClear)) {
            $empleado = new Empleado();
            $empleado->setDni($_SESSION['dni']);
            $data = $empleado->getByDni();

            $msg['msg']['conf'] = 'Datos del formulario reseteados.';
            $msg['form'] = formUpdateEmpleado($usuario, $data);
        } elseif ($btn === 'Cancelar' || isset($btnCancel)) {
            $msg['msg']['conf'] = 'Formulario cancelado.';
        } // else $msg['msg']['error'] = 'No se ha seleccionado ninguna acción.';
        // Redireccionamiento (según JS activado o no)
        redirect($js, $msg);
    }

    // DELETE ----------------------------------------------------------------------------------------------------------

    /**
     * Eliminación del empleado
     * 
     * @param string $dniEmpleado
     * @param array $msg
     * @return array - Mensajes a mostrar
     */
    private static function delete($dniEmpleado, $msg) {
        $usuario = filterSession('usuario');

        // Eliminamos la cuenta del empleado pero conservamos sus datos (debemos eliminar sus registros como admin)
        $empleado = new Empleado();
        $empleado->setDni($dniEmpleado);

        if ($empleado->delete()) {
            $msg['msg']['conf'] = 'Cuenta del empleado eliminada.';
            $msg['list'] = listEmpleados($usuario, null);
        } else {
            $msg['msg']['error'] = 'Ha ocurrido un error durante el proceso.';
        }

        return $msg;
    }

    // RESET -----------------------------------------------------------------------------------------------------------

    /**
     * Recuperación de la contraseña del empleado
     * 
     * @param string $dniEmpleado
     * @param array $msg
     * @return array - Mensajes a mostrar
     */
    private static function reset($dniEmpleado, $msg) {
        // Obtenemos los datos del empleado y la cuenta
        $usuario = new Empleado();
        $usuario->setDni($dniEmpleado);

        $empleado = $usuario->getByDni();

        if (isset($empleado)) {
            $cuenta = $empleado->getAccount();

            if (isset($cuenta)) {
                // if($cuenta->resetPass($email)) { ... } else { ... }
                // Generamos una nueva contraseña
                $pass = newPass();

                // Actualizamos la contraseña de la cuenta
                if ($cuenta->update($pass)) {
                    // Enviamos email con la nueva contraseña
                    if (sendMail($pass, $empleado->getEmail())) {
                        $msg['msg']['conf'] = 'Proceso realizado con éxito.';
                    } else {
                        $msg['msg']['error'] = 'Ha ocurrido un error en el envío del correo electrónico.';
                    }
                } else {
                    $msg['msg']['error'] = 'Ha ocurrido un error al actualizar la contraseña.';
                }
            } else {
                $msg['msg']['error'] = 'No se han podido obtener los datos de la cuenta del usuario.';
            }
        } else {
            $msg['msg']['error'] = 'No se han podido obtener los datos del usuario.';
        }

        return $msg;
    }

    // SEARCH ----------------------------------------------------------------------------------------------------------

    /**
     * Actualización del listado de empleados para mostrar solo aquellos registros que coincidan con los criterios de búsqueda introducidos por el usuario
     */
    public function search() {
        $usuario = filterSession('usuario');

        $msg = msg();

        // Datos del formulario
        $js = filterPost('js');
        $dni = mb_strtoupper(filterPost('dniSearch'));
        $nombre = mb_strtoupper(filterPost('nombreSearch'));
        $especialidad = mb_strtoupper(filterPost('especialidadSearch'));

        // Parámetros de búsqueda
        $search = ['dni' => $dni, 'nombre' => $nombre, 'especialidad' => $especialidad];

        // Mostramos el listado según los datos recogidos
        $msg['list'] = listEmpleados($usuario, $search);

        // Redireccionamiento (según JS activado o no)
        redirect($js, $msg);
    }

    // ACTION ----------------------------------------------------------------------------------------------------------

    /**
     * Acción solicitada desde el listado de empleados (modificación de los datos del empleado, eliminación del empleado o recuperación de la contraseña)
     */
    public function action() {
        $usuario = filterSession('usuario');
        $msg = msg();

        // Datos del formulario
        $js = filterPost('js');
        $dniEmpleado = mb_strtoupper(filterPost('dniEmpleado'));
        $btnMod = filterPost('btnMod');
        $btnDel = filterPost('btnDel');
        $btnRes = filterPost('btnRes');
        
        // Conservamos el DNI original
        $_SESSION['dni'] = $dniEmpleado;

        // Datos obligatorios
        if (isset($dniEmpleado)) {
            // Comprobamos la acción a realizar
            if (isset($btnMod)) {
                // Obtenemos los datos del emplead
                $usuario = new Empleado();
                $usuario->setDni($dniEmpleado);

                $empleado = $usuario->getByDni();

                // Mostramos el formuario de modificación con los datos del cliente
                $msg['form'] = formUpdateEmpleado($usuario, $empleado);
            } elseif (isset($btnDel)) {
                $msg = self::delete($dniEmpleado, $msg);
            } elseif (isset($btnRes)) {
                $msg = self::reset($dniEmpleado, $msg);
            } else {
                $msg['msg']['error'] = 'La acción no ha podido ser realizada.';
            }
        } else {
            $msg['msg']['error'] = 'Error en la obtención de datos.';
        }

        // Redireccionamiento (según JS activado o no)
        redirect($js, $msg);
    }

}
