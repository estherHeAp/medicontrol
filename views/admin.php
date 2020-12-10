<section id="page-admin" class="row">
    <!-- Añadir ------------------------------------------------------------------------------------------------------->
    <?php if ($usuario instanceof Admin) { ?>
        <form action="?c=admin&a=add" method="post" id="formAdd" class="col-12">
            <fieldset>
                <legend>Añadir administrador</legend>

                <?= $inputJs; ?>

                <div class="form-row align-items-end">
                    <div class="form-group col-12 col-sm-6 selectEmpleados">
                        <?= selectEmpleados($usuario, null); ?>
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
    <form action="?c=admin&a=search" method="post" id="formSearch" class="col-12">
        <fieldset>
            <legend>Buscar administrador</legend>

            <?= $inputJs; ?>

            <div class="form-row align-items-end">
                <div class="form-group col-12 col-sm-6 col-md-4">
                    <label for="nombreSearch">Nombre:</label>
                    <input type="search" name="nombreSearch" id="nombreSearch" class="form-control">
                </div>

                <div class="form-group col-12 col-sm-6 col-md-4">
                    <label for="especialidadSearch">Especialidad:</label>
                    <input type="search" name="especialidadSearch" id="especialidadSearch" class="form-control">
                </div>

                <div class="col-12 col-md-4">
                    <input type="submit" name="btnSearch" id="btnSearch" class="btn btn-light" value="Buscar">
                </div>
            </div>
        </fieldset>
    </form>

    <!-- Listar (modificar + eliminar + recuperar contraseña) --------------------------------------------------------->
    <div id="listAdmin" class="col-12 table-responsive text-center">
        <?php
        if (isset($msg)) {
            if ($msg['list'] !== '') {
                // Listas o mensajes a mostrar tras realizar una acción
                echo $msg['list'];
            } else {
                echo listAdmin($usuario, null);
            }
        } else {
            echo listAdmin($usuario, null);
        }
        ?>
    </div>

    <!-- Modificar ---------------------------------------------------------------------------------------------------->
    <div id="divUpdate" class="col-12">
        <?php
        if (isset($msg)) {
            if ($msg['form'] !== '') {
                // Formulario con los datos del empleado seleccionado
                echo $msg['form'];
            }
        }
        ?>
    </div>
</section>