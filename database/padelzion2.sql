-- Seed DB

DROP DATABASE IF EXISTS padelzion2;
CREATE DATABASE padelzion2 CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE padelzion2;

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
