<?php

/**
 * Controlador para la vista error
 *
 * @author Esther
 */
class ErrorController {

    /**
     * Cargamos la vista que muestra error
     */
    public function index() {
        require_once 'views/modules/header.php';
        require_once 'views/modules/msg.php';
        require_once 'views/error.php';
        require_once 'views/modules/footer.php';
    }
    
}
