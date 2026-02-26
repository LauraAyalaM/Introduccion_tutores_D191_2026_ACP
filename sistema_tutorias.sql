-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 26, 2026 at 04:06 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sistema_tutorias`
--
CREATE DATABASE IF NOT EXISTS `sistema_tutorias` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `sistema_tutorias`;

-- --------------------------------------------------------

--
-- Table structure for table `tb_reservas`
--

CREATE TABLE `tb_reservas` (
  `id_reserva` int(11) NOT NULL,
  `id_tutoria` int(11) NOT NULL,
  `id_estudiante` int(11) NOT NULL,
  `estado` enum('activa','cancelada','asistida') DEFAULT 'activa',
  `fecha_reserva` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_reservas`
--

INSERT INTO `tb_reservas` (`id_reserva`, `id_tutoria`, `id_estudiante`, `estado`, `fecha_reserva`) VALUES
(12, 1, 6, 'cancelada', '2026-02-25 04:23:05'),
(14, 3, 5, 'activa', '2026-02-25 04:23:05');

-- --------------------------------------------------------

--
-- Table structure for table `tb_rol`
--

CREATE TABLE `tb_rol` (
  `id_rol` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_rol`
--

INSERT INTO `tb_rol` (`id_rol`, `nombre`) VALUES
(1, 'estudiante'),
(2, 'profesor'),
(3, 'administrador');

-- --------------------------------------------------------

--
-- Table structure for table `tb_tutorias`
--

CREATE TABLE `tb_tutorias` (
  `id_tutoria` int(11) NOT NULL,
  `id_profesor` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  `tema` varchar(255) NOT NULL,
  `cupos` int(11) NOT NULL,
  `estado` enum('disponible','reservada','cancelada') DEFAULT 'disponible',
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_tutorias`
--

INSERT INTO `tb_tutorias` (`id_tutoria`, `id_profesor`, `fecha`, `hora_inicio`, `hora_fin`, `tema`, `cupos`, `estado`, `fecha_creacion`) VALUES
(1, 3, '2026-03-01', '08:00:00', '10:00:00', 'Matemáticas Básicas', 7, 'disponible', '2026-02-25 02:10:22'),
(3, 3, '2026-03-05', '09:00:00', '11:00:00', 'Base de Datos MySQL', 5, 'cancelada', '2026-02-25 02:10:22'),
(6, 7, '2026-02-25', '16:46:00', '17:47:00', 'Nuevas Tecnologias', 3, 'disponible', '2026-02-25 04:41:25');

-- --------------------------------------------------------

--
-- Table structure for table `tb_usuarios`
--

CREATE TABLE `tb_usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `id_rol` int(11) NOT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_usuarios`
--

INSERT INTO `tb_usuarios` (`id_usuario`, `nombre`, `correo`, `password`, `id_rol`, `activo`, `fecha_creacion`) VALUES
(1, 'Admin', 'admin@correo.com', '1234', 3, 1, '2026-02-23 08:13:55'),
(3, 'Camila', 'cami@gmail.com', '1234', 2, 1, '2026-02-25 01:18:42'),
(4, 'Lara', 'Lara@gmail.com', '1234', 1, 0, '2026-02-25 01:19:22'),
(5, 'Mario', 'Mario@gmail.com', '1234', 1, 1, '2026-02-25 04:19:28'),
(6, 'Lina', 'lina@gmail.com', '1234', 1, 1, '2026-02-25 04:20:28'),
(7, 'Pablo', 'pablo@gmail.com', '1234', 2, 1, '2026-02-25 04:20:45');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_reservas`
--
ALTER TABLE `tb_reservas`
  ADD PRIMARY KEY (`id_reserva`),
  ADD UNIQUE KEY `id_tutoria` (`id_tutoria`,`id_estudiante`),
  ADD KEY `id_estudiante` (`id_estudiante`);

--
-- Indexes for table `tb_rol`
--
ALTER TABLE `tb_rol`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indexes for table `tb_tutorias`
--
ALTER TABLE `tb_tutorias`
  ADD PRIMARY KEY (`id_tutoria`),
  ADD KEY `id_profesor` (`id_profesor`);

--
-- Indexes for table `tb_usuarios`
--
ALTER TABLE `tb_usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `correo` (`correo`),
  ADD KEY `id_rol` (`id_rol`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_reservas`
--
ALTER TABLE `tb_reservas`
  MODIFY `id_reserva` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tb_rol`
--
ALTER TABLE `tb_rol`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tb_tutorias`
--
ALTER TABLE `tb_tutorias`
  MODIFY `id_tutoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tb_usuarios`
--
ALTER TABLE `tb_usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_reservas`
--
ALTER TABLE `tb_reservas`
  ADD CONSTRAINT `tb_reservas_ibfk_1` FOREIGN KEY (`id_tutoria`) REFERENCES `tb_tutorias` (`id_tutoria`),
  ADD CONSTRAINT `tb_reservas_ibfk_2` FOREIGN KEY (`id_estudiante`) REFERENCES `tb_usuarios` (`id_usuario`);

--
-- Constraints for table `tb_tutorias`
--
ALTER TABLE `tb_tutorias`
  ADD CONSTRAINT `tb_tutorias_ibfk_1` FOREIGN KEY (`id_profesor`) REFERENCES `tb_usuarios` (`id_usuario`);

--
-- Constraints for table `tb_usuarios`
--
ALTER TABLE `tb_usuarios`
  ADD CONSTRAINT `tb_usuarios_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `tb_rol` (`id_rol`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
