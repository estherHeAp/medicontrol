$(function () {
    // Formulario de adición de empleados ------------------------------------------------------------------------------
    $("#page-empleados #formAdd").submit(function () {
        var data = $(this).serialize();
        $.post("?c=empleados&a=add", data, empleadosProcessData);

        return false;
    });

    // Formulario de búsqueda de empleados -----------------------------------------------------------------------------
    $("#page-empleados #formSearch").on("input", function () {
        var data = $(this).serialize();
        $.post("?c=empleados&a=search", data, empleadosProcessData);

        return false;
    });

    // Listado y acciones posibles (modificación, eliminación y recuperación de contraseña) de empleados ---------------
    $("#listEmpleados").on("submit", "#formAction", function () {
        return false;
    });

    $("#listEmpleados").on("click", ".btnMod", function () {
        var data = {js: $(".js").val(), dniEmpleado: $(this).parent().find(".dniEmpleado").val(), btnMod: $(this).val()};
        $.post("?c=empleados&a=action", data, empleadosProcessData);
    });

    $("#listEmpleados").on("click", ".btnDel", function () {
        var eliminar = confirm("La cuenta del empleado será desactivada (se conservarán sus datos para futuras referencias), ¿seguro que desea continuar?");

        if (eliminar) {
            var data = {js: $(".js").val(), dniEmpleado: $(this).parent().find(".dniEmpleado").val(), btnDel: $(this).val()};
            $.post("?c=empleados&a=action", data, empleadosProcessData);
        }
    });

    $("#listEmpleados").on("click", ".btnRes", function () {
        var data = {js: $(".js").val(), dniEmpleado: $(this).parent().find(".dniEmpleado").val(), btnRes: $(this).val()};
        $.post("?c=empleados&a=action", data, empleadosProcessData);
    });

    // Formulario de actualización de empleados ------------------------------------------------------------------------
    $("#page-empleados #divUpdate").on("submit", "#formUpdate", function () {
        return false;
    });

    $("#page-empleados #divUpdate").on("click", "#formUpdate input[type='submit']", function () {
        var data = $(this).parent().parent().parent().serialize() + "&btn=" + $(this).val();
        $.post("?c=empleados&a=update", data, empleadosProcessData);
    });

    // Procesamiento de datos devueltos por el servidor ----------------------------------------------------------------
    function empleadosProcessData(serverData) {
        processData(serverData);
    }
});
    