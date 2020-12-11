<?php

/**
 * Controlador para la gestión de especialidades
 *
 * @author Esther
 */
class EspecialidadesController {

    /**
     * Cargamos la vista de gestión de especialidades
     */
    public function index() {
        require_once 'views/modules/header.php';
        require_once 'views/modules/navPerfil.php';
        require_once 'views/modules/nav.php';
        require_once 'views/modules/msg.php';
        require_once 'views/especialidades.php';
        require_once 'views/modules/footer.php';
    }

    // CREATE ----------------------------------------------------------------------------------------------------------

    /**
     * Adición de una especialidad
     */
    public function add() {
        $usuario = filterSession('usuario');
        $msg = msg();

        // Datos del formulario
        $js = filterPost('js');
        $nombre = mb_strtoupper(filterPost('nombreAdd'));

        // Datos obligatorios
        if (isset($nombre)) {
            // ¿Existe ya la especialidad?
            if (!$usuario->getEspecialidadByNombre($nombre)) {
                $especialidad = new Especialidad();
                $especialidad->setNombre($nombre);

                if ($especialidad->create()) {
                    $msg['msg']['conf'] = 'Proceso realizado con éxito.';
                    $msg['list'] = listEspecialidades($usuario);
                    $msg['otros']['action'] = 'add';
                } else {
                    $msg['msg']['error'] = 'Ha ocurrido un error durante el proceso.';
                }
            } else {
                $msg['msg']['error'] = 'La especialidad indicada ya se encuentra registrada.';
            }
        } else {
            $msg['msg']['error'] = 'Error en la obtención de datos.';
        }

        // Redireccionamiento (según JS activado o no)
        redirect($js, $msg);
    }

    // DELETE ----------------------------------------------------------------------------------------------------------

    /**
     * Eliminación de una especialidad
     */
    public function delete() {
        $usuario = filterSession('usuario');
        $msg = msg();

        // Datos del formulario
        $js = filterPost('js');
        $nombre = mb_strtoupper(filterPost('especialidad'));

        // Datos obligatorios
        if (isset($nombre)) {
            $especialidad = new Especialidad();
            $especialidad->setNombre($nombre);

            // Si existen usuarios con la especialidad indicada, no podremos eliminarla sin antes actualizar la especialidad de estos usuarios
            if ($especialidad->check()) {
                $msg['msg']['error'] = 'Hay usuarios pertenecientes a esta especialidad, actualícelos para poder eliminarla.';
            } else {
                if ($especialidad->delete()) {
                    $msg['msg']['conf'] = 'Proceso realizado con éxito.';
                    $msg['list'] = listEspecialidades($usuario);
                } else {
                    $msg['msg']['error'] = 'Ha ocurrido un error durante el proceso.';
                }
            }
        } else {
            $msg['msg']['error'] = 'Error en la obtención de datos.';
        }

        // Redireccionamiento (según JS activado o no)
        redirect($js, $msg);
    }

}
