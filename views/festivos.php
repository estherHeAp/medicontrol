<section id="page-festivos" class="row">
    <!-- Añadir día festivo ------------------------------------------------------------------------------------------->
    <form action="?c=festivos&a=add" method="post" id="formAdd" class="col-12">
        <fieldset>
            <legend>Añadir día festivo</legend>

            <?= $inputJs; ?>

            <div class="form-row">
                <div class="form-group col-12">
                    <label for="fechaAdd">Fecha:</label>
                    <input type="date" name="fechaAdd" id="fechaAdd" class="form-control" required>
                </div>

                <div class="form-group col-12 col-sm-6 col-md-4">
                    <label for="manana1Add">Apertura (mañanas):</label>
                    <input type="time" name="manana1Add" id="manana1Add" class="form-control" value="00:00" required>
                </div>

                <div class="form-group col-12 col-sm-6 col-md-4">
                    <label for="manana2Add">Cierre (mañanas):</label>
                    <input type="time" name="manana2Add" id="manana2Add" class="form-control" value="00:00" required>
                </div>

                <div class="form-group col-12 col-sm-6 col-md-4">
                    <label for="tarde1Add">Apertura (tardes):</label>
                    <input type="time" name="tarde1Add" id="tarde1Add" class="form-control" value="00:00" required>
                </div>

                <div class="form-group col-12 col-sm-6 col-md-4">
                    <label for="tarde2Add">Cierre (tardes):</label>
                    <input type="time" name="tarde2Add" id="tarde2Add" class="form-control" value="00:00" required>
                </div>

                <div class="form-group col-12 col-sm-6 col-md-4">
                    <label for="duracionAdd">Duración máxima:</label>
                    <input type="number" name="duracionAdd" id="duracionAdd" class="form-control" value="0" required>
                </div>

                <div class="form-group col-12 col-sm-6 col-md-4">
                    <label for="maxAdd">Número máx. clientes:</label>
                    <input type="number" name="maxAdd" id="maxAdd" class="form-control" value="0" required>
                </div>

                <div class="col-12">
                    <input type="submit" name="btnAdd" id="btnAdd" class="btn btn-primary" value="Añadir festivo">
                    <input type="reset" name="btnCancelAdd" id="btnCancelAdd" class="btn btn-secondary" value="Cancelar">
                </div>
            </div>
        </fieldset>
    </form>

    <!-- Buscar ------------------------------------------------------------------------------------------------------->
    <form action="?c=festivos&a=search" method="post" id="formSearch" class="col-12">
        <fieldset>
            <legend>Buscar día festivo</legend>

            <?= $inputJs; ?>

            <div class="form-row align-items-end">
                <div class="form-group col-12 col-sm-6 col-md-4 col-lg-3">
                    <label for="fecha1Search">Fecha inicial:</label>
                    <input type="date" name="fecha1Search" id="fecha1Search" class="form-control">
                </div>

                <div class="form-group col-12 col-sm-6 col-md-4 col-lg-3">
                    <label for="fecha2Search">Fecha final:</label>
                    <input type="date" name="fecha2Search" id="fecha2Search" class="form-control">
                </div>

                <div class="col-12 col-md-4 col-lg-3 mr-0 mb-4 form-check form-check-inline">
                    <!-- Enviaremos la comparación a realizar con la DB
                         #openSearch = '<>' // #closeSearch = '=' // null o '' = like -->
                    <input type="checkbox" name="openSearch" id="openSearch" class="form-check-input" value="<>">
                    <label for="openSearch" class="form-check-label mr-5">Abierto</label>

                    <input type="checkbox" name="openSearch" id="closeSearch" class="form-check-input" value="=">
                    <label for="closeSearch" class="form-check-label">Cerrado</label>
                </div>

                <div class="col-12 col-lg-3">
                    <input type="submit" name="btnSearch" id="btnSearch" class="btn btn-light" value="Buscar">
                </div>
            </div>
        </fieldset>
    </form>

    <!-- Listar (modificar + eliminar) -------------------------------------------------------------------------------->
    <div id="listFestivos" class="col-12 table-responsive text-center">
        <?php
        if (isset($msg)) {
            if ($msg['list'] !== '') {
                // Listas o mensajes a mostrar tras realizar una acción
                echo $msg['list'];
            } else {
                echo listFestivos(null);
            }
        } else {
            echo listFestivos(null);
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