<section id="page-especialidades" class="row">
    <!-- Añadir ------------------------------------------------------------------------------------------------------->
    <form action="?c=especialidades&a=add" method="post" id="formAdd" class="col-12">
        <fieldset>
            <legend>Añadir especialidad</legend>

            <?= $inputJs; ?>

            <div class="form-row align-items-end">
                <div class="form-group col-12 col-sm-6">
                    <label for="nombreAdd">Nombre:</label>
                    <input type="text" name="nombreAdd" id="nombreAdd" class="form-control" required>
                </div>

                <div class="col-12 col-sm-6">
                    <input type="submit" name="btnAdd" id="btnAdd" class="btn btn-primary" value="Añadir">
                    <input type="reset" name="btnCancelAdd" id="btnCancelAdd" class="btn btn-secondary" value="Cancelar">
                </div>
            </div>
        </fieldset>
    </form>

    <!-- Listar (modificar + eliminar + recuperar contraseña) --------------------------------------------------------->
    <div id="listEspecialidades" class="col-12 table-responsive text-center">
        <?php
        if (isset($msg)) {
            if ($msg['list'] !== '') {
                // Listas o mensajes a mostrar tras realizar una acción
                echo $msg['list'];
            } else {
                echo listEspecialidades($usuario);
            }
        } else {
            echo listEspecialidades($usuario);
        }
        ?>
    </div>
</section>