-- 1. Creación de la base de datos
CREATE DATABASE IF NOT EXISTS sistema_roles_dinamico CHARACTER SET utf8mb4 COLLATE  utf8mb4_unicode_ci;
USE sistema_roles_dinamico;

-- 2. Tabla de roles
CREATE TABLE roles (
	id INT AUTO_INCREMENT PRIMARY KEY, nombre VARCHAR(30) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1000;

-- 3. Tabla de usuarios con llave foraneas
CREATE TABLE usuario(
	id INT AUTO_INCREMENT PRIMARY KEY, usuario VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol_id INT NOT NULL,
    FOREING KEY (rol_id) REFERENCES roles(id ON DELETE RESTRICT ON UPDATE CASCADE)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1000;

-- 4. insercion de datos semilla
INSERT INTO roles(id, nombre) VALUES 
(1, 'Administrador'),
(2, 'Supervisor'),
(3, 'Cliente');

-- 5. Insercion de usuarios
-- la contraseña de los usuarios creados por defecto son los siguientes
-- admin123, super123, cliente123
INSERT INTO  usuarios(usuario, password, rol_id) VALUES
('admin_user', '$2y$10$E9K8MxgH8vM5N3uVpL1xO.XjK3bY9h7S6R5eF4wD3cBaZ1yX2wVu.', 1),
('super_user', '$2y$10$L1xO.XjK3bY9h7S6R5eF4wD3cBaZ1yX2wVu.E9K8MxgH8vM5N3uVp', 2),
('cliente_user', '$2y$10$R5eF4wD3cBaZ1yX2wVu.E9K8MxgH8vM5N3uVpL1xO.XjK3bY9h7S6', 3);

