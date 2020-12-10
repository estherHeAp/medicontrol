<section id="page-calendarConfig" class="row">
    <!-- Listar (modificar + eliminar) -------------------------------------------------------------------------------->
    <div id="listCalendarConfig" class="col-12 table-responsive text-center">
        <?php
        if (isset($msg)) {
            if ($msg['list'] !== '') {
                // Listas o mensajes a mostrar tras realizar una acción
                echo $msg['list'];
            } else {
                echo listCalendars();
            }
        } else {
            echo listCalendars();
        }
        ?>
    </div>

    <!-- Modificar ---------------------------------------------------------------------------------------------------->
    <div id="divUpdate" class="col-12">
        <?php
        if (isset($msg)) {
            if ($msg['form'] !== '') {
                // Formulario con los datos del calendario seleccionado
                echo $msg['form'];
            }
        }
        ?>
    </div>
</section>