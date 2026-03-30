-- ==========================================
-- DATOS DE PRUEBA - LECHE EL TORREJÓN
-- ==========================================

-- Limpiar datos previos
DELETE FROM producto_suscripcion;
DELETE FROM suscripcion;
DELETE FROM cambiar_password;
DELETE FROM activar_cuenta;
DELETE FROM pedido_producto;
DELETE FROM pedido;
DELETE FROM carrito_producto;
DELETE FROM carrito;
DELETE FROM movimiento_saldo;
DELETE FROM usuario_rol;
DELETE FROM rol_permiso;
DELETE FROM permiso;
DELETE FROM rol;
DELETE FROM cuenta;
DELETE FROM usuario;

-- ==========================================
-- 1. ROLES
-- ==========================================
INSERT INTO rol (nombre_rol, created_at, updated_at) VALUES
('admin', NOW(), NOW()),
('user', NOW(), NOW()),
('vendedor', NOW(), NOW());

-- ==========================================
-- 2. PERMISOS
-- ==========================================
INSERT INTO permiso (desc_permiso, created_at, updated_at) VALUES
('ver_usuarios', NOW(), NOW()),
('editar_usuarios', NOW(), NOW()),
('eliminar_usuarios', NOW(), NOW()),
('ver_productos', NOW(), NOW()),
('crear_productos', NOW(), NOW()),
('editar_productos', NOW(), NOW()),
('eliminar_productos', NOW(), NOW()),
('ver_pedidos', NOW(), NOW()),
('editar_pedidos', NOW(), NOW()),
('ver_reportes', NOW(), NOW());

-- ==========================================
-- 3. ROL_PERMISO (Admin tiene todos los permisos)
-- ==========================================
INSERT INTO rol_permiso (id_rol, id_permiso, created_at, updated_at) VALUES
(1, 1, NOW(), NOW()),
(1, 2, NOW(), NOW()),
(1, 3, NOW(), NOW()),
(1, 4, NOW(), NOW()),
(1, 5, NOW(), NOW()),
(1, 6, NOW(), NOW()),
(1, 7, NOW(), NOW()),
(1, 8, NOW(), NOW()),
(1, 9, NOW(), NOW()),
(1, 10, NOW(), NOW()),
-- User puede ver productos y pedidos
(2, 4, NOW(), NOW()),
(2, 8, NOW(), NOW()),
-- Vendedor puede ver y crear productos
(3, 4, NOW(), NOW()),
(3, 5, NOW(), NOW()),
(3, 6, NOW(), NOW());

-- ==========================================
-- 4. USUARIOS Y CUENTAS
-- ==========================================
-- Usuario 1: Admin
INSERT INTO usuario (id, username, email, saldo, direccion, created_at, updated_at) VALUES
('550e8400-e29b-41d4-a716-446655440000', 'admin', 'admin@lecheeltorrejon.com', 1000.00, 'Calle Admin 1, Madrid', NOW(), NOW());

INSERT INTO cuenta (id_user, nombre, apellidos, password, activa, fecha_alta, created_at, updated_at) VALUES
('550e8400-e29b-41d4-a716-446655440000', 'Admin', 'Sistema', '$2y$10$8.v4/GFbVDQ8rSAiBFLRPO0eYLQDn8O8BqX5V8TQP.3j4YTzMdL3m', 1, NOW(), NOW(), NOW());

-- Usuario 2: Cliente regular
INSERT INTO usuario (id, username, email, saldo, direccion, created_at, updated_at) VALUES
('550e8400-e29b-41d4-a716-446655440001', 'cliente1', 'cliente1@example.com', 500.00, 'Avenida Principal 10, Madrid', NOW(), NOW());

INSERT INTO cuenta (id_user, nombre, apellidos, password, activa, fecha_alta, created_at, updated_at) VALUES
('550e8400-e29b-41d4-a716-446655440001', 'Juan', 'García López', '$2y$10$8.v4/GFbVDQ8rSAiBFLRPO0eYLQDn8O8BqX5V8TQP.3j4YTzMdL3m', 1, NOW(), NOW(), NOW());

-- Usuario 3: Cliente
INSERT INTO usuario (id, username, email, saldo, direccion, created_at, updated_at) VALUES
('550e8400-e29b-41d4-a716-446655440002', 'cliente2', 'itzan.casales@gmail.com', 750.50, 'Calle Secundaria 25, Barcelona', NOW(), NOW());

