<?php

/**
 * Controlador para la gestión de citas
 *
 * @author Esther
 */
class CitasController {

    /**
     * Cargamos la vista de gestión de citas
     */
    public function index() {
        require_once 'views/modules/header.php';
        require_once 'views/modules/navPerfil.php';
        require_once 'views/modules/nav.php';
        require_once 'views/modules/msg.php';
        require_once 'views/citas.php';
        require_once 'views/modules/footer.php';
    }

    // CREATE ----------------------------------------------------------------------------------------------------------

    /**
     * Reseteo del formulario de adición de citas
     * 
     * @param Usuario $usuario
     * @param array $msg
     * @return array - Mensajes a mostrar
     */
    private static function resetFormAdd($usuario, $msg) {
        $msg['otros']['action'] = 'add';
        $msg['calendar']['add'] = Calendario::createCalendar(0, 'add', date('m'), date('Y'), null);

        if ($usuario instanceof Empleado) {
            $msg['select']['clientes'] = selectClientes($usuario, null);
            $msg['select']['empleados'] = selectEmpleados($usuario, null);
        }

        // Reseteamos datos
        unset($_SESSION['fecha']);
        unset($_SESSION['cita']);

        return $msg;
    }

    /**
     * Adición de una cita
     */
    public function add() {
        $usuario = filterSession('usuario');
        $fechaMsg = filterSession('fecha');  // Fecha guardada al seleccionarla en el calendario (array marcas de $msg)

        $msg = msg();
        $msg['marcas'] = $fechaMsg;

        $fecha = !empty($msg['marcas']['add']['seleccion']) ? $msg['marcas']['add']['seleccion'] : null;

        // Datos del formulario
        $js = filterPost('js');
        $cliente = filterPost('clienteSelect');
        $empleado = filterPost('empleadoSelect') !== null ? filterPost('empleadoSelect') : null;     // No guardar como ''
        $hora = filterPost('horarioAdd');
        $asunto = mb_strtoupper(filterPost('asuntoAdd'));

        $btn = filterPost('btn');

        $btnAdd = filterPost('btnAdd');
        $btnCancel = filterPost('btnCancelAdd');

        if ($btn === 'Añadir' || isset($btnAdd)) {
            // Datos obligatorios
            if (isset($fecha) && isset($hora) && $asunto !== '') {
                // CDatos de la cita
                $cita = new Cita();
                $cita->set(null, null, null, $fecha, $hora, $asunto);

                if ($usuario instanceof Cliente) {
                    $cita->setCliente($usuario->getDni());
                } elseif ($usuario instanceof Empleado) {
                    $cita->setCliente($cliente);
                    $cita->setEmpleado($empleado);
                }

                // Datos obligatorios
                if ($cita->getCliente() !== null) {
                    // Creamos la cita y devolvemos la nueva lista de citas a mostrar (y el número de citas pendientes para clientes)
                    if ($cita->create()) {
                        $msg['msg']['conf'] = 'Proceso realizado con éxito.';
                        $msg = self::resetFormAdd($usuario, $msg);
                    } else {
                        $msg['msg']['error'] = 'Ha ocurrido un error durante el proceso.';
                    }
                } else {
                    $msg['msg']['error'] = 'El cliente no ha sido especificado.';
                }
            } else {
                $msg['msg']['error'] = 'Datos sin introducir.';
            }

            // Mostramos la lista en cualquier caso
            $msg['list'] = listCitas($usuario, null, 0);
        } elseif ($btn === 'Cancelar' || isset($btnCancel)) {
            $msg['msg']['conf'] = 'Formulario reseteado.';
            $msg = self::resetFormAdd($usuario, $msg);
        } // else $msg['msg']['error'] = 'No se ha seleccionado ninguna acción.';
        
        // Redireccionamiento (según JS activado o no)
        redirect($js, $msg);
    }

    // UPDATE ----------------------------------------------------------------------------------------------------------

