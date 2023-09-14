-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Sep 14, 2023 at 05:07 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hotelarenasspa`
--

-- --------------------------------------------------------

--
-- Table structure for table `acompanantes`
--

CREATE TABLE `acompanantes` (
  `id_acompanante` int(11) NOT NULL,
  `nro_registro_maestro` varchar(15) DEFAULT NULL,
  `tipo_de_servicio` varchar(10) DEFAULT NULL,
  `nro_de_orden_unico` int(11) DEFAULT NULL,
  `nro_documento` varchar(15) DEFAULT NULL,
  `nro_habitacion` varchar(3) DEFAULT NULL,
  `apellidos_y_nombres` varchar(50) DEFAULT NULL,
  `sexo` varchar(2) DEFAULT NULL,
  `edad` int(11) DEFAULT NULL,
  `parentesco` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `acompanantes`
--

INSERT INTO `acompanantes` (`id_acompanante`, `nro_registro_maestro`, `tipo_de_servicio`, `nro_de_orden_unico`, `nro_documento`, `nro_habitacion`, `apellidos_y_nombres`, `sexo`, `edad`, `parentesco`) VALUES
(58, 'SP-2023000015', 'SPA', 0, '789456', NULL, 'Soza, Monica', 'M', 99, 'Abuelo/a'),
(59, 'SP-2023000015', 'SPA', 1, NULL, NULL, 'Lipa, Abraham', 'M', 25, 'Nieto/a'),
(60, 'SP-2023000016', 'SPA', 1, NULL, NULL, 'Acom, Pañante', 'F', 26, 'Hijo/a'),
(61, 'SP-2023000016', 'SPA', 0, '89234929', NULL, 'Lipa, Abraham', 'M', 25, 'Hijo/a'),
(62, 'SP-2023000017', 'SPA', 1, NULL, NULL, 'Lipa, Abraham', 'M', 25, 'Abuelo/a'),
(63, 'SP-2023000017', 'SPA', 0, '10498126', NULL, 'CHAVEZ, ORMEÑO, JOSE', 'M', 52, 'Abuelo/a'),
(64, 'SP-2023000031', 'SPA', 0, '8947912', NULL, 'Poawiaw, iawaiow', 'M', 24, 'Abuelo/a'),
(65, 'SP-2023000032', 'SPA', 0, '40736563', NULL, 'GONZALEZ RONCAL, JONATHAN', 'M', 42, 'Padre/Madre'),
(66, 'SP-2023000032', 'SPA', 0, '00498126', NULL, 'CHAVEZ ORMEÑO, JOSÉ', 'M', 52, 'Padre/Madre'),
(67, 'SP-2023000032', 'SPA', 1, NULL, NULL, 'Maldonado, Carlos', 'M', 24, 'Hermano/a'),
(68, 'SP-2023000032', 'SPA', 1, NULL, NULL, 'Lipa, Abraham', 'M', 25, 'Sobrino/a'),
(69, 'SP-2023000032', 'SPA', 0, '10498126', NULL, 'CHAVEZ, ORMEÑO, JOSE', 'M', 52, 'Tío/a'),
(70, 'SP-2023000033', 'SPA', 0, '76368626', NULL, 'Lipa Calabilla, Abraham', 'M', 25, 'Abuelo/a'),
(71, 'SP-2023000033', 'SPA', 1, NULL, NULL, 'acom, panante', 'M', 24, 'Abuelo/a'),
(72, 'SP-2023000034', 'SPA', 0, '76368626', NULL, 'Lipa Calabilla, Abraham', 'M', 25, NULL),
(73, 'SP-2023000035', 'SPA', 0, '76368626', NULL, 'Lipa Calabilla, Abraham', 'M', 25, NULL),
(74, 'SP-2023000035', 'SPA', 1, NULL, NULL, 'Panante, Acom', 'F', 23, 'Hijo/a'),
(82, 'SP-2023000036', 'SPA', 0, '00498126', NULL, 'CHAVEZ ORMEÑO, JOSÉ', 'M', 52, NULL),
(112, 'SP-2023000037', 'SPA', 0, '00498126', NULL, 'CHAVEZ ORMEÑO, JOSÉ', 'M', 52, NULL),
(131, 'SP-2023000038', 'SPA', 0, '00492643', NULL, 'Lipa Cala, A', 'M', 25, NULL),
(132, 'SP-2023000039', 'SPA', 0, '00492643', NULL, 'Lipa Cala, A', 'M', 25, NULL),
(190, 'HT23000043', 'HOTEL', 0, '03457100', '201', 'AGUILAR SOTO, CARLOS EDUARDO', 'M', 23, ''),
(191, 'HT23000043', 'HOTEL', 1, '', '201', 'GARCES DALTO, MARIA', 'F', 24, 'Esposo/a'),
(192, 'HT23000043', 'HOTEL', 2, '', '201', 'AGUILAR GARCES, FRANCISCO', 'M', 12, 'Hijo/a'),
(193, 'HT23000043', 'HOTEL', 3, '', '201', 'AGUILAR GARCES, ALICIA', 'F', 9, 'Hijo/a'),
(195, 'HT23000002', 'HOTEL', 0, NULL, '205', 'CABALLERO GARCIA, JOSE ROGELIO', 'M', 26, ''),
(196, 'HT23000002', 'HOTEL', 1, NULL, '205', 'GARCIA LAZARO, ROSA ROXANA', 'F', 24, 'Esposo/a'),
(234, 'HT23000003', 'HOTEL', 0, '59392514', '204', 'LIBERTAD FRANCH, MARTIN LUIS', 'M', 45, ''),
(235, 'HT23000003', 'HOTEL', 1, '', '204', 'NEVER AUGEN, LARASON RUBI', 'F', 34, 'Esposo/a'),
(236, 'HT23000003', 'HOTEL', 1, '', '204', 'NEVER AUGEN, LARASON RUBI', 'F', 34, 'Esposo/a'),
(237, 'HT23000003', 'HOTEL', 0, '59392514', '204', 'LIBERTAD FRANCH, MARTIN LUIS', 'M', 45, ''),
(238, 'HT23000003', 'HOTEL', 0, '59392514', '204', 'LIBERTAD FRANCH, MARTIN LUIS', 'M', 45, ''),
(239, 'HT23000003', 'HOTEL', 0, '', '204', 'LIBERTAD FRANCH, MARTIN LUIS', 'M', 45, ''),
(240, 'HT23000003', 'HOTEL', 1, '', '204', 'NEVER AUGEN, LARASON RUBI', 'F', 34, 'Esposo/a'),
(241, 'HT23000003', 'HOTEL', 1, '', '204', 'NEVER AUGEN, LARASON RUBI', 'F', 34, 'Esposo/a'),
(242, 'HT23000003', 'HOTEL', 0, NULL, '204', 'LIBERTAD FRANCH, MARTIN LUIS', 'M', 45, ''),
(243, 'HT23000003', 'HOTEL', 1, NULL, '204', 'NEVER AUGEN, LARASON RUBI', 'F', 34, 'Esposo/a'),
(244, 'HT23000003', 'HOTEL', 1, NULL, '204', 'NEVER AUGEN, LARASON RUBI', 'F', 34, 'Esposo/a'),
(245, 'HT23000003', 'HOTEL', 0, '59392514', '204', 'LIBERTAD FRANCH, MARTIN LUIS', 'M', 45, ''),
(246, 'HT23000003', 'HOTEL', 1, '', '204', 'NEVER AUGEN, LARASON RUBI', 'F', 34, 'Esposo/a'),
(247, 'HT23000003', 'HOTEL', 1, '', '204', 'NEVER AUGEN, LARASON RUBI', 'F', 34, 'Esposo/a'),
(248, 'HT23000003', 'HOTEL', 1, '', '204', 'NEVER AUGEN, LARASON RUBI', 'F', 34, 'Esposo/a'),
(249, 'HT23000003', 'HOTEL', 0, '59392514', '204', 'LIBERTAD FRANCH, MARTIN LUIS', 'M', 45, ''),
(250, 'HT23000003', 'HOTEL', 1, '', '204', 'NEVER AUGEN, LARASON RUBI', 'F', 34, 'Esposo/a'),
(251, 'HT23000003', 'HOTEL', 1, '', '204', 'NEVER AUGEN, LARASON RUBI', 'F', 34, 'Esposo/a'),
(252, 'HT23000003', 'HOTEL', 0, '59392514', '204', 'LIBERTAD FRANCH, MARTIN LUIS', 'M', 45, ''),
(253, 'HT23000003', 'HOTEL', 1, '', '204', 'NEVER AUGEN, LARASON RUBI', 'F', 34, 'Esposo/a'),
(254, 'HT23000003', 'HOTEL', 1, '', '204', 'NEVER AUGEN, LARASON RUBI', 'F', 34, 'Esposo/a'),
(255, 'HT23000003', 'HOTEL', 0, '59392514', '204', 'LIBERTAD FRANCH, MARTIN LUIS', 'M', 45, ''),
(256, 'HT23000003', 'HOTEL', 1, '', '204', 'NEVER AUGEN, LARASON RUBI', 'F', 34, 'Esposo/a'),
(257, 'HT23000003', 'HOTEL', 1, '', '204', 'NEVER AUGEN, LARASON RUBI', 'F', 34, 'Esposo/a'),
(258, 'HT23000003', 'HOTEL', 0, '59392514', '204', 'LIBERTAD FRANCH, MARTIN LUIS', 'M', 45, ''),
(259, 'HT23000003', 'HOTEL', 1, '', '204', 'NEVER AUGEN, LARASON RUBI', 'F', 34, 'Esposo/a'),
(260, 'HT23000003', 'HOTEL', 0, '59392514', '204', 'LIBERTAD FRANCH, MARTIN LUIS', 'M', 45, ''),
(261, 'HT23000003', 'HOTEL', 1, '', '204', 'NEVER AUGEN, LARASON RUBI', 'F', 34, 'Esposo/a'),
(262, 'HT23000028', 'HOTEL', 0, NULL, '204', 'FERNANDEZ IBAÑEZ, ANGELO VLADIMIR', 'M', 45, ''),
(263, 'HT23000028', 'HOTEL', 1, NULL, '204', 'CASTILLO MENDEZ, ALICIA ', 'F', 34, 'Esposo/a'),
(264, 'HT23000029', 'HOTEL', 0, '78456333', '205', 'YUFRA MARTINEZ, JEFFERSON ANDRE', 'M', 56, ''),
(265, 'HT23000029', 'HOTEL', 1, '', '205', 'AGUILERA SOTILL, MARIA CIELO', 'F', 24, 'Esposo/a'),
(266, 'HT23000003', 'HOTEL', 0, '59392514', '204', 'LIBERTAD FRANCH, MARTIN LUIS', 'M', 45, ''),
(267, 'HT23000003', 'HOTEL', 1, '', '204', 'NEVER AUGEN, LARASON RUBI', 'F', 34, 'Esposo/a'),
(268, 'HT23000003', 'HOTEL', 0, '59391234', '204', 'LIBERTAD FRANCH, MARTIN LUIS', 'M', 45, ''),
(269, 'HT23000003', 'HOTEL', 1, '', '204', 'NEVER AUGEN, LARASON RUBI', 'F', 34, 'Esposo/a'),
(270, 'HT23000003', 'HOTEL', 0, '5213454', '204', 'LIBERTAD FRANCH, MARTIN LUIS', 'M', 45, ''),
(271, 'HT23000003', 'HOTEL', 1, '', '204', 'NEVER AUGEN, LARASON RUBI', 'F', 34, 'Esposo/a'),
(272, 'HT23000033', 'HOTEL', 0, '87542123', '202', 'MOLINA FIGUEROA, ROBERTO SOLANO', 'M', 45, ''),
(273, 'HT23000033', 'HOTEL', 1, '', '202', 'FERNANDEZ  GUILLE, ALONDRA', 'F', 25, 'Esposo/a'),
(274, 'HT23000003', 'HOTEL', 0, '5213454', '204', 'LIBERTAD FRANCH, MARTIN LUIS', 'M', 45, ''),
(275, 'HT23000003', 'HOTEL', 1, '', '204', 'NEVER AUGEN, LARASON RUBI', 'F', 34, 'Esposo/a'),
(276, 'HT23000003', 'HOTEL', 0, '5213454', '204', 'LIBERTAD FRANCH, MARTIN LUIS', 'M', 45, ''),
(277, 'HT23000003', 'HOTEL', 1, '', '204', 'NEVER AUGEN, LARASON RUBI', 'F', 34, 'Esposo/a'),
(278, 'HT23000003', 'HOTEL', 0, '5213454', '204', 'LIBERTAD FRANCH, MARTIN LUIS', 'M', 45, ''),
(279, 'HT23000003', 'HOTEL', 1, '', '204', 'NEVER AUGEN, LARASON RUBI', 'F', 34, 'Esposo/a'),
(280, 'SP-2023000040', 'SPA', 0, '48975135', NULL, 'Perso, Na', 'M', 45, NULL),
(281, 'SP-2023000041', 'SPA', 0, '89729392', NULL, 'prue, ba', 'M', 34, NULL),
(282, 'HT23000037', 'HOTEL', 0, '00498126', '201', 'CHAVEZ, ORMEÑO, JOSE', 'M', 52, ''),
(283, 'HT23000037', 'HOTEL', 1, '', '201', 'BARRIO, MEZA', 'F', 0, 'Padre/Madre'),
(284, 'HT23000037', 'HOTEL', 2, '', '201', 'BARRIO, ANTONIO', 'M', 15, 'Hijo/a'),
(285, 'HT23000038', 'HOTEL', 0, '77529743', '203', 'Eduardo, Carlos', 'M', 26, ''),
(286, 'HT23000039', 'HOTEL', 0, '77529743', '202', 'AGUILAR SOTO, CARLOS EDUARDO', 'M', 26, ''),
(287, 'SP-2023000042', 'SPA', 0, '823794', NULL, 'dawdaw, ', 'F', 34, NULL),
(288, 'SP-2023000043', 'SPA', 0, '823794', NULL, 'dawdaw, efefsdfe', 'F', 34, NULL),
(289, 'SP-2023000044', 'SPA', 0, '89239479', NULL, 'joawdo, awdoaw', 'M', 34, NULL),
(290, 'SP-2023000045', 'SPA', 0, '9239723', NULL, 'aaaaaaaaaaaaa. aaaa, ', 'M', 34, NULL),
(291, 'SP-2023000046', 'SPA', 0, '9239723', NULL, 'aaaaaaaaaaaaa, aaaa', 'M', 34, NULL),
(292, 'SP-2023000047', 'SPA', 0, '8289439', NULL, 'asdiojaisd, apsd', 'M', 34, NULL),
(293, 'SP-2023000048', 'SPA', 0, '92374297', NULL, 'oajiod, adkiao', 'M', 34, NULL),
(294, 'SP-2023000049', 'SPA', 0, '92749379', NULL, 'ooooooooo, oomwma', 'M', 34, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `centraldecostos`
--

CREATE TABLE `centraldecostos` (
  `id_central_de_costos` int(11) NOT NULL,
  `nombre_del_costo` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `centraldecostos`
--

INSERT INTO `centraldecostos` (`id_central_de_costos`, `nombre_del_costo`) VALUES
(9, 'Spa'),
(10, 'Hotel'),
(11, 'Bazar'),
(12, 'Cafeteria'),
(13, 'Salón de Belleza'),
(14, 'Administración');

-- --------------------------------------------------------

--
-- Table structure for table `cheking`
--

CREATE TABLE `cheking` (
  `id_checkin` int(11) NOT NULL,
  `id_unidad_de_negocio` int(11) DEFAULT NULL,
  `nro_registro_maestro` varchar(15) DEFAULT NULL,
  `tipo_de_servicio` varchar(10) DEFAULT NULL,
  `nro_reserva` varchar(10) DEFAULT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `id_persona` int(11) DEFAULT NULL,
  `lugar_procedencia` varchar(255) DEFAULT NULL,
  `id_modalidad` int(11) DEFAULT NULL,
  `fecha_in` date DEFAULT NULL,
  `hora_in` time DEFAULT NULL,
  `fecha_out` date DEFAULT NULL,
  `hora_out` time DEFAULT NULL,
  `tipo_transporte` varchar(255) DEFAULT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `observaciones_hospedaje` text DEFAULT NULL,
  `observaciones_pago` text DEFAULT NULL,
  `nro_personas` int(11) DEFAULT NULL,
  `nro_adultos` int(11) DEFAULT NULL,
  `nro_ninos` int(11) DEFAULT NULL,
  `nro_infantes` int(11) DEFAULT NULL,
  `monto_total` decimal(12,2) DEFAULT NULL,
  `estacionamiento` decimal(12,2) DEFAULT NULL,
  `nro_placa` varchar(10) DEFAULT NULL,
  `adelanto` decimal(12,2) DEFAULT NULL,
  `porcentaje_pago` decimal(3,2) DEFAULT NULL,
  `fecha_pago` date DEFAULT NULL,
  `forma_pago` varchar(20) DEFAULT NULL,
  `cerrada` tinyint(1) DEFAULT NULL,
  `fecha_cerrada` date DEFAULT NULL,
  `hora_cerrada` time DEFAULT NULL,
  `tipo_documento` int(1) DEFAULT NULL,
  `nro_documento` varchar(20) DEFAULT NULL,
  `tipo_comprobante` varchar(10) DEFAULT NULL,
  `tipo_documento_comprobante` int(1) DEFAULT NULL,
  `nro_documento_comprobante` varchar(20) DEFAULT NULL,
  `razon_social` varchar(255) NOT NULL,
  `direccion_comprobante` varchar(255) NOT NULL,
  `nro_habitacion` varchar(4) NOT NULL,
  `sexo` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cheking`
--

