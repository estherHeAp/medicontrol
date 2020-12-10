    <nav id="nav" class="navbar navbar-expand-md">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"><i class="fas fa-bars"></i></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="?c=main"><i class="fas fa-home"></i> Inicio</a></li>
                <li class="nav-item"><a class="nav-link" href="?c=citas">Citas</a></li>
                <li class="nav-item"><a class="nav-link" href="?c=consultas">Consultas</a></li>
                <?php if ($usuario instanceof Empleado) { ?>
                    <li class="nav-item dropdown">
                        <span class="dropdown-toggle nav-link" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Usuarios</span>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="?c=clientes">Clientes</a>
                            <?php if ($usuario instanceof Admin) { ?>
                                <a class="dropdown-item" href="?c=empleados">Empleados</a>
                            <?php } ?>
                            <a class="dropdown-item" href="?c=admin">Administradores</a>
                        </div>
                    </li>

                    <?php if ($usuario instanceof Admin) { ?>
                        <li class="nav-item"><a class="nav-link" href="?c=especialidades">Especialidades</a></li>

                        <li class="nav-item dropdown">
                            <span class="dropdown-toggle nav-link" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Calendario</span>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="?c=calendarConfig">Laboral</a>
                                <a class="dropdown-item" href="?c=festivos">Festivo</a>
                            </div>
                        </li>
                    <?php } ?>
                <?php } ?>
            </ul>
        </div>
    </nav>
</div>