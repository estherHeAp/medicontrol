<section id="page-main" class="row text-center">
    <h3 class="col-12 my-5">¡Bienvenido, <?= $nombre; ?>!</h3>

    <?php if ($usuario instanceof Cliente) { ?>
        <div class="col-12">
            <ul class="row pl-0">
                <li class="col-12 col-sm-6 py-3"><a href="?c=citas">Citas</a></li>
                <li class="col-12 col-sm-6 py-3"><a href="?c=consultas">Consultas</a></li>
            </ul>
        </div>
    <?php
    } else if ($usuario instanceof Empleado) {
        if ($usuario instanceof Admin) {
            ?>
            <div class="col-12">
                <ul class="row pl-0">
                    <li class="col-12 col-sm-6 col-md-3 py-3"><a href="?c=citas">Citas</a></li>
                    <li class="col-12 col-sm-6 col-md-3 py-3"><a href="?c=consultas">Consultas</a></li>
                    <li class="col-12 col-sm-6 col-md-3 py-3"><a href="?c=clientes">Clientes</a></li>
                    <li class="col-12 col-sm-6 col-md-3 py-3"><a href="?c=empleados">Empleados</a></li>
                    <li class="col-12 col-sm-6 col-md-3 py-3"><a href="?c=admin">Administradores</a></li>
                    <li class="col-12 col-sm-6 col-md-3 py-3"><a href="?c=especialidades">Especialidades</a></li>
                    <li class="col-12 col-sm-6 col-md-3 py-3"><a href="?c=calendarConfig">Calendario</a></li>
                    <li class="col-12 col-sm-6 col-md-3 py-3"><a href="?c=festivos">Festivos</a></li>
                </ul>
            </div>
    <?php } else { ?>
            <div class="col-12">
                <ul class="row pl-0">
                    <li class="col-12 col-sm-6 col-md-3 py-3"><a href="?c=citas">Citas</a></li>
                    <li class="col-12 col-sm-6 col-md-3 py-3"><a href="?c=consultas">Consultas</a></li>
                    <li class="col-12 col-sm-6 col-md-3 py-3"><a href="?c=clientes">Clientes</a></li>
                    <li class="col-12 col-sm-6 col-md-3 py-3"><a href="?c=admin">Administradores</a></li>
                </ul>
            </div>

        <?php }
    }
    ?>
</section>