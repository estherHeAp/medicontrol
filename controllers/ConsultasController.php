<?php

/**
 * Controlador para la gestión de consultas
 *
 * @author Esther
 */
class ConsultasController {

    /**
     * Cargamos la vista de gestión de consultas
     */
    public function index() {
        require_once 'views/modules/header.php';
        require_once 'views/modules/navPerfil.php';
        require_once 'views/modules/nav.php';
        require_once 'views/modules/msg.php';
        require_once 'views/consultas.php';
        require_once 'views/modules/footer.php';
    }

    // CREATE ----------------------------------------------------------------------------------------------------------

    /**
     * Adición de una consulta
     */
    public function add() {
        $usuario = filterSession('usuario');

        $msg = msg();

        // Datos del formulario
        $js = filterPost('js');
        $cita = filterPost('citaSelect');

        $btn = filterPost('btn');

        $btnAdd = filterPost('btnAdd');
        $btnCancel = filterPost('btnCancelAdd');

        if ($btn === 'Añadir' || isset($btnAdd)) {
            // Datos obligatorios
            if (isset($cita)) {
                // Comprobamos que la consulta no existe todavía
                $consulta = new Consulta();
                $consulta->setCita($cita);

                if ($consulta->check()) {
                    $msg['msg']['error'] = 'La consulta ya existe.';
                } else {
                    // Creamos la consulta
                    if ($consulta->create()) {
                        $msg['msg']['conf'] = 'Consulta añadida.';
                        $msg['list'] = listConsultas($usuario, null);
                        $msg['select']['citas'] = selectCitas($usuario);
                    } else {
                        $msg['msg']['error'] = 'Ha ocurrido un error al añadir la consulta.';
                    }
                }
            } else {
                // ERROR
                $msg['msg']['error'] = 'No se ha seleccionado ninguna cita.';
            }
        } elseif ($btn === 'Cancelar' || isset($btnCancel)) {
            $msg['msg']['conf'] = 'Formulario reseteado.';
            $msg['select']['citas'] = selectCitas($usuario);
        } // else $msg['msg']['error'] = 'No se ha seleccionado ninguna acción.';
        // Redireccionamiento (según JS activado o no)
        redirect($js, $msg);
    }

    // UPDATE ----------------------------------------------------------------------------------------------------------

    /**
     * Actualización de los datos de una consulta
     */
    public function update() {
        $usuario = filterSession('usuario');

        $msg = msg();

        // Datos del formulario
        $js = filterPost('js');
        $id = filterPost('idConsulta');
        $idCita = filterPost('idCita');
        $empleado = filterPost(('empleadoSelect')) !== null ? mb_strtoupper(filterPost(('empleadoSelect'))) : null;
        $asunto = mb_strtoupper(filterPost('asuntoUpdate'));
        $descripcion = mb_strtoupper(filterPost('descripcionUpdate'));
        $pruebas = filterPost('pruebasUpdate') !== null ? 1 : 0;
        $pruebasDet = mb_strtoupper(filterPost('pruebasDetUpdate'));
        $tratamientos = filterPost('tratamientosUpdate') !== null ? 1 : 0;
        $tratamientosDet = mb_strtoupper(filterPost('tratamientosDetUpdate'));
        $otros = mb_strtoupper(filterPost('otrosUpdate'));
        $importe = filterPost('importeUpdate');
        $pago = filterPost('pagoUpdate') !== null ? 1 : 0;

        $btn = filterPost('btn');

        $btnUpdate = filterPost('btnUpdate');
        $btnClear = filterPost('btnClearUpdate');
        $btnCancel = filterPost('btnCancelUpdate');

        $consulta = new Consulta();
        $consulta->setId($id);
        $datos = $consulta->get();

        if ($btn === 'Actualizar consulta' || isset($btnUpdate)) {
            // Datos obligatorios
            if (isset($id)) {
                // Actualizamos los datos de la consulta
                $consulta->set($id, $idCita, $descripcion, $pruebas, $pruebasDet, $tratamientos, $tratamientosDet, $otros, $importe, $pago);

                if ($asunto !== '') {
                    if ($consulta->update()) {
                        // Actualizamos el encargado de la cita
                        $cita = new Cita();
                        $cita->setId($idCita);

                        $data = $cita->get();
                        $data->setEmpleado($empleado);
                        $data->setAsunto($asunto);

                        if ($data->update()) {
                            $msg['msg']['conf'] = 'Consulta modificada.';
                            $msg['list'] = listConsultas($usuario, null);
                        } else {
                            $msg['msg']['error'] = 'Ha ocurrido un error durante la actualización de la cita';
                        }
                    } else {
                        $msg['msg']['error'] = 'Ha ocurrido un error durante la actualización de la consulta';
                    }
                } else {
                    // Preparamos los datos de la consulta sin guardar para mostrarlos en el formulario
                    $nuevaConsulta = [
                        'consulta' => $id,
                        'id_cita' => $idCita,
                        'descripcion' => $descripcion,
                        'pruebas' => $pruebas,
                        'pruebas_detalles' => $pruebasDet,
                        'tratamientos' => $tratamientos,
                        'tratamientos_detalles' => $tratamientosDet,
                        'otros_detalles' => $otros,
                        'importe' => $importe,
                        'pago' => $pago,
                        'dni_empleado' => $empleado,
                        'asunto' => $asunto
                    ];
                    
                    $msg['msg']['error'] = 'Debe indicar un asunto.';
                    $msg['form'] = formUpdateConsulta($usuario, $datos, $nuevaConsulta);
                }
            } else {
                $msg['msg']['error'] = 'Error en la obtención de datos.';
            }
        } elseif ($btn === 'Resetear datos' || isset($btnClear)) {
            $msg['msg']['conf'] = 'Datos del formulario reseteados.';
            $msg['form'] = formUpdateConsulta($usuario, $datos, null);
        } elseif ($btn === 'Cancelar' || isset($btnCancel)) {
            $msg['msg']['conf'] = 'Formulario cancelado.';
        } // else $msg['msg']['error'] = 'No se ha seleccionado ninguna acción.';
        // Redireccionamiento (según JS activado o no)
        redirect($js, $msg);
    }

