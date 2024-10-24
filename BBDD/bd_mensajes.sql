CREATE DATABASE BD_MENSAJES;

USE DATABASE BD_MENSAJES;

CREATE TABLE tbl_usuarios(
    id_usuario INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nombre_usuario VARCHAR(50) ,
    nombreReal_usuario VARCHAR(70) ,
    telf_usuario CHAR(9),
    psswd_usuario VARCHAR(60)
);

-- Tabla para guardar las solicitudes de amistad
CREATE TABLE tbl_solicitudes (
    id_solicitud INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    id_usuario_solicitante INT NOT NULL, -- El que envía la solicitud
    id_usuario_receptor INT NOT NULL,    -- El que recibe la solicitud
    estado_solicitud ENUM('pendiente', 'aceptada', 'rechazada') DEFAULT 'pendiente',
    fecha_solicitud DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario_solicitante) REFERENCES tbl_usuarios(id_usuario),
    FOREIGN KEY (id_usuario_receptor) REFERENCES tbl_usuarios(id_usuario)
);

-- Tabla para guardar a los amigos (solicitudes ya aceptadas)
CREATE TABLE tbl_amigos (
    id_amistad INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    id_usuario_Uno INT NOT NULL, -- el que envia la solicitud
    id_usuario_Dos INT NOT NULL,  -- el que acepta la solicitud
    fecha_amistad DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario_Uno) REFERENCES tbl_usuarios(id_usuario),
    FOREIGN KEY (id_usuario_Dos) REFERENCES tbl_usuarios(id_usuario)
);
