CREATE DATABASE IF NOT EXISTS kronno2026 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE kronno2026;
SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS justificativos;
DROP TABLE IF EXISTS asistencias;
DROP TABLE IF EXISTS usuarios;
DROP TABLE IF EXISTS alumnos;
DROP TABLE IF EXISTS profesores;
DROP TABLE IF EXISTS cursos;
SET FOREIGN_KEY_CHECKS=1;

CREATE TABLE cursos (id INT AUTO_INCREMENT PRIMARY KEY, nombre VARCHAR(100) NOT NULL, nivel VARCHAR(60) NOT NULL, turno ENUM('Mañana','Tarde') NOT NULL DEFAULT 'Mañana', anio YEAR NOT NULL DEFAULT 2026, activo TINYINT(1) NOT NULL DEFAULT 1) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE profesores (id INT AUTO_INCREMENT PRIMARY KEY, nombre VARCHAR(120) NOT NULL, email VARCHAR(120) UNIQUE, telefono VARCHAR(30), especialidad VARCHAR(100)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE alumnos (id INT AUTO_INCREMENT PRIMARY KEY, nombre VARCHAR(140) NOT NULL, documento VARCHAR(30) UNIQUE NOT NULL, curso_id INT NOT NULL, email VARCHAR(120), telefono VARCHAR(30), estado ENUM('Activo','Inactivo') DEFAULT 'Activo', FOREIGN KEY(curso_id) REFERENCES cursos(id));

CREATE TABLE asistencias (id INT AUTO_INCREMENT PRIMARY KEY, alumno_id INT NOT NULL, fecha DATE NOT NULL, estado ENUM('Presente','Ausente','Tardanza') NOT NULL, observacion VARCHAR(255), FOREIGN KEY(alumno_id) REFERENCES alumnos(id), INDEX idx_alumno_fecha (alumno_id, fecha), INDEX idx_fecha (fecha)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE justificativos (id INT AUTO_INCREMENT PRIMARY KEY, alumno_id INT NOT NULL, fecha DATE NOT NULL, motivo VARCHAR(255) NOT NULL, documento VARCHAR(255), estado ENUM('Pendiente','Aprobado','Rechazado') DEFAULT 'Pendiente', FOREIGN KEY(alumno_id) REFERENCES alumnos(id), INDEX idx_alumno (alumno_id)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLAS PARA BIOMETRÍA
CREATE TABLE biometric_tokens (
  id INT AUTO_INCREMENT PRIMARY KEY,
  alumno_id INT NOT NULL,
  token VARCHAR(64) UNIQUE NOT NULL,
  codigo_registro VARCHAR(20) UNIQUE,
  estado ENUM('activo', 'usado', 'expirado') DEFAULT 'activo',
  fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  fecha_expiracion DATETIME,
  FOREIGN KEY(alumno_id) REFERENCES alumnos(id),
  INDEX idx_alumno (alumno_id),
  INDEX idx_token (token)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE biometric_registrations (
  id INT AUTO_INCREMENT PRIMARY KEY,
  alumno_id INT NOT NULL,
  huella_hash VARCHAR(255) NOT NULL,
  huella_template LONGBLOB,
  dispositivo_id VARCHAR(100),
  fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  ultimo_acceso DATETIME,
  intentos_fallidos INT DEFAULT 0,
  bloqueado TINYINT(1) DEFAULT 0,
  FOREIGN KEY(alumno_id) REFERENCES alumnos(id),
  INDEX idx_alumno (alumno_id),
  INDEX idx_hash (huella_hash)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE biometric_attempts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  alumno_id INT NOT NULL,
  tipo ENUM('registro', 'autenticacion', 'asistencia') NOT NULL,
  resultado ENUM('exitoso', 'fallido', 'error') NOT NULL,
  razon_fallo VARCHAR(255),
  timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  ip_address VARCHAR(45),
  dispositivo_id VARCHAR(100),
  FOREIGN KEY(alumno_id) REFERENCES alumnos(id),
  INDEX idx_alumno (alumno_id),
  INDEX idx_timestamp (timestamp)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(120) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  nombre VARCHAR(140) NOT NULL,
  rol ENUM('profesor', 'alumno') NOT NULL,
  alumno_id INT NULL,
  profesor_id INT NULL,
  activo TINYINT(1) NOT NULL DEFAULT 1,
  fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY(alumno_id) REFERENCES alumnos(id),
  FOREIGN KEY(profesor_id) REFERENCES profesores(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9 CURSOS: 3 años × 3 modalidades
INSERT INTO cursos(nombre, nivel, turno) VALUES 
('1ro BTI', '1ro Bachillerato', 'Mañana'),
('1ro BATAN', '1ro Bachillerato', 'Mañana'),
('1ro BC', '1ro Bachillerato', 'Mañana'),
('2do BTI', '2do Bachillerato', 'Mañana'),
('2do BATAN', '2do Bachillerato', 'Mañana'),
('2do BC', '2do Bachillerato', 'Mañana'),
('3ro BTI', '3ro Bachillerato', 'Mañana'),
('3ro BATAN', '3ro Bachillerato', 'Mañana'),
('3ro BC', '3ro Bachillerato', 'Mañana');

INSERT INTO profesores(nombre, email, telefono, especialidad) VALUES 
('Eliot Daniel Balbuena Céspedes', 'eliot@krono.edu.py', '0981 100 001', 'Informática'),
('Giovanna Mishell Colmán Díaz', 'giovanna@krono.edu.py', '0981 100 002', 'Matemática'),
('Dennis Gabriel Domínguez', 'dennis@krono.edu.py', '0981 100 003', 'Lengua Castellana'),
('María Belén Ferreira Núñez', 'maria@krono.edu.py', '0981 100 004', 'Ciencias'),
('José Luis López Benítez', 'jose@krono.edu.py', '0981 100 005', 'Inglés'),
('Ana Paula Rojas', 'ana@krono.edu.py', '0981 100 006', 'Historia'),
('Carlos Vera', 'carlos@krono.edu.py', '0981 100 007', 'Educación Física'),
('Sofía Martínez', 'sofia@krono.edu.py', '0981 100 008', 'Artes'),
('Raúl González', 'raul@krono.edu.py', '0981 100 009', 'Física'),
('Patricia Núñez', 'patricia@krono.edu.py', '0981 100 010', 'Química');

-- 90 ALUMNOS: 10 por curso (con nombres reales y emails personalizados)
INSERT INTO alumnos(nombre, documento, curso_id, email, telefono) VALUES 
-- 1ro BTI (10)
('Carlos Méndez', 'A-1001', 1, 'carlos.mendez2008@gmail.com', '0981 234 101'),
('Sofía Rodríguez', 'A-1002', 1, 'sofia.rodriguez2008@gmail.com', '0981 234 102'),
('Miguel Acosta', 'A-1003', 1, 'miguel.acosta2008@gmail.com', '0981 234 103'),
('Paula Ferreira', 'A-1004', 1, 'paula.ferreira2008@gmail.com', '0981 234 104'),
('Lucas González', 'A-1005', 1, 'lucas.gonzalez2008@gmail.com', '0981 234 105'),
('Martina López', 'A-1006', 1, 'martina.lopez2008@gmail.com', '0981 234 106'),
('Diego Morales', 'A-1007', 1, 'diego.morales2008@gmail.com', '0981 234 107'),
('Valeria Pérez', 'A-1008', 1, 'valeria.perez2008@gmail.com', '0981 234 108'),
('Andrés Villegas', 'A-1009', 1, 'andres.villegas2008@gmail.com', '0981 234 109'),
('Camila Flores', 'A-1010', 1, 'camila.flores2008@gmail.com', '0981 234 110'),
-- 1ro BATAN (10)
('Gonzalo Romero', 'A-1011', 2, 'gonzalo.romero2008@gmail.com', '0981 234 111'),
('Isabella Castro', 'A-1012', 2, 'isabella.castro2008@gmail.com', '0981 234 112'),
('Javier Nuñez', 'A-1013', 2, 'javier.nunez2008@gmail.com', '0981 234 113'),
('Lucía Benítez', 'A-1014', 2, 'lucia.benitez2008@gmail.com', '0981 234 114'),
('Santiago Vega', 'A-1015', 2, 'santiago.vega2008@gmail.com', '0981 234 115'),
('Fátima Soto', 'A-1016', 2, 'fatima.soto2008@gmail.com', '0981 234 116'),
('Roberto Díaz', 'A-1017', 2, 'roberto.diaz2008@gmail.com', '0981 234 117'),
('Alejandra Ruiz', 'A-1018', 2, 'alejandra.ruiz2008@gmail.com', '0981 234 118'),
('Fernando Vargas', 'A-1019', 2, 'fernando.vargas2008@gmail.com', '0981 234 119'),
('Victoria Guzmán', 'A-1020', 2, 'victoria.guzman2008@gmail.com', '0981 234 120'),
-- 1ro BC (10)
('Ignacio Herrera', 'A-1021', 3, 'ignacio.herrera2008@gmail.com', '0981 234 121'),
('Mariana Salazar', 'A-1022', 3, 'mariana.salazar2008@gmail.com', '0981 234 122'),
('Pablo Jiménez', 'A-1023', 3, 'pablo.jimenez2008@gmail.com', '0981 234 123'),
('Romina Medina', 'A-1024', 3, 'romina.medina2008@gmail.com', '0981 234 124'),
('Óscar Aguilar', 'A-1025', 3, 'oscar.aguilar2008@gmail.com', '0981 234 125'),
('Gabriela Ortiz', 'A-1026', 3, 'gabriela.ortiz2008@gmail.com', '0981 234 126'),
('Matías Suárez', 'A-1027', 3, 'matias.suarez2008@gmail.com', '0981 234 127'),
('Daniela Castro', 'A-1028', 3, 'daniela.castro2008@gmail.com', '0981 234 128'),
('Gustavo Martín', 'A-1029', 3, 'gustavo.martin2008@gmail.com', '0981 234 129'),
('Natalia Vera', 'A-1030', 3, 'natalia.vera2008@gmail.com', '0981 234 130'),
-- 2do BTI (10)
('Felipe Espinoza', 'A-1031', 4, 'felipe.espinoza2007@gmail.com', '0981 234 131'),
('Catalina Fuentes', 'A-1032', 4, 'catalina.fuentes2007@gmail.com', '0981 234 132'),
('Ricardo Montes', 'A-1033', 4, 'ricardo.montes2007@gmail.com', '0981 234 133'),
('Adriana Delgado', 'A-1034', 4, 'adriana.delgado2007@gmail.com', '0981 234 134'),
('Bruno Ramírez', 'A-1035', 4, 'bruno.ramirez2007@gmail.com', '0981 234 135'),
('Constanza Rojas', 'A-1036', 4, 'constanza.rojas2007@gmail.com', '0981 234 136'),
('Héctor Parra', 'A-1037', 4, 'hector.parra2007@gmail.com', '0981 234 137'),
('Javiera Tapia', 'A-1038', 4, 'javiera.tapia2007@gmail.com', '0981 234 138'),
('Raúl Orozco', 'A-1039', 4, 'raul.orozco2007@gmail.com', '0981 234 139'),
('Susana Flores', 'A-1040', 4, 'susana.flores2007@gmail.com', '0981 234 140'),
-- 2do BATAN (10)
('Sergio Cortés', 'A-1041', 5, 'sergio.cortes2007@gmail.com', '0981 234 141'),
('Priscila Ibáñez', 'A-1042', 5, 'priscila.ibanez2007@gmail.com', '0981 234 142'),
('Tomás Gómez', 'A-1043', 5, 'tomas.gomez2007@gmail.com', '0981 234 143'),
('Verónica Sáez', 'A-1044', 5, 'veronica.saez2007@gmail.com', '0981 234 144'),
('Víctor Barrera', 'A-1045', 5, 'victor.barrera2007@gmail.com', '0981 234 145'),
('Yolanda Reyes', 'A-1046', 5, 'yolanda.reyes2007@gmail.com', '0981 234 146'),
('Álvaro Salinas', 'A-1047', 5, 'alvaro.salinas2007@gmail.com', '0981 234 147'),
('Zoe Méndez', 'A-1048', 5, 'zoe.mendez2007@gmail.com', '0981 234 148'),
('Arturo Valenzuela', 'A-1049', 5, 'arturo.valenzuela2007@gmail.com', '0981 234 149'),
('Bianca Sánchez', 'A-1050', 5, 'bianca.sanchez2007@gmail.com', '0981 234 150'),
-- 2do BC (10)
('Cristóbal Vidal', 'A-1051', 6, 'cristobal.vidal2007@gmail.com', '0981 234 151'),
('Doris Vallejos', 'A-1052', 6, 'doris.vallejos2007@gmail.com', '0981 234 152'),
('Emilio Carrasco', 'A-1053', 6, 'emilio.carrasco2007@gmail.com', '0981 234 153'),
('Esperanza Figueroa', 'A-1054', 6, 'esperanza.figueroa2007@gmail.com', '0981 234 154'),
('Fabio Tapia', 'A-1055', 6, 'fabio.tapia2007@gmail.com', '0981 234 155'),
('Francisca Vargas', 'A-1056', 6, 'francisca.vargas2007@gmail.com', '0981 234 156'),
('Gilberto Moreno', 'A-1057', 6, 'gilberto.moreno2007@gmail.com', '0981 234 157'),
('Herminia Iglesias', 'A-1058', 6, 'herminia.iglesias2007@gmail.com', '0981 234 158'),
('Ismael Riquelme', 'A-1059', 6, 'ismael.riquelme2007@gmail.com', '0981 234 159'),
('Josefina Bravo', 'A-1060', 6, 'josefina.bravo2007@gmail.com', '0981 234 160'),
-- 3ro BTI (10)
('Kirkwood Molina', 'A-1061', 7, 'kirkwood.molina2006@gmail.com', '0981 234 161'),
('Lorena Navarro', 'A-1062', 7, 'lorena.navarro2006@gmail.com', '0981 234 162'),
('Marcelo Córdova', 'A-1063', 7, 'marcelo.cordova2006@gmail.com', '0981 234 163'),
('Micaela Huerta', 'A-1064', 7, 'micaela.huerta2006@gmail.com', '0981 234 164'),
('Nemesio Contreras', 'A-1065', 7, 'nemesio.contreras2006@gmail.com', '0981 234 165'),
('Olimpia Durán', 'A-1066', 7, 'olimpia.duran2006@gmail.com', '0981 234 166'),
('Pascual Franco', 'A-1067', 7, 'pascual.franco2006@gmail.com', '0981 234 167'),
('Quirina Herrera', 'A-1068', 7, 'quirina.herrera2006@gmail.com', '0981 234 168'),
('Reynaldo Sepúlveda', 'A-1069', 7, 'reynaldo.sepulveda2006@gmail.com', '0981 234 169'),
('Silvina Núñez', 'A-1070', 7, 'silvina.nunez2006@gmail.com', '0981 234 170'),
-- 3ro BATAN (10)
('Timoteo Valdés', 'A-1071', 8, 'timoteo.valdes2006@gmail.com', '0981 234 171'),
('Úrsula Valverde', 'A-1072', 8, 'ursula.valverde2006@gmail.com', '0981 234 172'),
('Venancio Vásquez', 'A-1073', 8, 'venancio.vasquez2006@gmail.com', '0981 234 173'),
('Wanda Velasco', 'A-1074', 8, 'wanda.velasco2006@gmail.com', '0981 234 174'),
('Xerardo Vernet', 'A-1075', 8, 'xerardo.vernet2006@gmail.com', '0981 234 175'),
('Yanina Vidal', 'A-1076', 8, 'yanina.vidal2006@gmail.com', '0981 234 176'),
('Zacarias Vegas', 'A-1077', 8, 'zacarias.vegas2006@gmail.com', '0981 234 177'),
('Adolfo Vázquez', 'A-1078', 8, 'adolfo.vazquez2006@gmail.com', '0981 234 178'),
('Berta Vélez', 'A-1079', 8, 'berta.velez2006@gmail.com', '0981 234 179'),
('Celestino Vicente', 'A-1080', 8, 'celestino.vicente2006@gmail.com', '0981 234 180'),
-- 3ro BC (10)
('Dolores Villada', 'A-1081', 9, 'dolores.villada2006@gmail.com', '0981 234 181'),
('Edmundo Villar', 'A-1082', 9, 'edmundo.villar2006@gmail.com', '0981 234 182'),
('Estela Vindas', 'A-1083', 9, 'estela.vindas2006@gmail.com', '0981 234 183'),
('Eugenio Viola', 'A-1084', 9, 'eugenio.viola2006@gmail.com', '0981 234 184'),
('Fabiola Virgen', 'A-1085', 9, 'fabiola.virgen2006@gmail.com', '0981 234 185'),
('Genoveva Vitoria', 'A-1086', 9, 'genoveva.vitoria2006@gmail.com', '0981 234 186'),
('Honorio Vizcarrondo', 'A-1087', 9, 'honorio.vizcarrondo2006@gmail.com', '0981 234 187'),
('Irene Vivas', 'A-1088', 9, 'irene.vivas2006@gmail.com', '0981 234 188'),
('Julio Villazón', 'A-1089', 9, 'julio.villazon2006@gmail.com', '0981 234 189'),
('Kessia Villena', 'A-1090', 9, 'kessia.villena2006@gmail.com', '0981 234 190');

-- ASISTENCIAS: 90 registros (1 por alumno en 2026-08-18)
INSERT INTO asistencias(alumno_id, fecha, estado, observacion) VALUES 
(1, '2026-08-18', 'Presente', ''),
(2, '2026-08-18', 'Presente', ''),
(3, '2026-08-18', 'Ausente', ''),
(4, '2026-08-18', 'Tardanza', 'Llegó 15 minutos tarde'),
(5, '2026-08-18', 'Presente', ''),
(6, '2026-08-18', 'Presente', ''),
(7, '2026-08-18', 'Ausente', ''),
(8, '2026-08-18', 'Presente', ''),
(9, '2026-08-18', 'Presente', ''),
(10, '2026-08-18', 'Tardanza', ''),
(11, '2026-08-18', 'Presente', ''),
(12, '2026-08-18', 'Presente', ''),
(13, '2026-08-18', 'Ausente', ''),
(14, '2026-08-18', 'Tardanza', ''),
(15, '2026-08-18', 'Presente', ''),
(16, '2026-08-18', 'Presente', ''),
(17, '2026-08-18', 'Ausente', ''),
(18, '2026-08-18', 'Presente', ''),
(19, '2026-08-18', 'Presente', ''),
(20, '2026-08-18', 'Presente', ''),
(21, '2026-08-18', 'Presente', ''),
(22, '2026-08-18', 'Ausente', ''),
(23, '2026-08-18', 'Tardanza', ''),
(24, '2026-08-18', 'Presente', ''),
(25, '2026-08-18', 'Presente', ''),
(26, '2026-08-18', 'Presente', ''),
(27, '2026-08-18', 'Ausente', ''),
(28, '2026-08-18', 'Presente', ''),
(29, '2026-08-18', 'Tardanza', ''),
(30, '2026-08-18', 'Presente', ''),
(31, '2026-08-18', 'Presente', ''),
(32, '2026-08-18', 'Presente', ''),
(33, '2026-08-18', 'Ausente', ''),
(34, '2026-08-18', 'Tardanza', ''),
(35, '2026-08-18', 'Presente', ''),
(36, '2026-08-18', 'Presente', ''),
(37, '2026-08-18', 'Presente', ''),
(38, '2026-08-18', 'Ausente', ''),
(39, '2026-08-18', 'Presente', ''),
(40, '2026-08-18', 'Tardanza', ''),
(41, '2026-08-18', 'Presente', ''),
(42, '2026-08-18', 'Presente', ''),
(43, '2026-08-18', 'Ausente', ''),
(44, '2026-08-18', 'Tardanza', ''),
(45, '2026-08-18', 'Presente', ''),
(46, '2026-08-18', 'Presente', ''),
(47, '2026-08-18', 'Presente', ''),
(48, '2026-08-18', 'Ausente', ''),
(49, '2026-08-18', 'Presente', ''),
(50, '2026-08-18', 'Tardanza', ''),
(51, '2026-08-18', 'Presente', ''),
(52, '2026-08-18', 'Presente', ''),
(53, '2026-08-18', 'Ausente', ''),
(54, '2026-08-18', 'Tardanza', ''),
(55, '2026-08-18', 'Presente', ''),
(56, '2026-08-18', 'Presente', ''),
(57, '2026-08-18', 'Presente', ''),
(58, '2026-08-18', 'Ausente', ''),
(59, '2026-08-18', 'Presente', ''),
(60, '2026-08-18', 'Tardanza', ''),
(61, '2026-08-18', 'Presente', ''),
(62, '2026-08-18', 'Presente', ''),
(63, '2026-08-18', 'Ausente', ''),
(64, '2026-08-18', 'Tardanza', ''),
(65, '2026-08-18', 'Presente', ''),
(66, '2026-08-18', 'Presente', ''),
(67, '2026-08-18', 'Presente', ''),
(68, '2026-08-18', 'Ausente', ''),
(69, '2026-08-18', 'Presente', ''),
(70, '2026-08-18', 'Tardanza', ''),
(71, '2026-08-18', 'Presente', ''),
(72, '2026-08-18', 'Presente', ''),
(73, '2026-08-18', 'Ausente', ''),
(74, '2026-08-18', 'Tardanza', ''),
(75, '2026-08-18', 'Presente', ''),
(76, '2026-08-18', 'Presente', ''),
(77, '2026-08-18', 'Presente', ''),
(78, '2026-08-18', 'Ausente', ''),
(79, '2026-08-18', 'Presente', ''),
(80, '2026-08-18', 'Tardanza', ''),
(81, '2026-08-18', 'Presente', ''),
(82, '2026-08-18', 'Presente', ''),
(83, '2026-08-18', 'Ausente', ''),
(84, '2026-08-18', 'Tardanza', ''),
(85, '2026-08-18', 'Presente', ''),
(86, '2026-08-18', 'Presente', ''),
(87, '2026-08-18', 'Presente', ''),
(88, '2026-08-18', 'Ausente', ''),
(89, '2026-08-18', 'Presente', ''),
(90, '2026-08-18', 'Tardanza', '');

INSERT INTO justificativos(alumno_id, fecha, motivo, documento, estado) VALUES 
(3, '2026-08-18', 'Reposo médico', 'reposo-1026.pdf', 'Aprobado'),
(7, '2026-08-18', 'Consulta médica', 'consulta-1030.pdf', 'Pendiente'),
(4, '2026-08-17', 'Problemas familiares', 'nota-1027.pdf', 'Aprobado'),
(1, '2026-08-12', 'Representación institucional', 'nota-1024.pdf', 'Aprobado'),
(2, '2026-08-11', 'Control médico', 'control-1025.pdf', 'Rechazado'),
(5, '2026-08-10', 'Competencia deportiva', 'deporte-1028.pdf', 'Pendiente'),
(6, '2026-08-09', 'Reposo médico', 'reposo-1029.pdf', 'Aprobado'),
(8, '2026-08-08', 'Viaje familiar', 'viaje-1031.pdf', 'Pendiente'),
(9, '2026-08-07', 'Consulta odontológica', 'odonto-1032.pdf', 'Aprobado'),
(10, '2026-08-06', 'Situación familiar', 'nota-1033.pdf', 'Pendiente');

-- USUARIOS DE PRUEBA (profesores + algunos alumnos de muestra)
INSERT INTO usuarios(email, password, nombre, rol, profesor_id, activo) VALUES 
('eliot@krono.edu.py', '$2y$10$KVLt0BLsI.K3VLmJ5Z5zZ.U7N5Q6e5j9Z7q9X5p9Z5p9Z5p9Z5p9Z', 'Eliot Daniel Balbuena Céspedes', 'profesor', 1, 1),
('giovanna@krono.edu.py', '$2y$10$KVLt0BLsI.K3VLmJ5Z5zZ.U7N5Q6e5j9Z7q9X5p9Z5p9Z5p9Z5p9Z', 'Giovanna Mishell Colmán Díaz', 'profesor', 2, 1),
('dennis@krono.edu.py', '$2y$10$KVLt0BLsI.K3VLmJ5Z5zZ.U7N5Q6e5j9Z7q9X5p9Z5p9Z5p9Z5p9Z', 'Dennis Gabriel Domínguez', 'profesor', 3, 1);

-- USUARIOS DE PRUEBA (algunos alumnos de muestra)
INSERT INTO usuarios(email, password, nombre, rol, alumno_id, activo) VALUES
('carlos.mendez2008@gmail.com', '$2y$10$KVLt0BLsI.K3VLmJ5Z5zZ.U7N5Q6e5j9Z7q9X5p9Z5p9Z5p9Z5p9Z', 'Carlos Méndez', 'alumno', 1, 1),
('gonzalo.romero2008@gmail.com', '$2y$10$KVLt0BLsI.K3VLmJ5Z5zZ.U7N5Q6e5j9Z7q9X5p9Z5p9Z5p9Z5p9Z', 'Gonzalo Romero', 'alumno', 11, 1),
('ignacio.herrera2008@gmail.com', '$2y$10$KVLt0BLsI.K3VLmJ5Z5zZ.U7N5Q6e5j9Z7q9X5p9Z5p9Z5p9Z5p9Z', 'Ignacio Herrera', 'alumno', 21, 1);
