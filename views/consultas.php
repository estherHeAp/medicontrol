<section id="page-consultas" class="row">
    <!-- Añadir ------------------------------------------------------------------------------------------------------->
    <?php if ($usuario instanceof Empleado) { ?>
        <form action="?c=consultas&a=add" method="post" id="formAdd" class="col-12">
            <fieldset>
                <legend>Añadir consulta</legend>

                <?= $inputJs; ?>

                <div class="form-row align-items-end">
                    <div class="form-group col-12 col-sm-6 selectCitas">
                        <?= selectCitas($usuario); ?>
                    </div>

                    <div class="col-12 col-sm-6">
                        <input type="submit" name="btnAdd" id="btnAdd" class="btn btn-primary" value="Añadir">
                        <input type="submit" name="btnCancelAdd" id="btnCancelAdd" class="btn btn-secondary" value="Cancelar">
                    </div>
                </div>
            </fieldset>
        </form>
    <?php } ?>

    <!-- Buscar ------------------------------------------------------------------------------------------------------->
    <form action="?c=consultas&a=search" method="post" id="formSearch" class="col-12">
        <fieldset>
            <legend>Buscar consulta</legend>

            <?= $inputJs; ?>

            <div class="form-row align-items-end">
                <?php if ($usuario instanceof Empleado) { ?>
                    <div class="form-group col-12 col-sm-6 col-md-4 col-lg-3">
                        <label for="dniSearch">DNI:</label>
                        <input type="search" name="dniSearch" id="dniSearch" class="form-control">
                    </div>
                <?php } ?>

                <div class="form-group col-12 col-sm-6 col-md-4 col-lg-3">
                    <label for="asuntoSearch">Asunto:</label>
                    <input type="search" name="asuntoSearch" id="asuntoSearch" class="form-control">
                </div>

                <div class="col-12 col-sm-6 col-md-4 col-lg-3 mr-0 mb-4 form-check form-check-inline">
                    <input type="checkbox" name="pagoSearch" id="pagoSearch" class="form-check-input" value="1">
                    <label for="pagoSearch" class="form-check-label mr-5">Pagado</label>

                    <input type="checkbox" name="pagoSearch" id="noPagoSearch" class="form-check-input" value="0">
                    <label for="noPagoSearch" class="form-check-label">No pagado</label>
                </div>

                <div class="col-12 col-md-4 col-lg-3">
                    <input type="submit" name="btnSearch" id="btnSearch" class="btn btn-light" value="Buscar">
                </div>
            </div>
        </fieldset>
    </form>

    <!-- Listar (modificar + eliminar) -------------------------------------------------------------------------------->
    <div id="listConsultas" class="col-12 table-responsive text-center">
        <?php
        if (isset($msg)) {
            if ($msg['list'] !== '') {
                // Listas o mensajes a mostrar tras realizar una acción
                echo $msg['list'];
            } else {
                echo listConsultas($usuario, null);
            }
        } else {
            echo listConsultas($usuario, null);
        }
        ?>
    </div>

    <!-- Modificar ---------------------------------------------------------------------------------------------------->
    <div id="divUpdate" class="col-12">
        <?php
        if (isset($msg)) {
            if ($msg['form'] !== '') {
                // Formulario con los datos de la consulta seleccionada
                echo $msg['form'];
            }
        }
        ?>
    </div>
</section>