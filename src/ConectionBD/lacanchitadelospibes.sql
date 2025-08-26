-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 26-08-2025 a las 14:14:40
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `lacanchitadelospibes`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cancha`
--

CREATE TABLE `cancha` (
  `id_cancha` int(4) NOT NULL,
  `nombreCancha` varchar(20) NOT NULL,
  `precio` int(10) NOT NULL,
  `idUpdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `idCreate` timestamp NOT NULL DEFAULT current_timestamp(),
  `habilitado` int(11) NOT NULL DEFAULT 1,
  `cancelado` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cancha`
--

INSERT INTO `cancha` (`id_cancha`, `nombreCancha`, `precio`, `idUpdate`, `idCreate`, `habilitado`, `cancelado`) VALUES
(1, 'monumental', 100000, '2025-06-24 23:52:18', '2025-05-04 20:03:13', 1, 0),
(2, 'bombonera', 100, '2025-06-24 23:52:29', '2025-05-04 20:03:13', 1, 0),
(3, 'Fortin', 90000, '2025-06-24 23:53:10', '2025-05-04 20:03:28', 1, 0),
(4, 'Cilindro', 80000, '2025-06-24 23:53:28', '2025-05-04 20:03:28', 1, 0),
(5, 'Gasometro', 70000, '2025-06-24 23:53:41', '2025-05-04 20:05:32', 1, 0),
(6, 'Palacio', 60000, '2025-06-24 23:54:21', '2025-05-04 20:05:56', 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleado`
--

