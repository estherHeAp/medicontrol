<?php

/**
 * Clase que utilizaremos para conocer el controlador y método del mismo a ejecutar
 * (nos permitirá cargar la vista correspondiente)
 *
 * @author Esther
 */
class Routes {

    /**
     * Obtención del controlador a utilizar
     * 
     * @return object - Controlador a utilizar
     */
    private static function getController() {
        $c = filterGet('c');

        $controllerName = ucwords($c) . 'Controller';
        $file = 'controllers/' . $controllerName . '.php';

        if (!isset($c)) {
            header('location: ?c=' . C_DEF);

            $controllerName = ucwords(C_DEF) . 'Controller';
            $file = 'controllers/' . $controllerName . '.php';
        } elseif (is_file($file)) {
            if (!isset($_SESSION['usuario']) && $c != 'login') {
                header('location: ?c=' . C_DEF);

                $controllerName = ucwords(C_DEF) . 'Controller';
                $file = 'controllers/' . $controllerName . '.php';
            } elseif (isset($_SESSION['usuario']) && $c == 'login') {
                header('location: ?c=' . S_DEF);

                $controllerName = ucwords(S_DEF) . 'Controller';
                $file = 'controllers/' . $controllerName . '.php';
            }
        } else {
            $controllerName = ucwords(E_DEF) . 'Controller';
            $file = 'controllers/' . $controllerName . '.php';
        }

        require_once $file;
        $controller = new $controllerName();
        return $controller;
    }

    /**
     * Obtención del método a ejecutar
     * 
     * @param object $controller - Controador a utilizar
     * @return string - Método a ejecutar
     */
    private static function getAction($controller) {
        $a = filterGet('a');

        if (!isset($a) || !method_exists($controller, $a)) {
            $a = A_DEF;
        }

        return $a;
    }
    
    // -----------------------------------------------------------------------------------------------------------------

    /**
     * Ejecución del método correspondiente del controlador
     */
    public static function load() {
        $controller = self::getController();
        $action = self::getAction($controller);

        $controller->$action();
    }

}
