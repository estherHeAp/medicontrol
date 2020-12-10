$(function () {
    // Formulario de login ---------------------------------------------------------------------------------------------
    $("#page-login #formLogin").submit(function () {
        var data = $(this).serialize();
        $.post("?c=login&a=login", data, loginProcessData);

        return false;
    });

    // Formulario de recuperación de contraseña ------------------------------------------------------------------------
    $("#page-login #formReset").submit(function () {
        var data = $(this).serialize();
        $.post("?c=login&a=reset", data, loginProcessData);

        return false;
    });

    // Formulario de registro ------------------------------------------------------------------------------------------
    $("#page-login #formAdd").submit(function () {
        var data = $(this).serialize();
        $.post("?c=login&a=add", data, loginProcessData);

        return false;
    });

    // Procesamiento de datos devueltos por el servidor ----------------------------------------------------------------
    function loginProcessData(serverData) {
        processData(serverData);
    }
});