<?php
$c = filterGet('c');
$page = ucwords($c) . ' - ';

$usuario = filterSession('usuario');

if (isset($usuario)) {
    // Para actualizar los datos que han sido cambiados en otro navegador
    // Recuperamos la información directamente desde la DB
    $_SESSION['usuario'] = serialize($usuario->getByDni());
    $usuario = filterSession('usuario');

    $nombre = $usuario->getNombre();
    if (!($usuario instanceof Admin)) {
        $nombre .= ' ' . $usuario->getApellido1();
    }
} else {
    // Si el usuario ha sido eliminado mientras estaba conectado
    // por el mismo usuario desde otro navegador o por un empleado
    if ($c !== 'login') {
        header('refresh: 0; url= ?c=login');
    }
}

$msg = filterSession('msg');

unset($_SESSION['msg']);

// Valor 0 -> JS desactivado
// Valor 1 -> JS activado -> cambio del value desde el script functions.js
$js = 0;
$inputJs = '<input type="hidden" name="js" class="js" value="' . $js . '">';
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title><?= $page; ?>MediControl</title>
        <link rel="icon" type="image/png" href="views/assets/img/smallLogo.png">

        <!-- CSS -->
        <link href="views/assets/css/bootstrap/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="views/assets/css/all.min.css" rel="stylesheet" type="text/css"/>
        <link href="views/assets/css/base.css" rel="stylesheet" type="text/css"/>
        <link href="views/assets/css/styles.css" rel="stylesheet" type="text/css"/>
    </head>

    <body>

        <div class="container">
            
            <header class="row">
                <noscript>
                <p class="msg-info">Para una mejor experiencia y el correcto funcionamiento de la aplicación, active JavaScript en su navegador.</p>
                </noscript>

                <a class="col-12 mt-1 text-center" href="?c=main">
                    <img src="views/assets/img/bigLogo.png" alt="MediControl"/>
                </a>
            </header>
