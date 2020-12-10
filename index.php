<?php

require_once 'core/autoload.php';

// Iniciamos/Recuperamos sesión
if (!isset($_SESSION)) {
    session_start();
}

// Cargamos el controlador y su método correspondientes
Routes::load();
