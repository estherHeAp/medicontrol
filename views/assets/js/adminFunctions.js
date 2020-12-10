$(function () {
    // Formulario de adición de administradores ------------------------------------------------------------------------
    $("#page-admin #formAdd").submit(function () {
        return false;
    });

    $("#page-admin #formAdd input[type='submit']").click(function () {
        var data = {js: $(".js").val(), empleadoSelect: $(".empleadoSelect").val(), btn: $(this).val()};
        $.post("?c=admin&a=add", data, adminProcessData);
    });

    // Formulario de búsqueda de administradores -----------------------------------------------------------------------
    $("#page-admin #formSearch").on("input", function () {
        var data = $(this).serialize();
        $.post("?c=admin&a=search", data, adminProcessData);

        return false;
    });

    // Listado y acciones posibles (modificación, eliminación y recuperación de contraseña) de administradores ---------
    $("#listAdmin").on("submit", "#formAction", function () {
        return false;
    });

    $("#listAdmin").on("click", ".btnMod", function () {
        var data = {js: $(".js").val(), dniAdmin: $(this).parent().find(".dniAdmin").val(), btnMod: $(this).val()};
        $.post("?c=admin&a=action", data, adminProcessData);
    });

    $("#listAdmin").on("click", ".btnDel", function () {
        var eliminar = confirm("El empleado dejará de ser administrador, ¿desea continuar?");

        if (eliminar) {
            var data = {js: $(".js").val(), dniAdmin: $(this).parent().find(".dniAdmin").val(), btnDel: $(this).val()};
            $.post("?c=admin&a=action", data, adminProcessData);
        }
    });

    $("#listAdmin").on("click", ".btnRes", function () {
        var data = {js: $(".js").val(), dniAdmin: $(this).parent().find(".dniAdmin").val(), btnRes: $(this).val()};
        $.post("?c=admin&a=action", data, adminProcessData);
    });

    // Formulario de actualización de administradores ------------------------------------------------------------------
    $("#page-admin #divUpdate").on("submit", "#formUpdate", function () {
        return false;
    });

    $("#page-admin #divUpdate").on("click", "#formUpdate input[type='submit']", function () {
        var data = $(this).parent().parent().parent().serialize() + "&btn=" + $(this).val();
        $.post("?c=admin&a=update", data, adminProcessData);
    });

    // Procesamiento de datos devueltos por el servidor ----------------------------------------------------------------
    function adminProcessData(serverData) {
        processData(serverData);
    }
});
    