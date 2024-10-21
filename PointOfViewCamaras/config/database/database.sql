SELECT * FROM `envio` WHERE 1DROP DATABASE IF EXISTS POVCamaras;
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
    idUsuario INT NOT NULL,
    cantidad INT NOT NULL,
    precioUnitario DECIMAL(10,2) NOT NULL,
    idPedido INT,
    idProducto INT,
    subtotal DECIMAL(10,2) NOT NULL,
    CONSTRAINT PK_LINEA_PEDIDO PRIMARY KEY (idLineaPedido),
    CONSTRAINT FK_LINEA_PEDIDO_PEDIDO FOREIGN KEY (idPedido) REFERENCES PEDIDO(idPedido),
    CONSTRAINT FK_LINEA_PEDIDO_PRODUCTO FOREIGN KEY (idProducto) REFERENCES PRODUCTO(idProducto),
    CONSTRAINT FK_LINEA_PEDIDO_USUARIO FOREIGN KEY (idUsuario) REFERENCES USUARIOS(idUsuario)
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
  estadoPago VARCHAR(50) NOT NULL, 
  fechaPago DATE NOT NULL,
  FOREIGN KEY (idPedido) REFERENCES Pedido(idPedido),
  FOREIGN KEY (idMetodoPago) REFERENCES MetodoPago(idMetodoPago)
);



INSERT INTO MetodoPago (nombreMetodoPago) VALUES ('Tarjeta de Crédito'), ('Tarjeta de Débito'), ('PayPal'), ('Transferencia Bancaria');

INSERT INTO ROL VALUES ('','Administrador'),('','Cliente');

INSERT INTO USUARIOS VALUES ('','Administrador','1', 'admin1@gmail.com', '', '', 'admin1', '1', ''),('','Administrador','2', 'admin2@gmail.com', '', '', 'admin2', '1', '');

INSERT INTO CATEGORIA VALUES ('','Cámaras'),('','Accesorios');

INSERT INTO PRODUCTO (idProducto, nombreProducto, marca, modelo, descripcion, idCategoria, precioUnitario, stock, imagen) VALUES
('', 'EOS R8', 'CANON', 'Cámara Mirrorless Body', 'Conectividad: Wi-Fi y Bluetooth para transferencia de imágenes y disparo remoto desde tu smartphone o tablet.', '1', 1400, 2, '../assets/images/productos/1.jpg'),
('', 'EOS R6', 'CANON', 'Cámara Mirrorless Full-Frame', 'Estabilización de imagen en el cuerpo, sensor CMOS de 20 MP, video 4K UHD.', '1', 2500, 3, '../assets/images/productos/1.jpg'),
('', 'Alpha 7 IV', 'SONY', 'Cámara Mirrorless Full-Frame', 'Sensor Exmor R CMOS de 33 MP, procesador BIONZ XR, video 4K 60p.', '1', 2800, 4, '../assets/images/productos/1.jpg'),
('', 'Z6 II', 'NIKON', 'Cámara Mirrorless Full-Frame', 'Sensor CMOS de 24.5 MP, Dual EXPEED 6, 4K UHD.', '1', 2000, 5, '../assets/images/productos/1.jpg'),
('', 'Lumix S5', 'PANASONIC', 'Cámara Mirrorless Full-Frame', '24 MP, grabación de video 4K, diseño compacto y ligero.', '1', 1700, 6, '../assets/images/productos/1.jpg'),
('', 'X-T5', 'FUJIFILM', 'Cámara Mirrorless APS-C', '40 MP, video 6.2K, estabilización de imagen en el cuerpo (IBIS).', '1', 1900, 3, '../assets/images/productos/1.jpg'),
('', 'EOS M50 Mark II', 'CANON', 'Cámara Mirrorless APS-C', 'Sensor CMOS APS-C de 24.1 MP, video 4K, pantalla táctil abatible.', '1', 600, 10, '../assets/images/productos/1.jpg'),
('', 'Alpha 6400', 'SONY', 'Cámara Mirrorless APS-C', 'Sensor CMOS de 24.2 MP, video 4K HDR, Eye AF en tiempo real.', '1', 900, 8, '../assets/images/productos/1.jpg'),
('', 'Z50', 'NIKON', 'Cámara Mirrorless APS-C', '20.9 MP, video 4K, pantalla abatible para vlogging.', '1', 850, 5, '../assets/images/productos/1.jpg'),
('', 'GFX 100S', 'FUJIFILM', 'Cámara Mirrorless Medio Formato', '102 MP, estabilización en el cuerpo, video 4K.', '1', 6000, 2, '../assets/images/productos/1.jpg'),
('', 'OM-D E-M1 Mark III', 'OLYMPUS', 'Cámara Mirrorless Micro Cuatro Tercios', '20 MP, estabilización de imagen de 5 ejes, video 4K.', '1', 1500, 4, '../assets/images/productos/1.jpg'),

