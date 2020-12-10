-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-12-2020 a las 21:07:05
-- Versión del servidor: 10.4.13-MariaDB
-- Versión de PHP: 7.4.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `medicontrol`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administradores`
--

CREATE TABLE `administradores` (
  `dni_admin` varchar(9) COLLATE utf8_bin NOT NULL,
  `dni_empleado` varchar(9) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `administradores`
--

INSERT INTO `administradores` (`dni_admin`, `dni_empleado`) VALUES
('12345678A', '12345678A'),
('12345678A', '12345678B'),
('12345678A', '12345678C'),
('12345678A', '12345678D'),
('12345678B', '12345678B'),
('12345678B', '12345678C'),
('12345678B', '12345678D'),
('ADMIN', 'ADMIN');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `citas`
--

CREATE TABLE `citas` (
  `id` int(11) NOT NULL,
  `dni_cliente` varchar(9) COLLATE utf8_bin NOT NULL,
  `dni_empleado` varchar(9) COLLATE utf8_bin DEFAULT NULL,
  `id_fecha` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `asunto` varchar(50) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `citas`
--

INSERT INTO `citas` (`id`, `dni_cliente`, `dni_empleado`, `id_fecha`, `fecha`, `hora`, `asunto`) VALUES
(18, '09876543A', '12345678C', 5, '2020-12-11', '09:00:00', 'REVISIÓN'),
(19, '09876543B', '12345678A', 5, '2020-12-11', '09:30:00', 'REVISIÓN'),
(20, '09876543C', '12345678C', 1, '2020-12-14', '11:30:00', 'LIMPIEZA BUCAL'),
(21, '09876543D', NULL, 3, '2020-12-16', '18:00:00', 'PRUEBAS DE SANGRE'),
(22, '09876543G', '12345678C', 1, '2021-01-25', '10:00:00', 'ORTODONCIA');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `dni` varchar(9) COLLATE utf8_bin NOT NULL,
  `aseguradora` varchar(25) COLLATE utf8_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`dni`, `aseguradora`) VALUES
('09876543A', ''),
('09876543B', ''),
('09876543C', ''),
('09876543D', ''),
('09876543E', ''),
('09876543F', ''),
('09876543G', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `consultas`
--

CREATE TABLE `consultas` (
  `id` int(11) NOT NULL,
  `id_cita` int(11) NOT NULL,
  `descripcion` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  `pruebas` tinyint(1) DEFAULT NULL,
  `pruebas_detalles` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  `tratamientos` tinyint(1) DEFAULT NULL,
  `tratamientos_detalles` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  `otros_detalles` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  `importe` double DEFAULT NULL,
  `pago` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `consultas`
--

INSERT INTO `consultas` (`id`, `id_cita`, `descripcion`, `pruebas`, `pruebas_detalles`, `tratamientos`, `tratamientos_detalles`, `otros_detalles`, `importe`, `pago`) VALUES
(9, 18, NULL, 0, NULL, 0, NULL, NULL, NULL, 0),
(10, 19, '', 1, '', 1, '', '', 75, 0),
(11, 20, '', 0, '', 0, '', '', 25, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuentas`
--

