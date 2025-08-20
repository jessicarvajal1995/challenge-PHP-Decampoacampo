CREATE DATABASE IF NOT EXISTS productos_db;
USE productos_db;

CREATE TABLE IF NOT EXISTS productos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(255) NOT NULL,
    descripcion TEXT NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Datos solo de ejemplo al momento de crear la base de datos
INSERT INTO productos (nombre, descripcion, precio) VALUES
('Laptop Dell XPS 13', 'Laptop ultradelgada con procesador Intel Core i7, 16GB RAM, 512GB SSD', 1500000.00),
('iPhone 14 Pro', 'Smartphone Apple con cámara de 48MP, chip A16 Bionic, 128GB almacenamiento', 1200000.00); 