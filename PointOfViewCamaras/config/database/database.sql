DROP DATABASE IF EXISTS POVCamaras;
CREATE DATABASE POVCamaras;
USE POVCamaras;

CREATE TABLE ROL(
    idRol  INT AUTO_INCREMENT,
    nombreRol   VARCHAR(20) NOT NULL,
    CONSTRAINT PK_ROL PRIMARY KEY (idRol)
);

CREATE TABLE USUARIOS(
    idUsuario INT AUTO_INCREMENT,
    nombreUsuario  VARCHAR(50) NOT NULL,
    apellidos VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL,
    direccion VARCHAR(100),
    telefono VARCHAR(15)NOT NULL,
    contrasena VARCHAR(50) NOT NULL, 
    idRol INT DEFAULT '1',
    metodoPago VARCHAR(50),
    CONSTRAINT PK_USUARIOS PRIMARY KEY (idUsuario),
    CONSTRAINT FK_ROL_USUARIO FOREIGN KEY  (idRol) REFERENCES ROL(idRol)
);

CREATE TABLE CATEGORIA(
    idCategoria INT AUTO_INCREMENT,
    nombreCategoria VARCHAR(50) NOT NULL,
    CONSTRAINT PK_CATEGORIA PRIMARY KEY (idCategoria)
);

CREATE TABLE PRODUCTO(
    idProducto INT AUTO_INCREMENT,
    nombreProducto VARCHAR(50) NOT NULL,
    marca VARCHAR(50) NOT NULL,
    modelo VARCHAR(50) NOT NULL,
    descripcion TEXT,
    idCategoria INT,
    precioUnitario DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL,
    imagen VARCHAR(225),
    CONSTRAINT PK_PRODUCTO PRIMARY KEY (idProducto),
    CONSTRAINT FK_PRODUCTO_CATEGORIA FOREIGN KEY (idCategoria) REFERENCES CATEGORIA(idCategoria)
);

CREATE TABLE PEDIDO(
    idPedido INT AUTO_INCREMENT,
    nombreUsuario VARCHAR(50) NOT NULL,
    apellidos VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL,
    direccion VARCHAR(100),
    precioTotal DECIMAL(10,2) NOT NULL,
    estadoPedido VARCHAR(20) NOT NULL,
    fechaPedido DATE NOT NULL,
    estadoPago VARCHAR(50) DEFAULT 'Pendiente', 
    idUsuario INT,
    CONSTRAINT PK_PEDIDO PRIMARY KEY (idPedido),
    CONSTRAINT FK_PEDIDO_USUARIO FOREIGN KEY (idUsuario) REFERENCES USUARIOS(idUsuario)
);

CREATE TABLE CATEGORIA_PRODUCTO(
    idCategoria INT,
    idProducto INT,
    CONSTRAINT PK_CATEGORIA_PRODUCTO PRIMARY KEY (idProducto,idCategoria),
    CONSTRAINT FK_CATEGORIA_PRODUCTO_CATEGORIA FOREIGN KEY (idCategoria) REFERENCES CATEGORIA(idCategoria),
    CONSTRAINT FK_CATEGORIA_PRODUCTO_PRODUCTO FOREIGN KEY (idProducto) REFERENCES PRODUCTO(idProducto)
);

CREATE TABLE LINEA_PEDIDO(
    idLineaPedido INT AUTO_INCREMENT,
    cantidad INT NOT NULL,
    precioUnitario DECIMAL(10,2) NOT NULL,
    idPedido INT,
    idProducto INT,
    subtotal DECIMAL(10,2) NOT NULL,
    CONSTRAINT PK_LINEA_PEDIDO PRIMARY KEY (idLineaPedido),
    CONSTRAINT FK_LINEA_PEDIDO_PEDIDO FOREIGN KEY (idPedido) REFERENCES PEDIDO(idPedido),
    CONSTRAINT FK_LINEA_PEDIDO_PRODUCTO FOREIGN KEY (idProducto) REFERENCES PRODUCTO(idProducto)
);

create TABLE ENVIO(
    idEnvio INT AUTO_INCREMENT,
    numeroSeguimiento VARCHAR(100),
    fechaEnvio DATE NOT NULL,
    empresaEnvio VARCHAR(50) NOT NULL,
    idPedido INT,
    CONSTRAINT PK_ENVIO PRIMARY KEY (idEnvio),
    CONSTRAINT FK_ENVIO_PEDIDO FOREIGN KEY (idPedido) REFERENCES PEDIDO(idPedido)
);

CREATE TABLE MetodoPago (
  idMetodoPago INT PRIMARY KEY AUTO_INCREMENT,
  nombreMetodoPago VARCHAR(50) NOT NULL

);

CREATE TABLE Pago (
  idPago INT PRIMARY KEY AUTO_INCREMENT,
  idPedido INT NOT NULL,
  idMetodoPago INT NOT NULL,
  monto DECIMAL(10, 2) NOT NULL,
  estadoPago VARCHAR(50) NOT NULL,  -- Ej. 'Completado', 'Pendiente', 'Fallido'
  fechaPago DATE NOT NULL,
  FOREIGN KEY (idPedido) REFERENCES Pedido(idPedido),
  FOREIGN KEY (idMetodoPago) REFERENCES MetodoPago(idMetodoPago)
);

INSERT INTO ROL VALUES ('','Administrador'),('','Cliente');

INSERT INTO USUARIOS VALUES ('','Administrador','1', 'admin1@gmail.com', '', '', 'admin1', '2', ''),('','Administrador','2', 'admin2@gmail.com', '', '', 'admin2', '2', '');