CREATE TABLE `empleado` (
  `id_empleado` int(11) NOT NULL,
  `id_rol` int(11) NOT NULL,
  `id_persona` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `habilitado` int(11) NOT NULL DEFAULT 1,
  `cancelado` int(11) NOT NULL DEFAULT 0,
  `idCreate` timestamp NOT NULL DEFAULT current_timestamp(),
  `idUpdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empleado`
--

INSERT INTO `empleado` (`id_empleado`, `id_rol`, `id_persona`, `id_usuario`, `habilitado`, `cancelado`, `idCreate`, `idUpdate`) VALUES
(21, 2, 27, 24, 1, 0, '2025-06-28 13:26:46', '2025-06-28 13:41:04'),
(22, 6, 28, 25, 1, 0, '2025-08-20 11:48:26', '2025-08-20 11:48:26'),
(23, 6, 29, 26, 1, 0, '2025-08-21 18:29:19', '2025-08-21 18:29:19');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fecha`
--

CREATE TABLE `fecha` (
  `id_fecha` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `idCreate` timestamp NOT NULL DEFAULT current_timestamp(),
  `idUpdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `habilitado` int(11) NOT NULL DEFAULT 1,
  `cancelado` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `fecha`
--

INSERT INTO `fecha` (`id_fecha`, `fecha`, `idCreate`, `idUpdate`, `habilitado`, `cancelado`) VALUES
(3, '2025-06-26', '2025-06-28 13:27:48', '2025-06-28 13:27:48', 1, 0),
(4, '2025-07-05', '2025-06-28 13:46:00', '2025-06-28 13:46:00', 1, 0),
(5, '2025-07-11', '2025-06-28 14:34:19', '2025-06-28 14:34:19', 1, 0),
(6, '2025-08-28', '2025-08-23 14:46:59', '2025-08-23 14:46:59', 1, 0),
(7, '2025-08-25', '2025-08-23 15:06:57', '2025-08-23 15:06:57', 1, 0),
(8, '2025-08-29', '2025-08-25 13:50:09', '2025-08-25 13:50:09', 1, 0),
(9, '2025-08-27', '2025-08-25 15:30:07', '2025-08-25 15:30:07', 1, 0),
(10, '2025-08-30', '2025-08-25 15:33:16', '2025-08-25 15:33:16', 1, 0),
(11, '2025-08-31', '2025-08-26 11:38:03', '2025-08-26 11:38:03', 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horario`
--

CREATE TABLE `horario` (
  `id_horario` int(11) NOT NULL,
  `horario` time NOT NULL,
  `idCreate` timestamp NOT NULL DEFAULT current_timestamp(),
  `idUpdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `habilitado` int(11) NOT NULL DEFAULT 1,
  `cancelado` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `horario`
--

INSERT INTO `horario` (`id_horario`, `horario`, `idCreate`, `idUpdate`, `habilitado`, `cancelado`) VALUES
(1, '08:00:00', '2025-06-25 01:57:48', '2025-06-25 01:57:48', 1, 0),
(2, '09:00:00', '2025-06-25 01:57:48', '2025-06-25 01:57:48', 1, 0),
(3, '10:00:00', '2025-06-25 01:57:48', '2025-06-25 01:57:48', 1, 0),
(4, '11:00:00', '2025-06-25 01:57:48', '2025-06-25 01:57:48', 1, 0),
(5, '12:00:00', '2025-06-25 01:57:48', '2025-06-25 01:57:48', 1, 0),
(6, '13:00:00', '2025-06-25 01:57:48', '2025-06-25 01:57:48', 1, 0),
(7, '14:00:00', '2025-06-25 01:57:48', '2025-06-25 01:57:48', 1, 0),
(8, '15:00:00', '2025-06-25 01:57:48', '2025-06-25 01:57:48', 1, 0),
(9, '16:00:00', '2025-06-25 01:57:48', '2025-06-25 01:57:48', 1, 0),
(10, '17:00:00', '2025-06-25 01:57:48', '2025-06-25 01:57:48', 1, 0),
(11, '18:00:00', '2025-06-25 01:57:48', '2025-06-25 01:57:48', 1, 0),
(12, '19:00:00', '2025-06-25 01:57:48', '2025-06-25 01:57:48', 1, 0),
(13, '20:00:00', '2025-06-25 01:57:48', '2025-06-25 01:57:48', 1, 0),
(14, '21:00:00', '2025-06-25 01:57:48', '2025-06-25 01:57:48', 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `persona`
--

CREATE TABLE `persona` (
  `id_persona` int(11) NOT NULL,
  `apellido` varchar(20) NOT NULL,
  `nombre` varchar(20) NOT NULL,
  `edad` varchar(3) NOT NULL,
  `dni` varchar(10) NOT NULL,
  `telefono` varchar(11) NOT NULL,
  `habilitado` int(1) NOT NULL DEFAULT 1,
  `cancelado` int(1) NOT NULL DEFAULT 0,
  `idCreate` timestamp NOT NULL DEFAULT current_timestamp(),
  `idUpdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `persona`
--

INSERT INTO `persona` (`id_persona`, `apellido`, `nombre`, `edad`, `dni`, `telefono`, `habilitado`, `cancelado`, `idCreate`, `idUpdate`) VALUES
(27, 'mino', 'seba', '56', '26586325', '03354658955', 1, 0, '2025-06-28 13:26:46', '2025-06-30 13:51:54'),
(28, 'Del_Inap', 'Los Muchachos', '18', '23695475', '1157598523', 1, 0, '2025-08-20 11:48:26', '2025-08-20 17:19:57'),
(29, 'minotti', 'sebastian', '18', '26325896', '1156236589', 1, 0, '2025-08-21 18:29:19', '2025-08-21 18:29:19');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reserva`
--

CREATE TABLE `reserva` (
  `id_reserva` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_cancha` int(11) NOT NULL,
  `id_fecha` int(11) NOT NULL,
  `id_horario` int(11) NOT NULL,
  `idCreate` timestamp NOT NULL DEFAULT current_timestamp(),
  `idUpdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `habilitado` int(11) NOT NULL DEFAULT 1,
  `cancelado` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reserva`
--

INSERT INTO `reserva` (`id_reserva`, `id_usuario`, `id_cancha`, `id_fecha`, `id_horario`, `idCreate`, `idUpdate`, `habilitado`, `cancelado`) VALUES
(6, 24, 1, 3, 4, '2025-06-28 13:27:48', '2025-06-28 13:27:48', 1, 0),
(7, 24, 1, 4, 5, '2025-06-28 13:46:00', '2025-06-28 13:46:00', 1, 0),
(8, 24, 1, 5, 4, '2025-06-28 14:34:19', '2025-06-28 14:34:19', 1, 0),
(9, 25, 3, 6, 8, '2025-08-23 14:46:59', '2025-08-23 14:46:59', 1, 0),
(10, 25, 4, 7, 13, '2025-08-23 15:06:57', '2025-08-23 15:06:57', 1, 0),
(11, 24, 4, 8, 8, '2025-08-25 13:50:10', '2025-08-25 13:50:10', 1, 0),
(12, 25, 3, 9, 13, '2025-08-25 15:30:07', '2025-08-25 15:30:07', 1, 0),
(13, 24, 5, 10, 10, '2025-08-25 15:33:16', '2025-08-25 15:33:16', 1, 0),
(14, 24, 2, 11, 8, '2025-08-26 11:38:03', '2025-08-26 11:38:03', 1, 0),
(15, 25, 3, 9, 13, '2025-08-26 11:43:19', '2025-08-26 11:43:19', 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id_roles` int(11) NOT NULL,
  `idCreate` timestamp NOT NULL DEFAULT current_timestamp(),
  `idUpdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `habilitado` int(11) NOT NULL DEFAULT 1,
  `cancelado` int(11) NOT NULL DEFAULT 0,
  `rol` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id_roles`, `idCreate`, `idUpdate`, `habilitado`, `cancelado`, `rol`) VALUES
(0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0, ''),
(1, '2025-05-15 00:54:58', '2025-05-15 00:54:58', 1, 0, 'Dueño'),
(2, '2025-05-15 01:04:06', '2025-06-28 13:43:32', 1, 0, 'Administrador'),
(3, '0000-00-00 00:00:00', '2025-05-15 01:06:18', 1, 0, 'Bar'),
(4, '2025-05-15 01:05:25', '2025-05-15 01:05:25', 1, 0, 'Alquiler'),
(5, '0000-00-00 00:00:00', '2025-05-15 01:07:47', 1, 0, 'Estacionamiento'),
(6, '2025-06-10 01:13:04', '2025-06-10 01:15:06', 1, 0, 'Cliente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(4) NOT NULL,
  `email` varchar(40) NOT NULL,
  `clave` varchar(100) NOT NULL,
  `id_persona` int(11) NOT NULL,
  `idUpdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `idCreate` timestamp NOT NULL DEFAULT current_timestamp(),
  `habilitado` int(11) NOT NULL DEFAULT 1,
  `cancelado` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `email`, `clave`, `id_persona`, `idUpdate`, `idCreate`, `habilitado`, `cancelado`) VALUES
(24, 'sebastianminotti@gmail.com', '$2y$10$6wdgf2sdF0nm34vvwxIHu.y1gqFpG5h/j8x/ePGEN6nuYe.jysEqy', 27, '2025-06-28 13:26:46', '2025-06-28 13:26:46', 1, 0),
(25, 'losmuchachosdelinapifts@gmail.com', '$2y$10$Ek.t2FStpwiCCJE96BgLx.oFeLUfzNuN/gin9bIJ2lvei4bR5Vxye', 28, '2025-08-20 11:54:29', '2025-08-20 11:48:26', 1, 0),
(26, 'sminotti@outlook.es', '$2y$10$OXaYH66BqiwebDVnTpgo6O83QnTT0xfUaDw0o/b2iqUGn/O03xYyi', 29, '2025-08-21 18:29:19', '2025-08-21 18:29:19', 1, 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cancha`
--
ALTER TABLE `cancha`
  ADD PRIMARY KEY (`id_cancha`);

--
-- Indices de la tabla `empleado`
--
ALTER TABLE `empleado`
  ADD PRIMARY KEY (`id_empleado`),
  ADD KEY `id_rol` (`id_rol`,`id_persona`,`id_usuario`),
  ADD KEY `id_persona` (`id_persona`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `fecha`
--
ALTER TABLE `fecha`
  ADD PRIMARY KEY (`id_fecha`);

--
-- Indices de la tabla `horario`
--
ALTER TABLE `horario`
  ADD PRIMARY KEY (`id_horario`);

--
-- Indices de la tabla `persona`
--
ALTER TABLE `persona`
  ADD PRIMARY KEY (`id_persona`);

--
-- Indices de la tabla `reserva`
--
ALTER TABLE `reserva`
  ADD PRIMARY KEY (`id_reserva`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_cancha` (`id_cancha`),
  ADD KEY `id_fecha` (`id_fecha`),
  ADD KEY `id_horario` (`id_horario`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_roles`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD KEY `id_persona` (`id_persona`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cancha`
--
ALTER TABLE `cancha`
  MODIFY `id_cancha` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `empleado`
--
ALTER TABLE `empleado`
  MODIFY `id_empleado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `fecha`
--
ALTER TABLE `fecha`
  MODIFY `id_fecha` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `horario`
--
ALTER TABLE `horario`
  MODIFY `id_horario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `persona`
--
ALTER TABLE `persona`
  MODIFY `id_persona` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de la tabla `reserva`
--
ALTER TABLE `reserva`
  MODIFY `id_reserva` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_roles` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `empleado`
--
ALTER TABLE `empleado`
  ADD CONSTRAINT `empleado_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_roles`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `empleado_ibfk_2` FOREIGN KEY (`id_persona`) REFERENCES `persona` (`id_persona`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `empleado_ibfk_3` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `reserva`
--
ALTER TABLE `reserva`
  ADD CONSTRAINT `reserva_ibfk_1` FOREIGN KEY (`id_fecha`) REFERENCES `fecha` (`id_fecha`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reserva_ibfk_3` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reserva_ibfk_4` FOREIGN KEY (`id_horario`) REFERENCES `horario` (`id_horario`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reserva_ibfk_5` FOREIGN KEY (`id_cancha`) REFERENCES `cancha` (`id_cancha`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`id_persona`) REFERENCES `persona` (`id_persona`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