INSERT INTO cuenta (id_user, nombre, apellidos, password, activa, fecha_alta, created_at, updated_at) VALUES
('550e8400-e29b-41d4-a716-446655440002', 'María', 'Rodríguez Martín', '$2y$10$8.v4/GFbVDQ8rSAiBFLRPO0eYLQDn8O8BqX5V8TQP.3j4YTzMdL3m', 1, NOW(), NOW(), NOW());

-- Usuario 4: Cliente nuevo (inactivo)
INSERT INTO usuario (id, username, email, saldo, direccion, created_at, updated_at) VALUES
('550e8400-e29b-41d4-a716-446655440003', 'cliente3', 'cliente3@example.com', 0.00, 'Plaza Mayor 5, Valencia', NOW(), NOW());

INSERT INTO cuenta (id_user, nombre, apellidos, password, activa, fecha_alta, created_at, updated_at) VALUES
('550e8400-e29b-41d4-a716-446655440003', 'Carlos', 'Fernández Díaz', '$2y$10$8.v4/GFbVDQ8rSAiBFLRPO0eYLQDn8O8BqX5V8TQP.3j4YTzMdL3m', 1, NOW(), NOW(), NOW());

-- ==========================================
-- 5. USUARIO_ROL (Asignar roles)
-- ==========================================
INSERT INTO usuario_rol (id_usuario, id_rol, created_at, updated_at) VALUES
('550e8400-e29b-41d4-a716-446655440000', 1, NOW(), NOW()),
('550e8400-e29b-41d4-a716-446655440001', 2, NOW(), NOW()),
('550e8400-e29b-41d4-a716-446655440002', 2, NOW(), NOW()),
('550e8400-e29b-41d4-a716-446655440003', 2, NOW(), NOW());

-- ==========================================
-- 6. PRODUCTOS
-- ==========================================
INSERT INTO producto (nombre, precio, stock, descripcion, oferta, informacion_nutricional, tamanyo, unidad_medida, path_imagen, created_at, updated_at) VALUES
('Leche Entera 1L', 1.50, 150, 'Leche fresca de vaca de alta calidad', 0.00, '{"calorias": 60, "proteina": 3.2, "grasa": 3.6}', 1.0, 'L', '/images/leche-entera-1l.jpg', NOW(), NOW()),
('Leche Desnatada 1L', 1.20, 100, 'Leche desnatada fresca', 0.00, '{"calorias": 35, "proteina": 3.4, "grasa": 0.1}', 1.0, 'L', '/images/leche-desnatada-1l.jpg', NOW(), NOW()),
('Leche Semi 1L', 1.35, 120, 'Leche semidesnatada fresca', 0.00, '{"calorias": 49, "proteina": 3.3, "grasa": 1.9}', 1.0, 'L', '/images/leche-semi-1l.jpg', NOW(), NOW()),
('Yogur Natural 250g', 0.95, 80, 'Yogur natural sin azúcares añadidos', 0.10, '{"calorias": 59, "proteina": 3.5, "grasa": 0.4}', 250, 'g', '/images/yogur-natural-250g.jpg', NOW(), NOW()),
('Queso Fresco 500g', 4.50, 40, 'Queso fresco de oveja', 0.00, '{"calorias": 264, "proteina": 21, "grasa": 21}', 500, 'g', '/images/queso-fresco-500g.jpg', NOW(), NOW()),
('Mantequilla 250g', 3.20, 60, 'Mantequilla pura de la mejor calidad', 0.00, '{"calorias": 717, "proteina": 0.7, "grasa": 81}', 250, 'g', '/images/mantequilla-250g.jpg', NOW(), NOW()),
('Nata para Cocinar 200ml', 2.10, 75, 'Nata fresca para cocinar', 0.00, '{"calorias": 340, "proteina": 2.2, "grasa": 35}', 200, 'mL', '/images/nata-200ml.jpg', NOW(), NOW()),
('Leche Chocolate 1L', 1.85, 90, 'Leche con chocolate', 0.15, '{"calorias": 90, "proteina": 3.1, "grasa": 3.5}', 1.0, 'L', '/images/leche-chocolate-1l.jpg', NOW(), NOW()),
('Leche de Cabra 500ml', 2.80, 50, 'Leche de cabra fresca', 0.00, '{"calorias": 70, "proteina": 3.6, "grasa": 4.5}', 500, 'mL', '/images/leche-cabra-500ml.jpg', NOW(), NOW()),
('Kéfir Natural 400ml', 2.50, 45, 'Kéfir natural probiótico', 0.00, '{"calorias": 66, "proteina": 3.4, "grasa": 3.5}', 400, 'mL', '/images/kefir-400ml.jpg', NOW(), NOW());

