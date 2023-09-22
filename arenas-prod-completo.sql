-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Sep 22, 2023 at 05:52 PM
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
-- Database: `arenas-prod`
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
(1, 'Spa'),
(2, 'Hotel'),
(3, 'Bazar'),
(4, 'Cafeteria'),
(5, 'Salón de Belleza'),
(6, 'Administración');

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
  `por_pagar` decimal(12,2) NOT NULL,
  `estado` smallint(6) NOT NULL DEFAULT 1,
  `id_usuario` int(11) DEFAULT NULL,
  `fecha_hora_registro` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(3, 'MA-2023', 1),
(4, 'SP-2023', 1),
(5, 'CM-2023', 1),
(6, 'PRD', 1),
(7, 'RST', 1),
(8, 'SVH', 1),
(9, 'SRV', 1),
(10, 'PAQ', 1),
(11, 'HT23', 1),
(12, 'GRUPOS', 42),
(13, 'SERIE B', 1),
(14, 'SERIE F', 1),
(15, 'CORR B', 1),
(16, 'CORR F', 1),
(17, 'IGV', 10),
(18, 'RECIBOS SERIE', 1),
(19, 'RECIBOS CORR', 1),
(20, 'PEDIDOS', 1),
(21, 'TURNOS', 1);

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
(1, 1, '001', '001', 'BAZAR'),
(2, 3, '002', '002', 'CAFETERIA'),
(3, 4, '003', '003', 'CIRCUITO DE AGUA'),
(4, 5, '015', '015', 'EVENTOS'),
(5, 6, '005', '005', 'HELADOS'),
(6, 2, '006', '006', 'BEBIDAS'),
(7, 7, '007', '007', 'HIDROTERAPIA'),
(8, 8, '008', '008', 'HOTEL ARENAS'),
(9, 9, '009', '009', 'PACK DAY SPA'),
(10, 10, '011', '011', 'PROGRAMAS VARIOS'),
(11, 11, '012', '012', 'SALON'),
(12, 12, '013', '013', 'SPA'),
(13, 13, '014', '014', 'TRAGOS EN BASE A CERVEZA'),
(14, 14, '016', '016', 'VARIOS'),
(15, 15, '017', '017', 'VIKINGO\'S'),
(16, 1, '018', '001', 'ARTICULOS EN ALQUILER'),
(17, 1, '019', '001', 'ARTICULOS EN REPOSICION'),
(18, 1, '020', '001', 'ARTICULOS PARA LA VENTA'),
(19, 1, '021', '002', 'BEBIDAS CALIENTES'),
(20, 1, '022', '002', 'COCTELES Y CERVEZAS'),
(21, 1, '023', '002', 'ENSALADA FRUTAS'),
(22, 1, '024', '002', 'JUGOS Y ZUMOS'),
(23, 1, '025', '002', 'MILKSHAKES'),
(24, 1, '026', '002', 'PLATOS A LA CARTA'),
(25, 1, '027', '002', 'PORCIONES VARIAS'),
(26, 1, '028', '002', 'POSTRES'),
(27, 1, '029', '002', 'SANDWICH'),
(28, 1, '030', '015', 'ALQUILER DE SALAS Y/O SALONES'),
(29, 1, '031', '015', 'CURSOS Y TALLERES'),
(30, 1, '032', '015', 'SPA PARTY'),
(31, 1, '033', '011', 'ACADEMIA DE NATACION'),
(32, 1, '034', '011', 'AQUAFITNESS'),
(33, 1, '035', '012', 'NAILS SPA'),
(34, 1, '036', '012', 'PELUQUERIA'),
(35, 1, '037', '013', 'CIRCUITO DE AGUA'),
(36, 1, '038', '013', 'DEPILACIONES'),
(37, 1, '039', '013', 'TERAPIA DE MASAJES'),
(38, 1, '040', '013', 'TRATAMIENTOS CORPORALES'),
(39, 1, '041', '013', 'TRATAMIENTOS FACIALES');

-- --------------------------------------------------------

--
-- Table structure for table `grupo_modulo`
--

