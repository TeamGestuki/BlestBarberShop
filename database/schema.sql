CREATE DATABASE IF NOT EXISTS barberwest_db
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE barberwest_db;

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS turnos;
DROP TABLE IF EXISTS barbero_fotos;
DROP TABLE IF EXISTS sede_galeria;
DROP TABLE IF EXISTS barberos;
DROP TABLE IF EXISTS servicios;
DROP TABLE IF EXISTS sedes;
DROP TABLE IF EXISTS contactos;
DROP TABLE IF EXISTS usuarios;

SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(80) NOT NULL,
    apellido VARCHAR(80) NOT NULL,
    telefono VARCHAR(30),
    email VARCHAR(120) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol ENUM('admin','general') NOT NULL DEFAULT 'general',
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ultimo_acceso DATETIME NULL,
    remember_token_hash VARCHAR(255) NULL,
    remember_token_expira DATETIME NULL
);

CREATE TABLE contactos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(120) NOT NULL,
    email VARCHAR(120) NOT NULL,
    asunto VARCHAR(150),
    mensaje TEXT NOT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE sedes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(80) NOT NULL,
    direccion VARCHAR(150) NOT NULL,
    mapa_embed TEXT NULL,
    foto VARCHAR(255) NULL,
    activo TINYINT(1) NOT NULL DEFAULT 1
);

CREATE TABLE sede_galeria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sede_id INT NOT NULL,
    foto VARCHAR(255) NOT NULL,
    activo TINYINT(1) NOT NULL DEFAULT 1,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_sede_galeria_sede
        FOREIGN KEY (sede_id) REFERENCES sedes(id)
        ON DELETE CASCADE
);

CREATE TABLE barberos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    especialidad VARCHAR(120),
    foto VARCHAR(255) NULL,
    sede_id INT NOT NULL,
    activo TINYINT(1) NOT NULL DEFAULT 1,
    CONSTRAINT fk_barberos_sede
        FOREIGN KEY (sede_id) REFERENCES sedes(id)
);

CREATE TABLE barbero_fotos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    barbero_id INT NOT NULL,
    foto VARCHAR(255) NOT NULL,
    activo TINYINT(1) NOT NULL DEFAULT 1,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_barbero_fotos_barbero
        FOREIGN KEY (barbero_id) REFERENCES barberos(id)
        ON DELETE CASCADE
);

CREATE TABLE servicios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL,
    duracion_min INT NOT NULL DEFAULT 30,
    activo TINYINT(1) NOT NULL DEFAULT 1
);

CREATE TABLE turnos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    sede_id INT NOT NULL,
    barbero_id INT NOT NULL,
    servicio_id INT NOT NULL,
    fecha DATE NOT NULL,
    hora TIME NOT NULL,
    estado ENUM('pendiente','confirmado','cancelado','completado','ausente') NOT NULL DEFAULT 'pendiente',
    observaciones TEXT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_turnos_usuario
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id),

    CONSTRAINT fk_turnos_sede
        FOREIGN KEY (sede_id) REFERENCES sedes(id),

    CONSTRAINT fk_turnos_barbero
        FOREIGN KEY (barbero_id) REFERENCES barberos(id),

    CONSTRAINT fk_turnos_servicio
        FOREIGN KEY (servicio_id) REFERENCES servicios(id)
);

CREATE INDEX idx_turnos_usuario ON turnos(usuario_id);
CREATE INDEX idx_turnos_sede ON turnos(sede_id);
CREATE INDEX idx_turnos_barbero ON turnos(barbero_id);
CREATE INDEX idx_turnos_servicio ON turnos(servicio_id);
CREATE INDEX idx_turnos_fecha ON turnos(fecha);
CREATE UNIQUE INDEX uq_turno_barbero_fecha_hora
ON turnos(barbero_id, fecha, hora);

INSERT INTO usuarios
(nombre, apellido, telefono, email, password, rol)
VALUES
('Admin', 'Sistema', '1111111111', 'admin@blest.com', '$2y$12$7V/14xaIm/YnnrvzRosINu4/xk49vcvBDPdG0DIErWe0TtdqpYgLq', 'admin'),
('Usuario', 'General', '2222222222', 'usuario@blest.com', '$2y$12$0LubMCbipcMq2eLuIpZNluhflVon5m/yppWssoNwE9x1f7.QSQyBC', 'general');

INSERT INTO sedes
(nombre, direccion, mapa_embed, foto, activo)
VALUES
('Sede Naón', 'Montiel 1551, Barrio Naón, CABA', '<iframe src="https://maps.google.com/maps?q=Montiel%201551,%20Barrio%20Na%C3%B3n,%20CABA&t=&z=16&ie=UTF8&iwloc=&output=embed" width="100%" height="100%" style="border:0; min-height:380px;" allowfullscreen="" loading="lazy"></iframe>', 'img/sucursalnaon.webp', 1),
('Sede Villa Luro', 'Avenida Rivadavia 10545, Villa Luro, CABA', '<iframe src="https://maps.google.com/maps?q=Avenida%20Rivadavia%2010545,%20Villa%20Luro,%20CABA&t=&z=16&ie=UTF8&iwloc=&output=embed" width="100%" height="100%" style="border:0; min-height:380px;" allowfullscreen="" loading="lazy"></iframe>', 'img/sucursalluro.webp', 1);

INSERT INTO barberos
(nombre, especialidad, foto, sede_id, activo)
VALUES
('Juli', 'Fades y barba', 'img/juli.webp', 1, 1),
('Mauri', 'Corte clásico', 'img/mauri.webp', 1, 1),
('Tincho', 'Freestyle', 'img/tinchos.webp', 2, 1),
('Tommy', 'Color y mechas', 'img/tommys.webp', 2, 1);


INSERT INTO sede_galeria (sede_id, foto, activo) VALUES 
(1, 'img/cortenaon.webp', 1),
(1, 'img/cortenaon2.webp', 1),
(1, 'img/cortenaon3.webp', 1),
(1, 'img/cortenaon4.webp', 1),
(1, 'img/cortenaon5.webp', 1),
(1, 'img/cortenaon6.webp', 1),
(2, 'img/corteluro.webp', 1),
(2, 'img/corteluro2.webp', 1),
(2, 'img/corteluro3.webp', 1),
(2, 'img/corteluro4.webp', 1),
(2, 'img/corteluro5.webp', 1),
(2, 'img/corteluro6.webp', 1);


INSERT INTO barbero_fotos (barbero_id, foto, activo) VALUES 
(1, 'img/cortenaon.webp', 1),
(1, 'img/cortenaon2.webp', 1),
(2, 'img/cortenaon3.webp', 1),
(3, 'img/corteluro.webp', 1),
(4, 'img/corteluro2.webp', 1);

INSERT INTO servicios
(nombre, descripcion, precio, duracion_min, activo)
VALUES
('Corte Completo', 'Asesoramiento, corte con máquina/tijera y peinado con cera.', 17000, 30, 1),
('Corte + Barba', 'Corte completo + alineación, rebaje y perfilado de barba.', 22000, 60, 1),
('Solo Barba', 'Alineación, rebaje y perfilado.', 12000, 30, 1),
('Global', 'Decoloración global y matizado, incluye corte.', 55000, 90, 1),
('Mechas', 'Decoloración con gorro para mechas y matizado, incluye corte.', 50000, 90, 1),
('Perfilado cejas / Diseño & Freestyle', 'Diseño personalizado y perfilado.', 0, 30, 1);