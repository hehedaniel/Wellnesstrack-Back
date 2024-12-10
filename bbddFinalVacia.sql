-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 08-12-2024 a las 12:06:32
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `api`
--
CREATE DATABASE IF NOT EXISTS `api` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `api`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alimento`
--

CREATE TABLE `alimento` (
  `id` int(11) NOT NULL,
  `id_usuario_id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` longtext NOT NULL,
  `marca` varchar(255) NOT NULL,
  `cantidad` double NOT NULL,
  `proteinas` double NOT NULL,
  `grasas` double NOT NULL,
  `carbohidratos` double NOT NULL,
  `azucares` double NOT NULL,
  `calorias` double NOT NULL,
  `imagen` longtext NOT NULL,
  `estado` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `consumo_dia`
--

CREATE TABLE `consumo_dia` (
  `id` int(11) NOT NULL,
  `id_usuario_id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `comida` varchar(255) NOT NULL,
  `cantidad` double NOT NULL,
  `momento` varchar(255) NOT NULL,
  `hora` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ejercicio`
--

CREATE TABLE `ejercicio` (
  `id` int(11) NOT NULL,
  `id_usuario_id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` longtext NOT NULL,
  `grupo_muscular` varchar(255) NOT NULL,
  `dificultad` varchar(255) NOT NULL,
  `instrucciones` longtext NOT NULL,
  `valor_met` int(11) NOT NULL,
  `estado` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `enlace`
--

CREATE TABLE `enlace` (
  `id` int(11) NOT NULL,
  `id_ejercicio_id` int(11) NOT NULL,
  `enlace` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `messenger_messages`
--

CREATE TABLE `messenger_messages` (
  `id` bigint(20) NOT NULL,
  `body` longtext NOT NULL,
  `headers` longtext NOT NULL,
  `queue_name` varchar(190) NOT NULL,
  `created_at` datetime NOT NULL,
  `available_at` datetime NOT NULL,
  `delivered_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `peso`
--

CREATE TABLE `peso` (
  `id` int(11) NOT NULL,
  `id_usuario_id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `peso` double NOT NULL,
  `imc` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recetas`
--

CREATE TABLE `recetas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` longtext NOT NULL,
  `instrucciones` longtext NOT NULL,
  `cantidad_final` double NOT NULL,
  `proteinas` double NOT NULL,
  `grasas` double NOT NULL,
  `carbohidratos` double NOT NULL,
  `azucares` double NOT NULL,
  `calorias` double NOT NULL,
  `imagen` longtext NOT NULL,
  `id_usuario_id` int(11) NOT NULL,
  `estado` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `apellidos` varchar(255) NOT NULL,
  `correo` varchar(255) NOT NULL,
  `correo_v` varchar(255) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `rol` tinyint(1) NOT NULL,
  `edad` int(11) NOT NULL,
  `altura` double NOT NULL,
  `objetivo_opt` varchar(255) NOT NULL,
  `objetivo_num` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_realiza_ejercicio`
--

CREATE TABLE `usuario_realiza_ejercicio` (
  `id` int(11) NOT NULL,
  `id_ejercicio_id` int(11) NOT NULL,
  `id_usuario_id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `calorias` double NOT NULL,
  `tiempo` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `alimento`
--
ALTER TABLE `alimento`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_A3C395937EB2C349` (`id_usuario_id`);

--
-- Indices de la tabla `consumo_dia`
--
ALTER TABLE `consumo_dia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_77412CD17EB2C349` (`id_usuario_id`);

--
-- Indices de la tabla `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Indices de la tabla `ejercicio`
--
ALTER TABLE `ejercicio`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_95ADCFF47EB2C349` (`id_usuario_id`);

--
-- Indices de la tabla `enlace`
--
ALTER TABLE `enlace`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_8414B27913487F0F` (`id_ejercicio_id`);

--
-- Indices de la tabla `messenger_messages`
--
ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  ADD KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  ADD KEY `IDX_75EA56E016BA31DB` (`delivered_at`);

--
-- Indices de la tabla `peso`
--
ALTER TABLE `peso`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_DD7820B77EB2C349` (`id_usuario_id`);

--
-- Indices de la tabla `recetas`
--
ALTER TABLE `recetas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_8ADA30D57EB2C349` (`id_usuario_id`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuario_realiza_ejercicio`
--
ALTER TABLE `usuario_realiza_ejercicio`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_C16D635113487F0F` (`id_ejercicio_id`),
  ADD KEY `IDX_C16D63517EB2C349` (`id_usuario_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `alimento`
--
ALTER TABLE `alimento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `consumo_dia`
--
ALTER TABLE `consumo_dia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ejercicio`
--
ALTER TABLE `ejercicio`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `enlace`
--
ALTER TABLE `enlace`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `peso`
--
ALTER TABLE `peso`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `recetas`
--
ALTER TABLE `recetas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuario_realiza_ejercicio`
--
ALTER TABLE `usuario_realiza_ejercicio`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `alimento`
--
ALTER TABLE `alimento`
  ADD CONSTRAINT `FK_A3C395937EB2C349` FOREIGN KEY (`id_usuario_id`) REFERENCES `usuario` (`id`);

--
-- Filtros para la tabla `consumo_dia`
--
ALTER TABLE `consumo_dia`
  ADD CONSTRAINT `FK_77412CD17EB2C349` FOREIGN KEY (`id_usuario_id`) REFERENCES `usuario` (`id`);

--
-- Filtros para la tabla `ejercicio`
--
ALTER TABLE `ejercicio`
  ADD CONSTRAINT `FK_95ADCFF47EB2C349` FOREIGN KEY (`id_usuario_id`) REFERENCES `usuario` (`id`);

--
-- Filtros para la tabla `enlace`
--
ALTER TABLE `enlace`
  ADD CONSTRAINT `FK_8414B27913487F0F` FOREIGN KEY (`id_ejercicio_id`) REFERENCES `ejercicio` (`id`);

--
-- Filtros para la tabla `peso`
--
ALTER TABLE `peso`
  ADD CONSTRAINT `FK_DD7820B77EB2C349` FOREIGN KEY (`id_usuario_id`) REFERENCES `usuario` (`id`);

--
-- Filtros para la tabla `recetas`
--
ALTER TABLE `recetas`
  ADD CONSTRAINT `FK_8ADA30D57EB2C349` FOREIGN KEY (`id_usuario_id`) REFERENCES `usuario` (`id`);

--
-- Filtros para la tabla `usuario_realiza_ejercicio`
--
ALTER TABLE `usuario_realiza_ejercicio`
  ADD CONSTRAINT `FK_C16D635113487F0F` FOREIGN KEY (`id_ejercicio_id`) REFERENCES `ejercicio` (`id`),
  ADD CONSTRAINT `FK_C16D63517EB2C349` FOREIGN KEY (`id_usuario_id`) REFERENCES `usuario` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