CREATE TABLE `grupo_modulo` (
  `id_grupo_modulo` int(11) NOT NULL,
  `nombre_grupo_modulo` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(1, '100', 'PELUQUERIA'),
(2, '101', 'DEPILACIONES'),
(3, '102', 'MANICURA'),
(4, '103', 'PEDICURA'),
(5, '104', 'MASAJE'),
(6, '105', 'FACIALES'),
(7, '106', 'PODOLOGIA'),
(8, '107', 'CORPORALES');

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

-- --------------------------------------------------------

--
-- Table structure for table `modalidadcliente`
--

CREATE TABLE `modalidadcliente` (
  `id_modalidad` int(11) NOT NULL,
  `nombre_modalidad` varchar(20) DEFAULT NULL,
  `descripcion_modalidad` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(1, 'Natu', 0, '10498126', 'CHAVEZ, ORMEÑO', 'JOSE', 'M', 'TRUJILLO', '2023-08-11', 52, 'PER', 'DPTO SISTEMAS', 'URB. TACNA F-36', 'TACNA', 'PER', '962075545', 'assoflex@hotmail.com', 0, '2023-08-11 17:20:41');

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

-- --------------------------------------------------------

--
-- Table structure for table `terapistashabilidades`
--

CREATE TABLE `terapistashabilidades` (
  `id_terapistas_habilidad` int(11) NOT NULL,
  `id_persona` int(11) DEFAULT NULL,
  `id_habilidad` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(1, 'Supervisor', 123, '2023-07-26 00:00:00');

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
(1, 'UN001', 'HOTEL SPA ARENAS', 456, '2023-09-11 00:00:00'),
(2, 'UN002', 'SUMAQ WASI', 456, '2023-09-11 00:00:00'),
(3, 'UN003', 'ENSUEÑO', 456, '2023-09-11 00:00:00');

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
(2, '10498126', 'admin', 1, '123', '123', '2023-09-11 10:57:01', 1, 'ADMIN', 1, '08:00:00', '20:00:00', 1, '2030-09-11 00:00:00', 0, '2023-09-11 10:57:01');

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
  MODIFY `id_acompanante` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `centraldecostos`
--
ALTER TABLE `centraldecostos`
  MODIFY `id_central_de_costos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `cheking`
--
ALTER TABLE `cheking`
  MODIFY `id_checkin` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `comprobante_detalle`
--
ALTER TABLE `comprobante_detalle`
  MODIFY `id_comprobante_detalle` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `comprobante_ventas`
--
ALTER TABLE `comprobante_ventas`
  MODIFY `id_comprobante_ventas` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `config`
--
ALTER TABLE `config`
  MODIFY `id_config` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `documento_detalle`
--
ALTER TABLE `documento_detalle`
  MODIFY `id_documentos_detalle` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `documento_movimiento`
--
ALTER TABLE `documento_movimiento`
  MODIFY `id_documento_movimiento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fe_comprobante`
--
ALTER TABLE `fe_comprobante`
  MODIFY `IdFeC` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fe_items`
--
ALTER TABLE `fe_items`
  MODIFY `IdfeItem` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gruposdelacarta`
--
ALTER TABLE `gruposdelacarta`
  MODIFY `id_grupo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `grupo_modulo`
--
ALTER TABLE `grupo_modulo`
  MODIFY `id_grupo_modulo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `habilidadesprofesionales`
--
ALTER TABLE `habilidadesprofesionales`
  MODIFY `id_habilidad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `habitaciones`
--
ALTER TABLE `habitaciones`
  MODIFY `id_habitacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `impresoras`
--
ALTER TABLE `impresoras`
  MODIFY `id_impresora` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `modalidadcliente`
--
ALTER TABLE `modalidadcliente`
  MODIFY `id_modalidad` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `modulos`
--
ALTER TABLE `modulos`
  MODIFY `id_modulo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pais`
--
ALTER TABLE `pais`
  MODIFY `id_pais` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personanaturaljuridica`
--
ALTER TABLE `personanaturaljuridica`
  MODIFY `id_persona` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `productospaquete`
--
ALTER TABLE `productospaquete`
  MODIFY `id_paquete` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `productosreceta`
--
ALTER TABLE `productosreceta`
  MODIFY `id_receta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `recibo_de_pago`
--
ALTER TABLE `recibo_de_pago`
  MODIFY `Id_recibo_pago` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `region`
--
ALTER TABLE `region`
  MODIFY `id_region` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reservahabitaciones`
--
ALTER TABLE `reservahabitaciones`
  MODIFY `id_reserva_habitaciones` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id_reserva` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rooming`
--
ALTER TABLE `rooming`
  MODIFY `id_rooming` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `terapistas`
--
ALTER TABLE `terapistas`
  MODIFY `id_profesional` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `terapistashabilidades`
--
ALTER TABLE `terapistashabilidades`
  MODIFY `id_terapistas_habilidad` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tipodeproductos`
--
ALTER TABLE `tipodeproductos`
  MODIFY `id_tipo_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tipodeusuario`
--
ALTER TABLE `tipodeusuario`
  MODIFY `id_tipo_de_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `unidaddenegocio`
--
ALTER TABLE `unidaddenegocio`
  MODIFY `id_unidad_de_negocio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `usuariosmodulos`
--
ALTER TABLE `usuariosmodulos`
  MODIFY `id_usuario_modulo` int(11) NOT NULL AUTO_INCREMENT;

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
