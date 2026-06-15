CREATE DATABASE IF NOT EXISTS barberwest_db;
USE barberwest_db;

CREATE TABLE usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(80) NOT NULL,
  apellido VARCHAR(80) NOT NULL,
  telefono VARCHAR(30),
  email VARCHAR(120) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  rol ENUM('admin', 'general') NOT NULL DEFAULT 'general',
  creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
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
  direccion VARCHAR(150) NOT NULL
);

CREATE TABLE barberos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  especialidad VARCHAR(120),
  sede_id INT NOT NULL,
  FOREIGN KEY (sede_id) REFERENCES sedes(id)
);

CREATE TABLE servicios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  descripcion TEXT,
  precio DECIMAL(10,2) NOT NULL
);

CREATE TABLE turnos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT NOT NULL,
  barbero_id INT NOT NULL,
  servicio_id INT NOT NULL,
  fecha DATE NOT NULL,
  hora TIME NOT NULL,
  estado ENUM('pendiente', 'confirmado', 'cancelado') DEFAULT 'pendiente',
  creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
  FOREIGN KEY (barbero_id) REFERENCES barberos(id),
  FOREIGN KEY (servicio_id) REFERENCES servicios(id)
);

INSERT INTO usuarios (nombre, apellido, telefono, email, password, rol) VALUES
('Admin', 'Sistema', '1111111111', 'admin@blest.com', '$2y$12$7V/14xaIm/YnnrvzRosINu4/xk49vcvBDPdG0DIErWe0TtdqpYgLq', 'admin'),
('Usuario', 'General', '2222222222', 'usuario@blest.com', '$2y$12$0LubMCbipcMq2eLuIpZNluhflVon5m/yppWssoNwE9x1f7.QSQyBC', 'general');

INSERT INTO sedes (nombre, direccion) VALUES
('Sede Naón', 'Montiel 1551, Barrio Naón, CABA'),
('Sede Villa Luro', 'Avenida Rivadavia 10545, Villa Luro, CABA');

INSERT INTO barberos (nombre, especialidad, sede_id) VALUES
('Barbero Naón 1', 'Fades y barba', 1),
('Barbero Naón 2', 'Corte clásico', 1),
('Barbero Villa Luro 1', 'Freestyle', 2),
('Barbero Villa Luro 2', 'Color y mechas', 2);

INSERT INTO servicios (nombre, descripcion, precio) VALUES
('Corte Completo', 'Asesoramiento, corte con máquina/tijera y peinado con cera.', 17000),
('Corte + Barba', 'Corte completo + alineación, rebaje y perfilado de barba.', 22000),
('Solo Barba', 'Alineación, rebaje y perfilado.', 12000),
('Global', 'Decoloración global y matizado, incluye corte.', 55000),
('Mechas', 'Decoloración con gorro para mechas y matizado, incluye corte.', 50000),
('Perfilado cejas / Diseño & Freestyle', 'Diseño personalizado y perfilado.', 0);