$(function () {
    // Formuario de adición de días festivos ---------------------------------------------------------------------------
    $("#page-festivos #formAdd").submit(function () {
        var data = $(this).serialize();
        $.post("?c=festivos&a=add", data, festivosProcessData);

        return false;
    });

    // Formulario de búsqueda de días festivos -------------------------------------------------------------------------
    $("#page-festivos #formSearch").on("input submit", function () {
        var data = $(this).serialize();
        $.post("?c=festivos&a=search", data, festivosProcessData);

        return false;
    });

    // Actualización de días festivos al año actual --------------------------------------------------------------------
    $("#page-festivos #listFestivos").on("submit", "#formUpdateYear", function () {
        var data = $(this).serialize();
        $.post("?c=festivos&a=updateYear", data, festivosProcessData);

        return false;
    });

    // Listado y acciones posibles (modificación y eliminación) de días festivos ---------------------------------------
    $("#listFestivos").on("submit", "#formAction", function () {
        return false;
    });

    $("#listFestivos").on("click", ".btnMod", function () {
        var data = {js: $(".js").val(), fecha: $(this).parent().find(".fecha").val(), btnMod: $(this).val()};
        $.post("?c=festivos&a=action", data, festivosProcessData);
    });

    $("#listFestivos").on("click", ".btnDel", function () {
        var eliminar = confirm("El día festivo será eliminado, ¿seguro que desea continuar?");

        if (eliminar) {
            var data = {js: $(".js").val(), fecha: $(this).parent().find(".fecha").val(), btnDel: $(this).val()};
            $.post("?c=festivos&a=action", data, festivosProcessData);
        }
    });

    // Formulario de modificación de días festivos ---------------------------------------------------------------------
    $("#page-festivos #divUpdate").on("submit", "#formUpdate", function () {
        return false;
    });

    $("#page-festivos #divUpdate").on("click", "#formUpdate input[type='submit']", function () {
        var data = $(this).parent().parent().parent().serialize() + "&btn=" + $(this).val();
        $.post("?c=festivos&a=update", data, festivosProcessData);
    });

    // Procesamiento de datos devueltos por el servidor ----------------------------------------------------------------
    function festivosProcessData(serverData) {
        processData(serverData);
    }
});
    