    // DELETE ----------------------------------------------------------------------------------------------------------

    /**
     * Eliminación de una consulta
     * 
     * @param string $idConsulta
     * @param array $msg
     * @return array - Mensajes a mostrar
     */
    public function delete($idConsulta, $msg) {
        $usuario = filterSession('usuario');

        if (isset($idConsulta)) {
            // Eliminamos la consulta
            $consulta = new Consulta();
            $consulta->setId($idConsulta);

            if ($consulta->delete()) {
                $msg['msg']['conf'] = 'Proceso realizado con éxito.';
                $msg['list'] = listConsultas($usuario, null);
                $msg['select']['citas'] = selectCitas($usuario);
            } else {
                $msg['msg']['error'] = 'Ha ocurrido un error durante el proceso.';
            }
        } else {
            $msg['msg']['error'] = 'Error en la obtención de datos.';
        }

        return $msg;
    }

    // SEARCH ----------------------------------------------------------------------------------------------------------

    /**
     * Actualización del listado de consultas para mostrar solo aquellos registros que coincidan con los criterios de búsqueda introducidos por el usuario
     */
    public function search() {
        $usuario = filterSession('usuario');

        $msg = msg();

        // Datos del formulario
        $js = filterPost('js');
        $dni = filterPost('dniSearch') !== null ? mb_strtoupper(filterPost('dniSearch')) : '';
        $asunto = filterPost('asuntoSearch') !== null ? mb_strtoupper(filterPost('asuntoSearch')) : '';
        $pago = filterPost('pagoSearch') !== null ? filterPost('pagoSearch') : '';

        // Parámetros de búsqueda
        $search = ['dni' => $dni, 'asunto' => $asunto, 'pago' => $pago];

        // Mostramos el listado según los datos recogidos
        $msg['list'] = listConsultas($usuario, $search);

        // Redireccionamiento (según JS activado o no)
        redirect($js, $msg);
    }

    // ACTION ----------------------------------------------------------------------------------------------------------

    /**
     * Acción solicitada desde el listado de consultas (modificación de los datos de la consulta o eliminación de la consulta)
     */
    public function action() {
        $usuario = filterSession('usuario');

        $msg = msg();

        // Datos del formulario
        $js = filterPost('js');
        $idConsulta = filterPost('idConsulta');
        $btnMod = filterPost('btnMod');
        $btnDel = filterPost('btnDel');

        // Comprobamos la acción a realizar
        if (isset($btnMod)) {
            // Obtenemos los datos de la consulta
            $consulta = new Consulta();
            $consulta->setId($idConsulta);

            $data = $consulta->get();

            // Mostramos el formulario con los datos de la consulta
            // Establecemos el mensaje de error a través del propio formulario 
            $msg['form'] = formUpdateConsulta($usuario, $data, null);
        } elseif (isset($btnDel)) {
            $msg = self::delete($idConsulta, $msg);
        } else {
            $msg['msg']['error'] = 'La acción no ha podido ser realizada.';
        }

        // Redireccionamiento (según JS activado o no)
        redirect($js, $msg);
    }

}
