<?php

/**
 * Controlador para la gestión de días festivos
 *
 * @author Esther
 */
class FestivosController {

    /**
     * Cargamos la vista de gestión de días festivos
     */
    public function index() {
        require_once 'views/modules/header.php';
        require_once 'views/modules/navPerfil.php';
        require_once 'views/modules/nav.php';
        require_once 'views/modules/msg.php';
        require_once 'views/festivos.php';
        require_once 'views/modules/footer.php';
    }

    // CREATE ----------------------------------------------------------------------------------------------------------

    /**
     * Adición de un día festivo
     */
    public function add() {
        $msg = msg();

        // Datos del formulario
        $js = filterPost('js');
        $fecha = filterPost('fechaAdd');
        $manana1 = filterPost('manana1Add');
        $manana2 = filterPost('manana2Add');
        $tarde1 = filterPost('tarde1Add');
        $tarde2 = filterPost('tarde2Add');
        $duracion = filterPost('duracionAdd');
        $max = filterPost('maxAdd');

        // Datos obligatorios
        if (isset($fecha) && isset($manana1) && isset($manana2) && isset($tarde1) && isset($tarde2) && isset($duracion) && isset($max)) {
            // Comprobamos que el día indicado no se encuentra ya registrado como festivo
            $festivo = new Festivo();
            $festivo->set($manana1, $manana2, $tarde1, $tarde2, $duracion, $max, null, $fecha);

            if ($festivo->check()) {
                $msg['msg']['error'] = 'La fecha indicada ya se encuentra registrada como día festivo.';
            } else {
                if ($festivo->create()) {
                    $msg['msg']['conf'] = 'Proceso realizado con éxito.';
                    $msg['otros']['action'] = 'add';
                    $msg['list'] = listFestivos(null);
                } else {
                    $msg['msg']['error'] = 'Ha ocurrido un error durante el proceso.';
                }
            }
        } else {
            // ERROR
            $msg['msg']['error'] = 'Datos sin introducir.';
        }

        // Redireccionamiento (según JS activado o no)
        redirect($js, $msg);
    }

    // UPDATE ----------------------------------------------------------------------------------------------------------

    /**
     * Actualización de los datos de un día festivo
     */
    public function update() {
        $msg = msg();

        // Datos del formulario
        $js = filterPost('js');
        $fecha = filterPost('fechaUpdate');
        $manana1 = filterPost('manana1Update');
        $manana2 = filterPost('manana2Update');
        $tarde1 = filterPost('tarde1Update');
        $tarde2 = filterPost('tarde2Update');
        $duracion = filterPost('duracionUpdate');
        $max = filterPost('maxUpdate');

        $btn = filterPost('btn');

        $btnUpdate = filterPost('btnUpdate');
        $btnClear = filterPost('btnClearUpdate');
        $btnCancel = filterPost('btnCancelUpdate');

        $festivo = new Festivo();

        if ($btn === 'Actualizar datos' || isset($btnUpdate)) {
            // Datos obligatorios
            if (isset($fecha) && isset($manana1) && isset($manana2) && isset($tarde1) && isset($tarde2) && isset($duracion) && isset($max)) {
                // Actualizamos los datos del día festivo
                $festivo->set($manana1, $manana2, $tarde1, $tarde2, $duracion, $max, null, $fecha);

                if (!$festivo->check() || ($festivo->check() && $fecha === $_SESSION['dni'])) {
                    if ($festivo->update($_SESSION['dni'])) {
                        $msg['msg']['conf'] = 'Proceso realizado con éxito.';
                        $msg['list'] = listFestivos(null);
                    } else {
                        $msg['msg']['error'] = 'Ha ocurrido un error durante el proceso.';
                    }
                } else {
                    $msg['form'] = formUpdateFestivo($festivo);
                    $msg['msg']['error'] = 'La fecha ya existe registrada.';
                }
            } else {
                $festivo->set($manana1, $manana2, $tarde1, $tarde2, $duracion, $max, null, $fecha);
                $msg['msg']['error'] = 'Error en la obtención de datos o datos sin cumplimentar.';
                $msg['form'] = formUpdateFestivo($festivo);
            }
        } elseif ($btn === 'Resetear datos' || isset($btnClear)) {
            $festivo->setFecha($_SESSION['dni']);
            $data = $festivo->getByFecha();

            $msg['msg']['conf'] = 'Datos del formulario reseteado.';
            $msg['form'] = formUpdateFestivo($data);
        } elseif ($btn === 'Cancelar' || isset($btnCancel)) {
            $msg['msg']['conf'] = 'Formulario cancelado.';
        } // else $msg['msg']['error'] = 'No se ha seleccionado ninguna acción.';
        
        // Redireccionamiento (según JS activado o no)
       redirect($js, $msg);
    }

