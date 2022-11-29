-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 29-11-2022 a las 17:04:44
-- Versión del servidor: 10.4.25-MariaDB
-- Versión de PHP: 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `p3_tp`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados`
--

CREATE TABLE `empleados` (
  `id` int(5) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `perfil` varchar(50) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `clave` varchar(100) NOT NULL,
  `estado` varchar(50) NOT NULL,
  `fechaAlta` varchar(50) NOT NULL,
  `fechaBaja` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `empleados`
--

INSERT INTO `empleados` (`id`, `nombre`, `perfil`, `usuario`, `clave`, `estado`, `fechaAlta`, `fechaBaja`) VALUES
(1, 'Franco', 'socio', 'francosampi', '$2y$10$.PHDC.8ugoyA3ctbBnAdVOiDC/iHKR53QZEA5AqIH3s7nlb8SI0i2', 'activo', '2022-11-18 14:11:27', NULL),
(2, 'Juan', 'bartender', 'juantbar', '$2y$10$rpVM4gNsjpzPP/9X0TmITOiyfCgzrcjUXIu85.PHOKLj8e6WF0xQa', 'activo', '2022-11-18 14:11:04', NULL),
(4, 'Santiago', 'cocinero', 'santiagodlt', '$2y$10$q7xztqjcYVOdIM3sXstIoObaxmfR5vRPnNZC2eLQZFgCWq/fr4rx2', 'activo', '2022-11-18 15:11:25', NULL),
(5, 'Facundo', 'mozo', 'realmozo', '$2y$10$aNOzBnhE2yXIko5nl5cBtObMae384VzkQvasx5mOb8TSiuwX/XrwG', 'activo', '2022-11-18 11:11:55', NULL),
(6, 'Nicolas', 'cervecero', 'nicosch', '$2y$10$TGiBu.bXp7F1m9GwFYRbiO77MPkX6vfO5mrLuSl8QR71ErV1Y4NQi', 'activo', '2022-11-20 12:55:21', NULL),
(7, 'Felipe', 'socio', 'fpuiss', '$2y$10$xKfaShWC0EGtSGx5Z7wHqOALUhu.skLO37SLFFjkszRIXmL1AO5qu', 'inactivo', '2022-11-28 09:58:54', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `encuestas`
--

CREATE TABLE `encuestas` (
  `id` int(5) NOT NULL,
  `cliente` varchar(50) DEFAULT NULL,
  `codigoMesa` varchar(50) NOT NULL,
  `puntajeMesa` int(5) NOT NULL,
  `puntajeResto` int(5) NOT NULL,
  `puntajeMozo` int(5) NOT NULL,
  `puntajeCocinero` int(5) NOT NULL,
  `puntajePromedio` varchar(5) NOT NULL,
  `descripcion` varchar(66) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `encuestas`
--

INSERT INTO `encuestas` (`id`, `cliente`, `codigoMesa`, `puntajeMesa`, `puntajeResto`, `puntajeMozo`, `puntajeCocinero`, `puntajePromedio`, `descripcion`) VALUES
(3, 'Franco', 'TQ95C', 7, 8, 8, 4, '6.75', 'La milanesa mas dura que pionono de alfombra'),
(4, 'Cristina', 'TQ95C', 7, 8, 7, 9, '7.75', 'Carisima el agua'),
(5, 'Ricardito', 'TQ95C', 10, 10, 10, 10, '10', 'Tremendas las papas, un 10 a todo'),
(6, 'Ricardito', 'TQ95C', 9, 7, 7, 6, '7.25', 'Mucho tomate en la salsa :('),
(7, 'Ricardito', 'TQ95C', 9, 7, 7, 6, '7.25', 'Mucho tomate en la salsa :(');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facturas`
--