INSERT INTO `cheking` (`id_checkin`, `id_unidad_de_negocio`, `nro_registro_maestro`, `tipo_de_servicio`, `nro_reserva`, `nombre`, `id_persona`, `lugar_procedencia`, `id_modalidad`, `fecha_in`, `hora_in`, `fecha_out`, `hora_out`, `tipo_transporte`, `telefono`, `observaciones_hospedaje`, `observaciones_pago`, `nro_personas`, `nro_adultos`, `nro_ninos`, `nro_infantes`, `monto_total`, `estacionamiento`, `nro_placa`, `adelanto`, `porcentaje_pago`, `fecha_pago`, `forma_pago`, `cerrada`, `fecha_cerrada`, `hora_cerrada`, `tipo_documento`, `nro_documento`, `tipo_comprobante`, `tipo_documento_comprobante`, `nro_documento_comprobante`, `razon_social`, `direccion_comprobante`, `nro_habitacion`, `sexo`) VALUES
(97, 3, 'SP-2023000035', 'SPA', NULL, 'Lipa Calabilla, Abraham', 39, NULL, NULL, '2023-09-01', '14:06:25', NULL, NULL, NULL, NULL, NULL, NULL, 2, 2, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '76368626', NULL, NULL, NULL, 'LIPA CALABILLA ABRAHAM', 'Avenida', '', ''),
(108, 3, 'SP-2023000036', 'SPA', NULL, 'CHAVEZ ORMEÑO, JOSÉ', 53, NULL, NULL, '2023-09-05', '11:27:27', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '76368626', NULL, NULL, NULL, 'Lipa Calabilla, Abraham', 'Av. Bolognesi 123', '', ''),
(121, 3, 'SP-2023000037', 'SPA', NULL, 'CHAVEZ ORMEÑO, JOSÉ', 53, NULL, NULL, '2023-09-07', '13:36:03', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', ''),
(131, 3, 'SP-2023000038', 'SPA', NULL, 'Lipa Cala, A', 59, NULL, NULL, '2023-09-08', '14:24:00', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', ''),
(132, 3, 'SP-2023000039', 'SPA', NULL, 'Lipa Cala, A', 59, NULL, NULL, '2023-09-08', '14:38:28', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', ''),
(162, 3, 'HT23000043', 'HOTEL', '', 'AGUILAR SOTO CARLOS EDUARDO', 68, 'TACNA', NULL, '2023-09-06', '08:00:00', '2023-09-09', '15:00:00', NULL, '987654321', NULL, NULL, 4, 2, 2, NULL, NULL, 0.00, '000000', NULL, NULL, NULL, 'Full Hotelero', NULL, NULL, NULL, 1, '03457100', 'Factura', 1, '4512239569', 'COORPORACION FINANCIERA', 'calle uruguay 712', '201', 'M'),
(163, 3, 'HT23000002', 'HOTEL', '', 'CABALLERO GARCIA JOSE ROGELIO', 69, 'AREQUIPA', NULL, '2023-09-11', '08:00:00', '2023-09-14', '08:00:00', NULL, '987654321', NULL, NULL, 2, 2, 0, NULL, NULL, 0.00, '000000', NULL, NULL, NULL, 'Contado', NULL, NULL, NULL, 1, '87543698', 'Boleta', 2, '87542362', 'COORPORACION FINANCIERA', 'calle uruguay 712', '205', 'N'),
(188, 3, 'HT23000003', 'HOTEL', '', 'LIBERTAD FRANCH MARTIN LUIS', 71, 'AREQUIPA', NULL, '2023-09-17', '08:00:00', '2023-09-21', '12:00:00', NULL, '987654321', NULL, NULL, 2, 2, 0, NULL, NULL, 0.00, '000000', NULL, NULL, NULL, 'Paypal', NULL, NULL, NULL, 0, '', 'Factura', 1, '875412635', '', 'calle uruguay 712', '204', 'M'),
(189, 3, 'HT23000028', 'HOTEL', '', 'FERNANDEZ IBAÑEZ ANGELO VLADIMIR', 72, 'AREQUIPA', NULL, '2023-09-12', '08:00:00', '2023-09-15', '08:00:00', NULL, '98652314', NULL, NULL, 2, 2, 0, NULL, NULL, 0.00, '000000', NULL, NULL, NULL, 'Paypal', NULL, NULL, NULL, 1, '98563245', 'Factura', 1, '875436254', 'COORPORACION FINANCIERA', 'calle uruguay 712', '204', 'N'),
(190, 3, 'HT23000029', 'HOTEL', '', 'YUFRA MARTINEZ JEFFERSON ANDRE', 73, 'ICA', NULL, '2023-09-15', '08:00:00', '2023-09-19', '12:00:00', NULL, '987654321', NULL, NULL, 2, 2, 0, NULL, NULL, 0.00, '000000', NULL, NULL, NULL, 'Full Hotelero', NULL, NULL, NULL, 1, '78456333', 'Boleta', 1, '3266548956', 'COORPORACION FINANCIERA', 'calle uruguay 712', '205', 'N'),
(191, 3, 'HT23000003', 'HOTEL', '', 'LIBERTAD FRANCH MARTIN LUIS', 71, 'AREQUIPA', NULL, '2023-09-17', '08:00:00', '2023-09-21', '12:00:00', NULL, '987654321', NULL, NULL, 2, 2, 0, NULL, NULL, 0.00, '000000', NULL, NULL, NULL, 'Paypal', NULL, NULL, NULL, 1, '59392514', 'Factura', 1, '875412635', 'COORPORACION FINANCIERA', 'calle uruguay 712', '204', 'M'),
(192, 3, 'HT23000003', 'HOTEL', '', 'LIBERTAD FRANCH MARTIN LUIS', 74, 'AREQUIPA', NULL, '2023-09-17', '08:00:00', '2023-09-21', '12:00:00', NULL, '987654321', NULL, NULL, 2, 2, 0, NULL, NULL, 0.00, '000000', NULL, NULL, NULL, 'Paypal', NULL, NULL, NULL, 1, '59391234', 'Factura', 1, '875412635', 'COORPORACION FINANCIERA', 'calle uruguay 712', '204', 'N'),
(193, 3, 'HT23000003', 'HOTEL', '', 'LIBERTAD FRANCH MARTIN LUIS', 75, 'AREQUIPA', NULL, '2023-09-17', '08:00:00', '2023-09-21', '12:00:00', NULL, '987654321', NULL, NULL, 2, 2, 0, NULL, NULL, 0.00, '000000', NULL, NULL, NULL, 'Paypal', NULL, NULL, NULL, 1, '5213454', 'Factura', 1, '875412635', 'COORPORACION FINANCIERA', 'calle uruguay 712', '204', 'N'),
(194, 3, 'HT23000033', 'HOTEL', '', 'MOLINA FIGUEROA ROBERTO SOLANO', 76, 'CALAMA', NULL, '2023-09-22', '08:00:00', '2023-09-27', '12:00:00', NULL, '987654321', NULL, NULL, 2, 2, 0, NULL, NULL, 0.00, '000000', NULL, NULL, NULL, 'Paypal', NULL, NULL, NULL, 1, '87542123', 'Factura', 1, '20135558', 'COORPORACION FINANCIERA', 'calle uruguay 712', '202', 'N'),
(195, 3, 'HT23000003', 'HOTEL', '', 'LIBERTAD FRANCH MARTIN LUIS', 75, 'AREQUIPA', NULL, '2023-09-17', '08:00:00', '2023-09-21', '12:00:00', NULL, '987654321', NULL, NULL, 2, 2, 0, NULL, NULL, 0.00, '000000', NULL, NULL, NULL, 'Paypal', NULL, NULL, NULL, 1, '5213454', 'Factura', 1, '875412635', 'COORPORACION FINANCIERA', 'calle uruguay 712', '204', 'M'),
(196, 3, 'HT23000003', 'HOTEL', '', 'LIBERTAD FRANCH MARTIN LUIS', 75, 'AREQUIPA', NULL, '2023-09-17', '08:00:00', '2023-09-21', '12:00:00', NULL, '987654321', NULL, NULL, 2, 2, 0, NULL, NULL, 0.00, '000000', NULL, NULL, NULL, 'Paypal', NULL, NULL, NULL, 1, '5213454', 'Factura', 1, '875412635', 'COORPORACION FINANCIERA', 'calle uruguay 712', '204', 'M'),
(197, 3, 'HT23000003', 'HOTEL', '', 'LIBERTAD FRANCH MARTIN LUIS', 75, 'AREQUIPA', NULL, '2023-09-17', '08:00:00', '2023-09-21', '12:00:00', NULL, '987654321', NULL, NULL, 2, 2, 0, NULL, NULL, 0.00, '000000', NULL, NULL, NULL, 'Paypal', NULL, NULL, NULL, 1, '5213454', 'Factura', 1, '875412635', 'COORPORACION FINANCIERA', 'calle uruguay 712', '204', 'M'),
(198, 3, 'SP-2023000040', 'SPA', NULL, 'Perso, Na', 77, NULL, NULL, '2023-09-12', '15:05:55', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '', NULL, NULL, NULL, '', '', '', ''),
(199, 3, 'SP-2023000041', 'SPA', NULL, 'prue, ba', 78, NULL, NULL, '2023-09-12', '17:19:57', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', ''),
(200, 3, 'HT23000037', 'HOTEL', '', 'CHAVEZ, ORMEÑO JOSE', 53, 'TRUJILLO', NULL, '2023-09-12', '10:20:00', '2023-09-19', '12:00:00', NULL, '962075545', NULL, NULL, 3, 2, 1, NULL, NULL, 0.00, 'AH-22', NULL, NULL, NULL, 'Contado', NULL, NULL, NULL, 1, '76368626', 'Factura', 1, '10004981265', 'LIPA CALABILLA ABRAHAM', 'asdasd', '201', 'M'),
(201, 3, 'HT23000038', 'HOTEL', '', 'Eduardo Carlos', 55, 'TACNA', NULL, '2023-09-13', '08:00:00', '2023-09-15', '00:00:00', NULL, '987654321', NULL, NULL, 1, 1, 0, NULL, NULL, 0.00, '', NULL, NULL, NULL, 'Ninguno', NULL, NULL, NULL, 0, '77529743', 'Ninguno', 0, '', '', '', '203', 'M'),
(202, 3, 'HT23000039', 'HOTEL', '', 'AGUILAR SOTO CARLOS EDUARDO', 55, 'TACNA', NULL, '2023-09-14', '08:00:00', '2023-09-17', '00:00:00', NULL, '912536515', NULL, NULL, 1, 1, 0, NULL, NULL, 0.00, '', NULL, NULL, NULL, 'Ninguno', NULL, NULL, NULL, 1, '76368626', 'Ninguno', 0, '', 'LIPA CALABILLA ABRAHAM', 'ojaiwjdiao', '202', 'M'),
(203, 3, 'SP-2023000042', 'SPA', NULL, 'dawdaw, ', 80, NULL, NULL, '2023-09-13', '12:23:06', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', ''),
(204, 3, 'SP-2023000043', 'SPA', NULL, 'dawdaw, efefsdfe', 81, NULL, NULL, '2023-09-13', '12:23:59', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '', NULL, NULL, NULL, '', '', '', ''),
(205, 3, 'SP-2023000044', 'SPA', NULL, 'joawdo, awdoaw', 82, NULL, NULL, '2023-09-13', '15:11:55', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', ''),
(206, 3, 'SP-2023000045', 'SPA', NULL, 'aaaaaaaaaaaaa. aaaa, ', 83, NULL, NULL, '2023-09-13', '15:19:12', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', ''),
(207, 3, 'SP-2023000046', 'SPA', NULL, 'aaaaaaaaaaaaa, aaaa', 84, NULL, NULL, '2023-09-13', '15:19:22', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', ''),
(208, 3, 'SP-2023000047', 'SPA', NULL, 'asdiojaisd, apsd', 85, NULL, NULL, '2023-09-13', '15:21:37', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', ''),
(209, 3, 'SP-2023000048', 'SPA', NULL, 'oajiod, adkiao', 86, NULL, NULL, '2023-09-13', '15:22:37', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', ''),
(210, 3, 'SP-2023000049', 'SPA', NULL, 'ooooooooo, oomwma', 87, NULL, NULL, '2023-09-13', '16:09:39', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '', NULL, NULL, NULL, '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `comprobante_detalle`
--

CREATE TABLE `comprobante_detalle` (
  `id_comprobante_detalle` int(11) NOT NULL,
  `id_documentos_detalle` int(11) DEFAULT NULL,
  `tipo_movimiento` varchar(2) DEFAULT NULL,
  `nro_registro_maestro` varchar(15) DEFAULT NULL,
  `id_comprobante_ventas` int(11) DEFAULT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `cantidad` decimal(12,4) DEFAULT NULL,
  `tipo_de_unidad` varchar(4) DEFAULT NULL,
  `precio_unitario` decimal(12,2) DEFAULT NULL,
  `precio_total` decimal(12,2) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `fecha_hora_registro` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comprobante_detalle`
--

INSERT INTO `comprobante_detalle` (`id_comprobante_detalle`, `id_documentos_detalle`, `tipo_movimiento`, `nro_registro_maestro`, `id_comprobante_ventas`, `id_producto`, `cantidad`, `tipo_de_unidad`, `precio_unitario`, `precio_total`, `id_usuario`, `fecha_hora_registro`) VALUES
(96, 398, 'SA', 'SP-2023000049', 62, 95, 1.0000, 'UNID', 20.00, 20.00, 12, '2023-09-13 21:10:11'),
(97, 399, 'SA', 'SP-2023000049', 62, 95, 1.0000, 'UNID', 20.00, 20.00, 12, '2023-09-13 21:10:11'),
(98, 400, 'SA', 'SP-2023000049', 62, 95, 1.0000, 'UNID', 20.00, 20.00, 12, '2023-09-13 21:11:22'),
(99, 401, 'SA', 'SP-2023000049', 63, 78, 1.0000, NULL, 90.00, 90.00, 12, '2023-09-13 22:11:56');

-- --------------------------------------------------------

--
-- Table structure for table `comprobante_ventas`
--

CREATE TABLE `comprobante_ventas` (
  `id_comprobante_ventas` int(11) NOT NULL,
  `id_unidad_de_negocio` int(11) DEFAULT NULL,
  `tipo_movimiento` varchar(2) DEFAULT NULL,
  `tipo_comprobante` varchar(2) DEFAULT NULL,
  `nro_comprobante` varchar(15) DEFAULT NULL,
  `nro_registro_maestro` varchar(15) DEFAULT NULL,
  `tipo_documento_cliente` varchar(2) DEFAULT NULL,
  `nro_documento_cliente` varchar(15) DEFAULT NULL,
  `direccion_cliente` varchar(100) DEFAULT NULL,
  `fecha_documento` date DEFAULT NULL,
  `hora_documento` varchar(8) DEFAULT NULL,
  `subtotal` decimal(12,2) DEFAULT NULL,
  `igv` decimal(12,2) DEFAULT NULL,
  `porcentaje_igv` decimal(6,2) DEFAULT NULL,
  `total` decimal(12,2) DEFAULT NULL,
  `forma_de_pago` varchar(2) DEFAULT NULL,
  `monto_inicial` decimal(12,2) DEFAULT NULL,
  `fecha_de_pago_credito` date DEFAULT NULL,
  `monto_credito` decimal(12,2) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `fecha_hora_registro` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comprobante_ventas`
--

INSERT INTO `comprobante_ventas` (`id_comprobante_ventas`, `id_unidad_de_negocio`, `tipo_movimiento`, `tipo_comprobante`, `nro_comprobante`, `nro_registro_maestro`, `tipo_documento_cliente`, `nro_documento_cliente`, `direccion_cliente`, `fecha_documento`, `hora_documento`, `subtotal`, `igv`, `porcentaje_igv`, `total`, `forma_de_pago`, `monto_inicial`, `fecha_de_pago_credito`, `monto_credito`, `id_usuario`, `fecha_hora_registro`) VALUES
(4, 3, 'SA', '03', 'B001-00000004', 'SP-2023000038', '1', '00492643', 'Avenida', '2023-09-08', '14:30:59', 340.00, 34.00, 0.10, 374.00, NULL, 0.00, NULL, 374.00, 12, '2023-09-08 19:30:59'),
(5, 3, 'SA', '03', 'B001-00000005', 'SP-2023000039', '1', '00492643', 'avenida', '2023-09-08', '14:42:16', 80.00, 8.00, 0.10, 88.00, NULL, 0.00, NULL, 0.00, 12, '2023-09-08 20:45:59'),
(6, 3, 'SA', '01', 'F001-00000002', 'SP-2023000039', '6', '76368626', 'Avenida Algo', '2023-09-08', '15:29:23', 200.00, 20.00, 0.10, 220.00, NULL, 0.00, NULL, 0.00, 12, '2023-09-08 20:56:35'),
(7, 3, 'SA', '01', 'F001-00000003', 'SP-2023000039', '6', '', 'Avenidaaa', '2023-09-08', '16:29:17', 2000.00, 200.00, 0.10, 2200.00, NULL, 0.00, NULL, 2200.00, 12, '2023-09-08 21:29:17'),
(14, 3, 'SA', '03', 'B001-00000006', 'SP-2023000036', '1', '76368626', 'Av. Bolognesi 123', '2023-09-12', '10:57:29', 74.00, 7.40, 0.10, 81.40, NULL, 0.00, NULL, 81.40, 12, '2023-09-12 15:57:29'),
(15, 3, 'SA', '03', 'B001-00000007', 'SP-2023000036', '1', '76368626', 'Av. Bolognesi 123', '2023-09-12', '10:59:23', 74.00, 7.40, 0.10, 81.40, NULL, 0.00, NULL, 81.40, 12, '2023-09-12 15:59:23'),
(16, 3, 'SA', '03', 'B001-00000008', 'SP-2023000036', '1', '76368626', 'Av. Bolognesi 123', '2023-09-12', '11:00:02', 74.00, 7.40, 0.10, 81.40, NULL, 0.00, NULL, 0.00, 12, '2023-09-12 17:07:49'),
(19, 3, 'SA', '03', 'B001-00000009', 'SP-2023000035', '1', '76368626', 'Avenida', '2023-09-12', '11:10:06', 190.00, 19.00, 0.10, 209.00, NULL, 0.00, NULL, 209.00, 12, '2023-09-12 16:10:06'),
(21, 3, 'SA', '01', 'F001-00000004', 'HT23000037', '6', '20609799448', 'PJ. VIGIL NRO S/N OTR. CERCADO ', '2023-09-13', '11:42:47', 20.00, 2.00, 0.10, 22.00, NULL, 0.00, NULL, 0.00, 16, '2023-09-13 16:43:29'),
(43, 3, 'SA', '03', 'B001-00000010', 'SP-2023000036', '1', '76368626', 'Av. Bolognesi 123', '2023-09-13', '12:02:33', 74.00, 7.40, 0.10, 81.40, NULL, 0.00, NULL, 81.40, 12, '2023-09-13 17:02:33'),
(44, 3, 'SA', '03', 'B001-00000011', 'SP-2023000036', '1', '76368626', 'Av. Bolognesi 123', '2023-09-13', '12:04:47', 74.00, 7.40, 0.10, 81.40, NULL, 0.00, NULL, 81.40, 12, '2023-09-13 17:04:47'),
(47, 3, 'SA', '00', 'PD000-00000001', 'HT23000037', '0', '', '', '2023-09-13', '12:15:41', 600.00, 60.00, 0.10, 660.00, NULL, 0.00, NULL, 660.00, 22, '2023-09-13 17:15:41'),
(48, 3, 'SA', '00', 'PD000-00000001', 'HT23000037', '0', '', '', '2023-09-13', '12:18:02', 600.00, 60.00, 0.10, 660.00, NULL, 0.00, NULL, 660.00, 22, '2023-09-13 17:18:02'),
(49, 3, 'SA', '00', 'PD000-00000001', 'SP-2023000043', '0', '', '', '2023-09-13', '12:24:33', 90.00, 9.00, 0.10, 99.00, NULL, 0.00, NULL, 0.00, 12, '2023-09-13 17:25:07'),
(50, 3, 'SA', '00', 'PD000-00000001', 'HT23000039', '0', '', '', '2023-09-13', '12:31:14', 170.00, 17.00, 0.10, 187.00, NULL, 0.00, NULL, 0.00, 12, '2023-09-13 17:32:24'),
(51, 3, 'SA', '03', 'B001-00000012', 'HT23000037', '1', '00498126', '', '2023-09-13', '12:35:03', 300.00, 30.00, 0.10, 330.00, NULL, 0.00, NULL, 330.00, 22, '2023-09-13 17:35:03'),
(52, 3, 'SA', '03', 'B001-00000013', 'HT23000037', '1', '00498126', '', '2023-09-13', '12:36:28', 600.00, 60.00, 0.10, 660.00, NULL, 0.00, NULL, 660.00, 22, '2023-09-13 17:36:28'),
(53, 3, 'SA', '03', 'B001-00000014', 'HT23000037', '1', '76368626', 'asdasd', '2023-09-13', '12:37:44', 300.00, 30.00, 0.10, 330.00, NULL, 0.00, NULL, 0.00, 12, '2023-09-13 17:39:27'),
(54, 3, 'SA', '00', 'PD000-00000001', 'HT23000039', '0', '', 'calle uruguay 712', '2023-09-13', '12:40:05', 170.00, 17.00, 0.10, 187.00, NULL, 0.00, NULL, 187.00, 12, '2023-09-13 17:40:05'),
(55, 3, 'SA', '03', 'B001-00000015', 'HT23000039', '1', '76368626', 'asdasd', '2023-09-13', '12:40:59', 170.00, 17.00, 0.10, 187.00, NULL, 0.00, NULL, 187.00, 12, '2023-09-13 17:40:59'),
(56, 3, 'SA', '03', 'B001-00000016', 'SP-2023000036', '1', '76368626', 'Av. Bolognesi 123', '2023-09-13', '12:43:26', 74.00, 7.40, 0.10, 81.40, NULL, 0.00, NULL, 81.40, 12, '2023-09-13 17:43:26'),
(57, 3, 'SA', '03', 'B001-00000017', 'SP-2023000036', '1', '76368626', 'Av. Bolognesi 123', '2023-09-13', '12:56:33', 74.00, 67.27, 0.10, 141.27, NULL, 0.00, NULL, 141.27, 12, '2023-09-13 17:56:33'),
(58, 3, 'SA', '03', 'B001-00000018', 'SP-2023000036', '1', '76368626', 'Av. Bolognesi 123', '2023-09-13', '14:10:51', 67.27, 6.73, 0.10, 74.00, NULL, 0.00, NULL, 74.00, 12, '2023-09-13 19:10:51'),
(59, 3, 'SA', '00', 'PD000-00000001', 'HT23000039', '0', '', 'calle uruguay 712', '2023-09-13', '15:49:12', 45.45, 4.55, 0.10, 50.00, NULL, 0.00, NULL, 50.00, 12, '2023-09-13 20:49:12'),
(60, 3, 'SA', '00', 'PD000-00000001', 'HT23000039', '1', '76368626', 'ojaiwjdiao', '2023-09-13', '15:49:59', 18.18, 1.82, 0.10, 20.00, NULL, 0.00, NULL, 20.00, 12, '2023-09-13 20:49:59'),
(61, 3, 'SA', '00', 'PD000-00000001', 'SP-2023000049', '0', '', '', '2023-09-13', '16:11:22', 18.18, 1.82, 0.10, 20.00, NULL, 0.00, NULL, 20.00, 12, '2023-09-13 21:11:22'),
(62, 3, 'SA', '00', 'PD000-00000002', 'SP-2023000049', '6', '449845616', 'qwoeiqwoe', '2023-09-13', '16:57:14', 54.55, 5.45, 0.10, 60.00, NULL, 0.00, NULL, 0.00, 12, '2023-09-13 22:09:53'),
(63, 3, 'SA', '00', 'PD000-00000003', 'SP-2023000049', '0', '', '', '2023-09-13', '17:16:20', 81.82, 8.18, 0.10, 90.00, NULL, 0.00, NULL, 89.00, 12, '2023-09-13 22:16:47');

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

CREATE TABLE `config` (
  `id_config` int(11) NOT NULL,
  `codigo` varchar(15) DEFAULT NULL,
  `numero_correlativo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `config`
--

INSERT INTO `config` (`id_config`, `codigo`, `numero_correlativo`) VALUES
(2, 'RE23', 1),
(3, 'MA-2023', 0),
(4, 'SP-2023', 50),
(5, 'CM-2023', 59),
(6, 'PRD', 11),
(7, 'RST', 3),
(8, 'SVH', 5),
(9, 'SRV', 5),
(10, 'PAQ', 8),
(11, 'HT23', 39),
(12, 'GRUPOS', 10),
(13, 'SERIE B', 1),
(14, 'SERIE F', 1),
(15, 'CORR B', 19),
(16, 'CORR F', 5),
(17, 'IGV', 10),
(18, 'RECIBOS SERIE', 1),
(19, 'RECIBOS CORR', 23),
(20, 'PEDIDOS', 4);

-- --------------------------------------------------------

--
-- Table structure for table `documento_detalle`
--

CREATE TABLE `documento_detalle` (
  `id_documentos_detalle` int(11) NOT NULL,
  `tipo_movimiento` varchar(2) DEFAULT NULL,
  `nro_registro_maestro` varchar(15) DEFAULT NULL,
  `id_documento_movimiento` int(11) DEFAULT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  `id_producto` int(11) DEFAULT NULL,
  `nivel_descargo` int(11) DEFAULT NULL,
  `nro_habitacion` varchar(10) DEFAULT NULL,
  `cantidad` decimal(12,4) DEFAULT NULL,
  `tipo_de_unidad` varchar(4) DEFAULT NULL,
  `precio_unitario` decimal(12,2) DEFAULT NULL,
  `precio_total` decimal(12,2) DEFAULT NULL,
  `id_acompanate` int(11) DEFAULT NULL,
  `id_profesional` int(11) DEFAULT NULL,
  `fecha_servicio` date DEFAULT NULL,
  `hora_servicio` varchar(8) DEFAULT NULL,
  `fecha_termino` date DEFAULT NULL,
  `hora_termino` varchar(8) DEFAULT NULL,
  `nro_comprobante` varchar(15) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `id_recibo_de_pago` int(11) DEFAULT NULL,
  `anulado` int(11) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `fecha_hora_registro` datetime DEFAULT NULL,
  `id_item` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `documento_detalle`
--

INSERT INTO `documento_detalle` (`id_documentos_detalle`, `tipo_movimiento`, `nro_registro_maestro`, `id_documento_movimiento`, `fecha`, `id_producto`, `nivel_descargo`, `nro_habitacion`, `cantidad`, `tipo_de_unidad`, `precio_unitario`, `precio_total`, `id_acompanate`, `id_profesional`, `fecha_servicio`, `hora_servicio`, `fecha_termino`, `hora_termino`, `nro_comprobante`, `observaciones`, `id_recibo_de_pago`, `anulado`, `id_usuario`, `fecha_hora_registro`, `id_item`) VALUES
(189, 'SA', 'SP-2023000015', NULL, '2023-08-23 17:55:02', 77, 1, '', 1.0000, 'UNID', 100.00, 100.00, 58, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-08-23 17:55:02', 0),
(190, 'SA', 'SP-2023000015', NULL, '2023-08-23 17:55:02', 78, 1, '', 1.0000, NULL, 200.00, 200.00, 59, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-08-23 17:55:02', 0),
(191, 'SA', 'SP-2023000015', NULL, '2023-08-23 17:55:02', 71, 1, '', 1.0000, 'KILO', 1.00, 1.00, 59, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-08-23 17:55:02', 0),
(192, 'SA', 'SP-2023000015', NULL, '2023-08-23 17:55:02', 77, 1, '', 1.0000, 'UNID', 100.00, 100.00, 58, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-08-23 17:55:02', 0),
(193, 'SA', 'SP-2023000015', NULL, '2023-08-23 17:55:25', 77, 1, '', 1.0000, 'UNID', 100.00, 100.00, 58, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-08-23 17:55:25', 0),
(194, 'SA', 'SP-2023000015', NULL, '2023-08-23 17:55:25', 78, 1, '', 1.0000, NULL, 200.00, 200.00, 59, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-08-23 17:55:25', 0),
(195, 'SA', 'SP-2023000015', NULL, '2023-08-23 17:55:25', 71, 1, '', 1.0000, 'KILO', 1.00, 1.00, 59, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-08-23 17:55:25', 0),
(196, 'SA', 'SP-2023000015', NULL, '2023-08-23 17:55:25', 77, 1, '', 1.0000, 'UNID', 100.00, 100.00, 58, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-08-23 17:55:25', 0),
(197, 'SA', 'SP-2023000015', NULL, '2023-08-23 17:55:35', 77, 1, '', 1.0000, 'UNID', 100.00, 100.00, 58, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-08-23 17:55:35', 0),
(198, 'SA', 'SP-2023000015', NULL, '2023-08-23 17:55:35', 78, 1, '', 1.0000, NULL, 200.00, 200.00, 59, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-08-23 17:55:35', 0),
(199, 'SA', 'SP-2023000015', NULL, '2023-08-23 17:55:35', 71, 1, '', 1.0000, 'KILO', 1.00, 1.00, 59, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-08-23 17:55:35', 0),
(200, 'SA', 'SP-2023000015', NULL, '2023-08-23 17:55:35', 77, 1, '', 1.0000, 'UNID', 100.00, 100.00, 58, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-08-23 17:55:35', 0),
(201, 'SA', 'SP-2023000015', NULL, '2023-08-23 17:58:54', 77, 1, '', 1.0000, 'UNID', 100.00, 100.00, 58, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-08-23 17:58:54', 0),
(202, 'SA', 'SP-2023000015', NULL, '2023-08-23 17:58:54', 78, 1, '', 1.0000, NULL, 200.00, 200.00, 59, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-08-23 17:58:54', 0),
(203, 'SA', 'SP-2023000015', NULL, '2023-08-23 17:58:54', 71, 1, '', 1.0000, 'KILO', 1.00, 1.00, 59, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-08-23 17:58:54', 0),
(204, 'SA', 'SP-2023000015', NULL, '2023-08-23 17:58:54', 77, 1, '', 1.0000, 'UNID', 100.00, 100.00, 58, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-08-23 17:58:54', 0),
(205, 'SA', 'SP-2023000015', 51, '2023-08-23 17:59:28', 77, 1, '', 1.0000, 'UNID', 100.00, 100.00, 58, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-08-23 17:59:28', 0),
(206, 'SA', 'SP-2023000015', 51, '2023-08-23 17:59:28', 78, 1, '', 1.0000, NULL, 200.00, 200.00, 59, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-08-23 17:59:28', 0),
(207, 'SA', 'SP-2023000015', 51, '2023-08-23 17:59:28', 71, 1, '', 1.0000, 'KILO', 1.00, 1.00, 59, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-08-23 17:59:28', 0),
(208, 'SA', 'SP-2023000015', NULL, '2023-08-23 17:59:28', 77, 1, '', 1.0000, 'UNID', 100.00, 100.00, 58, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-08-23 17:59:28', 0),
(209, 'SA', 'SP-2023000015', NULL, '2023-08-23 17:59:28', 78, 1, '', 1.0000, NULL, 200.00, 200.00, 59, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-08-23 17:59:28', 0),
(210, 'SA', 'SP-2023000015', NULL, '2023-08-23 17:59:28', 71, 1, '', 1.0000, 'KILO', 1.00, 1.00, 59, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-08-23 17:59:28', 0),
(211, 'SA', 'SP-2023000015', NULL, '2023-08-23 17:59:28', 77, 3, '', 1.0000, 'UNID', 0.00, 0.00, 59, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-08-23 17:59:28', 206),
(212, 'SA', 'SP-2023000015', NULL, '2023-08-23 17:59:28', 74, 3, '', 2.0000, 'UNID', 0.00, 0.00, 59, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-08-23 17:59:28', 206),
(213, 'SA', 'SP-2023000015', NULL, '2023-08-23 18:01:54', 77, 1, '', 1.0000, 'UNID', 100.00, 100.00, 58, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-08-23 18:01:54', 0),
(214, 'SA', 'SP-2023000015', NULL, '2023-08-23 18:01:54', 78, 1, '', 1.0000, NULL, 200.00, 200.00, 59, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-08-23 18:01:54', 0),
(215, 'SA', 'SP-2023000015', NULL, '2023-08-23 18:01:54', 71, 1, '', 1.0000, 'KILO', 1.00, 1.00, 59, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-08-23 18:01:54', 0),
(216, 'SA', 'SP-2023000015', NULL, '2023-08-23 18:01:54', 77, 1, '', 1.0000, 'UNID', 100.00, 100.00, 58, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-08-23 18:01:54', 0),
(217, 'SA', 'SP-2023000017', NULL, '2023-08-24 10:23:53', 70, 1, '', 1.0000, 'UNID', 0.50, 0.50, 62, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-08-24 10:23:53', 0),
(218, 'SA', 'SP-2023000017', NULL, '2023-08-24 10:23:53', 71, 1, '', 1.0000, 'KILO', 0.30, 0.30, 62, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-08-24 10:23:53', 0),
(219, 'SA', 'SP-2023000017', NULL, '2023-08-24 10:23:53', 70, 1, '', 1.0000, 'UNID', 0.50, 0.50, 62, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-08-24 10:23:53', 0),
(220, 'SA', 'SP-2023000017', NULL, '2023-08-24 10:23:53', 71, 1, '', 1.0000, 'KILO', 0.30, 0.30, 62, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-08-24 10:23:53', 0),
(221, 'SA', 'SP-2023000031', NULL, '2023-08-24 11:30:34', 70, 1, '', 1.0000, 'UNID', 0.50, 0.50, 64, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-08-24 11:30:34', 0),
(222, 'SA', 'SP-2023000031', NULL, '2023-08-24 11:30:54', 77, 1, '', 1.0000, 'UNID', 50.00, 50.00, 64, 16, '2023-08-24', '11:30', NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-08-24 11:30:54', 0),
(223, 'SA', 'SP-2023000031', 54, '2023-08-24 11:30:54', 76, 2, '', 1.0000, 'LITR', 0.00, 0.00, 64, 16, '2023-08-24', '11:30', NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-08-24 11:30:54', 0),
(224, 'SA', 'SP-2023000031', NULL, '2023-08-24 11:31:12', 79, 1, '', 1.0000, NULL, 150.00, 150.00, 64, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-08-24 11:31:12', 0),
(225, 'SA', 'SP-2023000031', NULL, '2023-08-24 11:33:27', 78, 1, '', 6.0000, NULL, 90.00, 540.00, 64, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-08-24 11:33:27', 1),
(226, 'SA', 'SP-2023000031', 56, '2023-08-24 11:33:27', 77, 3, '', 6.0000, 'UNID', 0.00, 0.00, 64, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-08-24 11:33:27', 0),
(227, 'SA', 'SP-2023000031', 56, '2023-08-24 11:33:27', 74, 3, '', 12.0000, 'UNID', 0.00, 0.00, 64, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-08-24 11:33:27', 0),
(228, 'SA', 'SP-2023000031', 56, '2023-08-24 11:33:27', 76, 2, '', 6.0000, 'LITR', 0.00, 0.00, 64, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-08-24 11:33:27', 0),
(229, 'SA', 'SP-2023000031', 56, '2023-08-24 11:33:27', 70, 2, '', 36.0000, 'UNID', 0.00, 0.00, 64, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-08-24 11:33:27', 0),
(230, 'SA', 'SP-2023000031', 56, '2023-08-24 11:33:27', 71, 2, '', 36.0000, 'KILO', 0.00, 0.00, 64, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-08-24 11:33:27', 0),
(231, 'SA', 'SP-2023000031', 56, '2023-08-24 11:33:27', 72, 2, '', 6.0000, 'KILO', 0.00, 0.00, 64, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-08-24 11:33:27', 0),
(232, 'SA', 'SP-2023000031', 56, '2023-08-24 11:33:27', 73, 2, '', 24.0000, 'KILO', 0.00, 0.00, 64, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-08-24 11:33:27', 0),
(233, 'SA', 'SP-2023000032', NULL, '2023-08-25 13:34:24', 74, 1, '', 1.0000, 'UNID', 20.00, 20.00, 65, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 16, '2023-08-25 13:34:24', 0),
(234, 'SA', 'SP-2023000032', NULL, '2023-08-25 13:34:24', 77, 1, '', 1.0000, 'UNID', 50.00, 50.00, 65, 20, '2023-08-25', '15:00', NULL, NULL, NULL, NULL, NULL, NULL, 16, '2023-08-25 13:34:24', 0),
(235, 'SA', 'SP-2023000032', 57, '2023-08-25 13:34:24', 70, 2, '', 3.0000, 'UNID', 0.00, 0.00, 65, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 16, '2023-08-25 13:34:24', 0),
(236, 'SA', 'SP-2023000032', 57, '2023-08-25 13:34:24', 71, 2, '', 3.0000, 'KILO', 0.00, 0.00, 65, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 16, '2023-08-25 13:34:24', 0),
(237, 'SA', 'SP-2023000032', 57, '2023-08-25 13:34:24', 72, 2, '', 0.5000, 'KILO', 0.00, 0.00, 65, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 16, '2023-08-25 13:34:24', 0),
(238, 'SA', 'SP-2023000032', 57, '2023-08-25 13:34:24', 73, 2, '', 2.0000, 'KILO', 0.00, 0.00, 65, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 16, '2023-08-25 13:34:24', 0),
(239, 'SA', 'SP-2023000032', 57, '2023-08-25 13:34:24', 76, 2, '', 1.0000, 'LITR', 0.00, 0.00, 65, 20, '2023-08-25', '15:00', NULL, NULL, NULL, NULL, NULL, NULL, 16, '2023-08-25 13:34:24', 0),
(240, 'SA', 'SP-2023000032', NULL, '2023-08-25 13:34:25', 74, 1, '', 1.0000, 'UNID', 20.00, 20.00, 65, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 16, '2023-08-25 13:34:25', 0),
(241, 'SA', 'SP-2023000032', NULL, '2023-08-25 13:34:25', 77, 1, '', 1.0000, 'UNID', 50.00, 50.00, 65, 20, '2023-08-25', '15:00', NULL, NULL, NULL, NULL, NULL, NULL, 16, '2023-08-25 13:34:25', 0),
(242, 'SA', 'SP-2023000032', 58, '2023-08-25 13:34:25', 70, 2, '', 3.0000, 'UNID', 0.00, 0.00, 65, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 16, '2023-08-25 13:34:25', 0),
(243, 'SA', 'SP-2023000032', 58, '2023-08-25 13:34:25', 71, 2, '', 3.0000, 'KILO', 0.00, 0.00, 65, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 16, '2023-08-25 13:34:25', 0),
(244, 'SA', 'SP-2023000032', 58, '2023-08-25 13:34:25', 72, 2, '', 0.5000, 'KILO', 0.00, 0.00, 65, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 16, '2023-08-25 13:34:25', 0),
(245, 'SA', 'SP-2023000032', 58, '2023-08-25 13:34:25', 73, 2, '', 2.0000, 'KILO', 0.00, 0.00, 65, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 16, '2023-08-25 13:34:25', 0),
(246, 'SA', 'SP-2023000032', 58, '2023-08-25 13:34:25', 76, 2, '', 1.0000, 'LITR', 0.00, 0.00, 65, 20, '2023-08-25', '15:00', NULL, NULL, NULL, NULL, NULL, NULL, 16, '2023-08-25 13:34:25', 0),
(247, 'SA', 'SP-2023000032', NULL, '2023-08-31 10:16:22', 78, 1, '', 1.0000, NULL, 90.00, 90.00, 68, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-08-31 10:16:22', 1),
(248, 'SA', 'SP-2023000032', 59, '2023-08-31 10:16:22', 74, 3, '', 3.0000, 'UNID', 0.00, 0.00, 68, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-08-31 10:16:22', 0),
(249, 'SA', 'SP-2023000032', 59, '2023-08-31 10:16:22', 70, 3, '', 1.0000, 'KILO', 0.00, 0.00, 68, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-08-31 10:16:22', 0),
(250, 'SA', 'SP-2023000032', 59, '2023-08-31 10:16:22', 79, 3, '', 1.0000, '', 0.00, 0.00, 68, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-08-31 10:16:22', 0),
(251, 'SA', 'SP-2023000032', 59, '2023-08-31 10:16:22', 70, 2, '', 9.0000, 'UNID', 0.00, 0.00, 68, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-08-31 10:16:22', 0),
(252, 'SA', 'SP-2023000032', 59, '2023-08-31 10:16:22', 71, 2, '', 9.0000, 'KILO', 0.00, 0.00, 68, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-08-31 10:16:22', 0),
(253, 'SA', 'SP-2023000032', 59, '2023-08-31 10:16:22', 72, 2, '', 1.5000, 'KILO', 0.00, 0.00, 68, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-08-31 10:16:22', 0),
(254, 'SA', 'SP-2023000032', 59, '2023-08-31 10:16:22', 73, 2, '', 6.0000, 'KILO', 0.00, 0.00, 68, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-08-31 10:16:22', 0),
(255, 'SA', 'SP-2023000017', NULL, '2023-09-01 11:44:00', 95, 1, '', 1.0000, 'UNID', 20.00, 20.00, 62, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-09-01 11:44:00', 0),
(256, 'SA', 'SP-2023000017', NULL, '2023-09-01 11:44:32', 79, 1, '', 1.0000, NULL, 190.00, 190.00, 63, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-09-01 11:44:32', 0),
(257, 'SA', 'SP-2023000033', NULL, '2023-09-01 11:58:47', 95, 1, '', 1.0000, 'UNID', 20.00, 20.00, 71, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-09-01 11:58:47', 0),
(258, 'SA', 'SP-2023000033', NULL, '2023-09-01 11:59:18', 78, 1, '', 3.0000, NULL, 90.00, 270.00, 70, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-09-01 11:59:18', 1),
(259, 'SA', 'SP-2023000033', 63, '2023-09-01 11:59:18', 74, 3, '', 9.0000, 'UNID', 0.00, 0.00, 70, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-09-01 11:59:18', 0),
(260, 'SA', 'SP-2023000033', 63, '2023-09-01 11:59:18', 79, 3, '', 3.0000, '', 0.00, 0.00, 70, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-09-01 11:59:18', 0),
(261, 'SA', 'SP-2023000033', 63, '2023-09-01 11:59:18', 70, 2, '', 27.0000, 'UNID', 0.00, 0.00, 70, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-09-01 11:59:18', 0),
(262, 'SA', 'SP-2023000033', 63, '2023-09-01 11:59:18', 71, 2, '', 27.0000, 'KILO', 0.00, 0.00, 70, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-09-01 11:59:18', 0),
(263, 'SA', 'SP-2023000033', 63, '2023-09-01 11:59:18', 72, 2, '', 4.5000, 'KILO', 0.00, 0.00, 70, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-09-01 11:59:18', 0),
(264, 'SA', 'SP-2023000033', 63, '2023-09-01 11:59:18', 73, 2, '', 18.0000, 'KILO', 0.00, 0.00, 70, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-09-01 11:59:18', 0),
(265, 'SA', 'SP-2023000034', NULL, '2023-09-01 12:16:19', 76, 1, '', 1.0000, 'LITR', 10.00, 10.00, 72, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-09-01 12:16:19', 0),
(266, 'SA', 'SP-2023000035', NULL, '2023-09-07 15:43:43', 78, 1, '', 1.0000, NULL, 90.00, 90.00, 74, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-09-07 15:43:43', 1),
(267, 'SA', 'SP-2023000035', 65, '2023-09-01 14:08:23', 74, 3, '', 3.0000, 'UNID', 0.00, 0.00, 74, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-09-01 14:08:23', 0),
(268, 'SA', 'SP-2023000035', 65, '2023-09-01 14:08:23', 79, 3, '', 1.0000, '', 0.00, 0.00, 74, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-09-01 14:08:23', 0),
(269, 'SA', 'SP-2023000035', 65, '2023-09-01 14:08:23', 70, 2, '', 9.0000, 'UNID', 0.00, 0.00, 74, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-09-01 14:08:23', 0),
(270, 'SA', 'SP-2023000035', 65, '2023-09-01 14:08:23', 71, 2, '', 9.0000, 'KILO', 0.00, 0.00, 74, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-09-01 14:08:23', 0),
(271, 'SA', 'SP-2023000035', 65, '2023-09-01 14:08:23', 72, 2, '', 1.5000, 'KILO', 0.00, 0.00, 74, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-09-01 14:08:23', 0),
(272, 'SA', 'SP-2023000035', 65, '2023-09-01 14:08:23', 73, 2, '', 6.0000, 'KILO', 0.00, 0.00, 74, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-09-01 14:08:23', 0),
(273, 'SA', 'SP-2023000035', NULL, '2023-09-12 11:10:06', 79, 1, '', 1.0000, NULL, 190.00, 190.00, 73, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-09-12 11:10:06', 0),
(274, 'SA', 'SP-2023000036', NULL, '2023-09-12 11:00:02', 77, 1, '', 1.0000, 'UNID', 50.00, 50.00, 82, 20, '2023-09-05', '15:00', NULL, NULL, NULL, NULL, 17, NULL, 12, '2023-09-13 14:10:51', 0),
(275, 'SA', 'SP-2023000036', NULL, '2023-09-12 11:00:02', 74, 1, '', 1.0000, 'UNID', 20.00, 20.00, 82, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 17, NULL, 12, '2023-09-13 14:10:51', 0),
(276, 'SA', 'SP-2023000036', NULL, '2023-09-12 11:00:02', 102, 1, '', 1.0000, 'UNID', 4.00, 4.00, 82, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 17, NULL, 12, '2023-09-13 14:10:51', 0),
(277, 'SA', 'SP-2023000036', 67, '2023-09-05 11:29:44', 70, 2, '', 3.0000, 'UNID', 0.00, 0.00, 82, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-09-05 11:29:44', 0),
(278, 'SA', 'SP-2023000036', 67, '2023-09-05 11:29:44', 71, 2, '', 3.0000, 'KILO', 0.00, 0.00, 82, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-09-05 11:29:44', 0),
(279, 'SA', 'SP-2023000036', 67, '2023-09-05 11:29:44', 72, 2, '', 0.5000, 'KILO', 0.00, 0.00, 82, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-09-05 11:29:44', 0),
(280, 'SA', 'SP-2023000036', 67, '2023-09-05 11:29:44', 73, 2, '', 2.0000, 'KILO', 0.00, 0.00, 82, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-09-05 11:29:44', 0),
(281, 'SA', 'SP-2023000036', NULL, '2023-09-07 13:35:02', 78, 1, '', 1.0000, NULL, 90.00, 90.00, 82, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 16, '2023-09-07 13:35:02', 1),
(282, 'SA', 'SP-2023000036', NULL, '2023-09-07 13:35:02', 74, 1, '', 1.0000, 'UNID', 20.00, 20.00, 82, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 16, '2023-09-07 13:35:02', 0),
(283, 'SA', 'SP-2023000036', 68, '2023-09-07 13:35:02', 74, 3, '', 3.0000, 'UNID', 0.00, 0.00, 82, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 16, '2023-09-07 13:35:02', 0),
(284, 'SA', 'SP-2023000036', 68, '2023-09-07 13:35:02', 79, 3, '', 1.0000, '', 0.00, 0.00, 82, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 16, '2023-09-07 13:35:02', 0),
(285, 'SA', 'SP-2023000036', 68, '2023-09-07 13:35:02', 70, 2, '', 3.0000, 'UNID', 0.00, 0.00, 82, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 16, '2023-09-07 13:35:02', 0),
(286, 'SA', 'SP-2023000036', 68, '2023-09-07 13:35:02', 71, 2, '', 3.0000, 'KILO', 0.00, 0.00, 82, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 16, '2023-09-07 13:35:02', 0),
(287, 'SA', 'SP-2023000036', 68, '2023-09-07 13:35:02', 72, 2, '', 0.5000, 'KILO', 0.00, 0.00, 82, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 16, '2023-09-07 13:35:02', 0),
(288, 'SA', 'SP-2023000036', 68, '2023-09-07 13:35:02', 73, 2, '', 2.0000, 'KILO', 0.00, 0.00, 82, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 16, '2023-09-07 13:35:02', 0),
(289, 'SA', 'SP-2023000036', 68, '2023-09-07 13:35:02', 70, 2, '', 9.0000, 'UNID', 0.00, 0.00, 82, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 16, '2023-09-07 13:35:02', 0),
(290, 'SA', 'SP-2023000036', 68, '2023-09-07 13:35:02', 71, 2, '', 9.0000, 'KILO', 0.00, 0.00, 82, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 16, '2023-09-07 13:35:02', 0),
(291, 'SA', 'SP-2023000036', 68, '2023-09-07 13:35:02', 72, 2, '', 1.5000, 'KILO', 0.00, 0.00, 82, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 16, '2023-09-07 13:35:02', 0),
(292, 'SA', 'SP-2023000036', 68, '2023-09-07 13:35:02', 73, 2, '', 6.0000, 'KILO', 0.00, 0.00, 82, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 16, '2023-09-07 13:35:02', 0),
(293, 'SA', 'SP-2023000037', NULL, '2023-09-07 13:36:19', 74, 1, '', 1.0000, 'UNID', 20.00, 20.00, 112, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 16, '2023-09-07 13:36:19', 0),
(294, 'SA', 'SP-2023000037', 69, '2023-09-07 13:36:19', 70, 2, '', 3.0000, 'UNID', 0.00, 0.00, 112, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 16, '2023-09-07 13:36:19', 0),
(295, 'SA', 'SP-2023000037', 69, '2023-09-07 13:36:19', 71, 2, '', 3.0000, 'KILO', 0.00, 0.00, 112, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 16, '2023-09-07 13:36:19', 0),
(296, 'SA', 'SP-2023000037', 69, '2023-09-07 13:36:19', 72, 2, '', 0.5000, 'KILO', 0.00, 0.00, 112, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 16, '2023-09-07 13:36:19', 0),
(297, 'SA', 'SP-2023000037', 69, '2023-09-07 13:36:19', 73, 2, '', 2.0000, 'KILO', 0.00, 0.00, 112, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 16, '2023-09-07 13:36:19', 0),
(298, 'SA', 'SP-2023000038', NULL, '2023-09-08 14:30:59', 95, 1, '', 3.0000, 'UNID', 20.00, 60.00, 131, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-09-08 14:30:59', 0),
(299, 'SA', 'SP-2023000038', NULL, '2023-09-08 14:30:59', 95, 1, '', 4.0000, 'UNID', 20.00, 80.00, 131, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-09-08 14:30:59', 0),
(300, 'SA', 'SP-2023000038', NULL, '2023-09-08 14:30:59', 77, 1, '', 4.0000, 'UNID', 50.00, 200.00, 131, 16, '2023-09-08', '14:25', NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-09-08 14:30:59', 0),
(301, 'SA', 'SP-2023000039', NULL, '2023-09-08 14:42:16', 95, 1, '', 4.0000, 'UNID', 20.00, 80.00, 132, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-09-08 14:42:16', 0),
(302, 'SA', 'SP-2023000039', NULL, '2023-09-08 15:29:23', 95, 1, '', 10.0000, 'UNID', 20.00, 200.00, 132, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-09-08 15:29:23', 0),
(303, 'SA', 'SP-2023000039', NULL, '2023-09-08 16:29:17', 74, 1, '', 100.0000, 'UNID', 20.00, 2000.00, 132, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-09-08 16:29:17', 0),
(304, 'SA', 'SP-2023000039', 71, '2023-09-08 14:39:30', 70, 2, '', 300.0000, 'UNID', 0.00, 0.00, 132, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-09-08 14:39:30', 0),
(305, 'SA', 'SP-2023000039', 71, '2023-09-08 14:39:30', 71, 2, '', 300.0000, 'KILO', 0.00, 0.00, 132, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-09-08 14:39:30', 0),
(306, 'SA', 'SP-2023000039', 71, '2023-09-08 14:39:30', 72, 2, '', 50.0000, 'KILO', 0.00, 0.00, 132, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-09-08 14:39:30', 0),
(307, 'SA', 'SP-2023000039', 71, '2023-09-08 14:39:30', 73, 2, '', 200.0000, 'KILO', 0.00, 0.00, 132, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-09-08 14:39:30', 0),
(316, 'SA', 'SP-2023000039', NULL, '2023-09-11 15:15:53', 95, 1, '', 1.0000, 'UNID', 20.00, 20.00, 132, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-09-11 15:15:53', 0),
(317, 'S', 'HT23000003', NULL, '2023-09-12 21:19:50', 80, 1, '204', 1.0000, 'S', 180.00, 180.00, 252, NULL, '2023-09-17', NULL, NULL, NULL, NULL, NULL, 25, NULL, 1, '2023-09-13 12:13:21', 0),
(318, 'S', 'HT23000003', NULL, '2023-09-12 21:19:50', 80, 1, '204', 1.0000, 'S', 180.00, 180.00, 253, NULL, '2023-09-18', NULL, NULL, NULL, NULL, NULL, 25, NULL, 1, '2023-09-13 12:13:21', 0),
(319, 'S', 'HT23000003', NULL, '2023-09-12 21:19:50', 80, 1, '204', 1.0000, 'S', 180.00, 180.00, 253, NULL, '2023-09-19', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2023-09-12 21:19:50', 0),
(320, 'S', 'HT23000003', NULL, '2023-09-12 21:19:50', 80, 1, '204', 1.0000, 'S', 180.00, 180.00, 245, NULL, '2023-09-20', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2023-09-12 21:19:50', 0),
(321, 'S', 'HT23000003', NULL, '2023-09-12 21:23:20', 80, 1, '204', 1.0000, 'UNID', 180.00, 180.00, 247, NULL, '2023-09-17', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2023-09-12 21:23:20', 0),
(322, 'S', 'HT23000003', NULL, '2023-09-12 21:23:20', 80, 1, '204', 1.0000, 'UNID', 180.00, 180.00, 279, NULL, '2023-09-18', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2023-09-12 21:23:20', 0),
(323, 'S', 'HT23000003', NULL, '2023-09-12 21:23:20', 80, 1, '204', 1.0000, 'UNID', 180.00, 180.00, 257, NULL, '2023-09-19', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2023-09-12 21:23:20', 0),
(324, 'S', 'HT23000003', NULL, '2023-09-12 21:23:20', 80, 1, '204', 1.0000, 'UNID', 180.00, 180.00, 253, NULL, '2023-09-20', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2023-09-12 21:23:20', 0),
(325, 'SA', 'SP-2023000040', NULL, '2023-09-12 15:16:39', 95, 1, NULL, 1.0000, 'UNID', 20.00, 20.00, 280, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 25, NULL, 12, '2023-09-12 15:16:39', 0),
(326, 'SA', 'SP-2023000040', NULL, '2023-09-12 16:31:20', 78, 1, NULL, 1.0000, NULL, 90.00, 90.00, 280, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-09-12 16:31:20', 1),
(327, 'SA', 'SP-2023000040', 76, '2023-09-12 16:31:20', 74, 3, NULL, 3.0000, 'UNID', 0.00, 0.00, 280, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-09-12 16:31:20', 0),
(328, 'SA', 'SP-2023000040', 76, '2023-09-12 16:31:20', 79, 3, NULL, 1.0000, '', 0.00, 0.00, 280, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-09-12 16:31:20', 0),
(329, 'SA', 'SP-2023000040', 76, '2023-09-12 16:31:20', 70, 2, NULL, 9.0000, 'UNID', 0.00, 0.00, 280, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-09-12 16:31:20', 0),
(330, 'SA', 'SP-2023000040', 76, '2023-09-12 16:31:20', 71, 2, NULL, 9.0000, 'KILO', 0.00, 0.00, 280, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-09-12 16:31:20', 0),
(331, 'SA', 'SP-2023000040', 76, '2023-09-12 16:31:20', 72, 2, NULL, 1.5000, 'KILO', 0.00, 0.00, 280, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-09-12 16:31:20', 0),
(332, 'SA', 'SP-2023000040', 76, '2023-09-12 16:31:20', 73, 2, NULL, 6.0000, 'KILO', 0.00, 0.00, 280, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-09-12 16:31:20', 0),
(333, 'SA', 'SP-2023000015', 77, '2023-09-12 17:12:52', 77, 1, NULL, 1.0000, 'UNID', 100.00, 100.00, 58, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-09-12 17:12:52', 0),
(334, 'SA', 'SP-2023000015', 77, '2023-09-12 17:12:52', 78, 1, NULL, 1.0000, NULL, 200.00, 200.00, 59, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-09-12 17:12:52', 0),
(335, 'SA', 'SP-2023000015', 77, '2023-09-12 17:12:52', 71, 1, NULL, 1.0000, 'KILO', 1.00, 1.00, 59, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-09-12 17:12:52', 0),
(336, 'SA', 'SP-2023000015', 77, '2023-09-12 17:12:52', 74, 3, NULL, 3.0000, 'UNID', 0.00, 0.00, 59, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-09-12 17:12:52', 334),
(337, 'SA', 'SP-2023000015', 77, '2023-09-12 17:12:52', 79, 3, NULL, 1.0000, '', 0.00, 0.00, 59, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-09-12 17:12:52', 334),
(338, 'SA', 'SP-2023000015', 77, '2023-09-12 17:12:52', 70, 2, NULL, 9.0000, 'UNID', 0.00, 0.00, 59, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-09-12 17:12:52', 336),
(339, 'SA', 'SP-2023000015', 77, '2023-09-12 17:12:52', 71, 2, NULL, 9.0000, 'KILO', 0.00, 0.00, 59, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-09-12 17:12:52', 336),
(340, 'SA', 'SP-2023000015', 77, '2023-09-12 17:12:52', 72, 2, NULL, 1.5000, 'KILO', 0.00, 0.00, 59, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-09-12 17:12:52', 336),
(341, 'SA', 'SP-2023000015', 77, '2023-09-12 17:12:52', 73, 2, NULL, 6.0000, 'KILO', 0.00, 0.00, 59, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-09-12 17:12:52', 336),
(342, 'SA', 'SP-2023000035', 80, '2023-09-12 17:16:46', 77, 1, NULL, 1.0000, 'UNID', 100.00, 100.00, 58, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-09-12 17:16:46', 0),
(343, 'SA', 'SP-2023000035', 80, '2023-09-12 17:16:46', 78, 1, NULL, 1.0000, NULL, 200.00, 200.00, 59, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-09-12 17:16:46', 0),
(344, 'SA', 'SP-2023000035', 80, '2023-09-12 17:16:46', 71, 1, NULL, 1.0000, 'KILO', 1.00, 1.00, 59, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-09-12 17:16:46', 0),
(345, 'SA', 'SP-2023000035', 80, '2023-09-12 17:16:46', 74, 3, NULL, 3.0000, 'UNID', 0.00, 0.00, 59, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-09-12 17:16:46', 343),
(346, 'SA', 'SP-2023000035', 80, '2023-09-12 17:16:46', 79, 3, NULL, 1.0000, '', 0.00, 0.00, 59, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-09-12 17:16:46', 343),
(347, 'SA', 'SP-2023000035', 80, '2023-09-12 17:16:46', 70, 2, NULL, 9.0000, 'UNID', 0.00, 0.00, 59, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-09-12 17:16:46', 345),
(348, 'SA', 'SP-2023000035', 80, '2023-09-12 17:16:46', 71, 2, NULL, 9.0000, 'KILO', 0.00, 0.00, 59, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-09-12 17:16:46', 345),
(349, 'SA', 'SP-2023000035', 80, '2023-09-12 17:16:46', 72, 2, NULL, 1.5000, 'KILO', 0.00, 0.00, 59, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-09-12 17:16:46', 345),
(350, 'SA', 'SP-2023000035', 80, '2023-09-12 17:16:46', 73, 2, NULL, 6.0000, 'KILO', 0.00, 0.00, 59, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-09-12 17:16:46', 345),
(351, 'SA', 'SP-2023000041', 81, '2023-09-12 17:20:20', 78, 1, NULL, 6.0000, NULL, 90.00, 540.00, 281, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-09-12 17:20:20', 0),
(352, 'SA', 'SP-2023000041', 81, '2023-09-12 17:20:20', 74, 3, NULL, 18.0000, 'UNID', 0.00, 0.00, 281, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-09-12 17:20:20', 351),
(353, 'SA', 'SP-2023000041', 81, '2023-09-12 17:20:20', 79, 3, NULL, 6.0000, '', 0.00, 0.00, 281, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-09-12 17:20:20', 351),
(354, 'SA', 'SP-2023000041', 81, '2023-09-12 17:20:20', 70, 2, NULL, 54.0000, 'UNID', 0.00, 0.00, 281, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-09-12 17:20:20', 352),
(355, 'SA', 'SP-2023000041', 81, '2023-09-12 17:20:20', 71, 2, NULL, 54.0000, 'KILO', 0.00, 0.00, 281, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-09-12 17:20:20', 352),
(356, 'SA', 'SP-2023000041', 81, '2023-09-12 17:20:20', 72, 2, NULL, 9.0000, 'KILO', 0.00, 0.00, 281, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-09-12 17:20:20', 352),
(357, 'SA', 'SP-2023000041', 81, '2023-09-12 17:20:20', 73, 2, NULL, 36.0000, 'KILO', 0.00, 0.00, 281, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-09-12 17:20:20', 352),
(358, 'SA', 'SP-2023000041', 82, '2023-09-12 17:20:49', 102, 1, NULL, 10.0000, 'UNID', 4.00, 40.00, 281, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-09-12 17:20:49', 0),
(359, 'SA', 'SP-2023000041', 83, '2023-09-13 10:05:20', 78, 1, NULL, 1.0000, NULL, 90.00, 90.00, 281, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-09-13 10:05:20', 0),
(360, 'SA', 'SP-2023000041', 83, '2023-09-13 10:05:20', 74, 3, NULL, 3.0000, 'UNID', 0.00, 0.00, 281, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-09-13 10:05:20', 359),
(361, 'SA', 'SP-2023000041', 83, '2023-09-13 10:05:20', 79, 3, NULL, 1.0000, '', 0.00, 0.00, 281, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-09-13 10:05:20', 359),
(362, 'SA', 'SP-2023000041', 83, '2023-09-13 10:05:20', 70, 2, NULL, 9.0000, 'UNID', 0.00, 0.00, 281, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-09-13 10:05:20', 360),
(363, 'SA', 'SP-2023000041', 83, '2023-09-13 10:05:20', 71, 2, NULL, 9.0000, 'KILO', 0.00, 0.00, 281, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-09-13 10:05:20', 360),
(364, 'SA', 'SP-2023000041', 83, '2023-09-13 10:05:20', 72, 2, NULL, 1.5000, 'KILO', 0.00, 0.00, 281, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-09-13 10:05:20', 360),
(365, 'SA', 'SP-2023000041', 83, '2023-09-13 10:05:20', 73, 2, NULL, 6.0000, 'KILO', 0.00, 0.00, 281, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-09-13 10:05:20', 360),
(366, 'SA', 'HT23000037', NULL, '2023-09-13 17:25:07', 79, 1, '201', 1.0000, 'UNID', 300.00, 300.00, NULL, NULL, '2023-09-12', NULL, NULL, NULL, NULL, NULL, 25, NULL, 1, '2023-09-13 12:15:41', 0),
(367, 'SA', 'HT23000037', NULL, '2023-09-13 17:25:07', 79, 1, '201', 1.0000, 'UNID', 300.00, 300.00, 282, NULL, '2023-09-13', NULL, NULL, NULL, NULL, NULL, 25, NULL, 1, '2023-09-13 12:15:41', 0),
(368, 'SA', 'HT23000037', NULL, '2023-09-13 17:25:08', 79, 1, '201', 1.0000, 'UNID', 300.00, 300.00, 284, NULL, '2023-09-14', NULL, NULL, NULL, NULL, NULL, 25, NULL, 1, '2023-09-13 12:18:02', 0),
(369, 'SA', 'HT23000037', NULL, '2023-09-13 17:25:08', 79, 1, '201', 1.0000, 'UNID', 300.00, 300.00, 282, NULL, '2023-09-15', NULL, NULL, NULL, NULL, NULL, 25, NULL, 1, '2023-09-13 12:18:02', 0),
(370, 'SA', 'HT23000037', NULL, '2023-09-13 17:25:08', 79, 1, '201', 1.0000, 'UNID', 300.00, 300.00, 284, NULL, '2023-09-16', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2023-09-13 12:36:28', 0),
(371, 'SA', 'HT23000037', NULL, '2023-09-13 17:25:08', 79, 1, '201', 1.0000, 'UNID', 300.00, 300.00, 284, NULL, '2023-09-17', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2023-09-13 12:36:28', 0),
(372, 'SA', 'HT23000037', NULL, '2023-09-13 17:25:08', 79, 1, '201', 1.0000, 'UNID', 300.00, 300.00, 283, NULL, '2023-09-18', NULL, NULL, NULL, NULL, NULL, 27, NULL, 1, '2023-09-13 12:37:44', 0),
(373, 'SA', 'HT23000037', NULL, '2023-09-13 10:58:30', 95, 1, NULL, 1.0000, 'UNID', 20.00, 20.00, 282, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 21, NULL, 16, '2023-09-13 11:42:47', 0),
(374, 'SA', 'HT23000038', NULL, '2023-09-13 18:13:30', 79, 1, '203', 1.0000, 'UNID', 150.00, 150.00, NULL, NULL, '2023-09-13', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2023-09-13 18:13:30', 0),
(375, 'SA', 'HT23000038', NULL, '2023-09-13 18:13:30', 79, 1, '203', 1.0000, 'UNID', 150.00, 150.00, NULL, NULL, '2023-09-14', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2023-09-13 18:13:30', 0),
(376, 'SA', 'HT23000039', NULL, '2023-09-13 18:16:24', 79, 1, '202', 1.0000, 'UNID', 170.00, 170.00, NULL, NULL, '2023-09-14', NULL, NULL, NULL, NULL, NULL, 25, NULL, 1, '2023-09-13 12:31:14', 0),
(377, 'SA', 'HT23000039', NULL, '2023-09-13 18:16:24', 79, 1, '202', 1.0000, 'UNID', 170.00, 170.00, NULL, NULL, '2023-09-15', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2023-09-13 12:40:05', 0),
(378, 'SA', 'HT23000039', NULL, '2023-09-13 18:16:24', 79, 1, '202', 1.0000, 'UNID', 170.00, 170.00, NULL, NULL, '2023-09-16', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2023-09-13 12:40:59', 0),
(379, 'SA', 'HT23000003', 85, '2023-09-13 11:46:14', 78, 1, NULL, 1.0000, NULL, 90.00, 90.00, 234, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-09-13 11:46:14', 0),
(380, 'SA', 'HT23000003', 85, '2023-09-13 11:46:14', 74, 3, NULL, 3.0000, 'UNID', 0.00, 0.00, 234, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-09-13 11:46:14', 379),
(381, 'SA', 'HT23000003', 85, '2023-09-13 11:46:14', 79, 3, NULL, 1.0000, '', 0.00, 0.00, 234, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-09-13 11:46:14', 379),
(382, 'SA', 'HT23000003', 85, '2023-09-13 11:46:14', 70, 2, NULL, 9.0000, 'UNID', 0.00, 0.00, 234, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-09-13 11:46:14', 380),
(383, 'SA', 'HT23000003', 85, '2023-09-13 11:46:14', 71, 2, NULL, 9.0000, 'KILO', 0.00, 0.00, 234, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-09-13 11:46:14', 380),
(384, 'SA', 'HT23000003', 85, '2023-09-13 11:46:14', 72, 2, NULL, 1.5000, 'KILO', 0.00, 0.00, 234, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-09-13 11:46:14', 380),
(385, 'SA', 'HT23000003', 85, '2023-09-13 11:46:14', 73, 2, NULL, 6.0000, 'KILO', 0.00, 0.00, 234, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-09-13 11:46:14', 380),
(386, 'SA', 'HT23000003', 86, '2023-09-13 11:48:53', 77, 1, NULL, 1.0000, 'UNID', 50.00, 50.00, 234, 15, '2023-09-13', '11:48', NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-09-13 11:48:53', 0),
(387, 'SA', 'HT23000037', 87, '2023-09-13 11:50:16', 102, 1, NULL, 1.0000, 'UNID', 4.00, 4.00, 282, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 25, NULL, 16, '2023-09-13 11:51:03', 0),
(388, 'SA', 'SP-2023000043', 88, '2023-09-13 12:24:18', 78, 1, NULL, 1.0000, NULL, 90.00, 90.00, 288, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 25, NULL, 12, '2023-09-13 12:24:33', 0),
(389, 'SA', 'SP-2023000043', 88, '2023-09-13 12:24:18', 74, 3, NULL, 3.0000, 'UNID', 0.00, 0.00, 288, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-09-13 12:24:18', 388),
(390, 'SA', 'SP-2023000043', 88, '2023-09-13 12:24:18', 79, 3, NULL, 1.0000, '', 0.00, 0.00, 288, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-09-13 12:24:18', 388),
(391, 'SA', 'SP-2023000043', 88, '2023-09-13 12:24:18', 70, 2, NULL, 9.0000, 'UNID', 0.00, 0.00, 288, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-09-13 12:24:18', 389),
(392, 'SA', 'SP-2023000043', 88, '2023-09-13 12:24:18', 71, 2, NULL, 9.0000, 'KILO', 0.00, 0.00, 288, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-09-13 12:24:18', 389),
(393, 'SA', 'SP-2023000043', 88, '2023-09-13 12:24:18', 72, 2, NULL, 1.5000, 'KILO', 0.00, 0.00, 288, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-09-13 12:24:18', 389),
(394, 'SA', 'SP-2023000043', 88, '2023-09-13 12:24:18', 73, 2, NULL, 6.0000, 'KILO', 0.00, 0.00, 288, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-09-13 12:24:18', 389),
(395, 'SA', 'HT23000039', 89, '2023-09-13 15:47:45', 77, 1, NULL, 1.0000, 'UNID', 50.00, 50.00, 286, 15, '2023-09-13', '15:47', NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-09-13 15:49:12', 0),
(396, 'SA', 'HT23000039', 90, '2023-09-13 15:49:28', 95, 1, NULL, 1.0000, 'UNID', 20.00, 20.00, 286, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-09-13 15:49:59', 0),
(397, 'SA', 'HT23000039', 91, '2023-09-13 15:54:50', 77, 1, NULL, 1.0000, 'UNID', 50.00, 50.00, 286, 15, '2023-09-13', '15:54', NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-09-13 15:54:50', 0),
(398, 'SA', 'SP-2023000049', 92, '2023-09-13 16:10:11', 95, 1, NULL, 1.0000, 'UNID', 20.00, 20.00, 294, NULL, NULL, NULL, NULL, NULL, 'PD000-00000002', NULL, 30, NULL, 12, '2023-09-13 16:57:14', 0),
(399, 'SA', 'SP-2023000049', 92, '2023-09-13 16:10:11', 95, 1, NULL, 1.0000, 'UNID', 20.00, 20.00, 294, NULL, NULL, NULL, NULL, NULL, 'PD000-00000002', NULL, 30, NULL, 12, '2023-09-13 16:57:14', 0),
(400, 'SA', 'SP-2023000049', 92, '2023-09-13 16:10:11', 95, 1, NULL, 1.0000, 'UNID', 20.00, 20.00, 294, NULL, NULL, NULL, NULL, NULL, 'PD000-00000002', NULL, 30, NULL, 12, '2023-09-13 16:57:14', 0),
(401, 'SA', 'SP-2023000049', 93, '2023-09-13 17:11:56', 78, 1, NULL, 1.0000, NULL, 90.00, 90.00, 294, NULL, NULL, NULL, NULL, NULL, 'PD000-00000003', NULL, 31, NULL, 12, '2023-09-13 17:16:20', 0),
(402, 'SA', 'SP-2023000049', 93, '2023-09-13 17:11:56', 74, 3, NULL, 3.0000, 'UNID', 0.00, 0.00, 294, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-09-13 17:11:56', 401),
(403, 'SA', 'SP-2023000049', 93, '2023-09-13 17:11:56', 79, 3, NULL, 1.0000, '', 0.00, 0.00, 294, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-09-13 17:11:56', 401),
(404, 'SA', 'SP-2023000049', 93, '2023-09-13 17:11:56', 70, 2, NULL, 9.0000, 'UNID', 0.00, 0.00, 294, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-09-13 17:11:56', 402),
(405, 'SA', 'SP-2023000049', 93, '2023-09-13 17:11:56', 71, 2, NULL, 9.0000, 'KILO', 0.00, 0.00, 294, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-09-13 17:11:56', 402),
(406, 'SA', 'SP-2023000049', 93, '2023-09-13 17:11:56', 72, 2, NULL, 1.5000, 'KILO', 0.00, 0.00, 294, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-09-13 17:11:56', 402),
(407, 'SA', 'SP-2023000049', 93, '2023-09-13 17:11:56', 73, 2, NULL, 6.0000, 'KILO', 0.00, 0.00, 294, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-09-13 17:11:56', 402),
(408, 'SA', 'SP-2023000049', 94, '2023-09-13 17:37:06', 102, 1, NULL, 1.0000, 'UNID', 4.00, 4.00, 294, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-09-13 17:37:06', 0),
(409, 'SA', 'SP-2023000049', 95, '2023-09-13 17:37:23', 78, 1, NULL, 1.0000, NULL, 90.00, 90.00, 294, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, '2023-09-13 17:37:23', 0),
(410, 'SA', 'SP-2023000049', 95, '2023-09-13 17:37:23', 74, 3, NULL, 3.0000, 'UNID', 0.00, 0.00, 294, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-09-13 17:37:23', 409),
(411, 'SA', 'SP-2023000049', 95, '2023-09-13 17:37:23', 79, 3, NULL, 1.0000, '', 0.00, 0.00, 294, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-09-13 17:37:23', 409),
(412, 'SA', 'SP-2023000049', 95, '2023-09-13 17:37:23', 70, 2, NULL, 9.0000, 'UNID', 0.00, 0.00, 294, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-09-13 17:37:23', 410),
(413, 'SA', 'SP-2023000049', 95, '2023-09-13 17:37:23', 71, 2, NULL, 9.0000, 'KILO', 0.00, 0.00, 294, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-09-13 17:37:23', 410),
(414, 'SA', 'SP-2023000049', 95, '2023-09-13 17:37:23', 72, 2, NULL, 1.5000, 'KILO', 0.00, 0.00, 294, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-09-13 17:37:23', 410),
(415, 'SA', 'SP-2023000049', 95, '2023-09-13 17:37:23', 73, 2, NULL, 6.0000, 'KILO', 0.00, 0.00, 294, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 12, '2023-09-13 17:37:23', 410);

-- --------------------------------------------------------

--
-- Table structure for table `documento_movimiento`
--

CREATE TABLE `documento_movimiento` (
  `id_documento_movimiento` int(11) NOT NULL,
  `id_unidad_de_negocio` int(11) DEFAULT NULL,
  `tipo_movimiento` varchar(2) DEFAULT NULL,
  `tipo_documento` varchar(2) DEFAULT NULL,
  `nro_documento` varchar(10) DEFAULT NULL,
  `nro_registro_maestro` varchar(15) DEFAULT NULL,
  `fecha_movimiento` date DEFAULT NULL,
  `fecha_documento` date DEFAULT NULL,
  `hora_movimiento` varchar(8) DEFAULT NULL,
  `nro_de_comanda` varchar(10) DEFAULT NULL,
  `total` decimal(12,2) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `fecha_hora_registro` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `documento_movimiento`
--

INSERT INTO `documento_movimiento` (`id_documento_movimiento`, `id_unidad_de_negocio`, `tipo_movimiento`, `tipo_documento`, `nro_documento`, `nro_registro_maestro`, `fecha_movimiento`, `fecha_documento`, `hora_movimiento`, `nro_de_comanda`, `total`, `id_usuario`, `fecha_hora_registro`) VALUES
(51, 3, 'SA', 'CM', 'CM-2023', 'SP-2023000015', '2023-08-23', '2023-08-23', '17:59:28', 'CM-2023', 301.00, 12, '2023-08-23 17:59:28'),
(52, 3, 'SA', 'CM', 'CM-2023', 'SP-2023000017', '2023-08-24', '2023-08-24', '10:23:53', 'CM-2023', 0.80, 12, '2023-08-24 10:23:53'),
(53, 3, 'SA', 'CM', 'CM-2023', 'SP-2023000031', '2023-08-24', '2023-08-24', '11:30:34', 'CM-2023', 0.50, 12, '2023-08-24 11:30:34'),
(54, 3, 'SA', 'CM', 'CM-2023', 'SP-2023000031', '2023-08-24', '2023-08-24', '11:30:54', 'CM-2023', 50.00, 12, '2023-08-24 11:30:54'),
(55, 3, 'SA', 'CM', 'CM-2023', 'SP-2023000031', '2023-08-24', '2023-08-24', '11:31:12', 'CM-2023', 150.00, 12, '2023-08-24 11:31:12'),
(56, 3, 'SA', 'CM', 'CM-2023', 'SP-2023000031', '2023-08-24', '2023-08-24', '11:33:27', 'CM-2023', 540.00, 12, '2023-08-24 11:33:27'),
(57, 3, 'SA', 'CM', 'CM-2023', 'SP-2023000032', '2023-08-25', '2023-08-25', '13:34:24', 'CM-2023', 70.00, 16, '2023-08-25 13:34:24'),
(58, 3, 'SA', 'CM', 'CM-2023', 'SP-2023000032', '2023-08-25', '2023-08-25', '13:34:25', 'CM-2023', 70.00, 16, '2023-08-25 13:34:25'),
(59, 3, 'SA', 'CM', 'CM-2023', 'SP-2023000032', '2023-08-31', '2023-08-31', '10:16:22', 'CM-2023', 90.00, 12, '2023-08-31 10:16:22'),
(60, 3, 'SA', 'CM', 'CM-2023', 'SP-2023000017', '2023-09-01', '2023-09-01', '11:44:00', 'CM-2023', 20.00, 12, '2023-09-01 11:44:00'),
(61, 3, 'SA', 'CM', 'CM-2023', 'SP-2023000017', '2023-09-01', '2023-09-01', '11:44:32', 'CM-2023', 190.00, 12, '2023-09-01 11:44:32'),
(62, 3, 'SA', 'CM', 'CM-2023', 'SP-2023000033', '2023-09-01', '2023-09-01', '11:58:47', 'CM-2023', 20.00, 12, '2023-09-01 11:58:47'),
(63, 3, 'SA', 'CM', 'CM-2023', 'SP-2023000033', '2023-09-01', '2023-09-01', '11:59:18', 'CM-2023', 270.00, 12, '2023-09-01 11:59:18'),
(64, 3, 'SA', 'CM', 'CM-2023', 'SP-2023000034', '2023-09-01', '2023-09-01', '12:16:19', 'CM-2023', 10.00, 12, '2023-09-01 12:16:19'),
(65, 3, 'SA', 'CM', 'CM-2023', 'SP-2023000035', '2023-09-01', '2023-09-01', '14:08:23', 'CM-2023', 90.00, 12, '2023-09-01 14:08:23'),
(66, 3, 'SA', 'CM', 'CM-2023', 'SP-2023000035', '2023-09-01', '2023-09-01', '14:11:53', 'CM-2023', 190.00, 12, '2023-09-01 14:11:53'),
(67, 3, 'SA', 'CM', 'CM-2023', 'SP-2023000036', '2023-09-05', '2023-09-05', '11:29:44', 'CM-2023', 74.00, 12, '2023-09-05 11:29:44'),
(68, 3, 'SA', 'CM', 'CM-2023', 'SP-2023000036', '2023-09-07', '2023-09-07', '13:35:02', 'CM-2023', 110.00, 16, '2023-09-07 13:35:02'),
(69, 3, 'SA', 'CM', 'CM-2023', 'SP-2023000037', '2023-09-07', '2023-09-07', '13:36:19', 'CM-2023', 20.00, 16, '2023-09-07 13:36:19'),
(70, 3, 'SA', 'CM', 'CM-2023', 'SP-2023000038', '2023-09-08', '2023-09-08', '14:25:40', 'CM-2023', 340.00, 12, '2023-09-08 14:25:40'),
(71, 3, 'SA', 'CM', 'CM-2023', 'SP-2023000039', '2023-09-08', '2023-09-08', '14:39:30', 'CM-2023', 2280.00, 12, '2023-09-08 14:39:30'),
(72, 3, 'SA', 'CM', 'CM-2023', 'HT230000014', '2023-09-08', '2023-09-08', '16:59:09', 'CM-2023', 20.00, 12, '2023-09-08 16:59:09'),
(73, 3, 'SA', 'CM', 'CM-2023', 'HT23000043', '2023-09-11', '2023-09-11', '11:35:31', 'CM-2023', 90.00, 12, '2023-09-11 11:35:31'),
(74, 3, 'SA', 'CM', 'CM-2023', 'SP-2023000039', '2023-09-11', '2023-09-11', '15:15:53', 'CM-2023', 20.00, 12, '2023-09-11 15:15:53'),
(75, 3, 'SA', 'CM', 'CM-2023', 'SP-2023000040', '2023-09-12', '2023-09-12', '15:06:52', 'CM-2023', 20.00, 12, '2023-09-12 15:06:52'),
(76, 3, 'SA', 'CM', 'CM-2023', 'SP-2023000040', '2023-09-12', '2023-09-12', '16:31:20', 'CM-2023', 90.00, 12, '2023-09-12 16:31:20'),
(77, NULL, 'SA', 'CM', 'CM-2023', 'SP-2023000015', '2023-09-12', '2023-09-12', '17:12:52', 'CM-2023', 301.00, 12, '2023-09-12 17:12:52'),
(80, 3, 'SA', 'CM', 'CM-2023', 'SP-2023000035', '2023-09-12', '2023-09-12', '17:16:46', 'CM-2023', 301.00, 12, '2023-09-12 17:16:46'),
(81, 3, 'SA', 'CM', 'CM-2023', 'SP-2023000041', '2023-09-12', '2023-09-12', '17:20:20', 'CM-2023', 540.00, 12, '2023-09-12 17:20:20'),
(82, 3, 'SA', 'CM', 'CM-2023', 'SP-2023000041', '2023-09-12', '2023-09-12', '17:20:49', 'CM-2023', 40.00, 12, '2023-09-12 17:20:49'),
(83, 3, 'SA', 'CM', 'CM-2023', 'SP-2023000041', '2023-09-13', '2023-09-13', '10:05:20', 'CM-2023', 90.00, 12, '2023-09-13 10:05:20'),
(84, 3, 'SA', 'CM', 'CM-2023', 'HT23000037', '2023-09-13', '2023-09-13', '10:58:30', 'CM-2023', 20.00, 16, '2023-09-13 10:58:30'),
(85, 3, 'SA', 'CM', 'CM-2023', 'HT23000003', '2023-09-13', '2023-09-13', '11:46:14', 'CM-2023', 90.00, 12, '2023-09-13 11:46:14'),
(86, 3, 'SA', 'CM', 'CM-2023', 'HT23000003', '2023-09-13', '2023-09-13', '11:48:53', 'CM-2023', 50.00, 12, '2023-09-13 11:48:53'),
(87, 3, 'SA', 'CM', 'CM-2023', 'HT23000037', '2023-09-13', '2023-09-13', '11:50:16', 'CM-2023', 4.00, 16, '2023-09-13 11:50:16'),
(88, 3, 'SA', 'CM', 'CM-2023', 'SP-2023000043', '2023-09-13', '2023-09-13', '12:24:18', 'CM-2023', 90.00, 12, '2023-09-13 12:24:18'),
(89, 3, 'SA', 'CM', 'CM-2023', 'HT23000039', '2023-09-13', '2023-09-13', '15:47:45', 'CM-2023', 50.00, 12, '2023-09-13 15:47:45'),
(90, 3, 'SA', 'CM', 'CM-2023', 'HT23000039', '2023-09-13', '2023-09-13', '15:49:28', 'CM-2023', 20.00, 12, '2023-09-13 15:49:28'),
(91, 3, 'SA', 'CM', 'CM-2023', 'HT23000039', '2023-09-13', '2023-09-13', '15:54:50', 'CM-2023', 50.00, 12, '2023-09-13 15:54:50'),
(92, 3, 'SA', 'CM', 'CM-2023', 'SP-2023000049', '2023-09-13', '2023-09-13', '16:10:11', 'CM-2023', 60.00, 12, '2023-09-13 16:10:11'),
(93, 3, 'SA', 'CM', 'CM-2023', 'SP-2023000049', '2023-09-13', '2023-09-13', '17:11:56', 'CM-2023', 90.00, 12, '2023-09-13 17:11:56'),
(94, 3, 'SA', 'CM', 'CM-2023', 'SP-2023000049', '2023-09-13', '2023-09-13', '17:37:06', 'CM-2023', 4.00, 12, '2023-09-13 17:37:06'),
(95, 3, 'SA', 'CM', 'CM-2023', 'SP-2023000049', '2023-09-13', '2023-09-13', '17:37:23', 'CM-2023', 90.00, 12, '2023-09-13 17:37:23');

-- --------------------------------------------------------

--
-- Table structure for table `fe_comprobante`
--

CREATE TABLE `fe_comprobante` (
  `IdFeC` int(11) NOT NULL,
  `NroMov` varchar(16) DEFAULT NULL,
  `serieComprobante` varchar(4) DEFAULT NULL,
  `nroComprobante` varchar(8) DEFAULT NULL,
  `tipOperacion` varchar(2) DEFAULT NULL,
  `fecEmision` varchar(10) DEFAULT NULL,
  `fecPago` varchar(10) DEFAULT NULL,
  `codLocalEmisor` varchar(3) DEFAULT NULL,
  `TipDocUsuario` varchar(1) DEFAULT NULL,
  `numDocUsuario` varchar(15) DEFAULT NULL,
  `rznSocialUsuario` varchar(100) DEFAULT NULL,
  `tipMoneda` varchar(3) DEFAULT NULL,
  `sumDsctoGlobal` float(12,2) DEFAULT 0.00,
  `sumOtrosCargos` float(12,2) DEFAULT 0.00,
  `mtoDescuentos` float(12,2) DEFAULT 0.00,
  `mtoOperGravadas` float(12,2) NOT NULL DEFAULT 0.00,
  `mtoOperInafectas` float(12,2) NOT NULL DEFAULT 0.00,
  `mtoOperExoneradas` float(12,2) NOT NULL DEFAULT 0.00,
  `mtoIGV` float(12,2) NOT NULL DEFAULT 0.00,
  `mtoISC` float(12,2) NOT NULL DEFAULT 0.00,
  `mtoOtrosTributos` float(12,2) NOT NULL DEFAULT 0.00,
  `mtoImpVenta` float(12,2) NOT NULL DEFAULT 0.00,
  `xestado` int(1) DEFAULT 0,
  `xdocnro` varchar(15) DEFAULT NULL,
  `xfecha` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `xhora` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `fe_comprobante`
--

INSERT INTO `fe_comprobante` (`IdFeC`, `NroMov`, `serieComprobante`, `nroComprobante`, `tipOperacion`, `fecEmision`, `fecPago`, `codLocalEmisor`, `TipDocUsuario`, `numDocUsuario`, `rznSocialUsuario`, `tipMoneda`, `sumDsctoGlobal`, `sumOtrosCargos`, `mtoDescuentos`, `mtoOperGravadas`, `mtoOperInafectas`, `mtoOperExoneradas`, `mtoIGV`, `mtoISC`, `mtoOtrosTributos`, `mtoImpVenta`, `xestado`, `xdocnro`, `xfecha`, `xhora`) VALUES
(43, '64', 'PD00', '00000004', '01', '2023-09-13', '2023-09-13', '000', '6', NULL, '', 'PEN', 0.00, 0.00, 0.00, 85.45, 0.00, 0.00, 8.55, 0.00, 0.00, 94.00, 0, NULL, NULL, NULL),
(42, '63', 'PD00', '00000003', '01', '2023-09-13', '2023-09-13', '000', '6', NULL, '', 'PEN', 0.00, 0.00, 0.00, 81.82, 0.00, 0.00, 8.18, 0.00, 0.00, 90.00, 0, NULL, NULL, NULL),
(41, '62', 'PD00', '00000002', '01', '2023-09-13', '2023-09-13', '000', '6', NULL, 'ajdiowjdawo', 'PEN', 0.00, 0.00, 0.00, 54.55, 0.00, 0.00, 5.45, 0.00, 0.00, 60.00, 0, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `fe_items`
--

CREATE TABLE `fe_items` (
  `IdfeItem` int(11) NOT NULL,
  `NroMov` varchar(16) DEFAULT NULL,
  `serieComprobante` varchar(4) DEFAULT NULL,
  `nroComprobante` varchar(8) DEFAULT NULL,
  `codUnidadMedida` varchar(3) DEFAULT NULL,
  `ctdUnidadItem` decimal(23,10) DEFAULT 0.0000000000,
  `codProducto` varchar(30) DEFAULT NULL,
  `codProductoSUNAT` varchar(20) DEFAULT NULL,
  `desItem` varchar(240) DEFAULT NULL,
  `mtoValorUnitario` decimal(23,10) DEFAULT 0.0000000000,
  `mtoDsctoItem` decimal(15,2) DEFAULT 0.00,
  `mtoIgvItem` decimal(15,2) DEFAULT 0.00,
  `tipAfeIGV` varchar(2) DEFAULT NULL,
  `mtoIscItem` decimal(15,2) DEFAULT 0.00,
  `tipSisISC` varchar(2) DEFAULT NULL,
  `mtoPrecioVentaItem` decimal(23,10) DEFAULT 0.0000000000,
  `mtoValorVentaItem` decimal(12,2) DEFAULT 0.00
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `fe_items`
--

INSERT INTO `fe_items` (`IdfeItem`, `NroMov`, `serieComprobante`, `nroComprobante`, `codUnidadMedida`, `ctdUnidadItem`, `codProducto`, `codProductoSUNAT`, `desItem`, `mtoValorUnitario`, `mtoDsctoItem`, `mtoIgvItem`, `tipAfeIGV`, `mtoIscItem`, `tipSisISC`, `mtoPrecioVentaItem`, `mtoValorVentaItem`) VALUES
(80, '64', NULL, NULL, 'NIU', 1.0000000000, NULL, NULL, 'Dia de la madre', 81.8181818182, 0.00, 8.18, '10', 0.00, NULL, 90.0000000000, 81.82),
(79, '64', NULL, NULL, 'NIU', 1.0000000000, NULL, NULL, 'COCA COLA 1/2 LT', 3.6363636364, 0.00, 0.36, '10', 0.00, NULL, 4.0000000000, 3.64),
(78, '63', NULL, NULL, 'NIU', 1.0000000000, NULL, NULL, 'Dia de la madre', 81.8181818182, 0.00, 8.18, '10', 0.00, NULL, 90.0000000000, 81.82),
(77, '62', NULL, NULL, 'NIU', 1.0000000000, NULL, NULL, 'VINO CASILLERO DEL DIABLO', 18.1818181818, 0.00, 1.82, '10', 0.00, NULL, 20.0000000000, 18.18),
(76, '62', NULL, NULL, 'NIU', 1.0000000000, NULL, NULL, 'VINO CASILLERO DEL DIABLO', 18.1818181818, 0.00, 1.82, '10', 0.00, NULL, 20.0000000000, 18.18),
(75, '62', NULL, NULL, 'NIU', 1.0000000000, NULL, NULL, 'VINO CASILLERO DEL DIABLO', 18.1818181818, 0.00, 1.82, '10', 0.00, NULL, 20.0000000000, 18.18);

-- --------------------------------------------------------

--
-- Table structure for table `gruposdelacarta`
--

CREATE TABLE `gruposdelacarta` (
  `id_grupo` int(11) NOT NULL,
  `nro_orden` int(3) DEFAULT NULL,
  `codigo_subgrupo` varchar(3) DEFAULT NULL,
  `codigo_grupo` varchar(3) DEFAULT NULL,
  `nombre_grupo` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gruposdelacarta`
--

INSERT INTO `gruposdelacarta` (`id_grupo`, `nro_orden`, `codigo_subgrupo`, `codigo_grupo`, `nombre_grupo`) VALUES
(6, 1, '001', '001', 'Bazar'),
(7, 2, '002', '002', 'Spa'),
(8, 6, '003', '003', 'Bebidas'),
(9, 4, '006', '006', 'Platos de Comida'),
(10, 5, '007', '007', 'Tragos'),
(11, 1, '004', '003', 'Bebidas frias 1'),
(12, 2, '005', '003', 'Bebidas calientes 1'),
(14, 7, '008', '008', 'HOSPEDAJE'),
(20, 1, '010', '010', 'a'),
(21, 1, '010', '010', 'b'),
(22, 1, '010', '010', 'c');

-- --------------------------------------------------------

--
-- Table structure for table `grupo_modulo`
--

CREATE TABLE `grupo_modulo` (
  `id_grupo_modulo` int(11) NOT NULL,
  `nombre_grupo_modulo` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `grupo_modulo`
--

INSERT INTO `grupo_modulo` (`id_grupo_modulo`, `nombre_grupo_modulo`) VALUES
(1, 'Productos'),
(2, 'Logistica'),
(3, 'Usuarios');

-- --------------------------------------------------------

--
-- Table structure for table `habilidadesprofesionales`
--

CREATE TABLE `habilidadesprofesionales` (
  `id_habilidad` int(11) NOT NULL,
  `codigo_habilidad` varchar(20) DEFAULT NULL,
  `descripcion` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `habilidadesprofesionales`
--

INSERT INTO `habilidadesprofesionales` (`id_habilidad`, `codigo_habilidad`, `descripcion`) VALUES
(1, '546', 'Masaje N1'),
(3, '895', 'Masaje N2'),
(4, '563', 'Cosmiatria'),
(5, '564', 'ESTILISTA N1'),
(6, '565', 'ESTILISTA N2');

-- --------------------------------------------------------

--
-- Table structure for table `habitaciones`
--

CREATE TABLE `habitaciones` (
  `id_habitacion` int(11) NOT NULL,
  `id_unidad_de_negocio` int(11) DEFAULT NULL,
  `nro_habitacion` varchar(10) DEFAULT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `estado` varchar(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `habitaciones`
--

INSERT INTO `habitaciones` (`id_habitacion`, `id_unidad_de_negocio`, `nro_habitacion`, `id_producto`, `estado`) VALUES
(3, 3, '201', 79, NULL),
(4, 3, '202', 79, NULL),
(5, 3, '203', 79, NULL),
(32, 3, '204', 80, NULL),
(33, 3, '205', 80, NULL),
(34, 4, '101', 79, NULL),
(35, 4, '102', 79, NULL),
(36, 4, '103', 79, NULL),
(37, 4, '105', 80, NULL),
(38, 4, '106', 80, NULL),
(39, 4, '107', 80, NULL),
(40, 4, '108', 80, NULL),
(41, 5, '101', 79, NULL),
(42, 5, '102', 79, NULL),
(43, 5, '103', 80, NULL),
(44, 5, '104', 80, NULL),
(45, 3, '206', 79, NULL),
(46, 3, '207', 79, NULL),
(47, 3, '208', 79, NULL),
(48, 3, '209', 79, NULL),
(49, 3, '210', 79, NULL),
(50, 3, '211', 79, NULL),
(51, 3, '212', 79, NULL),
(52, 3, '213', 79, NULL),
(53, 3, '214', 79, NULL),
(54, 3, '215', 79, NULL),
(55, 3, '216', 79, NULL),
(56, 3, '217', 79, NULL),
(57, 3, '218', 79, NULL),
(58, 3, '219', 79, NULL),
(59, 3, '220', 79, NULL),
(60, 3, '221', 79, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `impresoras`
--

CREATE TABLE `impresoras` (
  `id_impresora` int(11) NOT NULL,
  `nombre_impresora` varchar(40) DEFAULT NULL,
  `ubicacion` varchar(40) DEFAULT NULL,
  `nro_ip` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `impresoras`
--

INSERT INTO `impresoras` (`id_impresora`, `nombre_impresora`, `ubicacion`, `nro_ip`) VALUES
(1, 'Impresora Ejemplo aaaaa5', 'Ubicación Ejemploaaaaa', '192.168.1.100'),
(6, 'Impresora Ejemplo', 'Ubicación Ejemplo', '192.168.1.100'),
(7, 'Impresora Ejemplo', 'Ubicación Ejemplo', '192.168.1.100');

-- --------------------------------------------------------

--
-- Table structure for table `modalidadcliente`
--

CREATE TABLE `modalidadcliente` (
  `id_modalidad` int(11) NOT NULL,
  `nombre_modalidad` varchar(20) DEFAULT NULL,
  `descripcion_modalidad` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `modalidadcliente`
--

INSERT INTO `modalidadcliente` (`id_modalidad`, `nombre_modalidad`, `descripcion_modalidad`) VALUES
(1, 'Hotel', 'descripcion de hotel'),
(2, 'Booking', 'metabuscador de viajes para reservas de alojamiento.'),
(3, 'Despegar', 'Despegar es una empresa de viajes líder en Latinoamérica basada en Buenos Aires.'),
(4, 'Agencia', 'Descripcion de Agencia.');

-- --------------------------------------------------------

--
-- Table structure for table `modulos`
--

CREATE TABLE `modulos` (
  `id_modulo` int(11) NOT NULL,
  `id_grupo_modulo` int(11) DEFAULT NULL,
  `nombre_modulo` varchar(40) DEFAULT NULL,
  `descripcion` varchar(250) DEFAULT NULL,
  `archivo_acceso` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `modulos`
--

INSERT INTO `modulos` (`id_modulo`, `id_grupo_modulo`, `nombre_modulo`, `descripcion`, `archivo_acceso`) VALUES
(1, 1, 'Catalogo_Productos', 'Lista de todos los productos', '//ruta//'),
(2, 1, 'Creacion_productos', 'Creamos los productos', '//ruta//'),
(3, 1, 'Cambio_precio_productos', 'cambios de precios a lso productos', '//ruta//'),
(4, 1, 'Listado_Productos', 'muestra los productos', '//ruta//'),
(5, 2, 'Listado_producto_o_Articulos', 'muestra los productos', '//ruta//'),
(6, 2, 'Ingreso_Productos', 'muestra los ingresos de los productos', '//ruta//'),
(7, 2, 'Salida_Productos', 'muestra los salidas de los productos', '//ruta//'),
(8, 2, 'Kardex', 'muestra Kardex', '//ruta//'),
(9, 3, 'Creacion_Usuarios', 'Se podra crear Usuarios', '//ruta//'),
(10, 3, 'Listado_Usuarios', 'Mostrar Usuarios', '//ruta//'),
(11, 3, 'Modificacion_Usuarios', 'Se podra modificar Usuarios', '//ruta//'),
(12, 2, 'Carga_Prueba', 'pruebaaaaaa', 'C:fakepathprueba.sql'),
(15, 2, 'Carga_Prueba', 'pruebaaaaaa', 'C:fakepathBD-app-prueba.sql');

-- --------------------------------------------------------

--
-- Table structure for table `pais`
--

CREATE TABLE `pais` (
  `id_pais` int(11) NOT NULL,
  `pais` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personanaturaljuridica`
--

CREATE TABLE `personanaturaljuridica` (
  `id_persona` int(11) NOT NULL,
  `tipo_persona` varchar(4) NOT NULL,
  `tipo_documento` int(11) NOT NULL,
  `nro_documento` varchar(20) NOT NULL,
  `apellidos` varchar(30) NOT NULL,
  `nombres` varchar(30) NOT NULL,
  `sexo` varchar(1) NOT NULL,
  `lugar_de_nacimiento` varchar(30) NOT NULL,
  `fecha` date DEFAULT NULL,
  `edad` int(3) NOT NULL,
  `nacionalidad` varchar(20) NOT NULL,
  `ocupacion` varchar(30) DEFAULT NULL,
  `direccion` varchar(60) NOT NULL,
  `ciudad` varchar(30) DEFAULT NULL,
  `pais` varchar(30) NOT NULL,
  `celular` varchar(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  `id_usuario_creacion` int(11) NOT NULL,
  `fecha_creacion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `personanaturaljuridica`
--

INSERT INTO `personanaturaljuridica` (`id_persona`, `tipo_persona`, `tipo_documento`, `nro_documento`, `apellidos`, `nombres`, `sexo`, `lugar_de_nacimiento`, `fecha`, `edad`, `nacionalidad`, `ocupacion`, `direccion`, `ciudad`, `pais`, `celular`, `email`, `id_usuario_creacion`, `fecha_creacion`) VALUES
(4, '', 0, '', '', '', '', '', '0000-00-00', 99, '', '', '', '', '', '', '', 0, '0000-00-00 00:00:00'),
(5, 'Natu', 0, '789456', 'Soza', 'Monica', 'M', 'New York', '1990-08-15', 99, 'US', 'Engineer', '123 Main Street', 'New York City', 'USA', '555-1234', 'john.doe@example.com', 456, '2023-07-26 00:00:00'),
(6, 'Natu', 0, '789450', 'Soza', 'Monica', 'M', 'New York', '1990-08-15', 32, 'US', 'Engineer', '123 Main Street', 'New York City', 'USA', '555-1234', 'john.doe@example.com', 456, '2023-07-26 00:00:00'),
(10, 'NATU', 0, '12345678', 'Garcia', 'Maria', 'F', 'Lima', '2023-08-02', 33, 'PER', 'Vendedor', 'Av. San Martin 123', 'Lima', 'PER', '987654321', 'maria@example.com', 0, '2023-08-02 17:42:28'),
(11, 'NATU', 0, '87889823', 'Batista, Moreno', 'Merry Anna ', 'F', 'PERU', '2023-08-02', 67, 'PER', 'Masajista', 'calle uruguay 712', 'TACNA', 'PER', '987654321', 'example@ugf.com', 0, '2023-08-02 19:01:24'),
(12, 'Natu', 0, '77327732', 'Lipa', 'Abrhaa', 'M', '', '0000-00-00', 23, '', '', '', '', '', '', '', 0, '2023-07-26 00:00:00'),
(13, 'Natu', 0, '8795456', 'asdasd', 'sadasd', 'M', '', '0000-00-00', 23, '', '', '', '', '', '', '', 0, '2023-07-26 00:00:00'),
(14, 'Natu', 0, '89754165', 'asdas', 'asdas', 'M', '', '0000-00-00', 23, '', '', '', '', '', '', '', 0, '2023-07-26 00:00:00'),
(15, 'Natu', 0, '56465123', 'asd', 'asdas', 'M', '', '0000-00-00', 23, '', '', '', '', '', '', '', 0, '2023-07-26 00:00:00'),
(16, 'Natu', 0, '89754651', 'asdas', 'asdas', 'M', '', '0000-00-00', 23, '', '', '', '', '', '', '', 0, '2023-07-26 00:00:00'),
(17, 'Natu', 0, '1231241', 'braham', 'asdas', 'M', '', '0000-00-00', 23, '', '', '', '', '', '', '', 0, '2023-07-26 00:00:00'),
(19, 'Natu', 0, '1231222', 'sda', 'asdas', 'M', '', '0000-00-00', 23, '', '', '', '', '', '', '', 0, '2023-07-26 00:00:00'),
(20, 'Natu', 0, '164561', 'braham', 'lipa', 'M', '', '0000-00-00', 23, '', '', '', '', '', '', '', 0, '2023-07-26 00:00:00'),
(21, 'Natu', 0, '123145', 'Abraham', 'kasd', 'M', '', '0000-00-00', 23, '', '', '', '', '', '', '', 0, '2023-07-26 00:00:00'),
(22, 'Natu', 0, '5216546', 'asdasd', 'afasf', 'M', '', '0000-00-00', 23, '', '', '', '', '', '', '', 0, '2023-07-26 00:00:00'),
(23, 'Natu', 0, '123124', 'wqe', 'qweqw', 'M', '', '0000-00-00', 23, '', '', '', '', '', '', '', 0, '2023-07-26 00:00:00'),
(24, 'Natu', 0, '124235', 'abraham', 'asdas', 'M', '', '0000-00-00', 23, '', '', '', '', '', '', '', 0, '2023-07-26 00:00:00'),
(25, 'Natu', 0, '3423523', 'brhaama', 'abrahan', 'M', '', '0000-00-00', 34, '', '', '', '', '', '', '', 0, '2023-07-26 00:00:00'),
(26, 'Natu', 0, '1432443', 'abraham', 'asdas', 'M', '', '0000-00-00', 23, '', '', '', '', '', '', '', 0, '2023-07-26 00:00:00'),
(27, 'Natu', 0, '14324235', 'Abraham', 'jasdj', 'M', '', '0000-00-00', 23, '', '', '', '', '', '', '', 0, '2023-07-26 00:00:00'),
(28, 'Natu', 0, '123141', 'Abraham', 'Abraham', 'M', '', '0000-00-00', 123, '', '', '', '', '', '', '', 0, '2023-07-26 00:00:00'),
(29, 'Natu', 0, '1235324', 'ahaam', 'lipa', 'M', '', '0000-00-00', 23, '', '', '', '', '', '', '', 0, '2023-07-26 00:00:00'),
(30, 'Natu', 0, '12313425', 'Lipa', 'Arbahjaj', 'M', '', '0000-00-00', 23, '', '', '', '', '', '', '', 0, '2023-07-26 00:00:00'),
(31, 'Natu', 0, '12312415', 'asd', 'asda', 'M', '', '0000-00-00', 123, '', '', '', '', '', '', '', 0, '2023-07-26 00:00:00'),
(32, 'Natu', 0, '121324', 'rbahaha', 'asdas', 'M', '', '0000-00-00', 12, '', '', '', '', '', '', '', 0, '2023-07-26 00:00:00'),
(33, 'Natu', 0, '1345646', 'AGUILAR SOTO', 'CARLOS EDUARDO', 'M', 'TACNA', '0000-00-00', 23, '', '', '', '', '', '', '', 0, '2023-07-26 00:00:00'),
(34, 'Natu', 0, '10498126', 'CHAVEZ, ORMEÑO', 'JOSE', 'M', 'TRUJILLO', '2023-08-11', 52, 'PER', 'DPTO SISTEMAS', 'URB. TACNA F-36', 'TACNA', 'PER', '962075545', 'assoflex@hotmail.com', 0, '2023-08-11 17:20:41'),
(35, 'Natu', 0, '1234235', 'sdaksdl', 'asdasd', 'M', '', '0000-00-00', 45, '', '', '', '', '', '', '', 0, '2023-07-26 00:00:00'),
(36, 'Natu', 0, '92736492', 'Lipasdasd', 'Abrahamasdasd', 'M', '', '0000-00-00', 25, '', '', '', '', '', '', '', 0, '2023-07-26 00:00:00'),
(37, 'Natu', 0, '5465465', 'titular', 'acompanante', 'F', '', '0000-00-00', 25, '', '', '', '', '', '', '', 0, '2023-07-26 00:00:00'),
(38, 'Natu', 0, '89234929', 'Lipa', 'Abraham', 'M', '', '0000-00-00', 25, '', '', '', '', '', '', '', 0, '2023-07-26 00:00:00'),
(39, 'Natu', 0, '76368626', 'Lipa Calabilla', 'Abraham', 'M', '', '0000-00-00', 25, '', '', '', '', '', '', '', 0, '2023-07-26 00:00:00'),
(40, 'Natu', 0, '76368627', 'Calabilla', 'Abraham', 'M', '', '0000-00-00', 25, '', '', '', '', '', '', '', 0, '2023-07-26 00:00:00'),
(41, 'Natu', 0, '18945616', 'Persona', 'Pers', 'M', '', '0000-00-00', 24, '', '', '', '', '', '', '', 0, '2023-07-26 00:00:00'),
(42, 'Natu', 0, '1241351', 'personas', 'persosnas', 'M', '', '0000-00-00', 24, '', '', '', '', '', '', '', 0, '2023-07-26 00:00:00'),
(43, 'Natu', 0, '235233', 'porseakje', 'paiwiw', 'M', '', '0000-00-00', 24, '', '', '', '', '', '', '', 0, '2023-07-26 00:00:00'),
(44, 'Natu', 0, '7646848', 'Poawiiau', 'ioaw', 'M', '', '0000-00-00', 25, '', '', '', '', '', '', '', 0, '2023-07-26 00:00:00'),
(45, 'Natu', 0, '1241241', 'qweqw', 'qweq', 'M', '', '0000-00-00', 23, '', '', '', '', '', '', '', 0, '2023-07-26 00:00:00'),
(46, 'Natu', 0, '0384023', 'Laowoia', 'opwoq', 'M', '', '0000-00-00', 234, '', '', '', '', '', '', '', 0, '2023-07-26 00:00:00'),
(47, 'Natu', 0, '1212441', 'Abraham', 'Lopa', 'M', '', '0000-00-00', 24, '', '', '', '', '', '', '', 0, '2023-07-26 00:00:00'),
(48, 'Natu', 0, '124124', 'Poawo', 'opaw', 'M', '', '0000-00-00', 24, '', '', '', '', '', '', '', 0, '2023-07-26 00:00:00'),
(49, 'Natu', 0, '8947912', 'Poawiaw', 'iawaiow', 'M', '', '0000-00-00', 24, '', '', '', '', '', '', '', 0, '2023-07-26 00:00:00'),
(50, 'Natu', 0, '40736563', 'GONZALEZ RONCAL', 'JONATHAN', 'M', '', '0000-00-00', 42, '', '', '', '', '', '', '', 0, '2023-07-26 00:00:00'),
(51, 'NATU', 0, '78451236', 'Jaramillo, Buenavista', 'Guillermo Raul', 'M', 'PERU', '2023-08-28', 28, 'PER', 'Masajista', 'calle uruguay 712', 'TACNA', 'PER', '987654321', 'example@ugf.com', 0, '2023-08-28 16:30:07'),
(52, 'NATU', 0, '78965423', 'Gutierrez , Eduardo', 'Carlos', 'M', 'PERU', '2023-08-29', 58, 'PER', 'Masajista', 'calle uruguay 712', 'TACNA', 'PER', '987654321', 'example@ugf.com', 0, '2023-08-29 17:33:51'),
(53, 'Natu', 0, '00498126', 'CHAVEZ ORMEÑO', 'JOSÉ', 'M', '', '0000-00-00', 52, '', '', '', '', '', '', '', 0, '2023-07-26 00:00:00'),
(54, 'NATU', 0, '00498120', 'TORRES, CARDONA', 'JUAN', 'M', 'LIMA', '2023-08-31', 53, 'PER', 'RECEPCIONISTA', 'URB. TACNA F-36', 'TACNA', 'PER', '962075545', 'jhons26@gmail.com', 0, '2023-08-31 20:54:07'),
(55, '0', 1, '77529743', 'AGUILAR SOTO', 'CARLOS EDUARDO', 'N', 'TACNA', '0000-00-00', 26, 'NA', 'ESTUDIANTE UNIVERSITARIO', 'calle uruguay 712', 'TACNA', 'NA', '912536515', 'car_aguilar_20@hotmail.com', 0, '2023-09-05 00:00:00'),
(56, '0', 1, '87452156', 'ROJELIO PANTI', 'ALEJANDRO', 'N', 'TACNA', '1965-08-25', 45, 'NA', 'INGENIERO', 'calle uruguay 712', 'TACNA', 'NA', '987654321', 'postulante@upt.pe', 0, '2023-09-06 00:00:00'),
(57, '0', 1, '56239841', 'GONZALES PEREZ', 'JUAN MARCELO', 'N', 'TACNA', '1965-06-08', 45, 'NA', 'INGENIERO', 'calle uruguay 712', 'TACNA', 'NA', '987456321', 'postulante@upt.pe', 0, '2023-09-06 00:00:00'),
(58, '0', 1, '78452130', 'VALDEMAR QUIROZ', 'JUAN MARCELO', 'N', 'TACNA', '1965-03-07', 45, 'NA', '..............................', 'calle uruguay 712', 'TACNA', 'NA', '987654321', 'postulante@upt.pe', 0, '2023-09-08 00:00:00'),
(59, 'NATU', 0, '00492643', 'Lipa Cala', 'A', 'M', '', NULL, 25, '', NULL, '', NULL, '', '', '', 0, '2023-09-08 14:24:00'),
(60, '0', 1, '8754236', 'FERNANDEZ IBAÑEZ', 'CHRISTIAN ROBERTO', 'N', 'TACNA', '1994-07-25', 32, 'NA', '................', 'CALLE ZELA #2122', 'TACNA', 'NA', '987654321', 'postulante@upt.pe', 0, '2023-09-11 00:00:00'),
(61, '0', 1, '77765623', 'ANDERSON JHOSON', 'CHRISTIAN ROBERTO', 'N', 'TACNA', '1969-12-22', 45, 'NA', '..............................', 'calle uruguay 712', 'TACNA', 'NA', '987654321', 'postulante@upt.pe', 0, '2023-09-11 00:00:00'),
(62, '0', 1, '00456900', 'AGUILAR SOTO', 'CARLOS EDUARDO', 'N', 'TACNA', '1997-07-29', 23, 'NA', 'ESTUDIANTE UNIVERSITARIO', 'calle uruguay 712', 'TACNA', 'NA', '987654321', 'postulante@upt.pe', 0, '2023-09-11 00:00:00'),
(63, '0', 1, '01456900', 'AGUILAR SOTO', 'CARLOS EDUARDO', 'N', 'TACNA', '1997-07-29', 23, 'NA', 'ESTUDIANTE UNIVERSITARIO', 'calle uruguay 712', 'TACNA', 'NA', '987654321', 'postulante@upt.pe', 0, '2023-09-11 00:00:00'),
(64, '0', 1, '02456900', 'AGUILAR SOTO', 'CARLOS EDUARDO', 'N', 'TACNA', '1997-07-29', 23, 'NA', 'ESTUDIANTE UNIVERSITARIO', 'calle uruguay 712', 'TACNA', 'NA', '987654321', 'postulante@upt.pe', 0, '2023-09-11 00:00:00'),
(65, '0', 1, '65324821', 'MORALES  GONZALES', 'FABIAN ORLANDO', 'N', 'AREQUIPA', '1986-04-25', 45, 'NA', '..............................', 'calle uruguay 712', 'TACNA', 'NA', '987654321', 'postulante@upt.pe', 0, '2023-09-11 00:00:00'),
(66, '0', 1, '03456900', 'AGUILAR SOTO', 'CARLOS EDUARDO', 'N', 'TACNA', '1997-07-29', 23, 'NA', 'ESTUDIANTE UNIVERSITARIO', 'calle uruguay 712', 'TACNA', 'NA', '987654321', 'postulante@upt.pe', 0, '2023-09-11 00:00:00'),
(67, '0', 1, '03456100', 'AGUILAR SOTO', 'CARLOS EDUARDO', 'N', 'TACNA', '1997-07-29', 23, 'NA', 'ESTUDIANTE UNIVERSITARIO', 'calle uruguay 712', 'TACNA', 'NA', '987654321', 'postulante@upt.pe', 0, '2023-09-11 00:00:00'),
(68, '0', 1, '03457100', 'AGUILAR SOTO', 'CARLOS EDUARDO', 'N', 'TACNA', '1997-07-29', 23, 'NA', 'ESTUDIANTE UNIVERSITARIO', 'calle uruguay 712', 'TACNA', 'NA', '987654321', 'postulante@upt.pe', 0, '2023-09-11 00:00:00'),
(69, '0', 1, '87543698', 'CABALLERO GARCIA', 'JOSE ROGELIO', 'N', 'AREQUIPA', '1994-03-10', 26, 'NA', '..............................', 'calle uruguay 712', 'TACNA', 'NA', '987654321', 'postulante@upt.pe', 0, '2023-09-11 00:00:00'),
(70, '0', 1, '59362514', 'LIBERTAD FRANCH', 'MARTIN LUIS', 'N', 'AREQUIPA', '1956-08-04', 45, 'NA', '...................', 'calle uruguay 712', 'TACNA', 'NA', '987654321', 'postulante@upt.pe', 0, '2023-09-11 00:00:00'),
(71, '0', 1, '59392514', 'LIBERTAD FRANCH', 'MARTIN LUIS', 'N', 'AREQUIPA', '1956-08-04', 45, 'NA', '...................', 'calle uruguay 712', 'TACNA', 'NA', '987654321', 'postulante@upt.pe', 0, '2023-09-11 00:00:00'),
(72, '0', 1, '98563245', 'FERNANDEZ IBAÑEZ', 'ANGELO VLADIMIR', 'N', 'AREQUIPA', '1965-04-08', 45, 'NA', '...........................', 'calle uruguay 712', 'TACNA', 'NA', '98652314', 'postulante@upt.pe', 0, '2023-09-12 00:00:00'),
(73, '0', 1, '78456333', 'YUFRA MARTINEZ', 'JEFFERSON ANDRE', 'N', 'ICA', '1956-02-29', 56, 'NA', '..............................', 'calle uruguay 712', 'TACNA', 'NA', '987654321', 'postulante@upt.pe', 0, '2023-09-12 00:00:00'),
(74, '0', 1, '59391234', 'LIBERTAD FRANCH', 'MARTIN LUIS', 'N', 'AREQUIPA', '1956-08-04', 45, 'NA', '...................', 'calle uruguay 712', 'TACNA', 'NA', '987654321', 'postulante@upt.pe', 0, '2023-09-12 00:00:00'),
(75, '0', 1, '5213454', 'LIBERTAD FRANCH', 'MARTIN LUIS', 'N', 'AREQUIPA', '1956-08-04', 45, 'NA', '...................', 'calle uruguay 712', 'TACNA', 'NA', '987654321', 'postulante@upt.pe', 0, '2023-09-12 00:00:00'),
(76, '0', 1, '87542123', 'MOLINA FIGUEROA', 'ROBERTO SOLANO', 'N', 'CALAMA', '1956-07-29', 45, 'NA', '......................', 'calle uruguay 712', 'TACNA', 'NA', '987654321', 'postulante@upt.pe', 0, '2023-09-12 00:00:00'),
(77, 'NATU', 0, '48975135', 'Perso', 'Na', 'M', '', NULL, 45, '', NULL, '', NULL, '', '', '', 0, '2023-09-12 15:05:55'),
(78, 'NATU', 0, '89729392', 'prue', 'ba', 'M', '', NULL, 34, '', NULL, '', NULL, '', '', '', 0, '2023-09-12 17:19:57'),
(79, 'NATU', 0, '00498127', 'TORRES, FLORES', 'JUAN', 'M', 'LIMA', '2023-09-13', 42, 'PER', 'SISTEMAS', 'URB. TACNA F-36', 'TACNA', 'PER', '952382217', 'assoflex@hotmail.com', 0, '2023-09-13 19:08:20'),
(80, 'NATU', 0, '823794', 'dawdaw', '', 'F', '', NULL, 34, '', NULL, '', NULL, '', '', '', 0, '2023-09-13 12:23:06'),
(81, 'NATU', 0, '823794', 'dawdaw', 'efefsdfe', 'F', '', NULL, 34, '', NULL, '', NULL, '', '', '', 0, '2023-09-13 12:23:58'),
(82, 'NATU', 0, '89239479', 'joawdo', 'awdoaw', 'M', '', NULL, 34, '', NULL, '', NULL, '', '', '', 0, '2023-09-13 15:11:55'),
(83, 'NATU', 0, '9239723', 'aaaaaaaaaaaaa. aaaa', '', 'M', '', NULL, 34, '', NULL, '', NULL, '', '', '', 0, '2023-09-13 15:19:12'),
(84, 'NATU', 0, '9239723', 'aaaaaaaaaaaaa', 'aaaa', 'M', '', NULL, 34, '', NULL, '', NULL, '', '', '', 0, '2023-09-13 15:19:22'),
(85, 'NATU', 0, '8289439', 'asdiojaisd', 'apsd', 'M', '', NULL, 34, '', NULL, '', NULL, '', '', '', 0, '2023-09-13 15:21:37'),
(86, 'NATU', 0, '92374297', 'oajiod', 'adkiao', 'M', '', NULL, 34, '', NULL, '', NULL, '', '', '', 0, '2023-09-13 15:22:37'),
(87, 'NATU', 0, '92749379', 'ooooooooo', 'oomwma', 'M', '', NULL, 34, '', NULL, '', NULL, '', '', '', 0, '2023-09-13 16:09:39');

-- --------------------------------------------------------

--
-- Table structure for table `productos`
--

CREATE TABLE `productos` (
  `id_producto` int(11) NOT NULL,
  `nombre_producto` varchar(50) DEFAULT NULL,
  `descripcion_del_producto` text DEFAULT NULL,
  `codigo` varchar(10) DEFAULT NULL,
  `tipo` varchar(4) DEFAULT NULL,
  `tipo_de_unidad` varchar(6) DEFAULT NULL,
  `id_grupo` int(11) DEFAULT NULL,
  `id_central_de_costos` int(11) DEFAULT NULL,
  `id_tipo_de_producto` int(11) DEFAULT NULL,
  `cantidad_de_fracciones` float(10,2) DEFAULT NULL,
  `tipo_de_unidad_de_fracciones` varchar(4) DEFAULT NULL,
  `fecha_de_vigencia` date DEFAULT NULL,
  `stock_min_temporada_baja` int(3) DEFAULT NULL,
  `stock_max_temporada_baja` int(3) DEFAULT NULL,
  `stock_min_temporada_alta` int(3) DEFAULT NULL,
  `stock_max_temporada_alta` int(3) DEFAULT NULL,
  `requiere_programacion` int(1) DEFAULT NULL,
  `tiempo_estimado` varchar(5) DEFAULT NULL,
  `codigo_habilidad` varchar(20) DEFAULT NULL,
  `tipo_comision` varchar(10) DEFAULT NULL,
  `costo_unitario` decimal(10,2) DEFAULT NULL,
  `costo_mano_de_obra` decimal(10,2) DEFAULT NULL,
  `costo_adicional` decimal(10,2) DEFAULT NULL,
  `porcentaje_margen` decimal(10,2) DEFAULT NULL,
  `precio_venta_01` decimal(10,2) DEFAULT NULL,
  `precio_venta_02` decimal(10,2) DEFAULT NULL,
  `precio_venta_03` decimal(10,2) DEFAULT NULL,
  `preparacion` text DEFAULT NULL,
  `id_impresora` int(2) DEFAULT NULL,
  `activo` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `productos`
--

INSERT INTO `productos` (`id_producto`, `nombre_producto`, `descripcion_del_producto`, `codigo`, `tipo`, `tipo_de_unidad`, `id_grupo`, `id_central_de_costos`, `id_tipo_de_producto`, `cantidad_de_fracciones`, `tipo_de_unidad_de_fracciones`, `fecha_de_vigencia`, `stock_min_temporada_baja`, `stock_max_temporada_baja`, `stock_min_temporada_alta`, `stock_max_temporada_alta`, `requiere_programacion`, `tiempo_estimado`, `codigo_habilidad`, `tipo_comision`, `costo_unitario`, `costo_mano_de_obra`, `costo_adicional`, `porcentaje_margen`, `precio_venta_01`, `precio_venta_02`, `precio_venta_03`, `preparacion`, `id_impresora`, `activo`) VALUES
(70, 'Cebolla blanca', NULL, 'PRD000006', 'PRD', 'KILO', 6, 12, 10, NULL, NULL, '2023-09-01', 3, 5, 3, 7, NULL, NULL, NULL, NULL, NULL, 0.00, 0.00, 0.00, 0.50, 1.00, 0.50, NULL, NULL, 1),
(71, 'Papa', NULL, 'PRD000002', 'PRD', 'KILO', 6, 11, 10, NULL, NULL, '2023-09-01', 1, 2, 1, 2, NULL, NULL, NULL, NULL, NULL, 0.00, 0.00, 0.00, 0.30, 0.30, 0.30, NULL, NULL, 1),
(72, 'Carne', NULL, 'PRD000003', 'PRD', 'KILO', 6, 11, 10, NULL, NULL, '2023-09-01', 1, 2, 1, 2, NULL, NULL, NULL, NULL, NULL, 0.00, 0.00, 0.00, 10.00, 10.00, 10.00, NULL, NULL, 1),
(73, 'Tomate', NULL, 'PRD000004', 'PRD', 'KILO', 6, 11, 10, NULL, NULL, '2023-09-01', 1, 2, 1, 2, NULL, NULL, NULL, NULL, NULL, 0.00, 0.00, 0.00, 0.40, 0.40, 0.40, NULL, NULL, 1),
(74, 'Lomo Saltado', 'Descripción de Lomo Saltado', 'RST000001', 'RST', 'UNIDAD', 9, 12, 12, NULL, NULL, '2023-09-01', NULL, NULL, NULL, NULL, NULL, '30', NULL, NULL, NULL, 0.00, 0.00, 0.00, 20.00, 19.00, 18.00, '    Primero, calienta una sartén antiadherente a fuego medio y agrega un poco de mantequilla. Rompe un huevo y viértelo en la sartén, sazona con sal y pimienta al gusto. Mientras el huevo se cocina, tuesta dos rebanadas de pan hasta que estén doradas. Una vez que el huevo esté listo, colócalo en una de las rebanadas de pan y añade algunas hojas de lechuga fresca. Cubre con la otra rebanada de pan y ¡listo!', NULL, 1),
(76, 'Loción de masaje', NULL, 'PRD000005', 'PRD', 'LITRO', 7, 9, 11, NULL, NULL, '2023-09-01', 1, 2, 1, 2, NULL, NULL, NULL, NULL, NULL, 0.00, 0.00, 0.00, 10.00, 10.00, 10.00, NULL, NULL, 1),
(77, 'Masaje relajante', 'Descripción de masaje', 'SRV001', 'SRV', 'UNIDAD', 7, 9, 12, NULL, NULL, '2023-09-01', NULL, NULL, NULL, NULL, 1, '30', '546', NULL, NULL, 0.00, 0.00, 0.00, 50.00, 50.00, 50.00, NULL, NULL, 1),
(78, 'Dia de la madre', 'Paquete promocional por un día especial', 'PQT001', 'PAQ', NULL, 6, NULL, 12, NULL, NULL, '2023-09-01', NULL, NULL, NULL, NULL, NULL, '30', NULL, NULL, NULL, 0.00, 0.00, 0.00, 90.00, 90.00, 90.00, NULL, NULL, 1),
(79, 'HAB. MATRIMONIAL', 'HOSPEDAJE MATRIMONIAL', 'SRV001', 'SVH', NULL, 14, 10, 12, NULL, NULL, '2023-09-30', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, 90.00, 70.00, 190.00, 180.00, 185.00, NULL, NULL, 1),
(80, 'HAB. DOBLE', 'HABITACION DOBLE', 'SRV001', 'SVH', NULL, 14, 10, 12, NULL, NULL, '2023-09-02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 85.00, 0.00, 170.00, 150.00, 150.00, NULL, NULL, 1),
(95, 'VINO CASILLERO DEL DIABLO', NULL, 'PRD000009', 'PRD', 'UNIDAD', 6, 12, 12, NULL, NULL, '2023-09-01', 2000, 2000, 2, 2, NULL, NULL, NULL, NULL, NULL, 0.00, 0.00, 0.00, 20.00, 10.00, 10.00, NULL, NULL, 1),
(96, 'HAB. TRIPLE', 'Descripcion de Hospedaje triple', 'SVH000003', 'SVH', NULL, 14, 10, 12, NULL, NULL, '2023-09-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 250.00, 0.00, 0.00, 300.00, 250.00, 260.00, NULL, NULL, 1),
(97, 'CEBICHE CARRETILLERO', 'Descripción de Producto de Prueba', 'RST000002', 'RST', NULL, 9, 12, 12, NULL, NULL, '2023-09-01', NULL, NULL, NULL, NULL, NULL, '30', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '    Primero, calienta una sartén antiadherente a fuego medio y agrega un poco de mantequilla. Rompe un huevo y viértelo en la sartén, sazona con sal y pimienta al gusto. Mientras el huevo se cocina, tuesta dos rebanadas de pan hasta que estén doradas. Una vez que el huevo esté listo, colócalo en una de las rebanadas de pan y añade algunas hojas de lechuga fresca. Cubre con la otra rebanada de pan y ¡listo!', NULL, 1),
(98, 'MASAJE HINDÚ', 'Descripción de Servicio de Prueba', 'SRV000003', 'SRV', NULL, 7, 9, 12, NULL, NULL, '2023-09-01', NULL, NULL, NULL, NULL, 1, '30', '895', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(100, 'HAB. SIMPLE', 'simple', 'SVH000004', 'SVH', NULL, 14, 10, 12, NULL, NULL, '2023-08-31', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0.00, 0.00, 150.00, 140.00, 145.00, NULL, NULL, 1),
(101, 'Manicure', 'a', 'SRV000004', 'SRV', NULL, 7, 9, 12, NULL, NULL, '2023-09-01', NULL, NULL, NULL, NULL, 1, '30', '563', NULL, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(102, 'COCA COLA 1/2 LT', NULL, 'PRD000010', 'PRD', 'UNIDAD', 11, 12, 13, NULL, NULL, NULL, 12, 18, 32, 60, NULL, NULL, NULL, NULL, NULL, 0.00, 2.00, 60.00, 4.00, 4.00, 3.50, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `productospaquete`
--

CREATE TABLE `productospaquete` (
  `id_paquete` int(11) NOT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `id_producto_producto` int(11) DEFAULT NULL,
  `cantidad` decimal(12,4) DEFAULT NULL,
  `tipo_de_unidad` varchar(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `productospaquete`
--

INSERT INTO `productospaquete` (`id_paquete`, `id_producto`, `id_producto_producto`, `cantidad`, `tipo_de_unidad`) VALUES
(11, 78, 74, 3.0000, 'UNID'),
(13, 78, 79, 1.0000, '');

-- --------------------------------------------------------

--
-- Table structure for table `productosreceta`
--

CREATE TABLE `productosreceta` (
  `id_receta` int(11) NOT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `id_producto_insumo` int(11) DEFAULT NULL,
  `cantidad` decimal(12,4) DEFAULT NULL,
  `tipo_de_unidad` varchar(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `productosreceta`
--

INSERT INTO `productosreceta` (`id_receta`, `id_producto`, `id_producto_insumo`, `cantidad`, `tipo_de_unidad`) VALUES
(35, 74, 70, 3.0000, 'UNID'),
(36, 74, 71, 3.0000, 'KILO'),
(37, 74, 72, 0.5000, 'KILO'),
(38, 74, 73, 2.0000, 'KILO'),
(44, 97, 71, 0.2000, 'KILO'),
(45, 97, 70, 0.2000, 'KILO'),
(47, 98, 95, 1.0000, 'UNID');

-- --------------------------------------------------------

--
-- Table structure for table `recibo_de_pago`
--

CREATE TABLE `recibo_de_pago` (
  `Id_recibo_pago` int(11) NOT NULL,
  `id_comprobante_ventas` int(11) DEFAULT NULL,
  `id_unidad_de_negocio` int(11) DEFAULT NULL,
  `tipo_movimiento` varchar(2) DEFAULT NULL,
  `nro_recibo` varchar(15) DEFAULT NULL,
  `nro_de_caja` int(11) DEFAULT NULL,
  `medio_pago` varchar(3) DEFAULT NULL,
  `nro_voucher` varchar(15) DEFAULT NULL,
  `moneda` varchar(3) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `total` decimal(12,2) DEFAULT NULL,
  `nro_cierre_turno` varchar(10) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `fecha_hora_registro` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `recibo_de_pago`
--

INSERT INTO `recibo_de_pago` (`Id_recibo_pago`, `id_comprobante_ventas`, `id_unidad_de_negocio`, `tipo_movimiento`, `nro_recibo`, `nro_de_caja`, `medio_pago`, `nro_voucher`, `moneda`, `fecha`, `total`, `nro_cierre_turno`, `id_usuario`, `fecha_hora_registro`) VALUES
(28, 62, 3, 'SA', 'RE01-000019', 1, 'EFE', '', 'PEN', '2023-09-13 17:09:14', 15.00, '1', 12, '2023-09-13 22:09:14'),
(29, 62, 3, 'SA', 'RE01-000020', 1, 'YAP', '549684984', 'PEN', '2023-09-13 17:09:37', 20.00, '1', 12, '2023-09-13 22:09:37'),
(30, 62, 3, 'SA', 'RE01-000021', 1, 'PLI', '89481691616', 'PEN', '2023-09-13 17:09:53', 25.00, '1', 12, '2023-09-13 22:09:53'),
(31, 63, 3, 'SA', 'RE01-000022', 1, 'EFE', '', 'PEN', '2023-09-13 17:16:47', 1.00, '1', 12, '2023-09-13 22:16:47');

-- --------------------------------------------------------

--
-- Table structure for table `region`
--

CREATE TABLE `region` (
  `id_region` int(11) NOT NULL,
  `id_pais` int(11) DEFAULT NULL,
  `region` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reservahabitaciones`
--

CREATE TABLE `reservahabitaciones` (
  `id_reserva_habitaciones` int(11) NOT NULL,
  `id_unidad_de_negocio` int(11) DEFAULT NULL,
  `nro_reserva` varchar(20) DEFAULT NULL,
  `nro_habitacion` varchar(10) DEFAULT NULL,
  `fecha_ingreso` date DEFAULT NULL,
  `fecha_salida` date DEFAULT NULL,
  `nro_noches` int(4) DEFAULT NULL,
  `precio_unitario` float DEFAULT NULL,
  `precio_total` float DEFAULT NULL,
  `nro_personas` int(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservahabitaciones`
--

INSERT INTO `reservahabitaciones` (`id_reserva_habitaciones`, `id_unidad_de_negocio`, `nro_reserva`, `nro_habitacion`, `fecha_ingreso`, `fecha_salida`, `nro_noches`, `precio_unitario`, `precio_total`, `nro_personas`) VALUES
(56, 3, 'RE23000001', '204', '2023-09-14', '2023-09-17', 3, 250, 1, 2),
(57, 3, 'RE23000001', '202', '2023-09-14', '2023-09-17', 3, 150, 1, 2),
(58, 3, 'RE23000001', '205', '2023-09-14', '2023-09-17', 3, 170, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `reservas`
--

CREATE TABLE `reservas` (
  `id_reserva` int(11) NOT NULL,
  `id_unidad_de_negocio` int(11) DEFAULT NULL,
  `nro_reserva` varchar(20) DEFAULT NULL,
  `nro_registro_maestro` varchar(15) DEFAULT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `lugar_procedencia` varchar(50) DEFAULT NULL,
  `id_modalidad` int(3) DEFAULT NULL,
  `fecha_llegada` date DEFAULT NULL,
  `hora_llegada` time DEFAULT NULL,
  `fecha_salida` date DEFAULT NULL,
  `tipo_transporte` varchar(20) DEFAULT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `observaciones_hospedaje` text DEFAULT NULL,
  `observaciones_pago` text DEFAULT NULL,
  `nro_personas` int(3) DEFAULT NULL,
  `nro_adultos` int(3) DEFAULT NULL,
  `nro_niños` int(3) DEFAULT NULL,
  `nro_infantes` int(3) DEFAULT NULL,
  `monto_total` float(12,2) DEFAULT NULL,
  `adelanto` float(12,2) DEFAULT NULL,
  `porcentaje_pago` int(3) DEFAULT NULL,
  `fecha_pago` date DEFAULT NULL,
  `forma_pago` varchar(20) DEFAULT NULL,
  `estado_pago` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservas`
--

INSERT INTO `reservas` (`id_reserva`, `id_unidad_de_negocio`, `nro_reserva`, `nro_registro_maestro`, `nombre`, `lugar_procedencia`, `id_modalidad`, `fecha_llegada`, `hora_llegada`, `fecha_salida`, `tipo_transporte`, `telefono`, `observaciones_hospedaje`, `observaciones_pago`, `nro_personas`, `nro_adultos`, `nro_niños`, `nro_infantes`, `monto_total`, `adelanto`, `porcentaje_pago`, `fecha_pago`, `forma_pago`, `estado_pago`) VALUES
(37, 3, 'RE23000001', '', 'ANDER GALARZA', 'ARICA-CHILE', 2, '2023-09-14', '08:00:00', '2023-09-17', 'BUS', '987654231', '...................', '......................', 5, 2, 2, 1, 1.00, 855.00, 50, '2023-09-12', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `rooming`
--

CREATE TABLE `rooming` (
  `id_rooming` int(11) NOT NULL,
  `id_checkin` int(11) NOT NULL,
  `nro_registro_maestro` varchar(15) DEFAULT NULL,
  `nro_habitacion` varchar(10) DEFAULT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `hora` time DEFAULT NULL,
  `nro_personas` int(11) DEFAULT NULL,
  `tarifa` float(12,2) DEFAULT NULL,
  `estado` varchar(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `rooming`
--

INSERT INTO `rooming` (`id_rooming`, `id_checkin`, `nro_registro_maestro`, `nro_habitacion`, `id_producto`, `fecha`, `hora`, `nro_personas`, `tarifa`, `estado`) VALUES
(396, 162, 'HT23000043', '201', 79, '2023-09-06', '08:00:00', 4, 150.00, 'NA'),
(397, 162, 'HT23000043', '201', 79, '2023-09-07', '12:00:00', 4, 150.00, 'NA'),
(398, 162, 'HT23000043', '201', 79, '2023-09-08', '12:00:00', 4, 150.00, 'NA'),
(399, 163, 'HT23000002', '205', 80, '2023-09-11', '08:00:00', 2, 150.00, 'NA'),
(400, 163, 'HT23000002', '205', 80, '2023-09-12', '12:00:00', 2, 150.00, 'NA'),
(401, 163, 'HT23000002', '205', 80, '2023-09-13', '12:00:00', 2, 150.00, 'NA'),
(498, 188, 'HT23000003', '204', 80, '2023-09-17', '08:00:00', 2, 180.00, 'NA'),
(499, 188, 'HT23000003', '204', 80, '2023-09-18', '12:00:00', 2, 180.00, 'NA'),
(500, 188, 'HT23000003', '204', 80, '2023-09-19', '12:00:00', 2, 180.00, 'NA'),
(501, 188, 'HT23000003', '204', 80, '2023-09-20', '12:00:00', 2, 180.00, 'NA'),
(502, 189, 'HT23000028', '204', 80, '2023-09-14', '08:00:00', 2, 170.00, 'NA'),
(503, 189, 'HT23000028', '204', 80, '2023-09-14', '08:00:00', 2, 170.00, 'NA'),
(504, 191, 'HT23000003', '204', 80, '2023-09-17', '08:00:00', 2, 180.00, 'NA'),
(505, 191, 'HT23000003', '204', 80, '2023-09-18', '12:00:00', 2, 180.00, 'NA'),
(506, 191, 'HT23000003', '204', 80, '2023-09-19', '12:00:00', 2, 180.00, 'NA'),
(507, 191, 'HT23000003', '204', 80, '2023-09-20', '12:00:00', 2, 180.00, 'NA'),
(508, 193, 'HT23000003', '204', 80, '2023-09-17', '08:00:00', 2, 180.00, 'NA'),
(509, 193, 'HT23000003', '204', 80, '2023-09-18', '12:00:00', 2, 180.00, 'NA'),
(510, 193, 'HT23000003', '204', 80, '2023-09-19', '12:00:00', 2, 180.00, 'NA'),
(511, 193, 'HT23000003', '204', 80, '2023-09-20', '12:00:00', 2, 180.00, 'NA'),
(512, 194, 'HT23000033', '202', 79, '2023-09-22', '08:00:00', 2, 170.00, 'NA'),
(513, 194, 'HT23000033', '202', 79, '2023-09-23', '12:00:00', 2, 170.00, 'NA'),
(514, 194, 'HT23000033', '202', 79, '2023-09-24', '12:00:00', 2, 170.00, 'NA'),
(515, 194, 'HT23000033', '202', 79, '2023-09-25', '12:00:00', 2, 170.00, 'NA'),
(516, 194, 'HT23000033', '202', 79, '2023-09-26', '12:00:00', 2, 170.00, 'NA'),
(517, 195, 'HT23000003', '204', 80, '2023-09-17', '08:00:00', 2, 180.00, 'NA'),
(518, 195, 'HT23000003', '204', 80, '2023-09-18', '12:00:00', 2, 180.00, 'NA'),
(519, 195, 'HT23000003', '204', 80, '2023-09-19', '12:00:00', 2, 180.00, 'NA'),
(520, 195, 'HT23000003', '204', 80, '2023-09-20', '12:00:00', 2, 180.00, 'NA'),
(521, 196, 'HT23000003', '204', 80, '2023-09-17', '08:00:00', 2, 180.00, 'NA'),
(522, 196, 'HT23000003', '204', 80, '2023-09-18', '12:00:00', 2, 180.00, 'NA'),
(523, 196, 'HT23000003', '204', 80, '2023-09-19', '12:00:00', 2, 180.00, 'NA'),
(524, 196, 'HT23000003', '204', 80, '2023-09-20', '12:00:00', 2, 180.00, 'NA'),
(525, 197, 'HT23000003', '204', 80, '2023-09-17', '08:00:00', 2, 180.00, 'NA'),
(526, 197, 'HT23000003', '204', 80, '2023-09-18', '12:00:00', 2, 180.00, 'NA'),
(527, 197, 'HT23000003', '204', 80, '2023-09-19', '12:00:00', 2, 180.00, 'NA'),
(528, 197, 'HT23000003', '204', 80, '2023-09-20', '12:00:00', 2, 180.00, 'NA'),
(529, 200, 'HT23000037', '201', 79, '2023-09-12', '10:20:00', 3, 300.00, 'NA'),
(530, 200, 'HT23000037', '201', 79, '2023-09-13', '12:00:00', 3, 300.00, 'NA'),
(531, 200, 'HT23000037', '201', 79, '2023-09-14', '12:00:00', 3, 300.00, 'NA'),
(532, 200, 'HT23000037', '201', 79, '2023-09-15', '12:00:00', 3, 300.00, 'NA'),
(533, 200, 'HT23000037', '201', 79, '2023-09-16', '12:00:00', 3, 300.00, 'NA'),
(534, 200, 'HT23000037', '201', 79, '2023-09-17', '12:00:00', 3, 300.00, 'NA'),
(535, 200, 'HT23000037', '201', 79, '2023-09-18', '12:00:00', 3, 300.00, 'NA'),
(536, 201, 'HT23000038', '203', 79, '2023-09-13', '08:00:00', 1, 150.00, 'NA'),
(537, 201, 'HT23000038', '203', 79, '2023-09-14', '12:00:00', 1, 150.00, 'NA'),
(538, 202, 'HT23000039', '202', 79, '2023-09-14', '08:00:00', 1, 170.00, 'NA'),
(539, 202, 'HT23000039', '202', 79, '2023-09-15', '12:00:00', 1, 170.00, 'NA'),
(540, 202, 'HT23000039', '202', 79, '2023-09-16', '12:00:00', 1, 170.00, 'NA');

-- --------------------------------------------------------

--
-- Table structure for table `terapistas`
--

CREATE TABLE `terapistas` (
  `id_profesional` int(11) NOT NULL,
  `id_persona` int(11) DEFAULT NULL,
  `tipo_documento` int(2) DEFAULT NULL,
  `nro_documento` varchar(20) DEFAULT NULL,
  `apellidos` varchar(40) DEFAULT NULL,
  `nombres` varchar(40) DEFAULT NULL,
  `sexo` varchar(1) DEFAULT NULL,
  `fecha_de_nacimiento` date DEFAULT NULL,
  `lugar_de_nacimiento` varchar(20) DEFAULT NULL,
  `estado_civil` varchar(2) DEFAULT NULL,
  `nombre_del_conyugue` varchar(60) DEFAULT NULL,
  `tipo_de_cliente` varchar(3) DEFAULT NULL,
  `direccion` varchar(60) DEFAULT NULL,
  `distrito` varchar(30) DEFAULT NULL,
  `provincia` varchar(30) DEFAULT NULL,
  `telefono` varchar(12) DEFAULT NULL,
  `celular` varchar(12) DEFAULT NULL,
  `Email` varchar(50) DEFAULT NULL,
  `contacto_de_Emergencia` varchar(60) DEFAULT NULL,
  `direccion_familia` varchar(60) DEFAULT NULL,
  `telefono_familia` varchar(12) DEFAULT NULL,
  `compania_que_pertenece` varchar(50) DEFAULT NULL,
  `fecha_ingreso` date DEFAULT NULL,
  `baja` int(1) DEFAULT NULL,
  `fecha_de_baja` date DEFAULT NULL,
  `hora_de_ingreso` time DEFAULT NULL,
  `hora_de_salida` time DEFAULT NULL,
  `hora_de_ingreso2` time DEFAULT NULL,
  `hora_de_salida2` time DEFAULT NULL,
  `dia_descanso` int(1) DEFAULT NULL,
  `area_de_trabajo` varchar(20) DEFAULT NULL,
  `cargo` varchar(20) DEFAULT NULL,
  `usuario` varchar(20) DEFAULT NULL,
  `clave_acceso` varchar(20) DEFAULT NULL,
  `nro_autogenerado` varchar(20) DEFAULT NULL,
  `nro_cussp` varchar(30) DEFAULT NULL,
  `tipo_de_trabajo` varchar(30) DEFAULT NULL,
  `haber_basico` int(4) DEFAULT NULL,
  `asignacion_familiar` int(1) DEFAULT NULL,
  `nro_hijos` int(2) DEFAULT NULL,
  `dependiente` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `terapistas`
--

INSERT INTO `terapistas` (`id_profesional`, `id_persona`, `tipo_documento`, `nro_documento`, `apellidos`, `nombres`, `sexo`, `fecha_de_nacimiento`, `lugar_de_nacimiento`, `estado_civil`, `nombre_del_conyugue`, `tipo_de_cliente`, `direccion`, `distrito`, `provincia`, `telefono`, `celular`, `Email`, `contacto_de_Emergencia`, `direccion_familia`, `telefono_familia`, `compania_que_pertenece`, `fecha_ingreso`, `baja`, `fecha_de_baja`, `hora_de_ingreso`, `hora_de_salida`, `hora_de_ingreso2`, `hora_de_salida2`, `dia_descanso`, `area_de_trabajo`, `cargo`, `usuario`, `clave_acceso`, `nro_autogenerado`, `nro_cussp`, `tipo_de_trabajo`, `haber_basico`, `asignacion_familiar`, `nro_hijos`, `dependiente`) VALUES
(15, 10, 0, '12345678', 'Garcia', 'Maria', 'F', '1990-03-15', 'Lima', 'SO', 'Andres Barraza', 'PEX', 'Av. San Martin 123', 'Miraflores', 'Lima', '012345678', '987654321', 'maria@example.com', 'Juan Perez', 'Av. Arequipa 456', '567890123', 'ABC Company', '2023-08-02', 0, '0000-00-00', '08:00:00', '17:00:00', '12:00:00', '13:00:00', 0, 'Ventas', 'Vendedor', 'mariag', 'password123', 'AUTO-123', 'C-7890', 'Tiempo completo', 1000, 1, 2, 0),
(16, 11, 0, '87889823', 'Batista, Moreno', 'Merry Anna ', 'F', '1956-07-29', 'PERU', 'CA', 'Eduardo Aguirre', 'PTC', 'calle uruguay 712', 'TACNA', 'TACNA', '987654231', '987654321', 'example@ugf.com', 'Delia', 'calle uruguay 712', '485236', 'SolRadiante', '2023-08-02', 0, '0000-00-00', '00:00:00', '00:00:00', '14:00:00', '19:00:00', 4, 'Masajes', 'Masajista', 'merry12', '123', '123', '213', 'Masajes', 1000, 1, 2, 0),
(17, 34, 0, '00498126', 'CHAVEZ, ORMEÑO', 'JOSE', 'M', '1970-09-24', 'TRUJILLO', 'DI', '', 'PTC', 'URB. TACNA F-36', 'TACNA', 'TACNA', '', '962075545', 'assoflex@hotmail.com', '', '', '', '', '2023-08-11', 0, '0000-00-00', '00:00:00', '00:00:00', '14:00:00', '18:00:00', 7, '', 'DPTO SISTEMAS', 'JOSE CHAVEZ', '123', '', '', '', 0, 0, 0, 0),
(20, 11, 0, '12345678', 'Garcio', 'Mario', 'M', '1990-03-15', 'Lima', 'SO', 'Andres Barraza', 'PEX', 'Av. San Martin 123', 'Miraflores', 'Lima', '012345678', '987654321', 'maria@example.com', 'Juan Perez', 'Av. Arequipa 456', '567890123', 'ABC Company', '2023-08-02', 0, '0000-00-00', '08:00:00', '17:00:00', '12:00:00', '13:00:00', 0, 'Ventas', 'Vendedor', 'mariag', 'password123', 'AUTO-123', 'C-7890', 'Tiempo completo', 1000, 1, 2, 0),
(21, 51, 0, '78451236', 'Jaramillo, Buenavista', 'Guillermo Raul', 'M', '1995-05-05', 'PERU', 'CA', 'Graciela Fousto', 'PTC', 'calle uruguay 712', 'TACNA', 'TACNA', '987654231', '987654321', 'example@ugf.com', 'Delia', 'calle uruguay 712', '485236', 'adddda', '2023-08-28', 0, '0000-00-00', '08:00:00', '13:00:00', '14:00:00', '18:00:00', 3, 'Masajes', 'Masajista', 'Guillermo09', '123', '123', '123', 'Masajes', 1000, 1, 2, 1),
(22, 52, 0, '78965423', 'Gutierrez , Eduardo', 'Carlos', 'M', '1965-07-08', 'PERU', 'CA', 'Eduardo Aguirre', 'PTC', 'calle uruguay 712', 'TACNA', 'TACNA', '987654231', '987654321', 'example@ugf.com', 'Delia', 'calle uruguay 712', '485236', 'CortesExpress', '2023-08-29', 0, '0000-00-00', '08:00:00', '13:00:00', '12:00:00', '18:00:00', 3, 'Masajes', 'Masajista', 'usuarioX', '123', '123', '213', 'Masajes', 1000, 1, 2, 1),
(23, 54, 0, '00498120', 'TORRES, CARDONA', 'JUAN', 'M', '1970-01-12', 'LIMA', 'SO', 'JUANA', 'PTC', 'URB. TACNA F-36', 'TACNA', 'TACNA', '242515', '962075545', 'jhons26@gmail.com', 'JUAN', 'SAN CLEMENTE 3434', '324324', 'EPS', '2023-08-31', 0, '0000-00-00', '09:00:00', '13:00:00', '14:00:00', '18:00:00', 1, 'ADMINISTRACION', 'RECEPCIONISTA', '', '', '', '234234', 'ADMINISTRATIVO', 1000, 0, 0, 0),
(24, 79, 0, '00498127', 'TORRES, FLORES', 'JUAN', 'M', '1980-12-12', 'LIMA', 'SO', 'JUANA', 'PTC', 'URB. TACNA F-36', 'TACNA', 'TACNA', '242515', '952382217', 'assoflex@hotmail.com', 'MARIA', 'SAN CLEMENTE 3434', '', '', '2023-09-13', 0, '0000-00-00', '08:00:00', '12:00:00', '00:00:00', '00:00:00', 1, 'ADMINISTRACION', 'SISTEMAS', '', '', '', '11111', 'ADMINISTRATIVO', 200, 0, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `terapistashabilidades`
--

CREATE TABLE `terapistashabilidades` (
  `id_terapistas_habilidad` int(11) NOT NULL,
  `id_persona` int(11) DEFAULT NULL,
  `id_habilidad` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `terapistashabilidades`
--

INSERT INTO `terapistashabilidades` (`id_terapistas_habilidad`, `id_persona`, `id_habilidad`) VALUES
(3, 11, 1),
(4, 11, 6),
(8, 34, 5),
(9, 34, 3),
(10, 10, 3),
(11, 11, 1),
(12, 10, 1),
(15, 51, 1),
(16, 51, 3),
(17, 52, 1),
(18, 52, 3),
(19, 54, 4);

-- --------------------------------------------------------

--
-- Table structure for table `tipodeproductos`
--

CREATE TABLE `tipodeproductos` (
  `id_tipo_producto` int(11) NOT NULL,
  `nombre_tipo_de_producto` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tipodeproductos`
--

INSERT INTO `tipodeproductos` (`id_tipo_producto`, `nombre_tipo_de_producto`) VALUES
(10, 'Insumo'),
(11, 'Suministro'),
(12, 'Producto Venta'),
(13, 'Producto de Consumo');

-- --------------------------------------------------------

--
-- Table structure for table `tipodeusuario`
--

CREATE TABLE `tipodeusuario` (
  `id_tipo_de_usuario` int(11) NOT NULL,
  `tipo_de_usuario` varchar(20) NOT NULL,
  `id_usuario_creacion` int(11) NOT NULL,
  `fecha_creacion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tipodeusuario`
--

INSERT INTO `tipodeusuario` (`id_tipo_de_usuario`, `tipo_de_usuario`, `id_usuario_creacion`, `fecha_creacion`) VALUES
(3, 'Supervisor', 123, '2023-07-26 00:00:00'),
(4, 'Recepcionista', 123, '2023-07-26 00:00:00'),
(5, 'Ventas', 123, '2023-07-26 00:00:00'),
(6, 'Logistica', 123, '2023-07-26 00:00:00'),
(7, 'Administrativo', 123, '2023-07-26 00:00:00'),
(8, 'Terapeuta', 123, '2023-07-26 00:00:00'),
(9, 'Pedidos', 123, '2023-07-26 00:00:00'),
(10, 'Mantenimientos', 123, '2023-07-26 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `unidaddenegocio`
--

CREATE TABLE `unidaddenegocio` (
  `id_unidad_de_negocio` int(11) NOT NULL,
  `codigo_unidad_de_negocio` varchar(10) NOT NULL,
  `nombre_unidad_de_negocio` varchar(40) NOT NULL,
  `id_usuario_creacion` int(11) NOT NULL,
  `fecha_creacion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `unidaddenegocio`
--

INSERT INTO `unidaddenegocio` (`id_unidad_de_negocio`, `codigo_unidad_de_negocio`, `nombre_unidad_de_negocio`, `id_usuario_creacion`, `fecha_creacion`) VALUES
(3, 'UN001', 'HOTEL SPA ARENAS', 456, '2023-07-26 00:00:00'),
(4, 'UN002', 'SUMAQ WASI', 456, '2023-07-26 00:00:00'),
(5, 'UN003', 'ENSUEÑO', 456, '2023-07-26 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nro_doc` varchar(14) NOT NULL,
  `usuario` varchar(40) NOT NULL,
  `id_persona` int(11) NOT NULL,
  `clave` varchar(20) NOT NULL,
  `clave_apertura` varchar(20) NOT NULL,
  `creacion_fecha_hora` datetime DEFAULT NULL,
  `id_unidad_de_negocio` int(11) NOT NULL,
  `cargo` varchar(30) NOT NULL,
  `id_tipo_de_usuario` int(11) NOT NULL,
  `hora_ingreso` time DEFAULT NULL,
  `hora_salida` time DEFAULT NULL,
  `activo` int(11) NOT NULL,
  `fecha_cese` datetime DEFAULT NULL,
  `id_usuario_creacion` int(11) NOT NULL,
  `fecha_creacion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nro_doc`, `usuario`, `id_persona`, `clave`, `clave_apertura`, `creacion_fecha_hora`, `id_unidad_de_negocio`, `cargo`, `id_tipo_de_usuario`, `hora_ingreso`, `hora_salida`, `activo`, `fecha_cese`, `id_usuario_creacion`, `fecha_creacion`) VALUES
(12, '123456', 'admin', 4, '123', '123', '2023-07-31 18:48:40', 4, 'prueba2', 9, '14:47:00', '11:47:00', 1, '2023-07-31 00:00:00', 0, '2023-07-31 00:00:00'),
(14, '789456', 'admin', 5, '123', '123', '2023-08-01 17:46:34', 4, 'sub-generente', 7, '08:00:00', '15:46:00', 1, '2023-08-01 00:00:00', 0, '2023-08-01 00:00:00'),
(16, '00498126', 'JOSE CHAVEZ', 17, '1234', '1234', '2023-08-11 20:13:12', 3, 'DPTO SISTEMAS', 3, '09:00:00', '13:00:00', 1, '0000-00-00 00:00:00', 0, '2023-08-11 00:00:00'),
(19, '12345678', 'mariag', 10, '123', '123', '2023-08-25 17:11:57', 3, 'prueba2', 4, '08:00:00', '18:00:00', 1, '0000-00-00 00:00:00', 0, '2023-08-25 00:00:00'),
(21, '76368627', 'abraham01', 40, '123', '123', '2023-08-29 17:22:30', 3, 'Masajista', 8, '08:00:00', '13:00:00', 1, '0000-00-00 00:00:00', 0, '2023-08-29 00:00:00'),
(22, '00498127', 'CARLA', 79, '123', '123', '2023-09-13 19:10:32', 4, 'SISTEMAS', 3, '08:00:00', '13:00:00', 1, '0000-00-00 00:00:00', 0, '2023-09-13 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `usuariosmodulos`
--

CREATE TABLE `usuariosmodulos` (
  `id_usuario_modulo` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_modulo` int(11) DEFAULT NULL,
  `tiene_acceso` int(1) DEFAULT NULL,
  `acceso_consulta` int(1) DEFAULT NULL,
  `acceso_modificacion` int(1) DEFAULT NULL,
  `acceso_creacion` int(1) DEFAULT NULL,
  `apertura_fecha_hora` datetime DEFAULT NULL,
  `cese_fecha_hora` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usuariosmodulos`
--

INSERT INTO `usuariosmodulos` (`id_usuario_modulo`, `id_usuario`, `id_modulo`, `tiene_acceso`, `acceso_consulta`, `acceso_modificacion`, `acceso_creacion`, `apertura_fecha_hora`, `cese_fecha_hora`) VALUES
(1, 12, 6, 1, 1, 1, 1, '2023-08-01 17:19:11', '2023-08-01 17:19:11'),
(2, 12, 7, 1, 1, 1, 1, '2023-08-01 17:19:11', '2023-08-01 17:19:11'),
(5, 14, 6, 1, 1, 1, 1, '2023-08-01 17:46:53', '2023-08-01 17:46:53'),
(6, 14, 7, 1, 1, 1, 1, '2023-08-01 17:46:53', '2023-08-01 17:46:53'),
(7, 14, 8, 1, 1, 1, 1, '2023-08-01 17:46:53', '2023-08-01 17:46:53'),
(8, 14, 7, 1, 1, 1, 1, '2023-08-03 19:28:24', '2023-08-03 19:28:24'),
(14, 21, 5, 1, 1, 1, 1, '2023-08-29 17:22:30', '0000-00-00 00:00:00'),
(15, 21, 6, 1, 0, 1, 0, '2023-08-29 17:22:31', '0000-00-00 00:00:00'),
(16, 21, 6, 1, 0, 1, 0, '2023-08-29 17:22:31', '0000-00-00 00:00:00'),
(17, 22, 1, 0, 0, 0, 0, '2023-09-13 19:10:32', '0000-00-00 00:00:00'),
(18, 22, 2, 0, 0, 0, 0, '2023-09-13 19:10:32', '0000-00-00 00:00:00'),
(19, 22, 3, 0, 0, 0, 0, '2023-09-13 19:10:33', '0000-00-00 00:00:00'),
(20, 22, 3, 0, 0, 0, 0, '2023-09-13 19:10:33', '0000-00-00 00:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `acompanantes`
--
ALTER TABLE `acompanantes`
  ADD PRIMARY KEY (`id_acompanante`);

--
-- Indexes for table `centraldecostos`
--
ALTER TABLE `centraldecostos`
  ADD PRIMARY KEY (`id_central_de_costos`);

--
-- Indexes for table `cheking`
--
ALTER TABLE `cheking`
  ADD PRIMARY KEY (`id_checkin`),
  ADD KEY `id_unidad_de_negocio` (`id_unidad_de_negocio`),
  ADD KEY `id_persona` (`id_persona`),
  ADD KEY `id_modalidad` (`id_modalidad`);

--
-- Indexes for table `comprobante_detalle`
--
ALTER TABLE `comprobante_detalle`
  ADD PRIMARY KEY (`id_comprobante_detalle`),
  ADD KEY `id_comprobante_ventas` (`id_comprobante_ventas`),
  ADD KEY `id_documentos_detalle` (`id_documentos_detalle`),
  ADD KEY `id_producto` (`id_producto`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indexes for table `comprobante_ventas`
--
ALTER TABLE `comprobante_ventas`
  ADD PRIMARY KEY (`id_comprobante_ventas`),
  ADD KEY `id_unidad_de_negocio` (`id_unidad_de_negocio`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indexes for table `config`
--
ALTER TABLE `config`
  ADD PRIMARY KEY (`id_config`);

--
-- Indexes for table `documento_detalle`
--
ALTER TABLE `documento_detalle`
  ADD PRIMARY KEY (`id_documentos_detalle`),
  ADD KEY `id_documento_movimiento` (`id_documento_movimiento`),
  ADD KEY `id_producto` (`id_producto`),
  ADD KEY `id_acompanate` (`id_acompanate`);

--
-- Indexes for table `documento_movimiento`
--
ALTER TABLE `documento_movimiento`
  ADD PRIMARY KEY (`id_documento_movimiento`),
  ADD KEY `id_unidad_de_negocio` (`id_unidad_de_negocio`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indexes for table `fe_comprobante`
--
ALTER TABLE `fe_comprobante`
  ADD PRIMARY KEY (`IdFeC`),
  ADD KEY `NroMov` (`NroMov`),
  ADD KEY `serieComprobante` (`serieComprobante`,`nroComprobante`),
  ADD KEY `xestado` (`xestado`,`xdocnro`);

--
-- Indexes for table `fe_items`
--
ALTER TABLE `fe_items`
  ADD PRIMARY KEY (`IdfeItem`),
  ADD KEY `NroMov` (`NroMov`,`serieComprobante`,`nroComprobante`);

--
-- Indexes for table `gruposdelacarta`
--
ALTER TABLE `gruposdelacarta`
  ADD PRIMARY KEY (`id_grupo`);

--
-- Indexes for table `grupo_modulo`
--
ALTER TABLE `grupo_modulo`
  ADD PRIMARY KEY (`id_grupo_modulo`);

--
-- Indexes for table `habilidadesprofesionales`
--
ALTER TABLE `habilidadesprofesionales`
  ADD PRIMARY KEY (`id_habilidad`);

--
-- Indexes for table `habitaciones`
--
ALTER TABLE `habitaciones`
  ADD PRIMARY KEY (`id_habitacion`),
  ADD KEY `id_unidad_de_negocio` (`id_unidad_de_negocio`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indexes for table `impresoras`
--
ALTER TABLE `impresoras`
  ADD PRIMARY KEY (`id_impresora`);

--
-- Indexes for table `modalidadcliente`
--
ALTER TABLE `modalidadcliente`
  ADD PRIMARY KEY (`id_modalidad`);

--
-- Indexes for table `modulos`
--
ALTER TABLE `modulos`
  ADD PRIMARY KEY (`id_modulo`),
  ADD KEY `id_grupo_modulo` (`id_grupo_modulo`);

--
-- Indexes for table `pais`
--
ALTER TABLE `pais`
  ADD PRIMARY KEY (`id_pais`);

--
-- Indexes for table `personanaturaljuridica`
--
ALTER TABLE `personanaturaljuridica`
  ADD PRIMARY KEY (`id_persona`);

--
-- Indexes for table `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id_producto`),
  ADD KEY `id_grupo` (`id_grupo`),
  ADD KEY `id_central_de_costos` (`id_central_de_costos`),
  ADD KEY `id_tipo_de_producto` (`id_tipo_de_producto`),
  ADD KEY `id_impresora` (`id_impresora`);

--
-- Indexes for table `productospaquete`
--
ALTER TABLE `productospaquete`
  ADD PRIMARY KEY (`id_paquete`),
  ADD KEY `id_producto` (`id_producto`),
  ADD KEY `id_producto_producto` (`id_producto_producto`);

--
-- Indexes for table `productosreceta`
--
ALTER TABLE `productosreceta`
  ADD PRIMARY KEY (`id_receta`),
  ADD KEY `id_producto` (`id_producto`),
  ADD KEY `id_producto_insumo` (`id_producto_insumo`);

--
-- Indexes for table `recibo_de_pago`
--
ALTER TABLE `recibo_de_pago`
  ADD PRIMARY KEY (`Id_recibo_pago`),
  ADD KEY `id_comprobante_ventas` (`id_comprobante_ventas`),
  ADD KEY `id_unidad_de_negocio` (`id_unidad_de_negocio`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indexes for table `region`
--
ALTER TABLE `region`
  ADD PRIMARY KEY (`id_region`),
  ADD KEY `id_pais` (`id_pais`);

--
-- Indexes for table `reservahabitaciones`
--
ALTER TABLE `reservahabitaciones`
  ADD PRIMARY KEY (`id_reserva_habitaciones`),
  ADD KEY `id_unidad_de_negocio` (`id_unidad_de_negocio`);

--
-- Indexes for table `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id_reserva`),
  ADD KEY `id_unidad_de_negocio` (`id_unidad_de_negocio`);

--
-- Indexes for table `rooming`
--
ALTER TABLE `rooming`
  ADD PRIMARY KEY (`id_rooming`),
  ADD KEY `id_producto` (`id_producto`),
  ADD KEY `id_checkin` (`id_checkin`);

--
-- Indexes for table `terapistas`
--
ALTER TABLE `terapistas`
  ADD PRIMARY KEY (`id_profesional`),
  ADD KEY `id_persona` (`id_persona`);

--
-- Indexes for table `terapistashabilidades`
--
ALTER TABLE `terapistashabilidades`
  ADD PRIMARY KEY (`id_terapistas_habilidad`),
  ADD KEY `id_persona` (`id_persona`),
  ADD KEY `id_habilidad` (`id_habilidad`);

--
-- Indexes for table `tipodeproductos`
--
ALTER TABLE `tipodeproductos`
  ADD PRIMARY KEY (`id_tipo_producto`);

--
-- Indexes for table `tipodeusuario`
--
ALTER TABLE `tipodeusuario`
  ADD PRIMARY KEY (`id_tipo_de_usuario`),
  ADD KEY `id_usuario_creacion` (`id_usuario_creacion`);

--
-- Indexes for table `unidaddenegocio`
--
ALTER TABLE `unidaddenegocio`
  ADD PRIMARY KEY (`id_unidad_de_negocio`),
  ADD KEY `id_usuario_creacion` (`id_usuario_creacion`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD KEY `id_persona` (`id_persona`),
  ADD KEY `id_unidad_de_negocio` (`id_unidad_de_negocio`),
  ADD KEY `id_tipo_de_usuario` (`id_tipo_de_usuario`);

--
-- Indexes for table `usuariosmodulos`
--
ALTER TABLE `usuariosmodulos`
  ADD PRIMARY KEY (`id_usuario_modulo`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_modulo` (`id_modulo`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `acompanantes`
--
ALTER TABLE `acompanantes`
  MODIFY `id_acompanante` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=295;

--
-- AUTO_INCREMENT for table `centraldecostos`
--
ALTER TABLE `centraldecostos`
  MODIFY `id_central_de_costos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `cheking`
--
ALTER TABLE `cheking`
  MODIFY `id_checkin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=211;

--
-- AUTO_INCREMENT for table `comprobante_detalle`
--
ALTER TABLE `comprobante_detalle`
  MODIFY `id_comprobante_detalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT for table `comprobante_ventas`
--
ALTER TABLE `comprobante_ventas`
  MODIFY `id_comprobante_ventas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `config`
--
ALTER TABLE `config`
  MODIFY `id_config` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `documento_detalle`
--
ALTER TABLE `documento_detalle`
  MODIFY `id_documentos_detalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=416;

--
-- AUTO_INCREMENT for table `documento_movimiento`
--
ALTER TABLE `documento_movimiento`
  MODIFY `id_documento_movimiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT for table `fe_comprobante`
--
ALTER TABLE `fe_comprobante`
  MODIFY `IdFeC` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `fe_items`
--
ALTER TABLE `fe_items`
  MODIFY `IdfeItem` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `gruposdelacarta`
--
ALTER TABLE `gruposdelacarta`
  MODIFY `id_grupo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `grupo_modulo`
--
ALTER TABLE `grupo_modulo`
  MODIFY `id_grupo_modulo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `habilidadesprofesionales`
--
ALTER TABLE `habilidadesprofesionales`
  MODIFY `id_habilidad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `habitaciones`
--
ALTER TABLE `habitaciones`
  MODIFY `id_habitacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `impresoras`
--
ALTER TABLE `impresoras`
  MODIFY `id_impresora` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `modalidadcliente`
--
ALTER TABLE `modalidadcliente`
  MODIFY `id_modalidad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `modulos`
--
ALTER TABLE `modulos`
  MODIFY `id_modulo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `pais`
--
ALTER TABLE `pais`
  MODIFY `id_pais` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personanaturaljuridica`
--
ALTER TABLE `personanaturaljuridica`
  MODIFY `id_persona` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT for table `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT for table `productospaquete`
--
ALTER TABLE `productospaquete`
  MODIFY `id_paquete` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `productosreceta`
--
ALTER TABLE `productosreceta`
  MODIFY `id_receta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `recibo_de_pago`
--
ALTER TABLE `recibo_de_pago`
  MODIFY `Id_recibo_pago` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `region`
--
ALTER TABLE `region`
  MODIFY `id_region` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reservahabitaciones`
--
ALTER TABLE `reservahabitaciones`
  MODIFY `id_reserva_habitaciones` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id_reserva` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `rooming`
--
ALTER TABLE `rooming`
  MODIFY `id_rooming` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=541;

--
-- AUTO_INCREMENT for table `terapistas`
--
ALTER TABLE `terapistas`
  MODIFY `id_profesional` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `terapistashabilidades`
--
ALTER TABLE `terapistashabilidades`
  MODIFY `id_terapistas_habilidad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `tipodeproductos`
--
ALTER TABLE `tipodeproductos`
  MODIFY `id_tipo_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tipodeusuario`
--
ALTER TABLE `tipodeusuario`
  MODIFY `id_tipo_de_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `unidaddenegocio`
--
ALTER TABLE `unidaddenegocio`
  MODIFY `id_unidad_de_negocio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `usuariosmodulos`
--
ALTER TABLE `usuariosmodulos`
  MODIFY `id_usuario_modulo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cheking`
--
ALTER TABLE `cheking`
  ADD CONSTRAINT `cheking_ibfk_1` FOREIGN KEY (`id_unidad_de_negocio`) REFERENCES `unidaddenegocio` (`id_unidad_de_negocio`),
  ADD CONSTRAINT `cheking_ibfk_2` FOREIGN KEY (`id_persona`) REFERENCES `personanaturaljuridica` (`id_persona`),
  ADD CONSTRAINT `cheking_ibfk_3` FOREIGN KEY (`id_modalidad`) REFERENCES `modalidadcliente` (`id_modalidad`);

--
-- Constraints for table `comprobante_detalle`
--
ALTER TABLE `comprobante_detalle`
  ADD CONSTRAINT `comprobante_detalle_ibfk_1` FOREIGN KEY (`id_comprobante_ventas`) REFERENCES `comprobante_ventas` (`id_comprobante_ventas`),
  ADD CONSTRAINT `comprobante_detalle_ibfk_2` FOREIGN KEY (`id_documentos_detalle`) REFERENCES `documento_detalle` (`id_documentos_detalle`),
  ADD CONSTRAINT `comprobante_detalle_ibfk_3` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`),
  ADD CONSTRAINT `comprobante_detalle_ibfk_4` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Constraints for table `comprobante_ventas`
--
ALTER TABLE `comprobante_ventas`
  ADD CONSTRAINT `comprobante_ventas_ibfk_1` FOREIGN KEY (`id_unidad_de_negocio`) REFERENCES `unidaddenegocio` (`id_unidad_de_negocio`),
  ADD CONSTRAINT `comprobante_ventas_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Constraints for table `documento_detalle`
--
ALTER TABLE `documento_detalle`
  ADD CONSTRAINT `documento_detalle_ibfk_1` FOREIGN KEY (`id_documento_movimiento`) REFERENCES `documento_movimiento` (`id_documento_movimiento`),
  ADD CONSTRAINT `documento_detalle_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`),
  ADD CONSTRAINT `documento_detalle_ibfk_3` FOREIGN KEY (`id_acompanate`) REFERENCES `acompanantes` (`id_acompanante`);

--
-- Constraints for table `documento_movimiento`
--
ALTER TABLE `documento_movimiento`
  ADD CONSTRAINT `documento_movimiento_ibfk_1` FOREIGN KEY (`id_unidad_de_negocio`) REFERENCES `unidaddenegocio` (`id_unidad_de_negocio`),
  ADD CONSTRAINT `documento_movimiento_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Constraints for table `habitaciones`
--
ALTER TABLE `habitaciones`
  ADD CONSTRAINT `habitaciones_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `habitaciones_ibfk_3` FOREIGN KEY (`id_unidad_de_negocio`) REFERENCES `unidaddenegocio` (`id_unidad_de_negocio`) ON DELETE CASCADE;

--
-- Constraints for table `modulos`
--
ALTER TABLE `modulos`
  ADD CONSTRAINT `modulos_ibfk_1` FOREIGN KEY (`id_grupo_modulo`) REFERENCES `grupo_modulo` (`id_grupo_modulo`);

--
-- Constraints for table `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`id_grupo`) REFERENCES `gruposdelacarta` (`id_grupo`),
  ADD CONSTRAINT `productos_ibfk_2` FOREIGN KEY (`id_central_de_costos`) REFERENCES `centraldecostos` (`id_central_de_costos`),
  ADD CONSTRAINT `productos_ibfk_3` FOREIGN KEY (`id_tipo_de_producto`) REFERENCES `tipodeproductos` (`id_tipo_producto`),
  ADD CONSTRAINT `productos_ibfk_4` FOREIGN KEY (`id_impresora`) REFERENCES `impresoras` (`id_impresora`);

--
-- Constraints for table `productospaquete`
--
ALTER TABLE `productospaquete`
  ADD CONSTRAINT `productospaquete_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`),
  ADD CONSTRAINT `productospaquete_ibfk_2` FOREIGN KEY (`id_producto_producto`) REFERENCES `productos` (`id_producto`);

--
-- Constraints for table `productosreceta`
--
ALTER TABLE `productosreceta`
  ADD CONSTRAINT `productosreceta_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`),
  ADD CONSTRAINT `productosreceta_ibfk_2` FOREIGN KEY (`id_producto_insumo`) REFERENCES `productos` (`id_producto`);

--
-- Constraints for table `recibo_de_pago`
--
ALTER TABLE `recibo_de_pago`
  ADD CONSTRAINT `recibo_de_pago_ibfk_1` FOREIGN KEY (`id_comprobante_ventas`) REFERENCES `comprobante_ventas` (`id_comprobante_ventas`),
  ADD CONSTRAINT `recibo_de_pago_ibfk_2` FOREIGN KEY (`id_unidad_de_negocio`) REFERENCES `unidaddenegocio` (`id_unidad_de_negocio`),
  ADD CONSTRAINT `recibo_de_pago_ibfk_3` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Constraints for table `region`
--
ALTER TABLE `region`
  ADD CONSTRAINT `region_ibfk_1` FOREIGN KEY (`id_pais`) REFERENCES `pais` (`id_pais`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `reservahabitaciones`
--
ALTER TABLE `reservahabitaciones`
  ADD CONSTRAINT `reservahabitaciones_ibfk_1` FOREIGN KEY (`id_unidad_de_negocio`) REFERENCES `unidaddenegocio` (`id_unidad_de_negocio`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`id_unidad_de_negocio`) REFERENCES `unidaddenegocio` (`id_unidad_de_negocio`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `rooming`
--
ALTER TABLE `rooming`
  ADD CONSTRAINT `rooming_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rooming_ibfk_2` FOREIGN KEY (`id_checkin`) REFERENCES `cheking` (`id_checkin`);

--
-- Constraints for table `terapistas`
--
ALTER TABLE `terapistas`
  ADD CONSTRAINT `terapistas_ibfk_1` FOREIGN KEY (`id_persona`) REFERENCES `personanaturaljuridica` (`id_persona`) ON DELETE NO ACTION ON UPDATE SET NULL;

--
-- Constraints for table `terapistashabilidades`
--
ALTER TABLE `terapistashabilidades`
  ADD CONSTRAINT `terapistashabilidades_ibfk_1` FOREIGN KEY (`id_habilidad`) REFERENCES `habilidadesprofesionales` (`id_habilidad`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `terapistashabilidades_ibfk_2` FOREIGN KEY (`id_persona`) REFERENCES `terapistas` (`id_persona`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`id_persona`) REFERENCES `personanaturaljuridica` (`id_persona`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `usuarios_ibfk_2` FOREIGN KEY (`id_tipo_de_usuario`) REFERENCES `tipodeusuario` (`id_tipo_de_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `usuarios_ibfk_3` FOREIGN KEY (`id_unidad_de_negocio`) REFERENCES `unidaddenegocio` (`id_unidad_de_negocio`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `usuariosmodulos`
--
ALTER TABLE `usuariosmodulos`
  ADD CONSTRAINT `usuariosmodulos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `usuariosmodulos_ibfk_2` FOREIGN KEY (`id_modulo`) REFERENCES `modulos` (`id_modulo`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