-- ==========================================
-- 7. CARRITOS
-- ==========================================
INSERT INTO carrito (id_usuario, precio_total, created_at, updated_at) VALUES
('550e8400-e29b-41d4-a716-446655440001', 1.50, NOW(), NOW()),
('550e8400-e29b-41d4-a716-446655440002', 4.50, NOW(), NOW()),
('550e8400-e29b-41d4-a716-446655440003', 15.00, NOW(), NOW());

-- ==========================================
-- 8. CARRITO_PRODUCTO (Items en carrito)
-- ==========================================
INSERT INTO carrito_producto (id_carrito, id_producto, cantidad, created_at, updated_at) VALUES
(2, 4, 2, NOW(), NOW()),
(2, 5, 1, NOW(), NOW());

-- ==========================================
-- 9. PEDIDOS
-- ==========================================
INSERT INTO pedido (id, codigo, id_usuario, estado, suscripcion, precio_total, created_at, updated_at) VALUES
('550e8400-e29b-41d4-a716-446655440100', 10001, '550e8400-e29b-41d4-a716-446655440001', 'entregado', 0, 12.50, DATE_SUB(NOW(), INTERVAL 10 DAY), DATE_SUB(NOW(), INTERVAL 10 DAY)),
('550e8400-e29b-41d4-a716-446655440101', 10002, '550e8400-e29b-41d4-a716-446655440001', 'procesado', 0, 8.75, DATE_SUB(NOW(), INTERVAL 5 DAY), DATE_SUB(NOW(), INTERVAL 5 DAY)),
('550e8400-e29b-41d4-a716-446655440102', 10003, '550e8400-e29b-41d4-a716-446655440002', 'entregado', 0, 6.40, DATE_SUB(NOW(), INTERVAL 15 DAY), DATE_SUB(NOW(), INTERVAL 15 DAY)),
('550e8400-e29b-41d4-a716-446655440103', 10004, '550e8400-e29b-41d4-a716-446655440002', 'reparto', 0, 15.30, DATE_SUB(NOW(), INTERVAL 2 DAY), DATE_SUB(NOW(), INTERVAL 2 DAY)),
('550e8400-e29b-41d4-a716-446655440104', 10005, '550e8400-e29b-41d4-a716-446655440001', 'creado', 0, 9.60, NOW(), NOW());

-- ==========================================
-- 10. PEDIDO_PRODUCTO (Items en pedidos)
-- ==========================================
INSERT INTO pedido_producto (id_pedido, id_producto, cantidad, created_at, updated_at) VALUES
('550e8400-e29b-41d4-a716-446655440100', 1, 3, NOW(), NOW()),
('550e8400-e29b-41d4-a716-446655440100', 4, 2, NOW(), NOW()),
('550e8400-e29b-41d4-a716-446655440101', 2, 4, NOW(), NOW()),
('550e8400-e29b-41d4-a716-446655440101', 3, 1, NOW(), NOW()),
('550e8400-e29b-41d4-a716-446655440102', 1, 2, NOW(), NOW()),
('550e8400-e29b-41d4-a716-446655440102', 7, 1, NOW(), NOW()),
('550e8400-e29b-41d4-a716-446655440103', 5, 1, NOW(), NOW()),
('550e8400-e29b-41d4-a716-446655440103', 6, 2, NOW(), NOW()),
('550e8400-e29b-41d4-a716-446655440104', 2, 2, NOW(), NOW()),
('550e8400-e29b-41d4-a716-446655440104', 8, 3, NOW(), NOW());