CREATE TABLE `facturas` (
  `id` int(5) NOT NULL,
  `nroOrden` varchar(50) NOT NULL,
  `codigoMesa` varchar(50) NOT NULL,
  `precioTotal` double NOT NULL,
  `fechaAlta` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `facturas`
--

INSERT INTO `facturas` (`id`, `nroOrden`, `codigoMesa`, `precioTotal`, `fechaAlta`) VALUES
(1, 'UCRAF', 'TQ95C', 1200, '2022-11-29 10:05:54\n'),
(2, 'HU03B', 'TQ95C', 1325, '2022-11-29 11:02:50\n'),
(3, '61CGL', 'TQ95C', 1030, '2022-11-29 11:32:22\n');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesas`
--

CREATE TABLE `mesas` (
  `id` int(5) NOT NULL,
  `codigo` varchar(50) NOT NULL,
  `idEmpleado` int(5) DEFAULT NULL,
  `estado` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `mesas`
--

INSERT INTO `mesas` (`id`, `codigo`, `idEmpleado`, `estado`) VALUES
(1, 'TQ95C', NULL, 'Cerrada'),
(2, 'MNZDW', NULL, 'Cerrada'),
(3, 'PTD1J', NULL, 'Cerrada'),
(5, 'J0A9X', NULL, 'Cerrada');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `nroOrden` varchar(50) NOT NULL,
  `idMesa` int(11) NOT NULL,
  `nombreCliente` varchar(50) NOT NULL,
  `estado` varchar(50) NOT NULL,
  `fechaAlta` varchar(50) NOT NULL,
  `precioTotal` double NOT NULL DEFAULT 0,
  `pathFoto` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `nroOrden`, `idMesa`, `nombreCliente`, `estado`, `fechaAlta`, `precioTotal`, `pathFoto`) VALUES
(1, 'UCRAF', 1, 'Palacio', 'Cobrado', '2022-11-28 15:31:42', 1200, './media/pedidos/Palacio-2022-11-28-03-31-42.jpg'),
(3, 'HU03B', 1, 'Cristina', 'Cobrado', '2022-11-29 10:53:39', 1325, './media/pedidos/Cristina-2022-11-29-10-53-39.jpg'),
(4, '61CGL', 1, 'Benedetto', 'Cobrado', '2022-11-29 11:28:12', 1030, './media/pedidos/Benedetto-2022-11-29-11-28-12.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `platos`
--

CREATE TABLE `platos` (
  `id` int(5) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `sector` varchar(50) NOT NULL,
  `precio` double NOT NULL,
  `stock` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `platos`
--

INSERT INTO `platos` (`id`, `nombre`, `sector`, `precio`, `stock`) VALUES
(1, 'Milanesa con papas', 'cocina', 1200, 97),
(2, 'Suprema con pure', 'cocina', 1200, 97),
(3, 'Bife de chorizo', 'cocina', 1200, 100),
(4, 'Fideos con tuco', 'cocina', 850, 98),
(5, 'Coca cola', 'barra', 180, 95),
(6, 'Agua', 'barra', 125, 96),
(7, 'Sprite', 'barra', 180, 100),
(8, 'Tiramisú', 'cocina', 600, 100);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(5) NOT NULL,
  `nroOrden` varchar(50) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `sector` varchar(50) NOT NULL,
  `precio` float NOT NULL,
  `estado` varchar(50) NOT NULL,
  `horaInicio` varchar(50) NOT NULL,
  `horaEstimada` varchar(50) DEFAULT NULL,
  `horaFinalizacion` varchar(50) DEFAULT NULL,
  `idEmpleado` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nroOrden`, `nombre`, `sector`, `precio`, `estado`, `horaInicio`, `horaEstimada`, `horaFinalizacion`, `idEmpleado`) VALUES
(2, 'UCRAF', 'Suprema con pure', 'cocina', 1200, 'Servido', '2022-11-28 15:35:31', '2022-11-28 16:05:31', '2022-11-28 16:04:53', 4),
(3, 'HU03B', 'Milanesa con papas', 'cocina', 1200, 'Servido', '2022-11-29 10:54:06', '2022-11-29 11:54:06', '2022-11-29 11:01:20', 4),
(4, 'HU03B', 'Agua', 'barra', 125, 'Servido', '2022-11-29 10:54:31', '2022-11-29 11:54:31', '2022-11-29 11:01:33', 2),
(5, '61CGL', 'Fideos con tuco', 'cocina', 850, 'Servido', '2022-11-29 11:28:34', '2022-11-29 12:13:34', '2022-11-29 11:31:35', 4),
(6, '61CGL', 'Coca cola', 'barra', 180, 'Servido', '2022-11-29 11:29:02', '2022-11-29 11:34:02', '2022-11-29 11:31:18', 2);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `encuestas`
--
ALTER TABLE `encuestas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `facturas`
--
ALTER TABLE `facturas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `mesas`
--
ALTER TABLE `mesas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `platos`
--
ALTER TABLE `platos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `empleados`
--
ALTER TABLE `empleados`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `encuestas`
--
ALTER TABLE `encuestas`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `facturas`
--
ALTER TABLE `facturas`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `mesas`
--
ALTER TABLE `mesas`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `platos`
--
ALTER TABLE `platos`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
