-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 09-11-2026 a las 03:24:03
-- Versión del servidor: 10.11.16-MariaDB
-- Versión de PHP: 8.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `kronno2026`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumnos`
--

CREATE TABLE `alumnos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(140) NOT NULL,
  `documento` varchar(30) NOT NULL,
  `curso_id` int(11) NOT NULL,
  `email` varchar(120) DEFAULT NULL,
  `telefono` varchar(30) DEFAULT NULL,
  `estado` enum('Activo','Inactivo') DEFAULT 'Activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `alumnos`
--

INSERT INTO `alumnos` (`id`, `nombre`, `documento`, `curso_id`, `email`, `telefono`, `estado`) VALUES
(1, 'Carlos Méndez', 'A-1001', 1, 'carlos.mendez2008@gmail.com', '0981 234 101', 'Activo'),
(2, 'Sofía Rodríguez', 'A-1002', 1, 'sofia.rodriguez2008@gmail.com', '0981 234 102', 'Activo'),
(3, 'Miguel Acosta', 'A-1003', 1, 'miguel.acosta2008@gmail.com', '0981 234 103', 'Activo'),
(4, 'Paula Ferreira', 'A-1004', 1, 'paula.ferreira2008@gmail.com', '0981 234 104', 'Activo'),
(5, 'Lucas González', 'A-1005', 1, 'lucas.gonzalez2008@gmail.com', '0981 234 105', 'Activo'),
(6, 'Martina López', 'A-1006', 1, 'martina.lopez2008@gmail.com', '0981 234 106', 'Activo'),
(7, 'Diego Morales', 'A-1007', 1, 'diego.morales2008@gmail.com', '0981 234 107', 'Activo'),
(8, 'Valeria Pérez', 'A-1008', 1, 'valeria.perez2008@gmail.com', '0981 234 108', 'Activo'),
(9, 'Andrés Villegas', 'A-1009', 1, 'andres.villegas2008@gmail.com', '0981 234 109', 'Activo'),
(10, 'Camila Flores', 'A-1010', 1, 'camila.flores2008@gmail.com', '0981 234 110', 'Activo'),
(11, 'Gonzalo Romero', 'A-1011', 2, 'gonzalo.romero2008@gmail.com', '0981 234 111', 'Activo'),
(12, 'Isabella Castro', 'A-1012', 2, 'isabella.castro2008@gmail.com', '0981 234 112', 'Activo'),
(13, 'Javier Nuñez', 'A-1013', 2, 'javier.nunez2008@gmail.com', '0981 234 113', 'Activo'),
(14, 'Lucía Benítez', 'A-1014', 2, 'lucia.benitez2008@gmail.com', '0981 234 114', 'Activo'),
(15, 'Santiago Vega', 'A-1015', 2, 'santiago.vega2008@gmail.com', '0981 234 115', 'Activo'),
(16, 'Fátima Soto', 'A-1016', 2, 'fatima.soto2008@gmail.com', '0981 234 116', 'Activo'),
(17, 'Roberto Díaz', 'A-1017', 2, 'roberto.diaz2008@gmail.com', '0981 234 117', 'Activo'),
(18, 'Alejandra Ruiz', 'A-1018', 2, 'alejandra.ruiz2008@gmail.com', '0981 234 118', 'Activo'),
(19, 'Fernando Vargas', 'A-1019', 2, 'fernando.vargas2008@gmail.com', '0981 234 119', 'Activo'),
(20, 'Victoria Guzmán', 'A-1020', 2, 'victoria.guzman2008@gmail.com', '0981 234 120', 'Activo'),
(21, 'Ignacio Herrera', 'A-1021', 3, 'ignacio.herrera2008@gmail.com', '0981 234 121', 'Activo'),
(22, 'Mariana Salazar', 'A-1022', 3, 'mariana.salazar2008@gmail.com', '0981 234 122', 'Activo'),
(23, 'Pablo Jiménez', 'A-1023', 3, 'pablo.jimenez2008@gmail.com', '0981 234 123', 'Activo'),
(24, 'Romina Medina', 'A-1024', 3, 'romina.medina2008@gmail.com', '0981 234 124', 'Activo'),
(25, 'Óscar Aguilar', 'A-1025', 3, 'oscar.aguilar2008@gmail.com', '0981 234 125', 'Activo'),
(26, 'Gabriela Ortiz', 'A-1026', 3, 'gabriela.ortiz2008@gmail.com', '0981 234 126', 'Activo'),
(27, 'Matías Suárez', 'A-1027', 3, 'matias.suarez2008@gmail.com', '0981 234 127', 'Activo'),
(28, 'Daniela Castro', 'A-1028', 3, 'daniela.castro2008@gmail.com', '0981 234 128', 'Activo'),
(29, 'Gustavo Martín', 'A-1029', 3, 'gustavo.martin2008@gmail.com', '0981 234 129', 'Activo'),
(30, 'Natalia Vera', 'A-1030', 3, 'natalia.vera2008@gmail.com', '0981 234 130', 'Activo'),
(31, 'Felipe Espinoza', 'A-1031', 4, 'felipe.espinoza2007@gmail.com', '0981 234 131', 'Activo'),
(32, 'Catalina Fuentes', 'A-1032', 4, 'catalina.fuentes2007@gmail.com', '0981 234 132', 'Activo'),
(33, 'Ricardo Montes', 'A-1033', 4, 'ricardo.montes2007@gmail.com', '0981 234 133', 'Activo'),
(34, 'Adriana Delgado', 'A-1034', 4, 'adriana.delgado2007@gmail.com', '0981 234 134', 'Activo'),
(35, 'Bruno Ramírez', 'A-1035', 4, 'bruno.ramirez2007@gmail.com', '0981 234 135', 'Activo'),
(36, 'Constanza Rojas', 'A-1036', 4, 'constanza.rojas2007@gmail.com', '0981 234 136', 'Activo'),
(37, 'Héctor Parra', 'A-1037', 4, 'hector.parra2007@gmail.com', '0981 234 137', 'Activo'),
(38, 'Javiera Tapia', 'A-1038', 4, 'javiera.tapia2007@gmail.com', '0981 234 138', 'Activo'),
(39, 'Raúl Orozco', 'A-1039', 4, 'raul.orozco2007@gmail.com', '0981 234 139', 'Activo'),
(40, 'Susana Flores', 'A-1040', 4, 'susana.flores2007@gmail.com', '0981 234 140', 'Activo'),
(41, 'Sergio Cortés', 'A-1041', 5, 'sergio.cortes2007@gmail.com', '0981 234 141', 'Activo'),
(42, 'Priscila Ibáñez', 'A-1042', 5, 'priscila.ibanez2007@gmail.com', '0981 234 142', 'Activo'),
(43, 'Tomás Gómez', 'A-1043', 5, 'tomas.gomez2007@gmail.com', '0981 234 143', 'Activo'),
(44, 'Verónica Sáez', 'A-1044', 5, 'veronica.saez2007@gmail.com', '0981 234 144', 'Activo'),
(45, 'Víctor Barrera', 'A-1045', 5, 'victor.barrera2007@gmail.com', '0981 234 145', 'Activo'),
(46, 'Yolanda Reyes', 'A-1046', 5, 'yolanda.reyes2007@gmail.com', '0981 234 146', 'Activo'),
(47, 'Álvaro Salinas', 'A-1047', 5, 'alvaro.salinas2007@gmail.com', '0981 234 147', 'Activo'),
(48, 'Zoe Méndez', 'A-1048', 5, 'zoe.mendez2007@gmail.com', '0981 234 148', 'Activo'),
(49, 'Arturo Valenzuela', 'A-1049', 5, 'arturo.valenzuela2007@gmail.com', '0981 234 149', 'Activo'),
(50, 'Bianca Sánchez', 'A-1050', 5, 'bianca.sanchez2007@gmail.com', '0981 234 150', 'Activo'),
(51, 'Cristóbal Vidal', 'A-1051', 6, 'cristobal.vidal2007@gmail.com', '0981 234 151', 'Activo'),
(52, 'Doris Vallejos', 'A-1052', 6, 'doris.vallejos2007@gmail.com', '0981 234 152', 'Activo'),
(53, 'Emilio Carrasco', 'A-1053', 6, 'emilio.carrasco2007@gmail.com', '0981 234 153', 'Activo'),
(54, 'Esperanza Figueroa', 'A-1054', 6, 'esperanza.figueroa2007@gmail.com', '0981 234 154', 'Activo'),
(55, 'Fabio Tapia', 'A-1055', 6, 'fabio.tapia2007@gmail.com', '0981 234 155', 'Activo'),
(56, 'Francisca Vargas', 'A-1056', 6, 'francisca.vargas2007@gmail.com', '0981 234 156', 'Activo'),
(57, 'Gilberto Moreno', 'A-1057', 6, 'gilberto.moreno2007@gmail.com', '0981 234 157', 'Activo'),
(58, 'Herminia Iglesias', 'A-1058', 6, 'herminia.iglesias2007@gmail.com', '0981 234 158', 'Activo'),
(59, 'Ismael Riquelme', 'A-1059', 6, 'ismael.riquelme2007@gmail.com', '0981 234 159', 'Activo'),
(60, 'Josefina Bravo', 'A-1060', 6, 'josefina.bravo2007@gmail.com', '0981 234 160', 'Activo'),
(61, 'Kirkwood Molina', 'A-1061', 7, 'kirkwood.molina2006@gmail.com', '0981 234 161', 'Activo'),
(62, 'Lorena Navarro', 'A-1062', 7, 'lorena.navarro2006@gmail.com', '0981 234 162', 'Activo'),
(63, 'Marcelo Córdova', 'A-1063', 7, 'marcelo.cordova2006@gmail.com', '0981 234 163', 'Activo'),
(64, 'Micaela Huerta', 'A-1064', 7, 'micaela.huerta2006@gmail.com', '0981 234 164', 'Activo'),
(65, 'Nemesio Contreras', 'A-1065', 7, 'nemesio.contreras2006@gmail.com', '0981 234 165', 'Activo'),
(66, 'Olimpia Durán', 'A-1066', 7, 'olimpia.duran2006@gmail.com', '0981 234 166', 'Activo'),
(67, 'Pascual Franco', 'A-1067', 7, 'pascual.franco2006@gmail.com', '0981 234 167', 'Activo'),
(68, 'Quirina Herrera', 'A-1068', 7, 'quirina.herrera2006@gmail.com', '0981 234 168', 'Activo'),
(69, 'Reynaldo Sepúlveda', 'A-1069', 7, 'reynaldo.sepulveda2006@gmail.com', '0981 234 169', 'Activo'),
(70, 'Silvina Núñez', 'A-1070', 7, 'silvina.nunez2006@gmail.com', '0981 234 170', 'Activo'),
(71, 'Timoteo Valdés', 'A-1071', 8, 'timoteo.valdes2006@gmail.com', '0981 234 171', 'Activo'),
(72, 'Úrsula Valverde', 'A-1072', 8, 'ursula.valverde2006@gmail.com', '0981 234 172', 'Activo'),
(73, 'Venancio Vásquez', 'A-1073', 8, 'venancio.vasquez2006@gmail.com', '0981 234 173', 'Activo'),
(74, 'Wanda Velasco', 'A-1074', 8, 'wanda.velasco2006@gmail.com', '0981 234 174', 'Activo'),
(75, 'Xerardo Vernet', 'A-1075', 8, 'xerardo.vernet2006@gmail.com', '0981 234 175', 'Activo'),
(76, 'Yanina Vidal', 'A-1076', 8, 'yanina.vidal2006@gmail.com', '0981 234 176', 'Activo'),
(77, 'Zacarias Vegas', 'A-1077', 8, 'zacarias.vegas2006@gmail.com', '0981 234 177', 'Activo'),
(78, 'Adolfo Vázquez', 'A-1078', 8, 'adolfo.vazquez2006@gmail.com', '0981 234 178', 'Activo'),
(79, 'Berta Vélez', 'A-1079', 8, 'berta.velez2006@gmail.com', '0981 234 179', 'Activo'),
(80, 'Celestino Vicente', 'A-1080', 8, 'celestino.vicente2006@gmail.com', '0981 234 180', 'Activo'),
(81, 'Dolores Villada', 'A-1081', 9, 'dolores.villada2006@gmail.com', '0981 234 181', 'Activo'),
(82, 'Edmundo Villar', 'A-1082', 9, 'edmundo.villar2006@gmail.com', '0981 234 182', 'Activo'),
(83, 'Estela Vindas', 'A-1083', 9, 'estela.vindas2006@gmail.com', '0981 234 183', 'Activo'),
(84, 'Eugenio Viola', 'A-1084', 9, 'eugenio.viola2006@gmail.com', '0981 234 184', 'Activo'),
(85, 'Fabiola Virgen', 'A-1085', 9, 'fabiola.virgen2006@gmail.com', '0981 234 185', 'Activo'),
(86, 'Genoveva Vitoria', 'A-1086', 9, 'genoveva.vitoria2006@gmail.com', '0981 234 186', 'Activo'),
(87, 'Honorio Vizcarrondo', 'A-1087', 9, 'honorio.vizcarrondo2006@gmail.com', '0981 234 187', 'Activo'),
(88, 'Irene Vivas', 'A-1088', 9, 'irene.vivas2006@gmail.com', '0981 234 188', 'Activo'),
(89, 'Julio Villazón', 'A-1089', 9, 'julio.villazon2006@gmail.com', '0981 234 189', 'Activo'),
(90, 'Kessia Villena', 'A-1090', 9, 'kessia.villena2006@gmail.com', '0981 234 190', 'Activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencias`
--

CREATE TABLE `asistencias` (
  `id` int(11) NOT NULL,
  `alumno_id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `estado` enum('Presente','Ausente','Tardanza') NOT NULL,
  `observacion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `asistencias`
--

INSERT INTO `asistencias` (`id`, `alumno_id`, `fecha`, `estado`, `observacion`) VALUES
(1, 1, '2026-08-18', 'Presente', ''),
(2, 2, '2026-08-18', 'Presente', ''),
(3, 3, '2026-08-18', 'Ausente', ''),
(4, 4, '2026-08-18', 'Tardanza', 'Llegó 15 minutos tarde'),
(5, 5, '2026-08-18', 'Presente', ''),
(6, 6, '2026-08-18', 'Presente', ''),
(7, 7, '2026-08-18', 'Ausente', ''),
(8, 8, '2026-08-18', 'Presente', ''),
(9, 9, '2026-08-18', 'Presente', ''),
(10, 10, '2026-08-18', 'Tardanza', ''),
(11, 11, '2026-08-18', 'Presente', ''),
(12, 12, '2026-08-18', 'Presente', ''),
(13, 13, '2026-08-18', 'Ausente', ''),
(14, 14, '2026-08-18', 'Tardanza', ''),
(15, 15, '2026-08-18', 'Presente', ''),
(16, 16, '2026-08-18', 'Presente', ''),
(17, 17, '2026-08-18', 'Ausente', ''),
(18, 18, '2026-08-18', 'Presente', ''),
(19, 19, '2026-08-18', 'Presente', ''),
(20, 20, '2026-08-18', 'Presente', ''),
(21, 21, '2026-08-18', 'Presente', ''),
(22, 22, '2026-08-18', 'Ausente', ''),
(23, 23, '2026-08-18', 'Tardanza', ''),
(24, 24, '2026-08-18', 'Presente', ''),
(25, 25, '2026-08-18', 'Presente', ''),
(26, 26, '2026-08-18', 'Presente', ''),
(27, 27, '2026-08-18', 'Ausente', ''),
(28, 28, '2026-08-18', 'Presente', ''),
(29, 29, '2026-08-18', 'Tardanza', ''),
(30, 30, '2026-08-18', 'Presente', ''),
(31, 31, '2026-08-18', 'Presente', ''),
(32, 32, '2026-08-18', 'Presente', ''),
(33, 33, '2026-08-18', 'Ausente', ''),
(34, 34, '2026-08-18', 'Tardanza', ''),
(35, 35, '2026-08-18', 'Presente', ''),
(36, 36, '2026-08-18', 'Presente', ''),
(37, 37, '2026-08-18', 'Presente', ''),
(38, 38, '2026-08-18', 'Ausente', ''),
(39, 39, '2026-08-18', 'Presente', ''),
(40, 40, '2026-08-18', 'Tardanza', ''),
(41, 41, '2026-08-18', 'Presente', ''),
(42, 42, '2026-08-18', 'Presente', ''),
(43, 43, '2026-08-18', 'Ausente', ''),
(44, 44, '2026-08-18', 'Tardanza', ''),
(45, 45, '2026-08-18', 'Presente', ''),
(46, 46, '2026-08-18', 'Presente', ''),
(47, 47, '2026-08-18', 'Presente', ''),
(48, 48, '2026-08-18', 'Ausente', ''),
(49, 49, '2026-08-18', 'Presente', ''),
(50, 50, '2026-08-18', 'Tardanza', ''),
(51, 51, '2026-08-18', 'Presente', ''),
(52, 52, '2026-08-18', 'Presente', ''),
(53, 53, '2026-08-18', 'Ausente', ''),
(54, 54, '2026-08-18', 'Tardanza', ''),
(55, 55, '2026-08-18', 'Presente', ''),
(56, 56, '2026-08-18', 'Presente', ''),
(57, 57, '2026-08-18', 'Presente', ''),
(58, 58, '2026-08-18', 'Ausente', ''),
(59, 59, '2026-08-18', 'Presente', ''),
(60, 60, '2026-08-18', 'Tardanza', ''),
(61, 61, '2026-08-18', 'Presente', ''),
(62, 62, '2026-08-18', 'Presente', ''),
(63, 63, '2026-08-18', 'Ausente', ''),
(64, 64, '2026-08-18', 'Tardanza', ''),
(65, 65, '2026-08-18', 'Presente', ''),
(66, 66, '2026-08-18', 'Presente', ''),
(67, 67, '2026-08-18', 'Presente', ''),
(68, 68, '2026-08-18', 'Ausente', ''),
(69, 69, '2026-08-18', 'Presente', ''),
(70, 70, '2026-08-18', 'Tardanza', ''),
(71, 71, '2026-08-18', 'Presente', ''),
(72, 72, '2026-08-18', 'Presente', ''),
(73, 73, '2026-08-18', 'Ausente', ''),
(74, 74, '2026-08-18', 'Tardanza', ''),
(75, 75, '2026-08-18', 'Presente', ''),
(76, 76, '2026-08-18', 'Presente', ''),
(77, 77, '2026-08-18', 'Presente', ''),
(78, 78, '2026-08-18', 'Ausente', ''),
(79, 79, '2026-08-18', 'Presente', ''),
(80, 80, '2026-08-18', 'Tardanza', ''),
(81, 81, '2026-08-18', 'Presente', ''),
(82, 82, '2026-08-18', 'Presente', ''),
(83, 83, '2026-08-18', 'Ausente', ''),
(84, 84, '2026-08-18', 'Tardanza', ''),
(85, 85, '2026-08-18', 'Presente', ''),
(86, 86, '2026-08-18', 'Presente', ''),
(87, 87, '2026-08-18', 'Presente', ''),
(88, 88, '2026-08-18', 'Ausente', ''),
(89, 89, '2026-08-18', 'Presente', ''),
(90, 90, '2026-08-18', 'Tardanza', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `biometric_attempts`
--

CREATE TABLE `biometric_attempts` (
  `id` int(11) NOT NULL,
  `alumno_id` int(11) NOT NULL,
  `tipo` enum('registro','autenticacion','asistencia') NOT NULL,
  `resultado` enum('exitoso','fallido','error') NOT NULL,
  `razon_fallo` varchar(255) DEFAULT NULL,
  `timestamp` timestamp NULL DEFAULT current_timestamp(),
  `ip_address` varchar(45) DEFAULT NULL,
  `dispositivo_id` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `biometric_registrations`
--

CREATE TABLE `biometric_registrations` (
  `id` int(11) NOT NULL,
  `alumno_id` int(11) NOT NULL,
  `huella_hash` varchar(255) NOT NULL,
  `huella_template` longblob DEFAULT NULL,
  `dispositivo_id` varchar(100) DEFAULT NULL,
  `fecha_registro` timestamp NULL DEFAULT current_timestamp(),
  `ultimo_acceso` datetime DEFAULT NULL,
  `intentos_fallidos` int(11) DEFAULT 0,
  `bloqueado` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `biometric_tokens`
--

CREATE TABLE `biometric_tokens` (
  `id` int(11) NOT NULL,
  `alumno_id` int(11) NOT NULL,
  `token` varchar(64) NOT NULL,
  `codigo_registro` varchar(20) DEFAULT NULL,
  `estado` enum('activo','usado','expirado') DEFAULT 'activo',
  `fecha_creacion` timestamp NULL DEFAULT current_timestamp(),
  `fecha_expiracion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cursos`
--

CREATE TABLE `cursos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `nivel` varchar(60) NOT NULL,
  `turno` enum('Mañana','Tarde') NOT NULL DEFAULT 'Mañana',
  `anio` year(4) NOT NULL DEFAULT 2026,
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cursos`
--

INSERT INTO `cursos` (`id`, `nombre`, `nivel`, `turno`, `anio`, `activo`) VALUES
(1, '1ro BTI', '1ro Bachillerato', 'Mañana', '2026', 1),
(2, '1ro BATAN', '1ro Bachillerato', 'Mañana', '2026', 1),
(3, '1ro BC', '1ro Bachillerato', 'Mañana', '2026', 1),
(4, '2do BTI', '2do Bachillerato', 'Mañana', '2026', 1),
(5, '2do BATAN', '2do Bachillerato', 'Mañana', '2026', 1),
(6, '2do BC', '2do Bachillerato', 'Mañana', '2026', 1),
(7, '3ro BTI', '3ro Bachillerato', 'Mañana', '2026', 1),
(8, '3ro BATAN', '3ro Bachillerato', 'Mañana', '2026', 1),
(9, '3ro BC', '3ro Bachillerato', 'Mañana', '2026', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `justificativos`
--

CREATE TABLE `justificativos` (
  `id` int(11) NOT NULL,
  `alumno_id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `motivo` varchar(255) NOT NULL,
  `documento` varchar(255) DEFAULT NULL,
  `estado` enum('Pendiente','Aprobado','Rechazado') DEFAULT 'Pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `justificativos`
--

INSERT INTO `justificativos` (`id`, `alumno_id`, `fecha`, `motivo`, `documento`, `estado`) VALUES
(1, 3, '2026-08-18', 'Reposo médico', 'reposo-1026.pdf', 'Aprobado'),
(2, 7, '2026-08-18', 'Consulta médica', 'consulta-1030.pdf', 'Pendiente'),
(3, 4, '2026-08-17', 'Problemas familiares', 'nota-1027.pdf', 'Aprobado'),
(4, 1, '2026-08-12', 'Representación institucional', 'nota-1024.pdf', 'Aprobado'),
(5, 2, '2026-08-11', 'Control médico', 'control-1025.pdf', 'Rechazado'),
(6, 5, '2026-08-10', 'Competencia deportiva', 'deporte-1028.pdf', 'Pendiente'),
(7, 6, '2026-08-09', 'Reposo médico', 'reposo-1029.pdf', 'Aprobado'),
(8, 8, '2026-08-08', 'Viaje familiar', 'viaje-1031.pdf', 'Pendiente'),
(9, 9, '2026-08-07', 'Consulta odontológica', 'odonto-1032.pdf', 'Aprobado'),
(10, 10, '2026-08-06', 'Situación familiar', 'nota-1033.pdf', 'Pendiente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profesores`
--

CREATE TABLE `profesores` (
  `id` int(11) NOT NULL,
  `nombre` varchar(120) NOT NULL,
  `email` varchar(120) DEFAULT NULL,
  `telefono` varchar(30) DEFAULT NULL,
  `especialidad` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `profesores`
--

INSERT INTO `profesores` (`id`, `nombre`, `email`, `telefono`, `especialidad`) VALUES
(1, 'Eliot Daniel Balbuena Céspedes', 'eliot@krono.edu.py', '0981 100 001', 'Informática'),
(2, 'Giovanna Mishell Colmán Díaz', 'giovanna@krono.edu.py', '0981 100 002', 'Matemática'),
(3, 'Dennis Gabriel Domínguez', 'dennis@krono.edu.py', '0981 100 003', 'Lengua Castellana'),
(4, 'María Belén Ferreira Núñez', 'maria@krono.edu.py', '0981 100 004', 'Ciencias'),
(5, 'José Luis López Benítez', 'jose@krono.edu.py', '0981 100 005', 'Inglés'),
(6, 'Ana Paula Rojas', 'ana@krono.edu.py', '0981 100 006', 'Historia'),
(7, 'Carlos Vera', 'carlos@krono.edu.py', '0981 100 007', 'Educación Física'),
(8, 'Sofía Martínez', 'sofia@krono.edu.py', '0981 100 008', 'Artes'),
(9, 'Raúl González', 'raul@krono.edu.py', '0981 100 009', 'Física'),
(10, 'Patricia Núñez', 'patricia@krono.edu.py', '0981 100 010', 'Química');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `email` varchar(120) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nombre` varchar(140) NOT NULL,
  `rol` enum('profesor','alumno') NOT NULL,
  `alumno_id` int(11) DEFAULT NULL,
  `profesor_id` int(11) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `fecha_creacion` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `email`, `password`, `nombre`, `rol`, `alumno_id`, `profesor_id`, `activo`, `fecha_creacion`) VALUES
(1, 'eliot@krono.edu.py', '$2y$10$KVLt0BLsI.K3VLmJ5Z5zZ.U7N5Q6e5j9Z7q9X5p9Z5p9Z5p9Z5p9Z', 'Eliot Daniel Balbuena Céspedes', 'profesor', NULL, 1, 1, '2026-11-09 01:12:44'),
(2, 'giovanna@krono.edu.py', '$2y$10$KVLt0BLsI.K3VLmJ5Z5zZ.U7N5Q6e5j9Z7q9X5p9Z5p9Z5p9Z5p9Z', 'Giovanna Mishell Colmán Díaz', 'profesor', NULL, 2, 1, '2026-11-09 01:12:44'),
(3, 'dennis@krono.edu.py', '$2y$10$KVLt0BLsI.K3VLmJ5Z5zZ.U7N5Q6e5j9Z7q9X5p9Z5p9Z5p9Z5p9Z', 'Dennis Gabriel Domínguez', 'profesor', NULL, 3, 1, '2026-11-09 01:12:44'),
(4, 'carlos.mendez2008@gmail.com', '$2y$10$KVLt0BLsI.K3VLmJ5Z5zZ.U7N5Q6e5j9Z7q9X5p9Z5p9Z5p9Z5p9Z', 'Carlos Méndez', 'alumno', 1, NULL, 1, '2026-11-09 01:12:44'),
(5, 'gonzalo.romero2008@gmail.com', '$2y$10$KVLt0BLsI.K3VLmJ5Z5zZ.U7N5Q6e5j9Z7q9X5p9Z5p9Z5p9Z5p9Z', 'Gonzalo Romero', 'alumno', 11, NULL, 1, '2026-11-09 01:12:44'),
(6, 'ignacio.herrera2008@gmail.com', '$2y$10$KVLt0BLsI.K3VLmJ5Z5zZ.U7N5Q6e5j9Z7q9X5p9Z5p9Z5p9Z5p9Z', 'Ignacio Herrera', 'alumno', 21, NULL, 1, '2026-11-09 01:12:44');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `alumnos`
--
ALTER TABLE `alumnos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `documento` (`documento`),
  ADD KEY `curso_id` (`curso_id`);

--
-- Indices de la tabla `asistencias`
--
ALTER TABLE `asistencias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_alumno_fecha` (`alumno_id`,`fecha`),
  ADD KEY `idx_fecha` (`fecha`);

--
-- Indices de la tabla `biometric_attempts`
--
ALTER TABLE `biometric_attempts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_alumno` (`alumno_id`),
  ADD KEY `idx_timestamp` (`timestamp`);

--
-- Indices de la tabla `biometric_registrations`
--
ALTER TABLE `biometric_registrations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_alumno` (`alumno_id`),
  ADD KEY `idx_hash` (`huella_hash`);

--
-- Indices de la tabla `biometric_tokens`
--
ALTER TABLE `biometric_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD UNIQUE KEY `codigo_registro` (`codigo_registro`),
  ADD KEY `idx_alumno` (`alumno_id`),
  ADD KEY `idx_token` (`token`);

--
-- Indices de la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `justificativos`
--
ALTER TABLE `justificativos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_alumno` (`alumno_id`);

--
-- Indices de la tabla `profesores`
--
ALTER TABLE `profesores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `alumno_id` (`alumno_id`),
  ADD KEY `profesor_id` (`profesor_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `alumnos`
--
ALTER TABLE `alumnos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT de la tabla `asistencias`
--
ALTER TABLE `asistencias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT de la tabla `biometric_attempts`
--
ALTER TABLE `biometric_attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `biometric_registrations`
--
ALTER TABLE `biometric_registrations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `biometric_tokens`
--
ALTER TABLE `biometric_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cursos`
--
ALTER TABLE `cursos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `justificativos`
--
ALTER TABLE `justificativos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `profesores`
--
ALTER TABLE `profesores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `alumnos`
--
ALTER TABLE `alumnos`
  ADD CONSTRAINT `alumnos_ibfk_1` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`);

--
-- Filtros para la tabla `asistencias`
--
ALTER TABLE `asistencias`
  ADD CONSTRAINT `asistencias_ibfk_1` FOREIGN KEY (`alumno_id`) REFERENCES `alumnos` (`id`);

--
-- Filtros para la tabla `biometric_attempts`
--
ALTER TABLE `biometric_attempts`
  ADD CONSTRAINT `biometric_attempts_ibfk_1` FOREIGN KEY (`alumno_id`) REFERENCES `alumnos` (`id`);

--
-- Filtros para la tabla `biometric_registrations`
--
ALTER TABLE `biometric_registrations`
  ADD CONSTRAINT `biometric_registrations_ibfk_1` FOREIGN KEY (`alumno_id`) REFERENCES `alumnos` (`id`);

--
-- Filtros para la tabla `biometric_tokens`
--
ALTER TABLE `biometric_tokens`
  ADD CONSTRAINT `biometric_tokens_ibfk_1` FOREIGN KEY (`alumno_id`) REFERENCES `alumnos` (`id`);

--
-- Filtros para la tabla `justificativos`
--
ALTER TABLE `justificativos`
  ADD CONSTRAINT `justificativos_ibfk_1` FOREIGN KEY (`alumno_id`) REFERENCES `alumnos` (`id`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`alumno_id`) REFERENCES `alumnos` (`id`),
  ADD CONSTRAINT `usuarios_ibfk_2` FOREIGN KEY (`profesor_id`) REFERENCES `profesores` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
