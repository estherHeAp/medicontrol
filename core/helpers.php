<?php

/**
 * Obtención de datos por GET
 * 
 * @param string $data
 * @return string
 */
function filterGet($data) {
    $result = !is_null(filter_input(INPUT_GET, $data)) && filter_input(INPUT_GET, $data) !== '' ? filter_input(INPUT_GET, $data) : null;
    return $result;
}

/**
 * Obtención de datos por POST
 * 
 * @param string $data
 * @return string
 */
function filterPost($data) {
    $result = !is_null(filter_input(INPUT_POST, $data)) && filter_input(INPUT_POST, $data) !== '' ? filter_input(INPUT_POST, $data) : null;
    return $result;
}

/**
 * Obtención de los objetos guardados en sesión
 * 
 * @param string $data
 * @return object
 */
function filterSession($data) {
    $result = isset($_SESSION[$data]) ? unserialize($_SESSION[$data]) : null;

    return $result;
}

// ---------------------------------------------------------------------------------------------------------------------

/**
 * Generación de una contraseña alfanumérica aleatoria
 * 
 * @return string
 */
function newPass() {
    $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
    $pass = substr(str_shuffle($permitted_chars), 0, 10);

    return $pass;
}

/**
 * Envío de un correo electrónico
 * 
 * @param string $pass
 * @param string $email
 * @return boolean - Envío existoso (true) o no (false)
 */
function sendMail($pass, $email) {
    $admin = DB::getAdminEmail();

    $titulo = 'MediControl - Nueva contraseña';
    $mensaje = 'De acuerdo a su petici&oacute;n, se le ha asignado una nueva contrase&ntilde;a para acceder a su cuenta en MediControl: ' . $pass;

    $header = "MIME-Version: 1.0\n";
    $header .= "Content-type: text/html; charset=iso-8859-1\n";
    $header .= "From: kennotamashi@gmail.com\n";
    $header .= "Return-path: kennotamashi@gmail.com\n";
    $header .= "X-Mailer: PHP/" . phpversion() . "\n";

    return mail($email, $titulo, $mensaje, $header);
}

// ---------------------------------------------------------------------------------------------------------------------

/**
 * Forma de redireccionamiento conveniente según JS activado o no
 * 
 * @param string $js - JavaScript activado (1) o no (0)
 * @param array $msg - Mensajes almacenados para su visualización
 */
function redirect($js, $msg) {
    $page = filterGet('c');

    if ($js == 0) {
        // JS desactivado
        $_SESSION['msg'] = serialize($msg);
        header("location: ?c=$page");
    } else {
        // JS activado
        header('content-type: application/json; charset=utf-8');
        echo json_encode($msg);
    }
}
