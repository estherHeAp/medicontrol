$(function () {
    var msg = $("#msg");

    // Calendario del formulario de adición ----------------------------------------------------------------------------
    // Mes anterior
    $("#formAdd").on("click", ".mesAnterior", function () {
        var mes = $("#formAdd .mes").text();
        var numMes = getMes(mes);

        var data = {js: 1, action: $("#formAdd .action").val(), mes: numMes, anio: $("#formAdd .anio").text()};
        $.get("?c=calendar&a=mesAnterior", data, calendarProcessData);
    });

    $("#formAdd").on("keydown", ".mesAnterior", function (e) {
        if (e.keyCode == "13") {
            var mes = $("#formAdd .mes").text();
            var numMes = getMes(mes);

            var data = {js: 1, action: $("#formAdd .action").val(), mes: numMes, anio: $("#formAdd .anio").text()};
            $.get("?c=calendar&a=mesAnterior", data, calendarProcessData);
        }
    });

    // Mes posterior
    $("#formAdd").on("click", ".mesPosterior", function () {
        var mes = $("#formAdd .mes").text();
        var numMes = getMes(mes);

        var data = {js: 1, action: $("#formAdd .action").val(), mes: numMes, anio: $("#formAdd .anio").text()};
        $.get("?c=calendar&a=mesPosterior", data, calendarProcessData);
    });

    $("#formAdd").on("keydown", ".mesPosterior", function (e) {
        if (e.keyCode == "13") {
            var mes = $("#formAdd .mes").text();
            var numMes = getMes(mes);

            var data = {js: 1, action: $("#formAdd .action").val(), mes: numMes, anio: $("#formAdd .anio").text()};
            $.get("?c=calendar&a=mesPosterior", data, calendarProcessData);
        }
    });

    // Año anterior
    $("#formAdd").on("click", ".anioAnterior", function () {
        var mes = $("#formAdd .mes").text();
        var numMes = getMes(mes);

        var data = {js: 1, action: $("#formAdd .action").val(), mes: numMes, anio: $("#formAdd .anio").text()};
        $.get("?c=calendar&a=anioAnterior", data, calendarProcessData);
    });

    $("#formAdd").on("keydown", ".anioAnterior", function (e) {
        if (e.keyCode == "13") {
            var mes = $("#formAdd .mes").text();
            var numMes = getMes(mes);

            var data = {js: 1, action: $("#formAdd .action").val(), mes: numMes, anio: $("#formAdd .anio").text()};
            $.get("?c=calendar&a=anioAnterior", data, calendarProcessData);
        }
    });

    // Año posterior
    $("#formAdd").on("click", ".anioPosterior", function () {
        var mes = $("#formAdd .mes").text();
        var numMes = getMes(mes);

        var data = {js: 1, action: $("#formAdd .action").val(), mes: numMes, anio: $("#formAdd .anio").text()};
        $.get("?c=calendar&a=anioPosterior", data, calendarProcessData);
    });

    $("#formAdd").on("keydown", ".anioPosterior", function (e) {
        if (e.keyCode == "13") {
            var mes = $("#formAdd .mes").text();
            var numMes = getMes(mes);

            var data = {js: 1, action: $("#formAdd .action").val(), mes: numMes, anio: $("#formAdd .anio").text()};
            $.get("?c=calendar&a=anioPosterior", data, calendarProcessData);
        }
    });

    // Días no deshabilitados
    $("#formAdd").on("click", ".dia:not(.disabled)", function () {
        var mes = $("#formAdd .mes").text();
        var numMes = getMes(mes);

        var data = {
            js: 1,
            action: "add",
            dia: $(this).find("a").text(),
            mes: numMes,
            anio: $("#formAdd .anio").text()
        };
        $.get("?c=calendar&a=getHorario", data, calendarProcessData);
    });

    $("#formAdd").on("keydown", ".dia:not(.disabled)", function (e) {
        if (e.keyCode == "13") {
            var mes = $("#formAdd .mes").text();
            var numMes = getMes(mes);

            var data = {
                js: 1,
                action: "add",
                dia: $(this).find("a").text(),
                mes: numMes,
                anio: $("#formAdd .anio").text()
            };
            $.get("?c=calendar&a=getHorario", data, calendarProcessData);
        }
    });

    // Calendario de formulario de modificación ------------------------------------------------------------------------
    // Mes anterior
    $("#divUpdate").on("click", ".mesAnterior", function () {
        var mes = $("#divUpdate .mes").text();
        var numMes = getMes(mes);

        var data = {js: 1, action: $("#formUpdate .action").val(), mes: numMes, anio: $("#divUpdate .anio").text()};
        $.get("?c=calendar&a=mesAnterior", data, calendarProcessData);
    });

    $("#divUpdate").on("keydown", ".mesAnterior", function (e) {
        if (e.keyCode == "13") {
            var mes = $("#divUpdate .mes").text();
            var numMes = getMes(mes);

            var data = {js: 1, action: $("#formUpdate .action").val(), mes: numMes, anio: $("#divUpdate .anio").text()};
            $.get("?c=calendar&a=mesAnterior", data, calendarProcessData);
        }
    });

    // Mes posterior
    $("#divUpdate").on("click", ".mesPosterior", function () {
        var mes = $("#divUpdate .mes").text();
        var numMes = getMes(mes);

        var data = {js: 1, action: $("#formUpdate .action").val(), mes: numMes, anio: $("#divUpdate .anio").text()};
        $.get("?c=calendar&a=mesPosterior", data, calendarProcessData);
    });

    $("#divUpdate").on("keydown", ".mesPosterior", function (e) {
        if (e.keyCode == "13") {
            var mes = $("#divUpdate .mes").text();
            var numMes = getMes(mes);

            var data = {js: 1, action: $("#formUpdate .action").val(), mes: numMes, anio: $("#divUpdate .anio").text()};
            $.get("?c=calendar&a=mesPosterior", data, calendarProcessData);
        }
    });

    // Año anterior
    $("#divUpdate").on("click", ".anioAnterior", function () {
        var mes = $("#divUpdate .mes").text();
        var numMes = getMes(mes);

        var data = {js: 1, action: $("#formUpdate .action").val(), mes: numMes, anio: $("#divUpdate .anio").text()};
        $.get("?c=calendar&a=anioAnterior", data, calendarProcessData);
    });

    $("#divUpdate").on("keydown", ".anioAnterior", function (e) {
        if (e.keyCode == "13") {
            var mes = $("#divUpdate .mes").text();
            var numMes = getMes(mes);

            var data = {js: 1, action: $("#formUpdate .action").val(), mes: numMes, anio: $("#divUpdate .anio").text()};
            $.get("?c=calendar&a=anioAnterior", data, calendarProcessData);
        }
    });

    // Año posterior
    $("#divUpdate").on("click", ".anioPosterior", function () {
        var mes = $("#divUpdate .mes").text();
        var numMes = getMes(mes);

        var data = {js: 1, action: $("#formUpdate .action").val(), mes: numMes, anio: $("#divUpdate .anio").text()};
        $.get("?c=calendar&a=anioPosterior", data, calendarProcessData);
    });

    $("#divUpdate").on("keydown", ".anioPosterior", function (e) {
        if (e.keyCode == "13") {
            var mes = $("#divUpdate .mes").text();
            var numMes = getMes(mes);

            var data = {js: 1, action: $("#formUpdate .action").val(), mes: numMes, anio: $("#divUpdate .anio").text()};
            $.get("?c=calendar&a=anioPosterior", data, calendarProcessData);
        }
    });

    // Días no deshabilitados
    $("#divUpdate").on("click", "td.dia:not(.disabled)", function () {
        var mes = $("#divUpdate .mes").text();
        var numMes = getMes(mes);

        var data = {
            js: 1,
            action: "update",
            dia: $(this).find("a").text(),
            mes: numMes,
            anio: $("#divUpdate .anio").text()
        };
        $.get("?c=calendar&a=getHorario", data, calendarProcessData);
    });

    $("#divUpdate").on("keydown", "td.dia:not(.disabled)", function (e) {
        if (e.keyCode == "13") {
            var mes = $("#divUpdate .mes").text();
            var numMes = getMes(mes);

            var data = {
                js: 1,
                action: "update",
                dia: $(this).find("a").text(),
                mes: numMes,
                anio: $("#divUpdate .anio").text()
            };
            $.get("?c=calendar&a=getHorario", data, calendarProcessData);
        }
    });

    // Obtención del número del mes pasado como parámetro --------------------------------------------------------------
    function getMes(mes) {
        var meses = ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"];
        var pos = meses.indexOf(mes);
        return pos + 1;
    }

    // Procesamiento de datos devueltos por el servidor ----------------------------------------------------------------
    function calendarProcessData(serverData) {
        // Mensaje de error
        if (serverData.msg.error !== "") {
            msg.show();
            msg.html("<p class='msg-error'>" + serverData.msg.error + "</p>");
        }

        // FormAdd
        if (serverData.calendar.add !== "") {
            $("#formAdd .calendario").html(serverData.calendar.add);
            $(".calendar a").removeAttr("href");
        }

        if (serverData.horario.add !== "") {
            $("#formAdd #horarioAdd").html(serverData.horario.add);
        }

        // FormUpdate
        if (serverData.calendar.update !== "") {
            $("#divUpdate .calendario").html(serverData.calendar.update);
            $(".calendar a").removeAttr("href");
        }

        if (serverData.horario.update !== "") {
            $("#divUpdate #horarioUpdate").html(serverData.horario.update);
        }
    }
});