    /**
     * Actualización de los datos de una cita
     */
    public function update() {
        $usuario = filterSession('usuario');
        $fechaMsg = filterSession('fecha');  // Fecha guardada al seleccionarla en el calendario (array marcas de $msg)
        $cita = filterSession('cita');  // Datos de la cita seleccionada

        $msg = msg();
        $msg['marcas'] = $fechaMsg;

        $nuevaFecha = !empty($msg['marcas']['update']['seleccion']) ? $msg['marcas']['update']['seleccion'] : null;

        // Datos del formulario
        $js = filterPost('js');
        $all = filterPost('all') !== null ? filterPost('all') : 0;
        $empleado = filterPost(('empleadoSelect')) !== null ? mb_strtoupper(filterPost(('empleadoSelect'))) : null;
        $hora = filterPost('horarioUpdate');
        $asunto = mb_strtoupper(filterPost('asuntoUpdate'));

        $btn = filterPost('btn');

        $btnUpdate = filterPost('btnUpdate');
        $btnClear = filterPost('btnClearUpdate');
        $btnCancel = filterPost('btnCancelUpdate');

        if ($btn === 'Actualizar cita' || isset($btnUpdate)) {
            // Datos obligatorios
            if (isset($cita)) {
                $fecha = isset($nuevaFecha) ? $nuevaFecha : $cita->getFecha();
                
                $cita->set($cita->getId(), $cita->getCliente(), $empleado, $fecha, $hora, $asunto);
                $msg['marcas']['update']['nuevaHora'] = $hora;

                if (isset($fecha) && isset($hora) && $asunto !== '') {
                    // Comprobamos si hay consultas asociadas
                    if ($cita->checkConsultas()) {
                        $msg['msg']['error'] = 'Error al modificar los datos de la cita, ya existe una consulta asociada.';
                    } else {
                        // Actualizamos la cita
                        
                        if ($cita->update()) {
                            $msg['msg']['conf'] = 'Proceso realizado con éxito.';
                            $msg['list'] = listCitas($usuario, null, $all);

                            // Reseteamos los datos
                            unset($_SESSION['fecha']);
                            unset($_SESSION['cita']);
                            unset($_SESSION['marcas']);
                        } else {
                            $msg['msg']['error'] = 'Ha ocurrido un error durante el proceso.';
                        }
                    }
                } else {
                    $msg['msg']['error'] = 'Datos sin introducir.';
                    $msg['form'] = formUpdateCita($msg, $usuario, $cita, $all);
                }
            } else {
                $msg['msg']['error'] = 'Los datos de la cita no han podido ser recuperados.';
            }
        } elseif ($btn === 'Resetear datos' || isset($btnClear)) {
            $msg['msg']['conf'] = 'Datos del formulario reseteados.';

            // Restablecemos las marcas originales
            $msg['marcas']['update']['seleccion'] = $cita->getFecha();
            $msg['marcas']['update']['original'] = $cita;
            $_SESSION['fecha'] = serialize($msg['marcas']);

            $msg['form'] = formUpdateCita($msg, $usuario, $cita, $all);
        } elseif ($btn === 'Cancelar' || isset($btnCancel)) {
            $msg['msg']['conf'] = 'Formulario cancelado.';

            // Reseteamos los datos
            unset($_SESSION['fecha']);
            unset($_SESSION['cita']);
            unset($_SESSION['marcas']);
        } // else $msg['msg']['error'] = 'No se ha seleccionado ninguna acción.';
        
        // Redireccionamiento (según JS activado o no)
        redirect($js, $msg);
    }

    // DELETE ----------------------------------------------------------------------------------------------------------

    /**
     * Eliminación de una cita
     * 
     * @param string $idCita
     * @param string $all
     * @param array $msg
     * @return array - Mensajes a mostrar
     */
    private static function delete($idCita, $all, $msg) {
        $usuario = filterSession('usuario');

        // Datos necesarios
        if (isset($idCita)) {
            // Comprobamos si existen consultas asociadas a la cita
            $cita = new Cita();
            $cita->setId($idCita);

            if (!$cita->checkConsultas()) {
                // Eliminamos la cita
                if ($cita->delete()) {
                    $msg['msg']['conf'] = 'Proceso realizado con éxito.';
                    $msg['otros']['all'] = $all;    // Conservamos el listado completo si es necesario
                    $msg['list'] = listCitas($usuario, null, $all);
                } else {
                    $msg['msg']['error'] = 'Ha ocurrido un error durante el proceso.';
                }
            } else {
                $msg['msg']['error'] = 'Existe una consulta asociada que no puede ser eliminada.';
            }
        } else {
            $msg['msg']['error'] = 'Datos sin introducir.';
        }

        // Devolvemos los mensajes que se puedan haber generado
        return $msg;
    }

