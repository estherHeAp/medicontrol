<?php

/**
 * Controlador para la vista de la página principal
 *
 * @author Esther
 */
class MainController {

    /**
     * Cargamos la vista que muestra la página principal
     */
    public function index() {
        require_once 'views/modules/header.php';
        require_once 'views/modules/navPerfil.php';
        require_once 'views/modules/msg.php';
        require_once 'views/main.php';
        require_once 'views/modules/footer.php';
    }
    
}
