$(function () {
    // Marcamos JS como activado
    $(".js").val(1);

    // Cuadro de mensajes
    var msg = $("#msg");
    msg.hide();

    // Enlaces del calendario de citas
    $(".calendar a").removeAttr("href");
    
    // -----------------------------------------------------------------------------------------------------------------

    $("section").on("blur", "input[type='text']", function () {
        $(this).val($(this).val().toUpperCase());
    });

    $("section").on("blur", "input[type='email']", function () {
        $(this).val($(this).val().toLowerCase());
    });

});

// Procesamiento de datos devueltos por el servidor --------------------------------------------------------------------
function processData(serverData) {
    var msg = $("#msg");
    var urlParams = window.location.search.toString();

    msg.show();

    // Mensajes
    if (serverData.msg.error !== "") {
        msg.html("<p class='col-12 msg-error'>" + serverData.msg.error + "</p>");
    } else if (serverData.msg.conf !== "") {
        msg.html("<p class='col-12 msg-conf'>" + serverData.msg.conf + "</p>");
    } else {
        if (urlParams.indexOf("?c=login") !== -1
                || urlParams.indexOf("?c=perfil") !== -1)
            window.location.reload();
    }

    // Formulario cambio de contraseña
    if (serverData.otros.action === "change") {
        $("#formChange").trigger("reset");
    }

    // Formulario de adición
    if (serverData.otros.action === "add") {
        // citas
        $("#page-citas #formAdd .selectClientes").html(serverData.select.clientes);
        $("#page-citas #formAdd .selectEmpleados").html(serverData.select.empleados);

        $("#page-citas #formAdd .calendario").html(serverData.calendar.add);
        $(".calendar a").removeAttr("href");

        $("#page-citas #horarioAdd").html("");

        // clientes, empleados
        $("#formAdd").trigger("reset");
    }

    // Select de citas
    if (serverData.select.citas !== "") {
        $("#page-consultas .selectCitas").html(serverData.select.citas);
    }

    // Select de empleados
    if (serverData.select.empleados !== "") {
        $("#page-admin #formAdd .selectEmpleados").html(serverData.select.empleados);
    }

    // Listados
    if (serverData.list !== "") {
        $("#listCitas, #listConsultas, #listClientes, #listEmpleados, #listAdmin, #listEspecialidades, #listCalendarConfig, #listFestivos").html(serverData.list);
    }

    // Formulario de modificación
    if (serverData.form !== "") {
        $("#divUpdate").html(serverData.form);

        // Volvemos a deshabilitar los enlaces
        $(".calendar a").removeAttr("href");
    } else {
        if (urlParams.indexOf("?c=perfil"))
            $("#divUpdate").html("");
    }

    // Volvemos a actualizar el valor de js
    $(".js").val(1);
}