    // SEARCH ----------------------------------------------------------------------------------------------------------

    /**
     * Actualización del listado de citas para mostrar solo aquellos registros que coincidan con los criterios de búsqueda introducidos por el usuario
     */
    public function search() {
        $usuario = filterSession('usuario');

        $msg = msg();

        // Datos del formulario
        $js = filterPost('js');
        $all = filterPost('allSearch');
        $dni = mb_strtoupper(filterPost('dniSearch'));
        $asunto = mb_strtoupper(filterPost('asuntoSearch'));

        // Parámetros de búsqueda
        $search = ['dni' => $dni, 'asunto' => $asunto];

        // Mostramos el listado según los datos recogidos
        $msg['otros']['all'] = $all;   // Conservamos la lista completa
        $msg['list'] = listCitas($usuario, $search, $all);

        // Redireccionamiento (según JS activado o no)
        redirect($js, $msg);
    }

    // LIST -------------------------------------------------------------------------------------------------------------

    /**
     * Obtención del listado citas pendientes o todas las citas registradas
     */
    public function list() {
        $usuario = filterSession('usuario');

        $msg = msg();

        // Datos del formulario
        $js = filterPost('js');
        $all = filterPost('all');
        $pendientes = filterPost('pendientes');     // Conservamos a vista del listado de citas pendientes
        // Comprobamos que botón se ha utilizado y mostramos la lista correspondiente
        if (isset($all)) {
            $msg['otros']['all'] = 1;  // Conservamos la vista del listado completo
            $msg['list'] = listCitas($usuario, null, 1);
        } else if (isset($pendientes)) {
            $msg['list'] = listCitas($usuario, null, 0);
        } else {
            $msg['msg']['error'] = 'No se ha seleccionado una acción.';
        }

        // Redireccionamiento (según JS activado o no)
        redirect($js, $msg);
    }

    // ACTION ----------------------------------------------------------------------------------------------------------

    /**
     * Acción solicitada desde el listado de citas (modificación de los datos de la cita o eliminación de la cita)
     */
    public function action() {
        $usuario = filterSession('usuario');

        $msg = msg();

        // Datos del formulario
        $js = filterPost('js');
        $all = filterPost('all');
        $idCita = filterPost('idCita');
        $btnMod = filterPost('btnMod');
        $btnDel = filterPost('btnDel');

        // Comprobamos la acción a realizar
        if (isset($btnMod)) {
            // Reseteamos la fecha seleccionada
            unset($_SESSION['fecha']);

            // Obtenemos los datos de la cita
            $cita = new Cita();
            $cita->setId($idCita);

            $data = $cita->get();

            // Guardamos las marcas del calendario
            $msg['marcas']['update']['original'] = $data;
            $msg['marcas']['update']['active'] = true;

            // Mostramos el formulario con los datos de la cita
            // Establecemos el mensaje de error a través del propio formulario 
            // Conservamos el listado de citas (completo o no)
            $msg['list'] = listCitas($usuario, null, $all);
            $msg['form'] = formUpdateCita($msg, $usuario, $data, $all);

            // Guardamos la cita en sesión para utilizarla como marca en los calendarios y horarios a cargar
            $_SESSION['cita'] = serialize($data);
            $_SESSION['marcas'] = serialize($msg);
        } elseif (isset($btnDel)) {
            $msg = self::delete($idCita, $all, $msg);
        } else {
            $msg['msg']['error'] = 'La acción no ha podido ser realizada.';
        }

        // Redireccionamiento (según JS activado o no)
        redirect($js, $msg);
    }

}
