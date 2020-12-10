$(function () {
    // Formulario de adición de citas ----------------------------------------------------------------------------------
    $("#page-citas #formAdd").submit(function () {
        return false;
    });

    $("#page-citas #formAdd input[type='submit'], #page-citas #formAdd input[type='reset']").click(function () {
        var data = {
            js: $(".js").val(),
            action: $(this).parent().parent().find(".action").val(),
            clienteSelect: $(".clienteSelect").val(),
            empleadoSelect: $(".empleadoSelect").val(),
            horarioAdd: $("#horarioAdd").val(),
            asuntoAdd: $("#asuntoAdd").val(),
            btn: $(this).val()
        };
        $.post("?c=citas&a=add", data, citasProcessData);
    });

    // Formulario de búsqueda de citas ---------------------------------------------------------------------------------
    $("#page-citas #formSearch").submit(function () {
        var data = $(this).serialize();
        $.post("?c=citas&a=search", data, citasProcessData);

        return false;
    });

    $("#page-citas #formSearch #dniSearch").on("input", function () {
        var data = $(this).parent().parent().parent().serialize();
        $.post("?c=citas&a=search", data, citasProcessData);
    });

    $("#page-citas #formSearch #asuntoSearch").on("input", function () {
        var data = $(this).parent().parent().parent().serialize();
        $.post("?c=citas&a=search", data, citasProcessData);
    });

    // Listado de todas las citas o solo de ls citas pendientes --------------------------------------------------------
    $("#page-citas #formList").submit(function () {
        return false;
    });

    $("#page-citas #formList #all").click(function () {
        $("#page-citas #formSearch #allSearch").val(1);
        $("#page-citas #formAction #allAction").val(1);

        var data = {
            js: $("#page-citas #formList .js").val(),
            all: $(this).val()
        };
        $.post("?c=citas&a=list", data, citasProcessData);
    });

    $("#page-citas #formList #pendientes").click(function () {
        $("#page-citas #formSearch #allSearch").val(0);
        $("#page-citas #formAction #allAction").val(0);

        var data = {
            js: $("#page-citas #formList .js").val(),
            pendientes: $(this).val()
        };
        $.post("?c=citas&a=list", data, citasProcessData);
    });

    // Listado y acciones posibles (modificación y eliminación) de citas -----------------------------------------------
    $("#listCitas").on("submit", "#formAction", function () {
        return false;
    });

    $("#listCitas").on("click", ".btnMod", function () {
        var data = {
            js: $(".js").val(),
            idCita: $(this).parent().find(".idCita").val(),
            all: $(this).parent().find("#all").val(),
            btnMod: $(this).val()
        };
        $.post("?c=citas&a=action", data, citasProcessData);
    });

    $("#listCitas").on("click", ".btnDel", function () {
        var eliminar = confirm("La cita será cancelada, ¿seguro que desea continuar?");

        if (eliminar) {
            var data = {
                js: $(".js").val(),
                idCita: $(this).parent().find(".idCita").val(),
                all: $(this).parent().find("#all").val(),
                btnDel: $(this).val()
            };
            $.post("?c=citas&a=action", data, citasProcessData);
        }
    });

    // Formulario de actualización de citas ----------------------------------------------------------------------------
    $("#page-citas #divUpdate").on("submit", "#formUpdate", function () {
        return false;
    });

    $("#page-citas #divUpdate").on("click", "#formUpdate input[type='submit']", function () {
        var data = {js: $(".js").val(),
            all: $(this).parent().parent().parent().find(".all").val(),
            action: $(this).parent().parent().parent().find(".action").val(),
            empleadoSelect: $(this).parent().parent().find(".empleadoSelect").val(),
            horarioUpdate: $(this).parent().parent().find("#horarioUpdate").val(),
            asuntoUpdate: $(this).parent().parent().find("#asuntoUpdate").val(),
            btn: $(this).val()
        };
        $.post("?c=citas&a=update", data, citasProcessData);
    });

    // Procesamiento de datos devueltos por el servidor ----------------------------------------------------------------
    function citasProcessData(serverData) {
        processData(serverData);
    }
});