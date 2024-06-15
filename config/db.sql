-- Crear la base de datos
CREATE DATABASE EjemploDB;

-- Usar la base de datos
USE EjemploDB;

-- Crear una tabla
CREATE TABLE Usuarios (
    ID INT PRIMARY KEY AUTO_INCREMENT,
    Nombre VARCHAR(50),
    Apellido VARCHAR(50),
    Email VARCHAR(100)
);

-- Insertar algunos registros de ejemplo
INSERT INTO Usuarios (Nombre, Apellido, Email)
VALUES ('Juan', 'Pérez', 'juan.perez@example.com'),
       ('Ana', 'García', 'ana.garcia@example.com'),
       ('Carlos', 'Rodríguez', 'carlos.rodriguez@example.com');