<?php

/**
 * Carga de los ficheros necesarios
 */

require_once 'core/config.php';
require_once 'core/helpers.php';
require_once 'core/Routes.php';
require_once 'core/DB.php';

require_once 'models/Usuario.php';
require_once 'models/Cliente.php';
require_once 'models/Empleado.php';
require_once 'models/Admin.php';
require_once 'models/Cuenta.php';
require_once 'models/Cita.php';
require_once 'models/Consulta.php';
require_once 'models/Calendario.php';
require_once 'models/Laboral.php';
require_once 'models/Festivo.php';
require_once 'models/Especialidad.php';

require_once 'views/contents/msg.php';

require_once 'views/contents/forms/update/formUpdatePerfil.php';
require_once 'views/contents/forms/update/formUpdateCita.php';
require_once 'views/contents/forms/update/formUpdateConsulta.php';
require_once 'views/contents/forms/update/formUpdateCliente.php';
require_once 'views/contents/forms/update/formUpdateEmpleado.php';
require_once 'views/contents/forms/update/formUpdateAdmin.php';
require_once 'views/contents/forms/update/formUpdateCalendar.php';
require_once 'views/contents/forms/update/formUpdateFestivo.php';

require_once 'views/contents/lists/listCitas.php';
require_once 'views/contents/lists/listConsultas.php';
require_once 'views/contents/lists/listClientes.php';
require_once 'views/contents/lists/listEmpleados.php';
require_once 'views/contents/lists/listAdmin.php';
require_once 'views/contents/lists/listCalendars.php';
require_once 'views/contents/lists/listFestivos.php';
require_once 'views/contents/lists/listEspecialidades.php';

require_once 'views/contents/selects/selectCitas.php';
require_once 'views/contents/selects/selectClientes.php';
require_once 'views/contents/selects/selectEmpleados.php';
require_once 'views/contents/selects/selectEspecialidades.php';