-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 29-01-2020 a las 16:52:12
-- Versión del servidor: 10.1.32-MariaDB
-- Versión de PHP: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `transfo8_transformate`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profiles`
--

CREATE TABLE `profiles` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0 =No Disponible. 1 = Disponible',
  `users` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 = Sin Acceso. 1 = Solo Lectura. 2 = Acceso Total.	',
  `profiles` tinyint(1) NOT NULL DEFAULT '0',
  `courses` tinyint(1) NOT NULL DEFAULT '0',
  `certifications` tinyint(1) NOT NULL DEFAULT '0',
  `podcasts` tinyint(1) NOT NULL DEFAULT '0',
  `tags` tinyint(1) NOT NULL DEFAULT '0',
  `liquidations` tinyint(1) NOT NULL DEFAULT '0',
  `banks` tinyint(1) NOT NULL DEFAULT '0',
  `coupons` tinyint(4) NOT NULL DEFAULT '0',
  `tickets` tinyint(1) NOT NULL DEFAULT '0',
  `finances` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `profiles`
--

INSERT INTO `profiles` (`id`, `name`, `status`, `users`, `profiles`, `courses`, `certifications`, `podcasts`, `tags`, `liquidations`, `banks`, `coupons`, `tickets`, `finances`, `created_at`, `updated_at`) VALUES
(1, 'Master', 1, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, '2020-01-28 00:00:00', '2020-01-29 15:52:04');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `profiles`
--
ALTER TABLE `profiles`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `profiles`
--
ALTER TABLE `profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
