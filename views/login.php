<section id="page-login" class="row">
    <div class="col-12">
        <p class="msg-info">Todos los campos son obligatorios.</p>
    </div>

    <div class="col-12">
        <div class="row">
            <!-- Acceder -------------------------------------------------------------------------------------->
            <form action="?c=login&a=login" method="post" id="formLogin" class="col-12 col-lg-6 px-0 pr-lg-2">
                <fieldset>
                    <legend>Acceder</legend>

                    <?= $inputJs; ?>

                    <div class="form-row align-items-end">
                        <div class="form-group col-12 col-sm-6 col-md-4 col-lg-6">
                            <label for="dniLogin">Usuario:</label>
                            <input type="text" name="dniLogin" id="dniLogin" class="form-control" pattern="^(([0-9]{8}[A-Z])||(admin)||(ADMIN))$" autofocus required>
                        </div>

                        <div class="form-group col-12 col-sm-6 col-md-4 col-lg-6">
                            <label for="passLogin">Contraseña:</label>
                            <input type="password" name="passLogin" id="passLogin" class="form-control" required>
                        </div>

                        <div class="col-12 col-md-4 col-lg-12">
                            <input type="submit" name="btnLogin" id="btnLogin" class="btn btn-primary" value="Entrar">
                        </div>
                    </div>
                </fieldset>
            </form>

            <!-- Recuperar contraseña ------------------------------------------------------------------------->
            <form action="?c=login&a=reset" method="post" id="formReset" class="col-12 col-lg-6 px-0 pl-lg-2">
                <fieldset>
                    <legend>¿Ha olvidado su contraseña?</legend>

                    <?= $inputJs; ?>

                    <div class="form-row align-items-end">
                        <div class="form-group col-12 col-md-6 col-lg-12">
                            <label for="emailReset">Introduzca su dirección de correo electrónico:</label>
                            <input type="email" name="emailReset" id="emailReset" class="form-control" required>
                        </div>

                        <div class="col-12 col-md-6 col-lg-12">
                            <input type="submit" name="btnReset" id="btnReset" class="btn btn-primary" value="Recuperar contraseña">
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>

    <!-- Añadir (registrarse) --------------------------------------------------------------------------------->
    <form action="?c=login&a=add" method="post" id="formAdd" class="col-12">
        <fieldset>
            <legend>Registrarse</legend>

            <?= $inputJs; ?>

            <div class="form-row">
                <div class="form-group col-12 col-md-6 col-lg-4">
                    <label for="dniAdd">DNI:</label>
                    <input type="text" name="dniAdd" id="dniAdd" class="form-control" pattern="^[0-9]{8}[A-Z]$" required>
                </div>

                <div class="form-group col-12 col-sm-6 col-lg-4">
                    <label for="nombreAdd">Nombre:</label>
                    <input type="text" name="nombreAdd" id="nombreAdd" class="form-control" required>
                </div>

                <div class="form-group col-12 col-sm-6 col-lg-4">
                    <label for="apellido1Add">Primer apellido:</label>
                    <input type="text" name="apellido1Add" id="apellido1Add" class="form-control" required>
                </div>

                <div class="form-group col-12 col-md-6 col-lg-4">
                    <label for="emailAdd">Correo electrónico:</label>
                    <input type="email" name="emailAdd" id="emailAdd" class="form-control" required>
                </div>

                <div class="form-group col-12 col-sm-6 col-lg-4">
                    <label for="passAdd">Contraseña:</label>
                    <input type="password" name="passAdd" id="passAdd" class="form-control" required>
                </div>

                <div class="form-group col-12 col-sm-6 col-lg-4">
                    <label for="repassAdd">Repetir contraseña:</label>
                    <input type="password" name="repassAdd" id="repassAdd" class="form-control" required>
                </div>

                <div class="col-12">
                    <input type="submit" name="btnAdd" id="btnAdd" class="btn btn-primary" value="Crear cuenta">
                </div>
            </div>
        </fieldset>
    </form>
</section>