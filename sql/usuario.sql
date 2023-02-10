drop table if exists usuario;

CREATE TABLE usuario (
id int AUTO_INCREMENT not null,

username varchar(128) NOT NULL,
password varchar(256) NOT NULL,
nombre varchar(128) DEFAULT NULL,
apellido varchar(256) DEFAULT NULL,
email varchar(128) NOT NULL,
telefono varchar(16),
tipologia varchar(256) DEFAULT NULL,
rol varchar(256) DEFAULT NULL,
data_registro timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
data_ultimo_acceso timestamp NULL DEFAULT NULL,
data_ultimo_movimiento timestamp NULL DEFAULT NULL,
PRIMARY KEY(id),


created_by varchar(64) DEFAULT NULL,
timestamp timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO usuario (id, username, password, nombre, apellido, email, tipologia, rol, data_registro, data_ultimo_acceso, data_ultimo_movimiento, created_by) VALUES
(1, 'andredavid', '$2y$10$qszCoRgw4fxuJ/qsIOXhLOpL4QlWATF9q1OL.G4TmvesqPArwnBnG', 'andre', 'alvizuri', 'andredavid.adam@gmail.com', 'rainweb', 'Administrador', '2022-11-06 18:26:13', '2023-01-01 20:31:39', NULL, NULL),
(13, 'marcoaurelio', '$2y$10$t7OVwTsrYqOq4LCSid1oneiGlCtDwijoiYC/h1gCKtDF5cTWi.aVq', 'marco', 'aurelio', 'marcoaurelio@gmail.com', 'dentalpro', 'manager', '2022-12-30 11:04:35', '2023-01-01 20:32:19', NULL, NULL),
(14, 'liliana', '$2y$10$QFH0pngag6PnTmGL4Ntsj.o8ytlYTAdKHQQmEvwIDZsuyXtPZ1iYC', 'liliana', 'magne', 'lilianamagne@gmail.com', 'dentalpro', 'administrador', '2023-01-01 20:26:47', '2023-01-01 20:31:53', NULL, NULL);

--
-- Índices para tablas volcadas
--

