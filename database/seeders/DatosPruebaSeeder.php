<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class DatosPruebaSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // Limpiar datos previos
    Schema::disableForeignKeyConstraints();
    DB::table('producto_suscripcion')->truncate();
    DB::table('suscripcion')->truncate();
    DB::table('cambiar_password')->truncate();
    DB::table('activar_cuenta')->truncate();
    DB::table('pedido_producto')->truncate();
    DB::table('pedido')->truncate();
    DB::table('carrito_producto')->truncate();
    DB::table('carrito')->truncate();
    DB::table('movimiento_saldo')->truncate();
    DB::table('usuario_rol')->truncate();
    DB::table('rol_permiso')->truncate();
    DB::table('permiso')->truncate();
    DB::table('rol')->truncate();
    DB::table('cuenta')->truncate();
    DB::table('usuario')->truncate();
    Schema::enableForeignKeyConstraints();

    // ==========================================
    // 1. ROLES
    // ==========================================
    DB::table('rol')->insert([
      ['nombre_rol' => 'admin', 'created_at' => now(), 'updated_at' => now()],
      ['nombre_rol' => 'user', 'created_at' => now(), 'updated_at' => now()],
      ['nombre_rol' => 'vendedor', 'created_at' => now(), 'updated_at' => now()],
    ]);

    // ==========================================
    // 2. PERMISOS
    // ==========================================
    DB::table('permiso')->insert([
      ['desc_permiso' => 'ver_usuarios', 'created_at' => now(), 'updated_at' => now()],
      ['desc_permiso' => 'editar_usuarios', 'created_at' => now(), 'updated_at' => now()],
      ['desc_permiso' => 'eliminar_usuarios', 'created_at' => now(), 'updated_at' => now()],
      ['desc_permiso' => 'ver_productos', 'created_at' => now(), 'updated_at' => now()],
      ['desc_permiso' => 'crear_productos', 'created_at' => now(), 'updated_at' => now()],
      ['desc_permiso' => 'editar_productos', 'created_at' => now(), 'updated_at' => now()],
      ['desc_permiso' => 'eliminar_productos', 'created_at' => now(), 'updated_at' => now()],
      ['desc_permiso' => 'ver_pedidos', 'created_at' => now(), 'updated_at' => now()],
      ['desc_permiso' => 'editar_pedidos', 'created_at' => now(), 'updated_at' => now()],
      ['desc_permiso' => 'ver_reportes', 'created_at' => now(), 'updated_at' => now()],
    ]);

    // ==========================================
    // 3. ROL_PERMISO
    // ==========================================
    DB::table('rol_permiso')->insert([
      // Admin tiene todos los permisos
      ['id_rol' => 1, 'id_permiso' => 1, 'created_at' => now(), 'updated_at' => now()],
      ['id_rol' => 1, 'id_permiso' => 2, 'created_at' => now(), 'updated_at' => now()],
      ['id_rol' => 1, 'id_permiso' => 3, 'created_at' => now(), 'updated_at' => now()],
      ['id_rol' => 1, 'id_permiso' => 4, 'created_at' => now(), 'updated_at' => now()],
      ['id_rol' => 1, 'id_permiso' => 5, 'created_at' => now(), 'updated_at' => now()],
      ['id_rol' => 1, 'id_permiso' => 6, 'created_at' => now(), 'updated_at' => now()],
      ['id_rol' => 1, 'id_permiso' => 7, 'created_at' => now(), 'updated_at' => now()],
      ['id_rol' => 1, 'id_permiso' => 8, 'created_at' => now(), 'updated_at' => now()],
      ['id_rol' => 1, 'id_permiso' => 9, 'created_at' => now(), 'updated_at' => now()],
      ['id_rol' => 1, 'id_permiso' => 10, 'created_at' => now(), 'updated_at' => now()],
      // User puede ver productos y pedidos
      ['id_rol' => 2, 'id_permiso' => 4, 'created_at' => now(), 'updated_at' => now()],
      ['id_rol' => 2, 'id_permiso' => 8, 'created_at' => now(), 'updated_at' => now()],
      // Vendedor puede ver, crear y editar productos
      ['id_rol' => 3, 'id_permiso' => 4, 'created_at' => now(), 'updated_at' => now()],
      ['id_rol' => 3, 'id_permiso' => 5, 'created_at' => now(), 'updated_at' => now()],
      ['id_rol' => 3, 'id_permiso' => 6, 'created_at' => now(), 'updated_at' => now()],
    ]);

    // ==========================================
    // 4. USUARIOS Y CUENTAS
    // ==========================================
    $users = [
      [
        'id' => '550e8400-e29b-41d4-a716-446655440000',
        'username' => 'admin',
        'email' => 'admin@lecheeltorrejon.com',
        'saldo' => 1000.00,
        'direccion' => 'Calle Admin 1, Madrid',
        'nombre' => 'Admin',
        'apellidos' => 'Sistema',
        'rol_id' => 1
      ],
      [
        'id' => '550e8400-e29b-41d4-a716-446655440001',
        'username' => 'cliente1',
        'email' => 'cliente1@example.com',
        'saldo' => 500.00,
        'direccion' => 'Avenida Principal 10, Madrid',
        'nombre' => 'Juan',
        'apellidos' => 'García López',
        'rol_id' => 2
      ],
      [
        'id' => '550e8400-e29b-41d4-a716-446655440002',
        'username' => 'cliente2',
        'email' => 'byjuaang@gmail.com',
        'saldo' => 750.50,
        'direccion' => 'Calle Secundaria 25, Barcelona',
        'nombre' => 'María',
        'apellidos' => 'Rodríguez Martín',
        'rol_id' => 2
      ],
      [
        'id' => '550e8400-e29b-41d4-a716-446655440003',
        'username' => 'cliente3',
        'email' => 'cliente3@example.com',
        'saldo' => 0.00,
        'direccion' => 'Plaza Mayor 5, Valencia',
        'nombre' => 'Carlos',
        'apellidos' => 'Fernández Díaz',
        'rol_id' => 2,
        'activa' => false
      ],
    ];

    foreach ($users as $user) {
      $rolId = $user['rol_id'];
      unset($user['rol_id']);

      DB::table('usuario')->insert([
        'id' => $user['id'],
        'username' => $user['username'],
        'email' => $user['email'],
        'saldo' => $user['saldo'],
        'direccion' => $user['direccion'],
        'created_at' => now(),
        'updated_at' => now()
      ]);

      $activa = $user['activa'] ?? true;
      unset($user['activa']);

      DB::table('cuenta')->insert([
        'id_user' => $user['id'],
        'nombre' => $user['nombre'],
        'apellidos' => $user['apellidos'],
        'password' => bcrypt('password123'),
        'activa' => $activa,
        'fecha_alta' => now(),
        'created_at' => now(),
        'updated_at' => now()
      ]);

      DB::table('usuario_rol')->insert([
        'id_usuario' => $user['id'],
        'id_rol' => $rolId,
        'created_at' => now(),
        'updated_at' => now()
      ]);
    }

    // ==========================================
    // 5. PRODUCTOS
    // ==========================================
    DB::table('producto')->insert([
      [
        'nombre' => 'Leche Entera 1L',
        'precio' => 1.50,
        'stock' => 150,
        'descripcion' => 'Leche fresca de vaca de alta calidad',
        'oferta' => 0.00,
        'informacion_nutricional' => json_encode(['calorias' => 60, 'proteinas' => 3.2, 'grasas' => 3.6]),
        'tamanyo' => 1.0,
        'unidad_medida' => 'L',
        'path_imagen' => '/images/leche-entera-1l.jpg',
        'created_at' => now(),
        'updated_at' => now()
      ],
      [
        'nombre' => 'Leche Desnatada 1L',
        'precio' => 1.20,
        'stock' => 100,
        'descripcion' => 'Leche desnatada fresca',
        'oferta' => 0.00,
        'informacion_nutricional' => json_encode(['calorias' => 35, 'proteinas' => 3.4, 'grasas' => 0.1]),
        'tamanyo' => 1.0,
        'unidad_medida' => 'L',
        'path_imagen' => '/images/leche-desnatada-1l.jpg',
        'created_at' => now(),
        'updated_at' => now()
      ],
      [
        'nombre' => 'Leche Semi 1L',
        'precio' => 1.35,
        'stock' => 120,
        'descripcion' => 'Leche semidesnatada fresca',
        'oferta' => 0.00,
        'informacion_nutricional' => json_encode(['calorias' => 49, 'proteinas' => 3.3, 'grasas' => 1.9]),
        'tamanyo' => 1.0,
        'unidad_medida' => 'L',
        'path_imagen' => '/images/leche-semi-1l.jpg',
        'created_at' => now(),
        'updated_at' => now()
      ],
      [
        'nombre' => 'Yogur Natural 250g',
        'precio' => 0.95,
        'stock' => 80,
        'descripcion' => 'Yogur natural sin azúcares añadidos',
        'oferta' => 0.10,
        'informacion_nutricional' => json_encode(['calorias' => 59, 'proteinas' => 3.5, 'grasas' => 0.4]),
        'tamanyo' => 250,
        'unidad_medida' => 'g',
        'path_imagen' => '/images/yogur-natural-250g.jpg',
        'created_at' => now(),
        'updated_at' => now()
      ],
      [
        'nombre' => 'Queso Fresco 500g',
        'precio' => 4.50,
        'stock' => 40,
        'descripcion' => 'Queso fresco de oveja',
        'oferta' => 0.00,
        'informacion_nutricional' => json_encode(['calorias' => 264, 'proteinas' => 21, 'grasas' => 21]),
        'tamanyo' => 500,
        'unidad_medida' => 'g',
        'path_imagen' => '/images/queso-fresco-500g.jpg',
        'created_at' => now(),
        'updated_at' => now()
      ],
      [
        'nombre' => 'Mantequilla 250g',
        'precio' => 3.20,
        'stock' => 60,
        'descripcion' => 'Mantequilla pura de la mejor calidad',
        'oferta' => 0.00,
        'informacion_nutricional' => json_encode(['calorias' => 717, 'proteinas' => 0.7, 'grasas' => 81]),
        'tamanyo' => 250,
        'unidad_medida' => 'g',
        'path_imagen' => '/images/mantequilla-250g.jpg',
        'created_at' => now(),
        'updated_at' => now()
      ],
      [
        'nombre' => 'Nata para Cocinar 200ml',
        'precio' => 2.10,
        'stock' => 75,
        'descripcion' => 'Nata fresca para cocinar',
        'oferta' => 0.00,
        'informacion_nutricional' => json_encode(['calorias' => 340, 'proteinas' => 2.2, 'grasas' => 35]),
        'tamanyo' => 200,
        'unidad_medida' => 'mL',
        'path_imagen' => '/images/nata-200ml.jpg',
        'created_at' => now(),
        'updated_at' => now()
      ],
      [
        'nombre' => 'Leche Chocolate 1L',
        'precio' => 1.85,
        'stock' => 90,
        'descripcion' => 'Leche con chocolate',
        'oferta' => 0.15,
        'informacion_nutricional' => json_encode(['calorias' => 90, 'proteinas' => 3.1, 'grasas' => 3.5]),
        'tamanyo' => 1.0,
        'unidad_medida' => 'L',
        'path_imagen' => '/images/leche-chocolate-1l.jpg',
        'created_at' => now(),
        'updated_at' => now()
      ],
      [
        'nombre' => 'Leche de Cabra 500ml',
        'precio' => 2.80,
        'stock' => 50,
        'descripcion' => 'Leche de cabra fresca',
        'oferta' => 0.00,
        'informacion_nutricional' => json_encode(['calorias' => 70, 'proteinas' => 3.6, 'grasas' => 4.5]),
        'tamanyo' => 500,
        'unidad_medida' => 'mL',
        'path_imagen' => '/images/leche-cabra-500ml.jpg',
        'created_at' => now(),
        'updated_at' => now()
      ],
      [
        'nombre' => 'Kéfir Natural 400ml',
        'precio' => 2.50,
        'stock' => 45,
        'descripcion' => 'Kéfir natural probiótico',
        'oferta' => 0.00,
        'informacion_nutricional' => json_encode(['calorias' => 66, 'proteinas' => 3.4, 'grasas' => 3.5]),
        'tamanyo' => 400,
        'unidad_medida' => 'mL',
        'path_imagen' => '/images/kefir-400ml.jpg',
        'created_at' => now(),
        'updated_at' => now()
      ],
    ]);

    // ==========================================
    // 6. CARRITOS
    // ==========================================
    DB::table('carrito')->insert([
      ['id_usuario' => '550e8400-e29b-41d4-a716-446655440001', 'precio_total' => 0.00, 'created_at' => now(), 'updated_at' => now()],
      ['id_usuario' => '550e8400-e29b-41d4-a716-446655440002', 'precio_total' => 4.50, 'created_at' => now(), 'updated_at' => now()],
      ['id_usuario' => '550e8400-e29b-41d4-a716-446655440003', 'precio_total' => 0.00, 'created_at' => now(), 'updated_at' => now()],
    ]);

    // ==========================================
    // 7. CARRITO_PRODUCTO
    // ==========================================
    DB::table('carrito_producto')->insert([
      ['id_carrito' => 2, 'id_producto' => 4, 'cantidad' => 2, 'created_at' => now(), 'updated_at' => now()],
      ['id_carrito' => 2, 'id_producto' => 5, 'cantidad' => 1, 'created_at' => now(), 'updated_at' => now()],
    ]);

    // ==========================================
    // 8. PEDIDOS
    // ==========================================
    DB::table('pedido')->insert([
      ['id' => '550e8400-e29b-41d4-a716-446655440100', 'codigo' => 10001, 'id_usuario' => '550e8400-e29b-41d4-a716-446655440001', 'estado' => 'entregado', 'suscripcion' => 0, 'precio_total' => 12.50, 'created_at' => now()->subDays(10), 'updated_at' => now()->subDays(10)],
      ['id' => '550e8400-e29b-41d4-a716-446655440101', 'codigo' => 10002, 'id_usuario' => '550e8400-e29b-41d4-a716-446655440001', 'estado' => 'procesado', 'suscripcion' => 0, 'precio_total' => 8.75, 'created_at' => now()->subDays(5), 'updated_at' => now()->subDays(5)],
      ['id' => '550e8400-e29b-41d4-a716-446655440102', 'codigo' => 10003, 'id_usuario' => '550e8400-e29b-41d4-a716-446655440002', 'estado' => 'entregado', 'suscripcion' => 0, 'precio_total' => 6.40, 'created_at' => now()->subDays(15), 'updated_at' => now()->subDays(15)],
      ['id' => '550e8400-e29b-41d4-a716-446655440103', 'codigo' => 10004, 'id_usuario' => '550e8400-e29b-41d4-a716-446655440002', 'estado' => 'reparto', 'suscripcion' => 0, 'precio_total' => 15.30, 'created_at' => now()->subDays(2), 'updated_at' => now()->subDays(2)],
      ['id' => '550e8400-e29b-41d4-a716-446655440104', 'codigo' => 10005, 'id_usuario' => '550e8400-e29b-41d4-a716-446655440001', 'estado' => 'creado', 'suscripcion' => 0, 'precio_total' => 9.60, 'created_at' => now(), 'updated_at' => now()],
    ]);

    // ==========================================
    // 9. PEDIDO_PRODUCTO
    // ==========================================
    DB::table('pedido_producto')->insert([
      ['id_pedido' => '550e8400-e29b-41d4-a716-446655440100', 'id_producto' => 1, 'cantidad' => 3, 'created_at' => now(), 'updated_at' => now()],
      ['id_pedido' => '550e8400-e29b-41d4-a716-446655440100', 'id_producto' => 4, 'cantidad' => 2, 'created_at' => now(), 'updated_at' => now()],
      ['id_pedido' => '550e8400-e29b-41d4-a716-446655440101', 'id_producto' => 2, 'cantidad' => 4, 'created_at' => now(), 'updated_at' => now()],
      ['id_pedido' => '550e8400-e29b-41d4-a716-446655440101', 'id_producto' => 3, 'cantidad' => 1, 'created_at' => now(), 'updated_at' => now()],
      ['id_pedido' => '550e8400-e29b-41d4-a716-446655440102', 'id_producto' => 1, 'cantidad' => 2, 'created_at' => now(), 'updated_at' => now()],
      ['id_pedido' => '550e8400-e29b-41d4-a716-446655440102', 'id_producto' => 7, 'cantidad' => 1, 'created_at' => now(), 'updated_at' => now()],
      ['id_pedido' => '550e8400-e29b-41d4-a716-446655440103', 'id_producto' => 5, 'cantidad' => 1, 'created_at' => now(), 'updated_at' => now()],
      ['id_pedido' => '550e8400-e29b-41d4-a716-446655440103', 'id_producto' => 6, 'cantidad' => 2, 'created_at' => now(), 'updated_at' => now()],
      ['id_pedido' => '550e8400-e29b-41d4-a716-446655440104', 'id_producto' => 2, 'cantidad' => 2, 'created_at' => now(), 'updated_at' => now()],
      ['id_pedido' => '550e8400-e29b-41d4-a716-446655440104', 'id_producto' => 8, 'cantidad' => 3, 'created_at' => now(), 'updated_at' => now()],
    ]);

    // ==========================================
    // 10. MOVIMIENTO_SALDO
    // ==========================================
    DB::table('movimiento_saldo')->insert([
      ['id' => '550e8400-e29b-41d4-a716-446655440200', 'id_usuario' => '550e8400-e29b-41d4-a716-446655440001', 'fecha' => now()->subDays(20), 'importe' => 100.00, 'tipo' => 'recarga', 'descripcion' => 'Recarga de saldo inicial', 'created_at' => now(), 'updated_at' => now()],
      ['id' => '550e8400-e29b-41d4-a716-446655440201', 'id_usuario' => '550e8400-e29b-41d4-a716-446655440001', 'fecha' => now()->subDays(10), 'importe' => -12.50, 'tipo' => 'pago', 'descripcion' => 'Pago pedido #10001', 'created_at' => now(), 'updated_at' => now()],
      ['id' => '550e8400-e29b-41d4-a716-446655440202', 'id_usuario' => '550e8400-e29b-41d4-a716-446655440001', 'fecha' => now()->subDays(5), 'importe' => -8.75, 'tipo' => 'pago', 'descripcion' => 'Pago pedido #10002', 'created_at' => now(), 'updated_at' => now()],
      ['id' => '550e8400-e29b-41d4-a716-446655440203', 'id_usuario' => '550e8400-e29b-41d4-a716-446655440002', 'fecha' => now()->subDays(30), 'importe' => 200.00, 'tipo' => 'recarga', 'descripcion' => 'Recarga de saldo inicial', 'created_at' => now(), 'updated_at' => now()],
      ['id' => '550e8400-e29b-41d4-a716-446655440204', 'id_usuario' => '550e8400-e29b-41d4-a716-446655440002', 'fecha' => now()->subDays(15), 'importe' => -6.40, 'tipo' => 'pago', 'descripcion' => 'Pago pedido #10003', 'created_at' => now(), 'updated_at' => now()],
      ['id' => '550e8400-e29b-41d4-a716-446655440205', 'id_usuario' => '550e8400-e29b-41d4-a716-446655440002', 'fecha' => now()->subDays(2), 'importe' => -15.30, 'tipo' => 'pago', 'descripcion' => 'Pago pedido #10004', 'created_at' => now(), 'updated_at' => now()],
      ['id' => '550e8400-e29b-41d4-a716-446655440206', 'id_usuario' => '550e8400-e29b-41d4-a716-446655440001', 'fecha' => now()->subDays(3), 'importe' => 50.00, 'tipo' => 'recarga', 'descripcion' => 'Recarga adicional', 'created_at' => now(), 'updated_at' => now()],
    ]);

    // ==========================================
    // 11. SUSCRIPCIONES
    // ==========================================
    DB::table('suscripcion')->insert([
      ['id' => '550e8400-e29b-41d4-a716-446655440300', 'id_usuario' => '550e8400-e29b-41d4-a716-446655440001', 'tipo' => 'semanal', 'fecha_inicio' => now()->subDays(30), 'created_at' => now(), 'updated_at' => now()],
      ['id' => '550e8400-e29b-41d4-a716-446655440301', 'id_usuario' => '550e8400-e29b-41d4-a716-446655440002', 'tipo' => 'mensual', 'fecha_inicio' => now()->subDays(15), 'created_at' => now(), 'updated_at' => now()],
    ]);

    // ==========================================
    // 12. PRODUCTO_SUSCRIPCION
    // ==========================================
    DB::table('producto_suscripcion')->insert([
      ['id_producto' => 1, 'id_suscripcion' => '550e8400-e29b-41d4-a716-446655440300', 'cantidad' => 2, 'created_at' => now(), 'updated_at' => now()],
      ['id_producto' => 4, 'id_suscripcion' => '550e8400-e29b-41d4-a716-446655440300', 'cantidad' => 1, 'created_at' => now(), 'updated_at' => now()],
      ['id_producto' => 2, 'id_suscripcion' => '550e8400-e29b-41d4-a716-446655440301', 'cantidad' => 1, 'created_at' => now(), 'updated_at' => now()],
      ['id_producto' => 6, 'id_suscripcion' => '550e8400-e29b-41d4-a716-446655440301', 'cantidad' => 1, 'created_at' => now(), 'updated_at' => now()],
    ]);

    // ==========================================
    // 13. ACTIVAR_CUENTA
    // ==========================================
    DB::table('activar_cuenta')->insert([
      ['id_user' => '550e8400-e29b-41d4-a716-446655440003', 'token' => 'token_activacion_cliente3_abc123def456', 'fecha_creacion' => now(), 'fecha_expiracion' => now()->addHours(24), 'usado' => 0, 'created_at' => now(), 'updated_at' => now()],
    ]);

    // ==========================================
    // 14. CAMBIAR_PASSWORD
    // ==========================================
    DB::table('cambiar_password')->insert([
      ['id_user' => '550e8400-e29b-41d4-a716-446655440002', 'token' => 'token_reset_cliente2_xyz789uvw012', 'fecha_creacion' => now(), 'fecha_expiracion' => now()->addHours(24), 'usado' => 0, 'created_at' => now(), 'updated_at' => now()],
    ]);

    // ==========================================
    // 15. DATOS_CONSULTAS
    // ==========================================
    DB::table('datos_consultas')->insert([
      ['nombre' => 'Pedro García', 'email' => 'pedro@example.com', 'telefono' => '612345678', 'consulta' => 'Me gustaría saber más sobre las suscripciones mensuales', 'created_at' => now(), 'updated_at' => now()],
      ['nombre' => 'Ana Martínez', 'email' => 'ana@example.com', 'telefono' => '623456789', 'consulta' => 'Tengo una pregunta sobre los productos orgánicos', 'created_at' => now(), 'updated_at' => now()],
      ['nombre' => 'Luis López', 'email' => 'luis@example.com', 'telefono' => '634567890', 'consulta' => 'Consulta sobre entregas a domicilio', 'created_at' => now(), 'updated_at' => now()],
    ]);
  }
}
