<section id="page-citas" class="row">
    <!-- Añadir ------------------------------------------------------------------------------------------------------->
    <form action="?c=citas&a=add" method="post" id="formAdd" class="col-12">
        <fieldset>
            <legend>Añadir cita</legend>

            <?= $inputJs; ?>
            <input type="hidden" name="action" class="action" value="add">

            <div class="form-row justify-content-center align-items-start">
                <?php if ($usuario instanceof Empleado) { ?>
                    <div class="form-group col-12 col-sm-6 selectClientes">    
                        <?= selectClientes($usuario, null); ?>
                    </div>

                    <div class="form-group col-12 col-sm-6 selectEmpleados">    
                        <?= selectEmpleados($usuario, null); ?>
                    </div>
                <?php } ?>

                <div class="col-12 col-sm-6 text-center calendario">
                    <?php
                    if (isset($msg)) {
                        if ($msg['calendar']['add'] !== '') {
                            echo $msg['calendar']['add'];
                        } else {
                            echo Calendario::createCalendar(0, 'add', date('n'), date('Y'), null);
                        }
                    } else {
                        echo Calendario::createCalendar(0, 'add', date('n'), date('Y'), null);
                    }
                    ?>
                </div>

                <div class="col-12 col-sm-6">
                    <div class="row">
                        <div class="form-group col-12 p-0 horario">
                            <label for="horarioAdd">Seleccionar hora:</label>
                            <select name="horarioAdd" id="horarioAdd" class="form-control">
                                <?php
                                if (isset($msg)) {
                                    if ($msg['horario']['add'] !== '') {
                                        echo $msg['horario']['add'];
                                    }
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group col-12 p-0">
                            <label for="asuntoAdd">Asunto:</label>
                            <input type="text" name="asuntoAdd" id="asuntoAdd" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <input type="submit" name="btnAdd" id="btnAdd" class="btn btn-primary" value="Añadir">
                    <input type="reset" name="btnCancelAdd" id="btnCancelAdd" class="btn btn-secondary" value="Cancelar">
                </div>
            </div>
        </fieldset>
    </form>

    <!--Buscar ------------------------------------------------------------------------------------------------------->
    <?php if ($usuario instanceof Empleado) { ?>
        <form action="?c=citas&a=search" method="post" id="formSearch" class="col-12">
            <fieldset>
                <legend>Buscar cita</legend>

                <?= $inputJs; ?>
                <input type="hidden" name="allSearch" id="allSearch" value="<?php
                if (isset($msg)) {
                    if ($msg['otros']['all'] !== '') {
                        echo $msg['otros']['all'];
                    } else {
                        echo '0';
                    }
                } else {
                    echo '0';
                }
                ?>">

                <div class="form-row align-items-end">
                    <div class="form-group col-12 col-sm-6 col-md-4">
                        <label for="dniSearch">DNI:</label>
                        <input type="search" name="dniSearch" id="dniSearch" class="form-control">
                    </div>

                    <div class="form-group col-12 col-sm-6 col-md-4">
                        <label for="asuntoSearch">Asunto:</label>
                        <input type="search" name="asuntoSearch" id="asuntoSearch" class="form-control">
                    </div>

                    <div class="col-12 col-md-4">
                        <input type="submit" name="btnSearch" id="btnSearch" class="btn btn-light" value="Buscar">
                    </div>
                </div>
            </fieldset>
        </form>
    <?php } ?>

    <!--Listar (modificar + eliminar) -------------------------------------------------------------------------------->
    <?php if ($usuario instanceof Empleado) { ?>
        <form action="?c=citas&a=list" method="post" id="formList" class="col-12">
            <?= $inputJs; ?>

            <div class="form-row">
                <div class="col-12">
                    <input type="submit" name="all" id="all" class="btn btn-light" value="Ver todas las citas">
                    <input type="submit" name="pendientes" id="pendientes" class="btn btn-light" value="Ver citas pendientes">
                </div>
            </div>
        </form>
    <?php } ?>

    <div id="listCitas" class="col-12 table-responsive text-center">
        <?php
        if (isset($msg)) {
            if ($msg['list'] !== '') {
                // Listas o mensajes a mostrar tras realizar una acción
                echo $msg['list'];
            } else {
                echo listCitas($usuario, null, 0);
            }
        } else {
            echo listCitas($usuario, null, 0);
        }
        ?>
    </div>

    <!--Modificar ---------------------------------------------------------------------------------------------------->
    <div class="col-12" id="divUpdate">
        <?php
        if (isset($msg)) {
            if ($msg['form'] !== '') {
                // Formulario con los datos de la cita seleccionada
                echo $msg['form'];
            }
        }
        ?>
    </div>
</section>