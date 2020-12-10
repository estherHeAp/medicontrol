$(function () {
    // Formulario de adición de clientes -------------------------------------------------------------------------------
    $("#page-clientes #formAdd").submit(function () {
        var data = $(this).serialize();
        $.post("?c=clientes&a=add", data, clientesProcessData);

        return false;
    });

    // Formulario de búsqueda de clientes ------------------------------------------------------------------------------
    $("#page-clientes #formSearch").on("input submit", function () {
        var data = $(this).serialize();
        $.post("?c=clientes&a=search", data, clientesProcessData);

        return false;
    });

    // Listado y acciones posibles (modificación, eliminación y recuperación de contraseña) de clientes ----------------
    $("#listClientes").on("submit", "#formAction", function () {
        return false;
    });

    $("#listClientes").on("click", ".btnMod", function () {
        var data = {js: $(".js").val(), dniCliente: $(this).parent().find(".dniCliente").val(), btnMod: $(this).val()};
        $.post("?c=clientes&a=action", data, clientesProcessData);
    });

    $("#listClientes").on("click", ".btnDel", function () {
        var eliminar = confirm("El cliente será eliminado, ¿seguro que desea continuar?");

        if (eliminar) {
            var data = {js: $(".js").val(), dniCliente: $(this).parent().find(".dniCliente").val(), btnDel: $(this).val()};
            $.post("?c=clientes&a=action", data, clientesProcessData);
        }
    });

    $("#listClientes").on("click", ".btnRes", function () {
        var data = {js: $(".js").val(), dniCliente: $(this).parent().find(".dniCliente").val(), btnRes: $(this).val()};
        $.post("?c=clientes&a=action", data, clientesProcessData);
    });

    // Formulario de actualización de clientes -------------------------------------------------------------------------
    $("#page-clientes #divUpdate").on("submit", "#formUpdate", function () {
        return false;
    });

    $("#page-clientes #divUpdate").on("click", "#formUpdate input[type='submit']", function () {
        var data = $(this).parent().parent().parent().serialize() + "&btn=" + $(this).val();
        $.post("?c=clientes&a=update", data, clientesProcessData);
    });

    // Procesamiento de datos devueltos por el servidor ----------------------------------------------------------------
    function clientesProcessData(serverData) {
        processData(serverData);
    }
});
    