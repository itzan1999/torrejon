CREATE TABLE `usuario` (
  `id` uuid PRIMARY KEY,
  `username` varchar(255) UNIQUE NOT NULL,
  `email` varchar(255) UNIQUE NOT NULL,
  `saldo` decimal DEFAULT 0,
  `direccion` string NOT NULL
);

CREATE TABLE `cuenta` (
  `id_user` uuid PRIMARY KEY,
  `nombre` varchar(255) NOT NULL,
  `apellidos` varchar(255) NOT NULL,
  `password` string NOT NULL,
  `activa` bool DEFAULT false,
  `fecha_alta` timestamp DEFAULT (now())
);

CREATE TABLE `activar_cuenta` (
  `id_user` uuid PRIMARY KEY,
  `token` varchar(255) NOT NULL,
  `fecha_creacion` timestamp DEFAULT (now()),
  `fecha_expiracion` timestamp,
  `usado` bool DEFAULT false,
  CONSTRAINT `chk_fecha_expiracion_valida` CHECK (fecha_expiracion > fecha_creacion)
);

CREATE TABLE `cambiar_password` (
  `id_user` uuid PRIMARY KEY,
  `token` varchar(255) NOT NULL,
  `fecha_creacion` timestamp DEFAULT (now()),
  `fecha_expiracion` timestamp,
  `usado` bool DEFAULT false,
  CONSTRAINT `chk_fecha_expiracion_valida` CHECK (fecha_expiracion > fecha_creacion)
);

CREATE TABLE `producto` (
  `id` integer PRIMARY KEY AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `precio` decimal NOT NULL,
  `stock` int DEFAULT 0,
  `descripcion` string,
  `oferta` decimal DEFAULT 0 COMMENT 'Porcentaje de oferta',
  `informacion_nutricional` string NOT NULL COMMENT 'JSON o XML que almacene los datos de la informacion nutricional',
  `tamanyo` decimal,
  `unidad_medida` ENUM ('mg', 'g', 'kg', 'mL', 'L'),
  `path_imagen` varchar(255),
  CONSTRAINT `chk_stock_no_negativo` CHECK (stock >= 0)
);

CREATE TABLE `pedido` (
  `id` uuid PRIMARY KEY,
  `codigo` int UNIQUE NOT NULL,
  `id_usuario` uuid NOT NULL,
  `estado` ENUM ('creado', 'procesado', 'reparto', 'entregado', 'cancelado', 'devuelto'),
  `subcripcion` bool DEFAULT false
);

CREATE TABLE `pedido_producto` (
  `id_pedido` uuid,
  `id_producto` int,
  `cantidad` int DEFAULT 1,
  CONSTRAINT `chk_cantidad_positiva` CHECK (cantidad > 0),
  PRIMARY KEY (`id_pedido`, `id_producto`)
);

CREATE TABLE `carrito` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `id_usuario` uuid UNIQUE,
  `precio_total` decimal
);

CREATE TABLE `carrito_producto` (
  `id_carrito` int,
  `id_producto` int,
  `cantidad` int DEFAULT 1,
  CONSTRAINT `chk_cantidad_positiva` CHECK (cantidad > 0),
  PRIMARY KEY (`id_carrito`, `id_producto`)
);

CREATE TABLE `datos_consultas` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telefono` string NOT NULL,
  `consulta` string NOT NULL
);

CREATE TABLE `rol` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `nombre_rol` varchar(255) UNIQUE NOT NULL
);

CREATE TABLE `permiso` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `desc_permiso` string NOT NULL
);

CREATE TABLE `usuario_rol` (
  `id_usuario` uuid,
  `id_rol` int,
  PRIMARY KEY (`id_usuario`, `id_rol`)
);

CREATE TABLE `rol_permiso` (
  `id_rol` int,
  `id_permiso` int,
  PRIMARY KEY (`id_rol`, `id_permiso`)
);

CREATE TABLE `movimiento_saldo` (
  `id` uuid PRIMARY KEY,
  `id_usuario` uuid,
  `fecha` timestamp DEFAULT (now()),
  `importe` double,
  `tipo` ENUM ('recarga', 'pago', 'devolucion')
);

CREATE TABLE `suscripcion` (
  `id` uuid PRIMARY KEY,
  `id_usuario` uuid NOT NULL,
  `tipo` ENUM ('semanal', 'mensual'),
  `fecha_inicio` datetime
);

CREATE TABLE `producto_suscripcion` (
  `id_producto` int,
  `id_suscripcion` uuid,
  `cantidad` int NOT NULL,
  PRIMARY KEY (`id_producto`, `id_suscripcion`)
);

ALTER TABLE `movimiento_saldo` ADD FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`);

ALTER TABLE `cuenta` ADD FOREIGN KEY (`id_user`) REFERENCES `usuario` (`id`);

ALTER TABLE `activar_cuenta` ADD FOREIGN KEY (`id_user`) REFERENCES `usuario` (`id`) ON DELETE CASCADE;

ALTER TABLE `cambiar_password` ADD FOREIGN KEY (`id_user`) REFERENCES `usuario` (`id`) ON DELETE CASCADE;

ALTER TABLE `pedido` ADD FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`);

ALTER TABLE `carrito` ADD FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`);

ALTER TABLE `pedido_producto` ADD FOREIGN KEY (`id_pedido`) REFERENCES `pedido` (`id`);

ALTER TABLE `pedido_producto` ADD FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id`);

ALTER TABLE `carrito_producto` ADD FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id`);

ALTER TABLE `carrito_producto` ADD FOREIGN KEY (`id_carrito`) REFERENCES `carrito` (`id`);

ALTER TABLE `usuario_rol` ADD FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`);

ALTER TABLE `usuario_rol` ADD FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id`);

ALTER TABLE `rol_permiso` ADD FOREIGN KEY (`id_permiso`) REFERENCES `permiso` (`id`);

ALTER TABLE `rol_permiso` ADD FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id`);

ALTER TABLE `producto_suscripcion` ADD FOREIGN KEY (`id_suscripcion`) REFERENCES `suscripcion` (`id`);

ALTER TABLE `suscripcion` ADD FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`);

ALTER TABLE `producto_suscripcion` ADD FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id`);
