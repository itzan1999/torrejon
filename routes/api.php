<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\PermisoController;
use App\Http\Controllers\Api\ProductoController;
use App\Http\Controllers\Api\ConsultaController;
use App\Http\Controllers\Api\CarritoController;
use App\Http\Controllers\Api\SuscripcionController;
use App\Http\Controllers\Api\UsuarioController;
use App\Http\Controllers\Api\RolController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PedidoController;

/*
|--------------------------------------------------------------------------|
|----------------------------- RUTAS DE LA API ----------------------------|
|--------------------------------------------------------------------------|
*/

/*
|--------------------------------------------------------------------------|
| 1. RUTAS DE PRODUCTOS 
|--------------------------------------------------------------------------|
*/
// Rutas publicas
Route::get('/productos', [ProductoController::class, 'index']);
Route::get('/productos/filtro', [ProductoController::class, 'productoFiltro']);
Route::get('/productos/{id}', [ProductoController::class, 'show']);
// Rutas protegidas (Administrador)
Route::middleware(['auth:sanctum', 'cuenta_activa'])->group(function () {
    // Administrador
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::post('/productos', [ProductoController::class, 'store']);
        Route::put('/productos/{id}', [ProductoController::class, 'update']);
        Route::patch('/productos/{id}', [ProductoController::class, 'adminPartialUpdate']);
        Route::delete('/productos/{id}', [ProductoController::class, 'destroy']);
    });
    // Usuario
    Route::patch('/productos/{id}', [ProductoController::class, 'partialUpdate'])->middleware('role:user');
});


/*
|--------------------------------------------------------------------------|
| 2. RUTAS DE PRODUCTOS (ADMINISTRADOR)
|--------------------------------------------------------------------------|
*/
// Rutas protegidas
Route::middleware(['auth:sanctum', 'cuenta_activa', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/permisos', [PermisoController::class, 'index']);
    Route::get('/permisos/{id}', [PermisoController::class, 'show']);
    Route::post('/permisos', [PermisoController::class, 'store']);
    Route::put('/permisos/{id}', [PermisoController::class, 'update']);
    Route::delete('/permisos/{id}', [PermisoController::class, 'destroy']);
});


/*
|--------------------------------------------------------------------------|
| 3. RUTAS DE CONSULTAS
|--------------------------------------------------------------------------|
*/
// Rutas publicas (Usuario)
Route::post('/consultas', [ConsultaController::class, 'store']);
// Rutas protegidas (Administrador)
Route::middleware(['auth:sanctum', 'cuenta_activa', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/consultas/{id}', [ConsultaController::class, 'show']);
    Route::get('/consultas', [ConsultaController::class, 'index']);
    Route::patch('/consultas/{id}', [ConsultaController::class, 'statusUpdate']);
    Route::delete('/consultas/{id}', [ConsultaController::class, 'destroy']);
});


/*
|--------------------------------------------------------------------------|
| 4. RUTAS DE USUARIO
|--------------------------------------------------------------------------|
*/
// Rutas protegidas
Route::middleware(['auth:sanctum', 'cuenta_activa'])->group(function () {
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        // Movimiento de saldo especiales administrador
        Route::get('/usuarios/{id}/movimientos', [UsuarioController::class, 'movimientoShow']);
        Route::get('/usuarios/movimientos', [UsuarioController::class, 'adminMovimientoIndex']);

        //4.1 Rutas de admin normales
        Route::get('/usuarios', [UsuarioController::class, 'index']);
        Route::get('/usuarios/filtro', [UsuarioController::class, 'usuarioFiltro']);
        Route::get('/usuarios/{id}', [UsuarioController::class, 'adminShow']);
        Route::post('/usuarios', [UsuarioController::class, 'create']);
        Route::put('/usuarios/{id}', [UsuarioController::class, 'adminUpdate']);
        Route::delete('/usuarios/{id}', [UsuarioController::class, 'adminDestroy']);
    });

    Route::middleware('role:user')->group(function () {
        // Movimientos de saldo especiales usuario
        Route::get('/usuarios/movimientos', [UsuarioController::class, 'movimientoIndex']);

        // 4.1 Rutas de usuario normales
        Route::get('/usuarios/{id}', [UsuarioController::class, 'show']);
        Route::put('/usuarios/{id}', [UsuarioController::class, 'update']);
        Route::delete('/usuarios/{id}', [UsuarioController::class, 'destroy']);

        // Ruta para que el usuario cambie la contraseña
        Route::patch('/usuarios/cambiarPassword', [UsuarioController::class, 'cambiarPassword']);
    });
});


