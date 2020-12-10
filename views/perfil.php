<section id="page-perfil" class="row">
    <div class="col-12">
        <p class="msg-info">Los campos marcados con  *  son obligatorios.</p>
    </div>

    <!-- Datos personales --------------------------------------------------------------------------------------------->
    <div id="divUpdate" class="col-12">
        <?= formUpdatePerfil($usuario); ?>
    </div>

    <!-- Cambiar contraseña ------------------------------------------------------------------------------------------->
    <form action="?c=perfil&a=change" method="post" id="formChange" class="col-12">
        <fieldset>
            <legend>Cambiar contraseña</legend>

            <?= $inputJs; ?>

            <div class="form-row align-items-end">
                <div class="form-group col-12 col-md-4 col-lg-3">
                    <label for="actualChange">Contraseña actual * :</label>
                    <input type="password" name="actualChange" id="actualChange" class="form-control" required>
                </div>

                <div class="form-group col-12 col-sm-6 col-md-4 col-lg-3">
                    <label for="passChange">Nueva contraseña * :</label>
                    <input type="password" name="passChange" id="passChange" class="form-control" required>
                </div>

                <div class="form-group col-12 col-sm-6 col-md-4 col-lg-3">
                    <label for="repassChange">Repita nueva contraseña * :</label>
                    <input type="password" name="repassChange" id="repassChange" class="form-control" required>
                </div>
                <div class="col-12 col-lg-3">
                    <input type="submit" name="btnChange" id="btnChange" class="btn btn-primary" value="Cambiar">
                </div>
            </div>
        </fieldset>
    </form>

    <!-- Eliminar cuenta ---------------------------------------------------------------------------------------------->
    <?php if ($usuario instanceof Cliente) { ?>
        <form action="?c=perfil&a=delete" method="post" id="formDelete" class="col-12">
            <fieldset>
                <legend>Eliminar cuenta</legend>

                <?= $inputJs; ?>

                <div class="form-row align-items-end">
                    <div class="form-group col-12 col-sm-6">
                        <label for="passDelete">Contraseña * :</label>
                        <input type="password" name="passDelete" id="passDelete" class="form-control" required>
                    </div>

                    <div class="col-12 col-sm-6">
                        <input type="submit" name="btnDelete" id="btnDelete" class="btn btn-danger" value="Eliminar">
                    </div>
                </div>
            </fieldset>
        </form>
    <?php } ?>
</section>