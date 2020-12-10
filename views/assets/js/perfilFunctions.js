$(function () {
    // Formulario de modificación de datos personale del usuario -------------------------------------------------------
    $("#page-perfil #divUpdate").on("submit", "#formUpdate", function () {
        return false;
    });

    $("#page-perfil #divUpdate").on("click", "#formUpdate input[type='submit']", function () {
        var data = $(this).parent().parent().parent().parent().serialize() 
                + "&fechaAltaUpdate=" + $("#fechaAltaUpdate").val() 
                + "&fechaBajaUpdate=" + $("#fechaBajaUpdate").val() 
                + "&especialidadUpdate=" + $("#especialidadUpdate").val() 
                + "&extensionUpdate=" + $("#extensionUpdate").val() 
                + "&btn=" + $(this).val();
        $.post("?c=perfil&a=update", data, perfilProcessData);
    });

    // Formulario de cambio de contraseña del usuario ------------------------------------------------------------------
    $("#page-perfil #formChange").submit(function () {
        var data = $(this).serialize();
        $.post("?c=perfil&a=change", data, perfilProcessData);

        return false;
    });

    // Formuario de eliminación de la cuenta de un usuario -------------------------------------------------------------
    $("#page-perfil #formDelete").submit(function () {
        var eliminar = confirm("Su cuenta será eliminada junto con sus datos personales, ¿seguro que desea continuar?");

        if (eliminar) {
            var data = $(this).serialize();
            $.post("?c=perfil&a=delete", data, perfilProcessData);
        }

        return false;
    });

    // Procesamiento de datos devueltos por el servidor ----------------------------------------------------------------
    function perfilProcessData(serverData) {
        processData(serverData);
    }
});