<?php

/**
 * Controlador para la gestión de usuarios clientes
 *
 * @author Esther
 */
class ClientesController {

    /**
     * Cargamos la vista de gestión de clientes
     */
    public function index() {
        require_once 'views/modules/header.php';
        require_once 'views/modules/navPerfil.php';
        require_once 'views/modules/nav.php';
        require_once 'views/modules/msg.php';
        require_once 'views/clientes.php';
        require_once 'views/modules/footer.php';
    }

    // CREATE ----------------------------------------------------------------------------------------------------------

    /**
     * Adición de un cliente
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
        $aseguradora = mb_strtoupper(filterPost('aseguradoraAdd'));

        // Datos obligatorios
        if (isset($dni) && isset($nombre) && isset($apellido1) && isset($email)) {
            // Comprobar si el usuario ya se encuentra registrado
            $cliente = new Cliente();
            $cliente->set($dni, $nombre, $apellido1, $apellido2, $sexo, $fechaNacimiento, $email, $telf, $aseguradora, null, null, null, null);

            if ($cliente->getByDni() !== null) {
                $msg['msg']['error'] = 'El usuario ya se encuentra registrado.';
            } else {
                // Comprobar si el email ya se encuentra en uso por otro usuario
                if ($cliente->getByEmail() !== null) {
                    $msg['msg']['error'] = 'La dirección de correo electrónico ya se encuentra en uso, introduzca una diferente.';
                } else {
                    // Generamos una contraseña para el usuario
                    $pass = newPass();

                    // Crear usuario y cuenta
                    $cuenta = new Cuenta();
                    $cuenta->set($dni, $pass);

                    if ($cliente->create() && $cuenta->create()) {
                        // Enviamos la contraseña al usuario
                        if (sendMail($pass, $email)) {
                            $msg['msg']['conf'] = 'Proceso realizado con éxito.';
                            $msg['otros']['action'] = 'add';
                            $msg['list'] = listClientes($usuario, null);
                        } else {
                            $msg['msg']['error'] = 'Ha ocurrido un error durante el registro.';
                        }
                    } else {
                        $msg['msg']['error'] = 'Ha ocurrido un error durante el registro.';
                    }
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
     * Actualización de los datos de un cliente
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
        $aseguradora = mb_strtoupper(filterPost('aseguradoraUpdate'));

        $btn = filterPost('btn');

        $btnUpdate = filterPost('btnUpdate');
        $btnClear = filterPost('btnClearUpdate');
        $btnCancel = filterPost('btnCancelUpdate');

        if ($btn === 'Actualizar datos' || isset($btnUpdate)) {
            $cliente = new Cliente();
            $cliente->setDni($dni);
            $data = $cliente->getByDni();

            // Datos obligatorios
            if ($dni !== '' && $nombre !== '' && $apellido1 !== '' && $email !== '') {
                // Actualizamos los datos del cliente
                $cliente->set($dni, $nombre, $apellido1, $apellido2, $sexo, $fechaNacimiento, $email, $telf, $aseguradora, null, null, null, null);

                if ($cliente->checkByDni() && $cliente->getDni() !== $_SESSION['dni']) {
                    // ¿DNI válido?
                    $msg['msg']['error'] = 'El usuario ya se encuentra registrado.';
                    $msg['form'] = formUpdateCliente($cliente, null);
                } elseif (!$cliente->check() && $cliente->checkByEmail()) {
                    // ¿Email válido?
                    // Si cambiamos el DNI del usuario, debemos buscar el email con el antiguo DNI (para el mismo usuario)
                    if ($cliente->checkEmailByDni($_SESSION['dni'])) {
                        if ($cliente->update()) {
                            $msg['msg']['conf'] = 'Cliente actualizado.';
                            $msg['list'] = listClientes($usuario, null);
                        } else {
                            $msg['msg']['error'] = 'Ha ocurrido un error durante la actualización del cliente.';
                        }
                    } else {
                        $msg['msg']['error'] = 'La dirección de correo electrónico ya se encuentra en uso por otro usuario. Introduzca una diferente.';
                        $msg['form'] = formUpdateCliente($cliente, null);
                    }
                } else {
                    if ($cliente->update()) {
                        $msg['msg']['conf'] = 'Cliente actualizado.';
                        $msg['list'] = listClientes($usuario, null);
                    } else {
                        $msg['msg']['error'] = 'Ha ocurrido un error durante la actualización del cliente.';
                    }
                }
            } else {
                $msg['msg']['error'] = 'Error en la obtención de datos o datos sin introducir.';
                $cliente->set($dni, $nombre, $apellido1, $apellido2, $sexo, $fechaNacimiento, $email, $telf, $aseguradora, null, null, null, null);
                $msg['form'] = formUpdateCliente($cliente, null);
            }
        } elseif ($btn === 'Resetear datos' || isset($btnClear)) {
            $cliente = new Cliente();
            $cliente->setDni($_SESSION['dni']);
            $data = $cliente->getByDni();

            $msg['msg']['conf'] = 'Datos del formulario reseteados.';
            $msg['form'] = formUpdateCliente($data, null);
        } elseif ($btn === 'Cancelar' || isset($btnCancel)) {
            $msg['msg']['conf'] = 'Formulario cancelado.';
        } // else $msg['msg']['error'] = 'No se ha seleccionado ninguna acción.';
        // Redireccionamiento (según JS activado o no)
        redirect($js, $msg);
    }

    // DELETE ----------------------------------------------------------------------------------------------------------

    /**
     * Eliminación del cliente
     * 
     * @param string $dniCliente
     * @param array $msg
     * @return array - Mensajes a mostrar
     */
    private static function delete($dniCliente, $msg) {
        $usuario = filterSession('usuario');

        // Comprobar si el cliente tiene consultas asociadas
        $cliente = new Cliente();
        $cliente->setDni($dniCliente);
        $consultas = $cliente->getConsultas(null);

        if (sizeof($consultas) > 0) {
            $msg['msg']['error'] = 'El usuario dispone de consultas asociadas que no pueden ser eliminadas.';
        } else {
            // Eliminamos el cliente
            if ($cliente->delete()) {
                $msg['msg']['conf'] = 'Cliente eliminado.';
                $msg['list'] = listClientes($usuario, null);
            } else {
                $msg['msg']['error'] = 'Ha ocurrido un error durante el proceso.';
            }
        }

        return $msg;
    }

