<?php

/**
 * Controlador para la gestión de usuarios administradores
 *
 * @author Esther
 */
class AdminController {

    /**
     * Cargamos la vista de administradores
     */
    public function index() {
        require_once 'views/modules/header.php';
        require_once 'views/modules/navPerfil.php';
        require_once 'views/modules/nav.php';
        require_once 'views/modules/msg.php';
        require_once 'views/admin.php';
        require_once 'views/modules/footer.php';
    }

    // CREATE ----------------------------------------------------------------------------------------------------------

    /**
     * Adición del empleado como administrador
     */
    public function add() {
        $usuario = filterSession('usuario');
        $msg = msg();

        // Datos del formulario
        $js = filterPost('js');
        $empleado = filterPost('empleadoSelect');

        $btn = filterPost('btn');

        $btnAdd = filterPost('btnAdd');
        $btnCancel = filterPost('btnCancelAdd');

        if ($btn === 'Añadir' || isset($btnAdd)) {
            // Datos obligatorios
            if (isset($empleado)) {
                // Añadimos el empleado como admin
                $admin = new Admin();
                $admin->setDni($empleado);

                if ($admin->create()) {
                    $msg['msg']['conf'] = 'Administrador añadido.';
                    $msg['list'] = listAdmin($usuario, null);
                    $msg['select']['empleados'] = selectEmpleados($usuario, null);
                } else {
                    $msg['msg']['error'] = 'Ha ocurrido un error al añadir al administrador.';
                }
            } else {
                $msg['msg']['error'] = 'No se ha seleccionado ningún empleado.';
            }
        } elseif ($btn === 'Cancelar' || isset($btnCancel)) {
            $msg['msg']['conf'] = 'Formulario reseteado.';
            $msg['select']['empleados'] = selectEmpleados($usuario, null);
        } // else $msg['msg']['error'] = 'No se ha seleccionado ninguna acción.';
        // Redireccionamiento (según JS activado o no)
        redirect($js, $msg);
    }

    // UPDATE ----------------------------------------------------------------------------------------------------------

    /**
     * Actualización de los datos del empleado administrador
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
            $empleado = new Admin();
            $empleado->setDni($dni);
            $data = $empleado->getByDni();

            // Datos obligatorios
            if ($dni !== '' && $nombre !== '' && $apellido1 !== '' && $email !== '') {
                // Actualizamos los datos del empleado
                $empleado->set($dni, $nombre, $apellido1, $apellido2, $sexo, $fechaNacimiento, $email, $telf, null, $fechaAlta, $fechaBaja, $especialidad, $extension);

                if ($empleado->checkByDni() && $empleado->getDni() !== $_SESSION['dni']) {
                    // ¿DNI válido?
                    // Los empleados pueden estar registrados pero no estar activos (con una cuenta de acceso)
                    $cuenta = $empleado->getAccount();
                    
                    $msg['msg']['error'] = 'El usuario ya se encuentra registrado.';
                    
                    if (!isset($cuenta)) {
                        $msg['msg']['error'] .= ' (antiguo empleado)';
                    }
                    
                    $msg['form'] = formUpdateAdmin($usuario, $empleado);
                }elseif (!$empleado->check() && $empleado->checkByEmail()) {
                    // ¿Email válido?
                    // Si cambiamos el DNI del usuario, debemos buscar el email con el antiguo DNI (para el mismo usuario)
                    if ($empleado->checkEmailByDni($_SESSION['dni'])) {
                        if ($empleado->update()) {
                            $msg['msg']['conf'] = 'Empleado actualizado.';
                            $msg['list'] = listAdmin($usuario, null);
                        } else {
                            $msg['msg']['error'] = 'Ha ocurrido un error durante la actualización del empleado.';
                        }
                    } else {
                        $msg['msg']['error'] = 'La dirección de correo electrónico indicada ya se encuentra en uso por otro usuario.';
                        $msg['form'] = formUpdateAdmin($usuario, $empleado);
                    }
                } else {
                    if ($empleado->update()) {
                        $msg['msg']['conf'] = 'Empleado actualizado.';
                        $msg['list'] = listAdmin($usuario, null);
                    } else {
                        $msg['msg']['error'] = 'Ha ocurrido un error durante la actualización del empleado.';
                    }
                }
            } else {
                $empleado->set($dni, $nombre, $apellido1, $apellido2, $sexo, $fechaNacimiento, $email, $telf, null, $fechaAlta, $fechaBaja, $especialidad, $extension);
                $msg['msg']['error'] = 'Error en la obtención de datos o datos sin introducir.';
                $msg['form'] = formUpdateAdmin($usuario, $empleado);
            }
        } elseif ($btn === 'Resetear datos' || isset($btnClear)) {
            $empleado = new Admin();
            $empleado->setDni($_SESSION['dni']);
            $data = $empleado->getByDni();

            $msg['msg']['conf'] = 'Datos del formulario reseteados.';
            $msg['form'] = formUpdateAdmin($usuario, $data);
        } elseif ($btn === 'Cancelar' || isset($btnCancel)) {
            $msg['msg']['conf'] = 'Formulario cancelado.';
        } // else $msg['msg']['error'] = 'No se ha seleccionado ninguna acción.';
        // Redireccionamiento (según JS activado o no)
        redirect($js, $msg);
    }

    // DELETE ----------------------------------------------------------------------------------------------------------

    /**
     * Eliminación del empleado como administrador
     * 
     * @param string $dniAdmin
     * @param array $msg
     * @return array - Mensajes a mostrar
     */
    private static function delete($dniAdmin, $msg) {
        $usuario = filterSession('usuario');

        // Eliminamos al empleado de la tabla de admin
        $admin = new Admin();
        $admin->setDni($dniAdmin);

        // Eliminamos el cliente
        if ($admin->delete()) {
            $msg['msg']['conf'] = 'Administrador eliminado.';
            $msg['list'] = listAdmin($usuario, null);
            $msg['select']['empleados'] = selectEmpleados($usuario, null);
        } else {
            $msg['msg']['error'] = 'Ha ocurrido un error durante el proceso.';
        }

        return $msg;
    }