-- ==========================================
-- 11. MOVIMIENTO_SALDO (Historial de transacciones)
-- ==========================================
INSERT INTO movimiento_saldo (id, id_usuario, fecha, importe, tipo, descripcion, created_at, updated_at) VALUES
('550e8400-e29b-41d4-a716-446655440200', '550e8400-e29b-41d4-a716-446655440001', DATE_SUB(NOW(), INTERVAL 20 DAY), 100.00, 'recarga', 'Recarga de saldo inicial', NOW(), NOW()),
('550e8400-e29b-41d4-a716-446655440201', '550e8400-e29b-41d4-a716-446655440001', DATE_SUB(NOW(), INTERVAL 10 DAY), -12.50, 'pago', 'Pago pedido #10001', NOW(), NOW()),
('550e8400-e29b-41d4-a716-446655440202', '550e8400-e29b-41d4-a716-446655440001', DATE_SUB(NOW(), INTERVAL 5 DAY), -8.75, 'pago', 'Pago pedido #10002', NOW(), NOW()),
('550e8400-e29b-41d4-a716-446655440203', '550e8400-e29b-41d4-a716-446655440002', DATE_SUB(NOW(), INTERVAL 30 DAY), 200.00, 'recarga', 'Recarga de saldo inicial', NOW(), NOW()),
('550e8400-e29b-41d4-a716-446655440204', '550e8400-e29b-41d4-a716-446655440002', DATE_SUB(NOW(), INTERVAL 15 DAY), -6.40, 'pago', 'Pago pedido #10003', NOW(), NOW()),
('550e8400-e29b-41d4-a716-446655440205', '550e8400-e29b-41d4-a716-446655440002', DATE_SUB(NOW(), INTERVAL 2 DAY), -15.30, 'pago', 'Pago pedido #10004', NOW(), NOW()),
('550e8400-e29b-41d4-a716-446655440206', '550e8400-e29b-41d4-a716-446655440001', DATE_SUB(NOW(), INTERVAL 3 DAY), 50.00, 'recarga', 'Recarga adicional', NOW(), NOW());

-- ==========================================
-- 12. SUSCRIPCIONES
-- ==========================================
INSERT INTO suscripcion (id, id_usuario, tipo, fecha_inicio, created_at, updated_at) VALUES
('550e8400-e29b-41d4-a716-446655440300', '550e8400-e29b-41d4-a716-446655440001', 'semanal', DATE_SUB(NOW(), INTERVAL 30 DAY), NOW(), NOW()),
('550e8400-e29b-41d4-a716-446655440301', '550e8400-e29b-41d4-a716-446655440002', 'mensual', DATE_SUB(NOW(), INTERVAL 15 DAY), NOW(), NOW());

-- ==========================================
-- 13. PRODUCTO_SUSCRIPCION (Productos en suscripciones)
-- ==========================================
INSERT INTO producto_suscripcion (id_producto, id_suscripcion, cantidad, created_at, updated_at) VALUES
(1, '550e8400-e29b-41d4-a716-446655440300', 2, NOW(), NOW()),
(4, '550e8400-e29b-41d4-a716-446655440300', 1, NOW(), NOW()),
(2, '550e8400-e29b-41d4-a716-446655440301', 1, NOW(), NOW()),
(6, '550e8400-e29b-41d4-a716-446655440301', 1, NOW(), NOW());

-- ==========================================
-- 14. ACTIVAR_CUENTA
-- ==========================================
INSERT INTO activar_cuenta (id_user, token, fecha_creacion, fecha_expiracion, usado, created_at, updated_at) VALUES
('550e8400-e29b-41d4-a716-446655440003', 'token_activacion_cliente3_abc123def456', NOW(), DATE_ADD(NOW(), INTERVAL 24 HOUR), 0, NOW(), NOW());

-- ==========================================
-- 15. CAMBIAR_PASSWORD
-- ==========================================
INSERT INTO cambiar_password (id_user, token, fecha_creacion, fecha_expiracion, usado, created_at, updated_at) VALUES
('550e8400-e29b-41d4-a716-446655440002', 'token_reset_cliente2_xyz789uvw012', NOW(), DATE_ADD(NOW(), INTERVAL 24 HOUR), 0, NOW(), NOW());

-- ==========================================
-- 16. DATOS_CONSULTAS
-- ==========================================
INSERT INTO datos_consultas (nombre, email, telefono, consulta, created_at, updated_at) VALUES
('Pedro García', 'pedro@example.com', '612345678', 'Me gustaría saber más sobre las suscripciones mensuales', NOW(), NOW()),
('Ana Martínez', 'ana@example.com', '623456789', 'Tengo una pregunta sobre los productos orgánicos', NOW(), NOW()),
('Luis López', 'luis@example.com', '634567890', 'Consulta sobre entregas a domicilio', NOW(), NOW());