/*
|--------------------------------------------------------------------------|
| 5. RUTAS DE CARRITO (USUARIO)
|--------------------------------------------------------------------------|
*/
// Rutas protegidas
Route::middleware(['auth:sanctum', 'role:user', 'cuenta_activa'])->group(function () {
    Route::get('/carrito', [CarritoController::class, 'show']);
    Route::post('/carrito/productos', [CarritoController::class, 'store']);
    Route::patch('/carrito/productos/{id}', [CarritoController::class, 'update']);
    Route::delete('/carrito/productos/{id}', [CarritoController::class, 'destroy']);
    Route::delete('/carrito/productos', [CarritoController::class, 'destroyAll']);
});


/*
|--------------------------------------------------------------------------|
| 6. RUTAS DE SUSCRIPCIÓN
|--------------------------------------------------------------------------|
*/
// Rutas protegidas
Route::middleware(['auth:sanctum', 'cuenta_activa'])->group(function () {
    // Administrador
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('/suscripcion', [SuscripcionController::class, 'adminIndex']);
        Route::get('/suscripcion/filtro', [SuscripcionController::class, 'adminFiltro']);
        Route::post('/suscripcion', [SuscripcionController::class, 'store']);
        Route::put('/suscripcion/{id}', [SuscripcionController::class, 'update']);
        Route::delete('/suscripcion/{id}', [SuscripcionController::class, 'destroy']);
    });
    // Usuario
    Route::middleware('role:user')->group(function () {
        Route::get('/suscripcion', [SuscripcionController::class, 'index']);
        Route::post('/suscripcion', [SuscripcionController::class, 'store']);
        Route::put('/suscripcion/{id}', [SuscripcionController::class, 'update']);
        Route::delete('/suscripcion/{id}', [SuscripcionController::class, 'destroy']);
    });
});


/*
|--------------------------------------------------------------------------|
| 7. RUTAS DE ROLES (ADMINISTRADOR)
|--------------------------------------------------------------------------|
*/
// Rutas protegidas
Route::middleware(['auth:sanctum', 'cuenta_activa', 'role:admin'])->prefix('admin')->group(function () {
    // 7.1 Rutas de roles especiales
    Route::get('/roles/{id}', [RolController::class, 'obtenerInfoRolPermiso']);
    Route::post('/usuarios/{idUsuario}/rol', [RolController::class, 'asignarRolUsuario']);
    Route::post('/roles/{id}/permisos', [RolController::class, 'asignarPermisoRol']);
    Route::delete('/roles/{id}/permisos/{idPermiso}', [RolController::class, 'eliminarPermisoRol']);

    // 7.2 Rutas de roles normales
    Route::post('/roles', [RolController::class, 'store']);
    Route::put('/roles/{id}', [RolController::class, 'update']);
    Route::delete('/roles/{id}', [RolController::class, 'destroy']);
    Route::get('/roles', [RolController::class, 'index']);
});



/*
|--------------------------------------------------------------------------|
| 8. RUTAS DE AUTENTICACIÓN (USUARIO EN GENERAL)
|--------------------------------------------------------------------------|
*/
// Rutas publicas
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/activar-cuenta/{token}', [AuthController::class, 'activateAccount']);
Route::post('/auth/reset-password', [AuthController::class, 'createPasswordResetToken']);
Route::post('/auth/cambiar-password', [AuthController::class, 'changePassword']);



/*
|--------------------------------------------------------------------------|
| 9. RUTAS DE PEDIDOS
|--------------------------------------------------------------------------|
*/
// Rutas protegidas
Route::middleware(['auth:sanctum', 'cuenta_activa'])->group(function () {
    // Administrador
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('/pedidos', [PedidoController::class, 'adminIndex']);
        Route::get('/pedidos/filtro', [PedidoController::class, 'pedidoFiltro']);
        Route::get('/pedidos/{id}', [PedidoController::class, 'adminShow']);
        Route::patch('/pedidos/{id}/estado', [PedidoController::class, 'estadoUpdate']);

        // Esta función eliminaría el pedido
        Route::delete('/pedidos/{id}', [PedidoController::class, 'destroy']);
    });

    // Usuario
    Route::middleware('role:user')->group(function () {
        Route::get('/pedidos', [PedidoController::class, 'index']);
        Route::get('/pedidos/{id}', [PedidoController::class, 'show']);
        Route::post('/pedidos', [PedidoController::class, 'store']);
        Route::patch('/pedidos/{id}/estado', [PedidoController::class, 'estadoUpdate']);

        // Esta función cancelaría el pedido si tuviera la posibilidad de hacerlo
        Route::delete('/pedidos/{id}', [PedidoController::class, 'destroy']);
    });
});
