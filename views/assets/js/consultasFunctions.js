$(function () {
    // Formulario de adición de consultas ------------------------------------------------------------------------------
    $("#page-consultas #formAdd").submit(function () {
        return false;
    });

    $("#page-consultas #formAdd input[type='submit']").click(function () {
        var data = {
            js: $(".js").val(),
            citaSelect: $(".citaSelect").val(),
            btn: $(this).val()
        };

        $.post("?c=consultas&a=add", data, consultasProcessData);
    });

    // Formulario de búsqueda de consultas -----------------------------------------------------------------------------
    $("#page-consultas #formSearch").on("input submit", function () {
        var data = $(this).serialize();
        $.post("?c=consultas&a=search", data, consultasProcessData);

        return false;
    });

    // Listado y acciones posibles (modificación y eliminación) de consultas -------------------------------------------
    $("#listConsultas").on("submit", "#formAction", function () {
        return false;
    });

    $("#listConsultas").on("click", ".btnMod", function () {
        var data = {js: $(".js").val(), idConsulta: $(this).parent().find(".idConsulta").val(), btnMod: $(this).val()};
        $.post("?c=consultas&a=action", data, consultasProcessData);
    });

    $("#listConsultas").on("click", ".btnDel", function () {
        var eliminar = confirm("La consulta será eliminada, ¿seguro que desea continuar?");

        if (eliminar) {
            var data = {js: $(".js").val(), idConsulta: $(this).parent().find(".idConsulta").val(), btnDel: $(this).val()};
            $.post("?c=consultas&a=action", data, consultasProcessData);
        }
    });

    // Formulario de actualización de consultas ------------------------------------------------------------------------
    $("#page-consultas #divUpdate").on("submit", "#formUpdate", function () {
        return false;
    });

    $("#page-consultas #divUpdate").on("click", "#formUpdate input[type='submit']", function () {
        var data = $(this).parent().parent().parent().serialize() + "&btn=" + $(this).val();
        $.post("?c=consultas&a=update", data, consultasProcessData);
    });

    // Procesamiento de datos devueltos por el servidor ----------------------------------------------------------------
    function consultasProcessData(serverData) {
        processData(serverData);
    }
});
    