<section id="page-clientes" class="row">
    <div class="col-12">
        <p class="msg-info">Los campos marcados con  *  son obligatorios.</p>
    </div>

    <!-- Añadir ------------------------------------------------------------------------------------------------------->
    <form action="?c=clientes&a=add" method="post" id="formAdd" class="col-12">
        <fieldset>
            <legend>Añadir cliente</legend>

            <?= $inputJs; ?>

            <div class="form-row">
                <div class="form-group col-12 col-md-4">
                    <label for="dniAdd">DNI * :</label>
                    <input type="text" name="dniAdd" id="dniAdd" class="form-control" pattern="^[0-9]{8}[A-Z]$" required>
                </div>

                <div class="form-group col-12 col-sm-6 col-md-4">
                    <label for="nombreAdd">Nombre * :</label>
                    <input type="text" name="nombreAdd" id="nombreAdd" class="form-control" required>
                </div>

                <div class="form-group col-12 col-sm-6 col-md-4">
                    <label for="apellido1Add">Primer apellido * :</label>
                    <input type="text" name="apellido1Add" id="apellido1Add" class="form-control" required>
                </div>

                <div class="form-group col-12 col-sm-6 col-md-4">
                    <label for="apellido2Add">Segundo apellido:</label>
                    <input type="text" name="apellido2Add" id="apellido2Add" class="form-control">
                </div>

                <div class="form-group col-12 col-sm-6 col-md-4">
                    <label for="sexoAdd">Sexo:</label>
                    <select name="sexoAdd" id="sexoAdd" class="form-control">
                        <option value=""></option>
                        <option value="F">Mujer</option>
                        <option value="M">Hombre</option>
                    </select>
                </div>

                <div class="form-group col-12 col-sm-6 col-md-4">
                    <label for="fechaNacimientoAdd">Fecha de nacimiento:</label>
                    <input type="date" name="fechaNacimientoAdd" id="fechaNacimientoAdd" class="form-control" max="<?= date('Y-m-d'); ?>">
                </div>

                <div class="form-group col-12 col-sm-6 col-md-4">
                    <label for="emailAdd">Correo electrónico * :</label>
                    <input type="email" name="emailAdd" id="emailAdd" class="form-control" required>
                </div>

                <div class="form-group col-12 col-sm-6 col-md-4">
                    <label for="telfAdd">Teléfono:</label>
                    <input type="tel" name="telfAdd" id="telfAdd" class="form-control">
                </div>

                <div class="form-group col-12 col-sm-6 col-md-4">
                    <label for="aseguradoraAdd">Aseguradora:</label>
                    <input type="text" name="aseguradoraAdd" id="aseguradoraAdd" class="form-control">
                </div>

                <div class="col-12">
                    <input type="submit" name="btnAdd" id="btnAdd" class="btn btn-primary" value="Crear cuenta">
                    <input type="reset" name="btnCancelAdd" id="btnCancelAdd" class="btn btn-secondary" value="Cancelar">
                </div>
            </div>
        </fieldset>
    </form>

    <!-- Buscar ------------------------------------------------------------------------------------------------------->
    <form action="?c=clientes&a=search" method="post" id="formSearch" class="col-12">
        <fieldset>
            <legend>Buscar cliente</legend>

            <?= $inputJs; ?>

            <div class="form-row align-items-end">
                <div class="form-group col-12 col-sm-6">
                    <label for="dniSearch">DNI:</label>
                    <input type="search" name="dniSearch" id="dniSearch" class="form-control">
                </div>

                <div class="col-12 col-sm-6">
                    <input type="submit" name="btnSearch" id="btnSearch" class="btn btn-light" value="Buscar">
                </div>
            </div>
        </fieldset>
    </form>

    <!-- Listar (modificar + eliminar + recuperar contraseña) --------------------------------------------------------->
    <div id="listClientes" class="col-12 table-responsive text-center">
        <?php
        if (isset($msg)) {
            if ($msg['list'] !== '') {
                // Listas o mensajes a mostrar tras realizar una acción
                echo $msg['list'];
            } else {
                echo listClientes($usuario, null);
            }
        } else {
            echo listClientes($usuario, null);
        }
        ?>
    </div>

    <!-- Modificar ---------------------------------------------------------------------------------------------------->
    <div id="divUpdate" class="col-12">
        <?php
        if (isset($msg)) {
            if ($msg['form'] !== '') {
                // Formulario con los datos del cliente seleccionado
                echo $msg['form'];
            }
        }
        ?>
    </div>
</section>