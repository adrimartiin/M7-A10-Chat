CREATE DATABASE BD_MENSAJES;

USE BD_MENSAJES;

CREATE TABLE tbl_usuarios(
    id_usuario INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nombre_usuario VARCHAR(50) ,
    nombreReal_usuario VARCHAR(70) ,
    telf_usuario CHAR(9),
    psswd_usuario VARCHAR(60)
);

CREATE TABLE tbl_solicitudes (
    id_solicitud INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    id_usuario_solicitante INT NOT NULL, 
    id_usuario_receptor INT NOT NULL,    
    estado_solicitud ENUM('pendiente', 'aceptada', 'rechazada') DEFAULT 'pendiente',
    fecha_solicitud DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario_solicitante) REFERENCES tbl_usuarios(id_usuario),
    FOREIGN KEY (id_usuario_receptor) REFERENCES tbl_usuarios(id_usuario)
);

CREATE TABLE tbl_amigos (
    id_amistad INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    id_usuario_Uno INT NOT NULL, 
    id_usuario_Dos INT NOT NULL,  
    fecha_amistad DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario_Uno) REFERENCES tbl_usuarios(id_usuario),
    FOREIGN KEY (id_usuario_Dos) REFERENCES tbl_usuarios(id_usuario)    
);

CREATE TABLE tbl_mensajes(
    id_mensaje INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    id_usuario_emisor INT NOT NULL,
    id_usuario_receptor INT NOT NULL,
    mensaje VARCHAR(250) NOT NULL,
    fecha_mensaje DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario_emisor) REFERENCES tbl_usuarios(id_usuario),
    FOREIGN KEY (id_usuario_receptor) REFERENCES tbl_usuarios(id_usuario)
);