    // RESET -----------------------------------------------------------------------------------------------------------

    /**
     * Recuperación de la contraseña del cliente
     * 
     * @param string $dniCliente
     * @param array $msg
     * @return array - Mensajes a mostrar
     */
    private static function reset($dniCliente, $msg) {
        // Obtenemos los datos del cliente y la cuenta
        $usuario = new Cliente();
        $usuario->setDni($dniCliente);

        $cliente = $usuario->getByDni();

        if (isset($cliente)) {
            $cuenta = $cliente->getAccount();

            if (isset($cuenta)) {
                // if($cuenta->resetPass($email)) { ... } else { ... }
                // Generamos una nueva contraseña
                $pass = newPass();

                // Actualizamos la contraseña de la cuenta
                if ($cuenta->update($pass)) {
                    // Enviamos email con la nueva contraseña
                    if (sendMail($pass, $cliente->getEmail())) {
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
     * Actualización del listado de clientes para mostrar solo aquellos registros que coincidan con los criterios de búsqueda introducidos por el usuario
     */
    public function search() {
        $usuario = filterSession('usuario');

        $msg = msg();

        // Datos del formulario
        $js = filterPost('js');
        $dni = mb_strtoupper(filterPost('dniSearch'));

        // Parámetros de búsqueda
        $search = ['dni' => $dni];

        // Mostramos el listado según los datos recogidos
        $msg['list'] = listClientes($usuario, $search);

        // Redireccionamiento (según JS activado o no)
        redirect($js, $msg);
    }

    // ACTION ----------------------------------------------------------------------------------------------------------

    /**
     * Acción solicitada desde el listado de clientes (modificación de los datos del cliente, eliminación del cliente o recuperación de la contraseña)
     */
    public function action() {
        $usuario = filterSession('usuario');

        //$msg = filterSession('msg') !== null ? filterSession('msg') : msg();
        $msg = msg();

        // Datos del formulario
        $js = filterPost('js');
        $dniCliente = mb_strtoupper(filterPost('dniCliente'));
        $btnMod = filterPost('btnMod');
        $btnDel = filterPost('btnDel');
        $btnRes = filterPost('btnRes');

        // Conservamos el DNI original
        $_SESSION['dni'] = $dniCliente;

        // Datos obligatorios
        if (isset($dniCliente)) {
            // Comprobamos la acción a realizar
            if (isset($btnMod)) {
                // Obtenemos los datos del cliente
                $usuario = new Usuario();
                $usuario->setDni($dniCliente);

                $cliente = $usuario->getByDni();

                // Mostramos el formuario de modificación con los datos del cliente
                $msg['form'] = formUpdateCliente($cliente, $dniCliente);
            } elseif (isset($btnDel)) {
                $msg = self::delete($dniCliente, $msg);
            } elseif (isset($btnRes)) {
                $msg = self::reset($dniCliente, $msg);
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
