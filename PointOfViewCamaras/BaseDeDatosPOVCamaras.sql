DROP DATABASE IF EXISTS POVCamaras;
CREATE DATABASE POVCamaras;
USE POVCamaras;

CREATE TABLE ROL(
    idRol    VARCHAR(3),
    nombreRol   VARCHAR(20) NOT NULL,
    CONSTRAINT PK_ROL PRIMARY KEY (idRol)
);

CREATE TABLE USUARIOS(
    idUsuario    VARCHAR(100),
    nombreUsuario  VARCHAR(50) NOT NULL,
    apellidos VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL,
    dni VARCHAR(10),
    direccion VARCHAR(100),
    telefono VARCHAR(15)NOT NULL,
    contrasena VARCHAR(50) NOT NULL,
    rol VARCHAR(3),
    CONSTRAINT PK_USUARIOS PRIMARY KEY (idUsuario),
    CONSTRAINT FK_ROL_USUARIO FOREIGN KEY  (rol) REFERENCES ROL(idRol)
);
CREATE TABLE PEDIDO(
    idPedido VARCHAR(100),
    nombreUsuario VARCHAR(50) NOT NULL,
    apellidos VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL,
    direccion VARCHAR(100),
    precioTotal DECIMAL(10,2) NOT NULL,
    estadoPedido VARCHAR(20) NOT NULL,
    fechaPedido DATE NOT NULL,
    idUsuario VARCHAR(100),
    CONSTRAINT PK_PEDIDO PRIMARY KEY (idPedido),
    CONSTRAINT FK_PEDIDO_USUARIO FOREIGN KEY (idUsuario) REFERENCES USUARIOS(idUsuario)
);
CREATE TABLE CATEGORIA(
    idCategoria VARCHAR(100),
    nombreCategoria VARCHAR(50) NOT NULL,
    CONSTRAINT PK_CATEGORIA PRIMARY KEY (idCategoria)
);

CREATE TABLE PRODUCTO(
    idProducto VARCHAR(100),
    nombreProducto VARCHAR(50) NOT NULL,
    marca VARCHAR(50) NOT NULL,
    modelo VARCHAR(50) NOT NULL,
    descripcion VARCHAR(100),
    idCategoria VARCHAR(100),
    precioUnitario DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL,
    imagen VARCHAR(100),
    CONSTRAINT PK_PRODUCTO PRIMARY KEY (idProducto),
    CONSTRAINT FK_PRODUCTO_CATEGORIA FOREIGN KEY (idCategoria) REFERENCES CATEGORIA(idCategoria)
);
CREATE TABLE CATEGORIA_PRODUCTO(
    idCategoria VARCHAR(100),
    idProducto VARCHAR(100),
    CONSTRAINT PK_CATEGORIA_PRODUCTO PRIMARY KEY (idProducto,idCategoria),
    CONSTRAINT FK_CATEGORIA_PRODUCTO_CATEGORIA FOREIGN KEY (idCategoria) REFERENCES CATEGORIA(idCategoria),
    CONSTRAINT FK_CATEGORIA_PRODUCTO_PRODUCTO FOREIGN KEY (idProducto) REFERENCES PRODUCTO(idProducto)
);

CREATE TABLE LINEA_PEDIDO(
    idLineaPedido VARCHAR(100),
    cantidad INT NOT NULL,
    precioUnitario DECIMAL(10,2) NOT NULL,
    idPedido VARCHAR(100),
    idProducto VARCHAR(100),
    subtotal DECIMAL(10,2) NOT NULL,
    CONSTRAINT PK_LINEA_PEDIDO PRIMARY KEY (idLineaPedido),
    CONSTRAINT FK_LINEA_PEDIDO_PEDIDO FOREIGN KEY (idPedido) REFERENCES PEDIDO(idPedido),
    CONSTRAINT FK_LINEA_PEDIDO_PRODUCTO FOREIGN KEY (idProducto) REFERENCES PRODUCTO(idProducto)
);

create TABLE ENVIO(
    idEnvio VARCHAR(100),
    numeroSeguimiento VARCHAR(100),
    fechaEnvio DATE NOT NULL,
    empresaEnvio VARCHAR(50) NOT NULL,
    idPedido VARCHAR(100),
    CONSTRAINT PK_ENVIO PRIMARY KEY (idEnvio),
    CONSTRAINT FK_ENVIO_PEDIDO FOREIGN KEY (idPedido) REFERENCES PEDIDO(idPedido)
);

-- CREATE TABLE PROYECTOS(
--     cod_proyecto    VARCHAR(2),
--     nom_proyecto    VARCHAR(20),
--     ciu_proyecto    VARCHAR(10),
--     CONSTRAINT PK_PROYECTOS PRIMARY KEY (cod_proyecto)
-- );

-- CREATE TABLE   SUMINISTRAR(
--     Suministrador   VARCHAR(2),
--     Pieza           VARCHAR(2),
--     Proyecto        VARCHAR(2),
--     cantidad        INT,
--     CONSTRAINT PK_SUMINISTRAR PRIMARY KEY (Suministrador,Pieza,proyecto),
--     CONSTRAINT FK_SUMINISTRAR_SUMINISTRADORES FOREIGN KEY (suministrador)  REFERENCES SUMINISTRADORES(idSuministrador),
--     CONSTRAINT FK_SUMINISTRAR_PIEZAS FOREIGN KEY  (Pieza) REFERENCES PIEZAS(codPieza),
--     CONSTRAINT FK_SUMINISTRAR_PROYECTOS FOREIGN KEY  (Proyecto) REFERENCES PROYECTOS(cod_proyecto)
-- );

-- INSERT INTO PRODUCTOS 
--     VALUES   ('S1', 'Smith', 20 ,'Londres'),
--              ('S2' ,'Jones', 10 ,'París' ),
--              ('S3' ,'Blake' ,30 ,'París'),
--              ('S4', 'Clark',20 ,'Londres'),
--              ('S5' ,'Adams',30 ,'Atenas' );

-- INSERT INTO  PIEZAS 
--     VALUES  ('P1', 'Mesa','rojo' ,12 ,'Londres' ),
--             ('P2' ,'Silla' ,'blanca' ,17 ,'París' ),
--             ('P3', 'Armario', 'gris',17, 'Roma' ),
--             ('P4' ,'Archivador', 'rojo', 14 ,'Londres'), 
--             ('P5', 'Puerta', 'blanca', 12,'París' ),
--             ('P6','Lámpara' ,'amarilla', 19 ,'Londres');