    /**
     * Actualización del año de los festivos al actual
     */
    public function updateYear() {
        $msg = msg();

        // Datos del formulario
        $js = filterPost('js');

        if (Festivo::updateYear()) {
            $msg['msg']['conf'] = 'Días festivos actualizados.';
            $msg['list'] = listFestivos(null);
        } else {
            $msg['msg']['error'] = 'Ha ocurrido un error durante el proceso.';
        }

        // Redireccionamiento (según JS activado o no)
        redirect($js, $msg);
    }

    // DELETE ----------------------------------------------------------------------------------------------------------

    /**
     * Eliminación de un día festivo
     * 
     * @param string $fecha
     * @param array $msg
     * @return array - Mensajes a mostrar
     */
    public function delete($fecha, $msg) {
        if (isset($fecha)) {
            // Eliminamos el día festivo
            $festivo = new Festivo();
            $festivo->setFecha($fecha);

            if ($festivo->delete()) {
                $msg['msg']['conf'] = 'Proceso realizado con éxito.';
                $msg['list'] = listFestivos(null);
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
     * Actualización del listado de días festivos para mostrar solo aquellos registros que coincidan con los criterios de búsqueda introducidos por el usuario
     */
    public function search() {
        $msg = msg();

        // Datos del formulario
        $js = filterPost('js');
        $fecha1 = filterPost('fecha1Search');
        $fecha2 = filterPost('fecha2Search');
        $open = filterPost('openSearch') !== null ? filterPost('openSearch') : 'like';   // '<>' abiertos, '=' cerrados, 'like' null o ''
        // Parámetros de búsqueda
        $search = ['fecha1' => $fecha1, 'fecha2' => $fecha2, 'open' => $open];

        // Mostramos el listado según los datos recogidos
        $msg['list'] = listFestivos($search);

        // Redireccionamiento (según JS activado o no)
        redirect($js, $msg);
    }

    // ACTION ----------------------------------------------------------------------------------------------------------

    /**
     * Acción solicitada desde el listado de días festivos (modificación de los datos del día festivo o eliminación del día festivo)
     */
    public function action() {
        $usuario = filterSession('usuario');

        $msg = msg();

        // Datos del formulario
        $js = filterPost('js');
        $fecha = filterPost('fecha');
        $btnMod = filterPost('btnMod');
        $btnDel = filterPost('btnDel');
        
        // Conservamos la fecha original (reutilizamos la sesión de los DNI)
        $_SESSION['dni'] = $fecha;

        // Comprobamos la acción a realizar
        if (isset($btnMod)) {
            // Obtenemos los datos del día festivo
            $festivo = new Festivo();
            $festivo->setFecha($fecha);

            $data = $festivo->getByFecha();

            // Mostramos el formulario con los datos del día festivo
            // Establecemos el mensaje de error a través del propio formulario 
            $msg['form'] = formUpdateFestivo($data);
        } elseif (isset($btnDel)) {
            $msg = self::delete($fecha, $msg);
        } else {
            $msg['msg']['error'] = 'La acción no ha podido ser realizada.';
        }

        // Redireccionamiento (según JS activado o no)
        redirect($js, $msg);
    }

}
