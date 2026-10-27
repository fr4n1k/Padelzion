-- Seed DB

DROP DATABASE IF EXISTS padelzion;
CREATE DATABASE padelzion CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE padelzion;
SET NAMES utf8mb4;

-- Usuarios
CREATE TABLE IF NOT EXISTS usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(50) NOT NULL,
  apellido VARCHAR(50) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(50) NOT NULL
);

-- Usuario demo
INSERT INTO usuarios (nombre, apellido, email, password) VALUES
('Franco', 'Bollati', 'franco.bollati@demo.com', 'demo1234');

-- Complejos
CREATE TABLE IF NOT EXISTS complejos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  zona VARCHAR(50) NOT NULL,
  cantidad_canchas INT NOT NULL,
  puntaje DECIMAL(2,1) NOT NULL,
  opiniones INT NOT NULL,
  precio_desde INT NOT NULL,
  imagen VARCHAR(100) NOT NULL
);

INSERT INTO complejos (nombre, zona, cantidad_canchas, puntaje, opiniones, precio_desde, imagen) VALUES
('Aconcagua Pádel Club', 'Ciudad', 5, 4.8, 126, 18000, 'complejo-aconcagua.jpg'),
('Godoy Cruz Pádel', 'Godoy Cruz', 4, 4.7, 88, 20000, 'complejo-godoy-cruz.png'),
('Punto de Oro Pádel', 'Chacras de Coria', 6, 4.9, 204, 22000, 'complejo-punto-de-oro.png'),
('Guaymallén Pádel', 'Guaymallén', 4, 4.5, 47, 16000, 'complejo-guaymallen.png'),
('Las Heras Pádel Center', 'Las Heras', 6, 4.8, 152, 21000, 'complejo-las-heras.png'),
('La Red Pádel Club', 'Maipú', 3, 4.6, 61, 17000, 'complejo-la-red.png');

-- Testimonios
CREATE TABLE IF NOT EXISTS testimonios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  cita VARCHAR(255) NOT NULL,
  nombre VARCHAR(50) NOT NULL,
  meta VARCHAR(100) NOT NULL,
  imagen VARCHAR(100) NOT NULL,
  estrellas INT NOT NULL
);

INSERT INTO testimonios (cita, nombre, meta, imagen, estrellas) VALUES
('“Reservo el pádel de los martes desde el celular en dos minutos. Las panorámicas de Aconcagua Pádel Club están impecables.”', 'Lucía Fernández', 'Juega pádel hace 3 años', 'testimonio-lucia.jpg', 5),
('“Antes reservaba por WhatsApp y nunca sabía si había lugar. Ahora veo los horarios libres y confirmo en un minuto.”', 'Martín Acosta', 'Juega todos los jueves', 'testimonio-martin.jpg', 5),
('“Si no llegamos, cancelo desde Mis reservas y listo. Sin llamar a nadie.”', 'Federico Ruiz', 'Juega en Godoy Cruz y en Ciudad', 'testimonio-federico.jpg', 5);