CREATE TABLE `cuentas` (
  `usuario` varchar(9) COLLATE utf8_bin NOT NULL,
  `contrasena` varchar(255) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `cuentas`
--

INSERT INTO `cuentas` (`usuario`, `contrasena`) VALUES
('09876543A', '$2y$10$R5nA71ZKPlu2El0XwZEmQ.tkyB9dWglPE/ofpZmUsJUsv7KfBJWGS'),
('09876543B', '$2y$10$ClwiLy6LkxN0F83Rr/rJpukqS8cIH32aHQ/yJZWD4GMOPmPgj8g9O'),
('09876543C', '$2y$10$K8KTXRDUg5IK2t2jlHymNex/KkYC1REboxQyofzV0VD5fHy66Xmi6'),
('09876543D', '$2y$10$kdISf51DjehcrdXSc9aBSOgKkTY/gCbWomacHEH08XJgoLZVm25VC'),
('09876543E', '$2y$10$.OL3Xh7J8vXOHfqtATn./OfwL79gsHX3bdPO4HWexmdY1BX0xu3EK'),
('09876543F', '$2y$10$uAqkctUA.OSvUys79yC4r.tTgHB3bEbnbcSK2HsOcNp0k3Czr5Fqa'),
('09876543G', '$2y$10$XUZ.vspMOTzkA9KSi1BJhOp.i0ETJBymRkvO73A7Wn3MN7p/5J.f.'),
('12345678A', '$2y$10$DGE4Pvsppz2CuSBIh8bGbuAEpkJ43zNOUKnDzflk/PQF9bFzonoBK'),
('12345678B', '$2y$10$ysTE/1789X7Q5FXxTtGNKu3jXwWVj2lxiWAQKxTzZ3/C/UcJ40FRi'),
('12345678C', '$2y$10$Mev2qEHTz.1c5RYhHirZ9.7MdiynXd/WQufrwS0kBwIhq2gLrjixW'),
('12345678D', '$2y$10$a2TlzHfQWc51A6jq7.lsBead8OGdM8I6nKKFzMtbDkH7K.18C/yra'),
('ADMIN', '$2y$10$H1mg4SR../JX.JBy7kpEzecR1Jp1sS5occLaITWCZRbjs6uGxp.gy');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `disponibilidad`
--

CREATE TABLE `disponibilidad` (
  `id` int(11) NOT NULL,
  `duracion_cita` int(11) NOT NULL,
  `max_clientes` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `disponibilidad`
--

INSERT INTO `disponibilidad` (`id`, `duracion_cita`, `max_clientes`) VALUES
(1, 0, 0),
(7, 30, 2),
(8, 30, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados`
--

CREATE TABLE `empleados` (
  `dni` varchar(9) COLLATE utf8_bin NOT NULL,
  `fecha_alta` date DEFAULT NULL,
  `fecha_baja` date DEFAULT NULL,
  `especialidad` varchar(25) COLLATE utf8_bin NOT NULL,
  `extension` varchar(5) COLLATE utf8_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `empleados`
--

INSERT INTO `empleados` (`dni`, `fecha_alta`, `fecha_baja`, `especialidad`, `extension`) VALUES
('12345678A', NULL, NULL, 'CARDIOLOGÍA', NULL),
('12345678B', NULL, NULL, 'NEUROLOGÍA', NULL),
('12345678C', NULL, NULL, 'ODONTOLOGÍA', NULL),
('12345678D', NULL, NULL, 'CARDIOLOGÍA', NULL),
('ADMIN', NULL, NULL, 'ROOT', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `especialidades`
--

CREATE TABLE `especialidades` (
  `nombre` varchar(25) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `especialidades`
--

INSERT INTO `especialidades` (`nombre`) VALUES
('CARDIOLOGÍA'),
('NEUROLOGÍA'),
('ODONTOLOGÍA'),
('ROOT');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fechas`
--

CREATE TABLE `fechas` (
  `id` int(11) NOT NULL,
  `id_disponibilidad` int(11) NOT NULL,
  `id_horarios` int(11) NOT NULL,
  `tipo` char(1) COLLATE utf8_bin NOT NULL,
  `dia` varchar(10) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `fechas`
--

INSERT INTO `fechas` (`id`, `id_disponibilidad`, `id_horarios`, `tipo`, `dia`) VALUES
(1, 7, 6, 'L', '1'),
(2, 7, 6, 'L', '2'),
(3, 7, 6, 'L', '3'),
(4, 7, 6, 'L', '4'),
(5, 8, 7, 'L', '5'),
(6, 1, 1, 'L', '6'),
(7, 1, 1, 'L', '7'),
(16, 1, 1, 'F', '2020-12-25'),
(17, 1, 1, 'F', '2021-01-06');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horarios`
--

CREATE TABLE `horarios` (
  `id` int(11) NOT NULL,
  `manana1` time NOT NULL,
  `manana2` time NOT NULL,
  `tarde1` time NOT NULL,
  `tarde2` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `horarios`
--

INSERT INTO `horarios` (`id`, `manana1`, `manana2`, `tarde1`, `tarde2`) VALUES
(1, '00:00:00', '00:00:00', '00:00:00', '00:00:00'),
(6, '09:00:00', '14:00:00', '17:00:00', '20:00:00'),
(7, '09:00:00', '14:00:00', '00:00:00', '00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `dni` varchar(9) COLLATE utf8_bin NOT NULL,
  `nombre` varchar(20) COLLATE utf8_bin NOT NULL,
  `apellido1` varchar(50) COLLATE utf8_bin NOT NULL,
  `apellido2` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `sexo` char(1) COLLATE utf8_bin DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `email` varchar(100) COLLATE utf8_bin NOT NULL,
  `telf` int(9) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`dni`, `nombre`, `apellido1`, `apellido2`, `sexo`, `fecha_nacimiento`, `email`, `telf`) VALUES
('09876543A', 'ÁLVARO', 'LÓPEZ', '', '', NULL, 'pruebas1@pruebas1.p', NULL),
('09876543B', 'BELMA', 'MORCILLO', '', '', NULL, 'pruebas2@pruebas2.p', NULL),
('09876543C', 'CARLOS', 'MONTERO', '', '', NULL, 'pruebas3@pruebas3.p', NULL),
('09876543D', 'DANIELA', 'GUTIÉRREZ', '', '', NULL, 'pruebas4@pruebas4.p', NULL),
('09876543E', 'ESMERALDA', 'LÓPEZ', '', '', NULL, 'pruebas5@pruebas5.p', NULL),
('09876543F', 'FERNANDO', 'MACÍAS', '', '', NULL, 'pruebas6@pruebas6.p', NULL),
('09876543G', 'GUILLERMO', 'CARRASCO', '', '', NULL, 'pruebas7@pruebas7.p', NULL),
('12345678A', 'ALMUDENA', 'VELEDA', '', '', NULL, 'pruebas11@pruebas11.p', NULL),
('12345678B', 'BELMA', 'HERNÁNDEZ', '', '', NULL, 'pruebas22@pruebas22.p', NULL),
('12345678C', 'CARLA', 'VILLAFAINA', '', '', NULL, 'pruebas33@pruebas33.p', NULL),
('12345678D', 'DYLAN', 'DÍAZ', '', '', NULL, 'pruebas44@pruebas44.p', NULL),
('ADMIN', 'ADMIN', 'ADMIN', '', '', NULL, 'kennotamashi@gmail.com', 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administradores`
--
ALTER TABLE `administradores`
  ADD PRIMARY KEY (`dni_admin`,`dni_empleado`),
  ADD KEY `fk_adm_emp` (`dni_empleado`);

--
-- Indices de la tabla `citas`
--
ALTER TABLE `citas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_cit_cli` (`dni_cliente`),
  ADD KEY `fk_cit_emp` (`dni_empleado`),
  ADD KEY `fk_cit_fec` (`id_fecha`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`dni`);

--
-- Indices de la tabla `consultas`
--
ALTER TABLE `consultas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_con_cit` (`id_cita`);

--
-- Indices de la tabla `cuentas`
--
ALTER TABLE `cuentas`
  ADD PRIMARY KEY (`usuario`);

--
-- Indices de la tabla `disponibilidad`
--
ALTER TABLE `disponibilidad`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD PRIMARY KEY (`dni`),
  ADD KEY `fk_emp_esp` (`especialidad`);

--
-- Indices de la tabla `especialidades`
--
ALTER TABLE `especialidades`
  ADD PRIMARY KEY (`nombre`);

--
-- Indices de la tabla `fechas`
--
ALTER TABLE `fechas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_fec_dis` (`id_disponibilidad`),
  ADD KEY `fk_fec_hor` (`id_horarios`);

--
-- Indices de la tabla `horarios`
--
ALTER TABLE `horarios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`dni`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `citas`
--
ALTER TABLE `citas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT de la tabla `consultas`
--
ALTER TABLE `consultas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT de la tabla `disponibilidad`
--
ALTER TABLE `disponibilidad`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT de la tabla `fechas`
--
ALTER TABLE `fechas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT de la tabla `horarios`
--
ALTER TABLE `horarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `administradores`
--
ALTER TABLE `administradores`
  ADD CONSTRAINT `fk_adm_adm` FOREIGN KEY (`dni_admin`) REFERENCES `empleados` (`dni`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_adm_emp` FOREIGN KEY (`dni_empleado`) REFERENCES `empleados` (`dni`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `citas`
--
ALTER TABLE `citas`
  ADD CONSTRAINT `fk_cit_cli` FOREIGN KEY (`dni_cliente`) REFERENCES `clientes` (`dni`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cit_emp` FOREIGN KEY (`dni_empleado`) REFERENCES `empleados` (`dni`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cit_fec` FOREIGN KEY (`id_fecha`) REFERENCES `fechas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD CONSTRAINT `fk_cli_dni` FOREIGN KEY (`dni`) REFERENCES `usuarios` (`dni`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `consultas`
--
ALTER TABLE `consultas`
  ADD CONSTRAINT `fk_con_cit` FOREIGN KEY (`id_cita`) REFERENCES `citas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `cuentas`
--
ALTER TABLE `cuentas`
  ADD CONSTRAINT `fk_cue_usu` FOREIGN KEY (`usuario`) REFERENCES `usuarios` (`dni`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD CONSTRAINT `fk_emp_dni` FOREIGN KEY (`dni`) REFERENCES `usuarios` (`dni`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_emp_esp` FOREIGN KEY (`especialidad`) REFERENCES `especialidades` (`nombre`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `fechas`
--
ALTER TABLE `fechas`
  ADD CONSTRAINT `fk_fec_dis` FOREIGN KEY (`id_disponibilidad`) REFERENCES `disponibilidad` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_fec_hor` FOREIGN KEY (`id_horarios`) REFERENCES `horarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
