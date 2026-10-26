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

-- Usuarios demo
INSERT INTO usuarios (nombre, apellido, email, password) VALUES
('Franco', 'Bollati', 'franco.bollati@demo.com', 'demo1234'),
('Juan', 'Perez', 'juan.perez@demo.com', 'demo1234');

-- Complejos
CREATE TABLE IF NOT EXISTS complejos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  zona VARCHAR(50) NOT NULL,
  direccion VARCHAR(150) NOT NULL,
  descripcion TEXT NOT NULL,
  cantidad_canchas INT NOT NULL,
  puntaje DECIMAL(2,1) NOT NULL,
  opiniones INT NOT NULL,
  precio_desde INT NOT NULL
);

INSERT INTO complejos (nombre, zona, direccion, descripcion, cantidad_canchas, puntaje, opiniones, precio_desde) VALUES
('Aconcagua Pádel Club', 'Ciudad', 'Av. Emilio Civit 650, Ciudad de Mendoza', 'Complejo de pádel con 5 canchas: dos panorámicas de cristal de 12 mm, dos cubiertas de muro y una exterior con vista a la cordillera. Césped sintético texturizado, iluminación LED y un bar con terraza para el tercer tiempo. A 2 cuadras del Parque General San Martín.', 5, 4.8, 126, 18000),
('Godoy Cruz Pádel', 'Godoy Cruz', 'San Martín Sur 1250, Godoy Cruz', 'Nave cubierta con 4 canchas: dos panorámicas y dos de muro. Se juega igual con lluvia o con viento zonda. Tiene vestuarios con duchas, estacionamiento propio y alquiler de paletas.', 4, 4.7, 88, 20000),
('Punto de Oro Pádel', 'Chacras de Coria', 'Viamonte 4800, Chacras de Coria', 'Seis canchas entre viñedos y con vista a la cordillera: tres panorámicas, dos cubiertas y una exterior. Ideal para jugar al atardecer y quedarse a comer algo en el quincho.', 6, 4.9, 204, 22000),
('Guaymallén Pádel', 'Guaymallén', 'Bandera de los Andes 2300, Guaymallén', 'Complejo de barrio con 4 canchas: una panorámica, una cubierta y dos exteriores de cristal. Buen precio, iluminación LED y kiosco con bebidas frías.', 4, 4.5, 47, 16000),
('Las Heras Pádel Center', 'Las Heras', 'Av. San Martín 3100, Las Heras', 'Uno de los complejos más grandes de la zona norte: 6 canchas, dos panorámicas, dos cubiertas y dos exteriores. Tiene pileta en verano, cantina y escuela de pádel para principiantes.', 6, 4.8, 152, 21000),
('La Red Pádel Club', 'Maipú', 'Ozamis 1500, Maipú', 'Club chico y tranquilo con 3 canchas de césped sintético: una panorámica, una cubierta y una exterior rodeada de árboles. Ideal para partidos entre amigos.', 3, 4.6, 61, 17000);

-- Imagenes de los complejos
CREATE TABLE IF NOT EXISTS imagenes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  complejo_id INT NOT NULL,
  archivo VARCHAR(100) NOT NULL,
  portada TINYINT(1) NOT NULL DEFAULT 0,
  FOREIGN KEY (complejo_id) REFERENCES complejos(id)
);

INSERT INTO imagenes (complejo_id, archivo, portada) VALUES
(1, 'complejo-aconcagua.jpg', 1),
(1, 'cancha-panoramica.jpg', 0),
(1, 'cancha-muro.jpg', 0),
(2, 'complejo-godoy-cruz.png', 1),
(2, 'cancha-panoramica.jpg', 0),
(2, 'cancha-muro.jpg', 0),
(3, 'complejo-punto-de-oro.png', 1),
(3, 'cancha-panoramica.jpg', 0),
(3, 'cancha-normal.jpg', 0),
(4, 'complejo-guaymallen.png', 1),
(4, 'cancha-muro.jpg', 0),
(4, 'cancha-normal.jpg', 0),
(5, 'complejo-las-heras.png', 1),
(5, 'cancha-panoramica.jpg', 0),
(5, 'cancha-normal.jpg', 0),
(6, 'complejo-la-red.png', 1),
(6, 'cancha-muro.jpg', 0),
(6, 'cancha-normal.jpg', 0);

