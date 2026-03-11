-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 09, 2026 at 02:38 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

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
-- Table structure for table `tb_materias`
--

CREATE TABLE `tb_materias` (
  `id_materia` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `estado` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_materias`
--

INSERT INTO `tb_materias` (`id_materia`, `nombre`, `descripcion`, `estado`) VALUES
(1, 'Español', 'lenguaje', 1),
(2, 'Ingles', 'Lenguaje ext', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tb_profesor_materia`
--

CREATE TABLE `tb_profesor_materia` (
  `id_profesor_materia` int(11) NOT NULL,
  `id_profesor` int(11) NOT NULL,
  `id_materia` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_profesor_materia`
--

INSERT INTO `tb_profesor_materia` (`id_profesor_materia`, `id_profesor`, `id_materia`) VALUES
(1, 3, 1),
(2, 7, 1),
(3, 7, 2);

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
(17, 10, 6, 'activa', '2026-03-08 21:13:43');

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
  `id_materia` int(11) DEFAULT NULL,
  `fecha` date NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  `cupos` int(11) NOT NULL,
  `estado` enum('disponible','reservada','cancelada','finalizada') DEFAULT 'disponible',
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_tutorias`
--

INSERT INTO `tb_tutorias` (`id_tutoria`, `id_profesor`, `id_materia`, `fecha`, `hora_inicio`, `hora_fin`, `cupos`, `estado`, `fecha_creacion`) VALUES
(9, 3, 1, '2026-03-11', '16:08:00', '18:08:00', 4, 'disponible', '2026-03-08 21:09:05'),
(10, 7, 2, '2026-03-17', '07:12:00', '09:15:00', 3, 'disponible', '2026-03-08 21:11:32'),
(11, 7, 1, '2026-03-25', '17:11:00', '18:13:00', 4, 'disponible', '2026-03-08 21:12:11');

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
-- Indexes for table `tb_materias`
--
ALTER TABLE `tb_materias`
  ADD PRIMARY KEY (`id_materia`);

--
-- Indexes for table `tb_profesor_materia`
--
ALTER TABLE `tb_profesor_materia`
  ADD PRIMARY KEY (`id_profesor_materia`),
  ADD UNIQUE KEY `id_profesor` (`id_profesor`,`id_materia`),
  ADD UNIQUE KEY `id_profesor_2` (`id_profesor`,`id_materia`),
  ADD UNIQUE KEY `unique_profesor_materia` (`id_profesor`,`id_materia`),
  ADD KEY `id_materia` (`id_materia`);

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
-- AUTO_INCREMENT for table `tb_materias`
--
ALTER TABLE `tb_materias`
  MODIFY `id_materia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tb_profesor_materia`
--
ALTER TABLE `tb_profesor_materia`
  MODIFY `id_profesor_materia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tb_reservas`
--
ALTER TABLE `tb_reservas`
  MODIFY `id_reserva` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `tb_rol`
--
ALTER TABLE `tb_rol`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tb_tutorias`
--
ALTER TABLE `tb_tutorias`
  MODIFY `id_tutoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tb_usuarios`
--
ALTER TABLE `tb_usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_profesor_materia`
--
ALTER TABLE `tb_profesor_materia`
  ADD CONSTRAINT `tb_profesor_materia_ibfk_1` FOREIGN KEY (`id_profesor`) REFERENCES `tb_usuarios` (`id_usuario`),
  ADD CONSTRAINT `tb_profesor_materia_ibfk_2` FOREIGN KEY (`id_materia`) REFERENCES `tb_materias` (`id_materia`);

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
