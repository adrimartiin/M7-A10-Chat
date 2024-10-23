CREATE DATABASE BD_MENSAJES;

USE DATABASE BD_MENSAJES;

CREATE TABLE tbl_usuarios(
    id_usuario INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nombre_usuario VARCHAR(50) ,
    nombreReal_usuario VARCHAR(70) ,
    telf_usuario CHAR(9),
    psswd_usuario VARCHAR(60)
);

CREATE TBL tbl_mensajes (
    id_mensaje INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    id_usuario_emisor INT,
    id_usuario_receptor INT,
    mensaje VARCHAR(250),
    fecha_envio DATETIME DEFAULT CURRENT_TIMESTAMP,
);

ALTER TABLE tbl_mensajes ADD CONSTRAINT fk_usuario_emisor FOREIGN KEY (id_usuario_emisor) REFERENCES tbl_usuarios(id_usuario);
ALTER TABLE tbl_mensajes ADD CONSTRAINT fk_usuario_receptor FOREIGN KEY (id_usuario_receptor) REFERENCES tbl_usuarios(id_usuario);


INSERT INTO tbl_usuarios (id_usuario,nombre_usuario, nombreReal_usuario, telf_usuario, psswd_usuario) VALUES 
('', 'user1', 'Juan Pérez', '123456789', '12345'),
('', 'user2', 'Ana Gómez', '987654321', '123456'),
('', 'user3', 'Luis Fernández', '456789123', '1234567'),
('', 'user4', 'María López', '321654987', '12345678');
