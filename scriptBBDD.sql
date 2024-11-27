CREATE SCHEMA `tienda_bd`;

USE tienda_bd;

CREATE TABLE categorias (
    categoria VARCHAR(30) PRIMARY KEY,
    descripcion VARCHAR(255)
);

CREATE TABLE usuarios (
    usuario VARCHAR(15) PRIMARY KEY,
    contraseña VARCHAR(255)
);

DROP TABLE usuarios;

CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50),
    precio NUMERIC(6,2),
    categoria VARCHAR(30), -- Se agrega esta columna para la clave foránea
    CONSTRAINT `productos_categoria_fk` FOREIGN KEY (`categoria`) REFERENCES `categorias` (`categoria`),
    stock INT DEFAULT 0,
    imagen VARCHAR(60),
    descripcion VARCHAR(255)
);

SELECT * FROM categorias;
SELECT * FROM productos;
SELECT * FROM usuario;