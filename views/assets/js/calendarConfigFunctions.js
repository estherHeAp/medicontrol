$(function () {
    // Listado y acciones posibles (actualización) de horarios laborales -----------------------------------------------
    $("#listCalendarConfig").on("submit", "#formAction", function () {
        return false;
    });

    $("#listCalendarConfig").on("click", ".btnMod", function () {
        var data = {js: $(".js").val(), dia: $(this).parent().find(".dia").val()};
        $.post("?c=calendarConfig&a=action", data, calendarConfigProcessData);
    });

    // Formulario de actualización de horarios laborales ---------------------------------------------------------------
    $("#page-calendarConfig #divUpdate").on("submit", "#formUpdate", function () {
        return false;
    });

    $("#page-calendarConfig #divUpdate").on("click", "#formUpdate input[type='submit']", function () {
        var data = $(this).parent().parent().parent().serialize() + "&btn=" + $(this).val();
        $.post("?c=calendarConfig&a=update", data, calendarConfigProcessData);
    });

    // Procesamiento de datos devueltos por el servidor ----------------------------------------------------------------
    function calendarConfigProcessData(serverData) {
        processData(serverData);
    }
});
    