-- Canchas
CREATE TABLE IF NOT EXISTS canchas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  complejo_id INT NOT NULL,
  nombre VARCHAR(50) NOT NULL,
  tipo VARCHAR(20) NOT NULL,
  cobertura VARCHAR(20) NOT NULL,
  precio_dia INT NOT NULL,
  precio_noche INT NOT NULL,
  imagen VARCHAR(100) NOT NULL,
  FOREIGN KEY (complejo_id) REFERENCES complejos(id)
);

INSERT INTO canchas (complejo_id, nombre, tipo, cobertura, precio_dia, precio_noche, imagen) VALUES
(1, 'Cancha 1', 'Panorámica', 'Techada', 24000, 28000, 'cancha-panoramica.jpg'),
(1, 'Cancha 2', 'Panorámica', 'Techada', 24000, 28000, 'cancha-panoramica.jpg'),
(1, 'Cancha 3', 'Muro', 'Cubierta', 22000, 26000, 'cancha-muro.jpg'),
(1, 'Cancha 4', 'Muro', 'Cubierta', 22000, 26000, 'cancha-muro.jpg'),
(1, 'Cancha 5', 'Normal', 'Aire libre', 18000, 21000, 'cancha-normal.jpg'),
(2, 'Cancha 1', 'Panorámica', 'Cubierta', 24000, 28000, 'cancha-panoramica.jpg'),
(2, 'Cancha 2', 'Panorámica', 'Cubierta', 24000, 28000, 'cancha-panoramica.jpg'),
(2, 'Cancha 3', 'Muro', 'Cubierta', 22000, 25000, 'cancha-muro.jpg'),
(2, 'Cancha 4', 'Muro', 'Cubierta', 20000, 24000, 'cancha-muro.jpg'),
(3, 'Cancha 1', 'Panorámica', 'Techada', 26000, 30000, 'cancha-panoramica.jpg'),
(3, 'Cancha 2', 'Panorámica', 'Techada', 26000, 30000, 'cancha-panoramica.jpg'),
(3, 'Cancha 3', 'Panorámica', 'Techada', 26000, 30000, 'cancha-panoramica.jpg'),
(3, 'Cancha 4', 'Muro', 'Techada', 24000, 28000, 'cancha-muro.jpg'),
(3, 'Cancha 5', 'Muro', 'Techada', 24000, 28000, 'cancha-muro.jpg'),
(3, 'Cancha 6', 'Normal', 'Aire libre', 22000, 25000, 'cancha-normal.jpg'),
(4, 'Cancha 1', 'Panorámica', 'Techada', 20000, 24000, 'cancha-panoramica.jpg'),
(4, 'Cancha 2', 'Muro', 'Techada', 18000, 22000, 'cancha-muro.jpg'),
(4, 'Cancha 3', 'Normal', 'Aire libre', 16000, 19000, 'cancha-normal.jpg'),
(4, 'Cancha 4', 'Normal', 'Aire libre', 16000, 19000, 'cancha-normal.jpg'),
(5, 'Cancha 1', 'Panorámica', 'Techada', 25000, 29000, 'cancha-panoramica.jpg'),
(5, 'Cancha 2', 'Panorámica', 'Techada', 25000, 29000, 'cancha-panoramica.jpg'),
(5, 'Cancha 3', 'Muro', 'Cubierta', 23000, 27000, 'cancha-muro.jpg'),
(5, 'Cancha 4', 'Muro', 'Cubierta', 23000, 27000, 'cancha-muro.jpg'),
(5, 'Cancha 5', 'Normal', 'Aire libre', 21000, 24000, 'cancha-normal.jpg'),
(5, 'Cancha 6', 'Normal', 'Aire libre', 21000, 24000, 'cancha-normal.jpg'),
(6, 'Cancha 1', 'Panorámica', 'Techada', 21000, 25000, 'cancha-panoramica.jpg'),
(6, 'Cancha 2', 'Muro', 'Techada', 19000, 23000, 'cancha-muro.jpg'),
(6, 'Cancha 3', 'Normal', 'Aire libre', 17000, 20000, 'cancha-normal.jpg');

-- Reservas
CREATE TABLE IF NOT EXISTS reservas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT NOT NULL,
  cancha_id INT NOT NULL,
  fecha DATE NOT NULL,
  hora VARCHAR(5) NOT NULL,
  total INT NOT NULL,
  estado VARCHAR(20) NOT NULL DEFAULT 'confirmada',
  creada DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
  FOREIGN KEY (cancha_id) REFERENCES canchas(id)
);

-- Reservas de Juan Perez
INSERT INTO reservas (usuario_id, cancha_id, fecha, hora, total) VALUES
(2, 1, CURDATE(), '20:00', 42000),
(2, 1, CURDATE(), '21:30', 42000);

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