('', 'Mavic Air 2', 'DJI', 'Dron con Cámara 4K', 'Capaz de grabar video 4K, con modos de vuelo inteligentes y control remoto.', '2', 800, 6, '../assets/images/productos/1.jpg'),
('', 'Insta360 ONE X2', 'Insta360', 'Cámara 360', 'Cámara de acción con grabación de video en 360 grados, resistente al agua.', '1', 430, 9, '../assets/images/productos/1.jpg'),
('', 'GoPro HERO10 Black', 'GoPro', 'Cámara de acción', 'Grabación de video en 5.3K, estabilización de imagen HyperSmooth 4.0.', '2', 500, 7, '../assets/images/productos/1.jpg'),
('', 'Pocket 2', 'DJI', 'Cámara de bolsillo estabilizada', 'Grabación de video 4K con gimbal de 3 ejes integrado, ideal para vlogs.', '2', 350, 15, '../assets/images/productos/1.jpg'),
('', 'Lumix GH5 II', 'PANASONIC', 'Cámara Mirrorless Micro Cuatro Tercios', '20.3 MP, video 4K 60p, estabilización de imagen dual IS 2.', '1', 1700, 4, '../assets/images/productos/1.jpg'),
('', 'Alpha 7C', 'SONY', 'Cámara Mirrorless Full-Frame Compacta', '24.2 MP, video 4K HDR, estabilización de imagen en 5 ejes.', '1', 1800, 6, '../assets/images/productos/1.jpg'),
('', 'EOS RP', 'CANON', 'Cámara Mirrorless Full-Frame', '26.2 MP, video 4K, pantalla táctil abatible, compacta y ligera.', '1', 1300, 3, '../assets/images/productos/1.jpg'),
('', 'Z7 II', 'NIKON', 'Cámara Mirrorless Full-Frame', '45.7 MP, Dual EXPEED 6, video 4K UHD.', '1', 3000, 2, '../assets/images/productos/1.jpg'),
('', 'X-T30 II', 'FUJIFILM', 'Cámara Mirrorless APS-C', '26.1 MP, video 4K, diseño retro y compacto.', '1', 900, 12, '../assets/images/productos/1.jpg'),
('', 'SL2-S', 'LEICA', 'Cámara Mirrorless Full-Frame', '24 MP, estabilización de imagen en el cuerpo, video 4K.', '1', 4500, 1, '../assets/images/productos/1.jpg'),

('', 'Hero9 Black', 'GoPro', 'Cámara de acción', 'Grabación de video en 5K, pantalla frontal para selfies.', '2', 400, 8, '../assets/images/productos/1.jpg'),
('', 'Osmo Action', 'DJI', 'Cámara de acción', 'Video 4K HDR, pantalla frontal, resistente al agua sin carcasa.', '2', 300, 9, '../assets/images/productos/1.jpg'),
('', 'Alpha 1', 'SONY', 'Cámara Mirrorless Full-Frame', '50.1 MP, grabación de video 8K, hasta 30 fps en ráfaga.', '1', 6500, 1, '../assets/images/productos/1.jpg'),
('', 'GFX 50S II', 'FUJIFILM', 'Cámara Mirrorless Medio Formato', '51.4 MP, estabilización en el cuerpo, diseño ergonómico.', '1', 4000, 2, '../assets/images/productos/1.jpg'),
('', 'EOS R5', 'CANON', 'Cámara Mirrorless Full-Frame', '45 MP, grabación de video 8K, estabilización de imagen.', '1', 3900, 3, '../assets/images/productos/1.jpg'),
('', 'Z9', 'NIKON', 'Cámara Mirrorless Full-Frame', '45.7 MP, grabación de video 8K, sin espejo de alta velocidad.', '1', 5500, 2, '../assets/images/productos/1.jpg'),
('', 'GH6', 'PANASONIC', 'Cámara Mirrorless Micro Cuatro Tercios', '25.2 MP, grabación de video 5.7K, estabilización en el cuerpo.', '1', 2200, 5, '../assets/images/productos/1.jpg'),
('', 'X-H2S', 'FUJIFILM', 'Cámara Mirrorless APS-C', '26.1 MP, video 6.2K, disparo continuo de alta velocidad.', '1', 2500, 4, '../assets/images/productos/1.jpg'),
('', 'FP L', 'SIGMA', 'Cámara Mirrorless Full-Frame', '61 MP, video 4K, diseño compacto y modular.', '1', 3000, 2, '../assets/images/productos/1.jpg');
