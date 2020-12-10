$(function () {
    // Formulario de adición de especialidades -------------------------------------------------------------------------
    $("#page-especialidades #formAdd").submit(function () {
        var data = $(this).serialize();
        $.post("?c=especialidades&a=add", data, especialidadesProcessData);

        return false;
    });

    // Listado y acciones posibles (eliminación) de especialidades -----------------------------------------------------
    $("#listEspecialidades").on("submit", "#formDelete", function () {
        var eliminar = confirm("Su cuenta será eliminada junto con sus datos personales, ¿seguro que desea continuar?");

        if (eliminar) {
            var data = $(this).serialize();
            $.post("?c=especialidades&a=delete", data, especialidadesProcessData);
        }

        return false;
    });

    // Procesamiento de datos devueltos por el servidor ----------------------------------------------------------------
    function especialidadesProcessData(serverData) {
        processData(serverData);
    }
});
    