    // RESET -----------------------------------------------------------------------------------------------------------

    /**
     * Recuperación de la contraseña del empleado
     * 
     * @param string $dniAdmin
     * @param array $msg - Mensajes a mostrar
     */
    private static function reset($dniAdmin, $msg) {
        // Obtenemos los datos del empleado y la cuenta
        $usuario = new Admin();
        $usuario->setDni($dniAdmin);

        $admin = $usuario->getByDni();

        if (isset($admin)) {
            $cuenta = $admin->getAccount();

            if (isset($cuenta)) {
                // if($cuenta->resetPass($email)) { ... } else { ... }
                // Generamos una nueva contraseña
                $pass = newPass();

                // Actualizamos la contraseña de la cuenta
                if ($cuenta->update($pass)) {
                    // Enviamos email con la nueva contraseña
                    if (sendMail($pass, $admin->getEmail())) {
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
    }

    // SEARCH ----------------------------------------------------------------------------------------------------------

    /**
     * Actualización del listado de administradores para mostrar solo aquellos registros que coincidan con los criterios de búsqueda introducidos por el usuario
     */
    public function search() {
        $usuario = filterSession('usuario');
        $msg = msg();

        // Datos del formulario
        $js = filterPost('js');
        $nombre = mb_strtoupper(filterPost('nombreSearch'));
        $especialidad = mb_strtoupper(filterPost('especialidadSearch'));

        // Parámetros de búsqueda
        $search = ['nombre' => $nombre, 'especialidad' => $especialidad];

        // Mostramos el listado según los datos recogidos
        $msg['list'] = listAdmin($usuario, $search);

        // Redireccionamiento (según JS activado o no)
        redirect($js, $msg);
    }

    // ACTION ----------------------------------------------------------------------------------------------------------

    /**
     * Acción solicitada desde el listado de administradores (modificación de los datos del empleado, eliminación del administrador o recuperación de la contraseña)
     */
    public function action() {
        $usuario = filterSession('usuario');
        $msg = msg();

        // Datos del formulario
        $js = filterPost('js');
        $dniAdmin = mb_strtoupper(filterPost('dniAdmin'));
        $btnMod = filterPost('btnMod');
        $btnDel = filterPost('btnDel');
        $btnRes = filterPost('btnRes');

        // Conservamos el DNI original
        $_SESSION['dni'] = $dniAdmin;

        // Datos obligatorios
        if (isset($dniAdmin)) {
            // Comprobamos la acción a realizar
            if (isset($btnMod)) {
                // Obtenemos los datos del emplead
                $usuario = new Admin();
                $usuario->setDni($dniAdmin);

                $admin = $usuario->getByDni();

                // Mostramos el formuario de modificación con los datos del administrador
                $msg['form'] = formUpdateAdmin($usuario, $admin);
            } elseif (isset($btnDel)) {
                $msg = self::delete($dniAdmin, $msg);
            } elseif (isset($btnRes)) {
                $msg = self::reset($dniAdmin, $msg);
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
