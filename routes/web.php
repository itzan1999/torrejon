<?php

use FontLib\Table\Type\name;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeUnit\FunctionUnit;
use App\Http\Controllers\Api\AuthController as ApiAuth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ViewController;
use App\Http\Controllers\DashboardController;

// --RUTAS DE LAS VISTAS--

/*
|--------------------------------------------------------------------------
| RUTAS DE PÁGINAS PRINCIPALES
|--------------------------------------------------------------------------
*/

Route::get('/debug-session', function () {
    return response()->json([
        'session_driver' => config('session.driver'),
        'session_domain' => config('session.domain'),
        'secure_cookie' => config('session.secure'),
        'app_url' => config('app.url'),
        'app_env' => config('app.env'),
        'storage_writable' => is_writable(storage_path('framework/sessions')),
        'session_id' => session()->getId(),
        'session_data' => session()->all(),
    ]);
})->middleware('web');

Route::get('/', [ViewController::class, 'index'])->name('pages.index');

Route::get('el-torrejon', [ViewController::class, 'elTorrejon'])->name('pages.eltorrejon');

Route::get('/calidad', [ViewController::class, 'calidad'])->name('pages.calidad');

Route::get('/tienda', [ViewController::class, 'tienda'])->name('pages.tienda');

Route::get('/noticias', [ViewController::class, 'noticias'])->name('pages.noticias');

Route::get('/noticias/receta-chocolate-a-la-taza', [ViewController::class, 'recetaChocolate'])->name('pages.Noticias.recetaChocolate');

Route::get('/noticias/aumenta-el-consumo-de-leche-fresca-en-espana', [ViewController::class, 'aumentoConsumoLeche'])->name('pages.Noticias.aumentoConsumoLeche');

Route::get('/contacto', [ViewController::class, 'contacto'])->name('pages.contacto');


/*
|--------------------------------------------------------------------------
| RUTAS DE PÁGINAS LEGALES
|--------------------------------------------------------------------------
*/
Route::get('/aviso-legal', [ViewController::class, 'avisoLegal'])->name('pages.Legal.avisoLegal');

Route::get('/politica-de-privacidad', [ViewController::class, 'politicaDePrivacidad'])->name('pages.Legal.politicaPrivacidad');

Route::get('/politica-de-cookies', [ViewController::class, 'politicaDeCookies'])->name('pages.Legal.politicaCookies');

Route::get('/politica-de-proteccion-de-datos-personales', [ViewController::class, 'politicaDeProteccionDeDatosPersonales'])->name('pages.Legal.politicaProteccionDeDatosPersonales');


/*
|--------------------------------------------------------------------------
| RUTAS DE PÁGINAS Autenticación (Auth)
|--------------------------------------------------------------------------
*/

/*-------- Rutas para login y logout --------*/
// Ruta para devolver vista de login y lo que hace el login
Route::get('/login', [ViewController::class, 'login'])->name('login');
Route::post('/inicioSesion', [AuthController::class, 'login'])->name('verficarLogin');
// Ruta para cerrar sesión
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*-------- Rutas para registro, activar cuenta, solicitar cambio de contraseña y cambiar contraseña de un usuario --------*/
// Rutas para registro y activar cuenta, la primera devuelve la vista del registro, la segunda devuelve la vista de activar cuenta en función del token que se le pase por query
Route::get('/register', [AuthController::class, 'register'])->name('pages.register');
Route::get('/activar-cuenta', [AuthController::class, 'activarCuenta'])->name('pages.activarCuenta');

// Primera ruta para solicitar cambio de contraseña, la segunda para cambiar la contraseña en función del token
Route::get('/recovery-password', [AuthController::class, 'solicitarCambiarPassword'])->name('pages.solicitarCambiarPassword');
Route::get('/cambiar-password', [AuthController::class, 'cambiarPassword'])->name('pages.cambiarPassword');


/*
|--------------------------------------------------------------------------
| RUTAS DE PÁGINAS Para el CARRITO
|--------------------------------------------------------------------------
*/

// Ruta para el mostar el carrito (la cesta del usuario)
Route::get('/carrito', [ViewController::class, 'verCarrito'])->name('pages.Carrito.verCarrito')->middleware(['auth', 'role:user', 'cuenta_activa']);


/*
|--------------------------------------------------------------------------
| RUTAS DE PÁGINAS DASHBOARD (ADMIN Y USUARIOS)
|--------------------------------------------------------------------------
*/

/*-------- ADMIN PANEL--------*/
Route::middleware(['auth', 'role:admin', 'cuenta_activa'])->prefix('admin/panel')
        ->name('pages.Dashboard.Admin.')
        ->group(function () {

                // Panel principal del admin
                Route::get('/', [DashboardController::class, 'panelAdmin'])->name('panelAdmin');

                // Ruta para los contactos (consultas)
                Route::get('contactos', [DashboardController::class, 'contactos'])->name('Contactos.contactos');

                // Rutas panel de usuarios
                Route::get('usuarios', [DashboardController::class, 'listaUsuariosAdmin'])->name('Usuarios.usuariosAdmin');
                Route::get('usuarios/nuevo', [DashboardController::class, 'nuevoUsuarioAdmin'])->name('Usuarios.nuevoAdmin');
                Route::get('usuarios/{id}/detalle', [DashboardController::class, 'detalleUsuarioAdmin'])->name('Usuarios.detalleAdmin');
                Route::get('usuarios/{id}/editar', [DashboardController::class, 'editarUsuarioAdmin'])->name('Usuarios.editarAdmin');

                // Rutas panel de pedidos
                Route::get('pedidos', [DashboardController::class, 'listaPedidosAdmin'])->name('Pedidos.pedidosAdmin');
                Route::get('pedidos/{id}/detalle', [DashboardController::class, 'detallePedidoAdmin'])->name('Pedidos.detalleAdmin');
                Route::get('pedidos/{id}/editar', [DashboardController::class, 'editarPedidoAdmin'])->name('Pedidos.editarAdmin');

                // Rutas panel de productos
                Route::get('productos', [DashboardController::class, 'listaProductosAdmin'])->name('Productos.productosAdmin');
                Route::get('productos/nuevo', [DashboardController::class, 'nuevoProductoAdmin'])->name('Productos.nuevoAdmin');
                Route::get('productos/{id}/detalle', [DashboardController::class, 'detalleProductoAdmin'])->name('Pedidos.detalleAdmin');
                Route::get('productos/{id}/editar', [DashboardController::class, 'editarProductoAdmin'])->name('Pedidos.editarAdmin');


                // Rutas panel de roles

                // Rutas panel de permisos

                // Rutas panel de suscripciones
                Route::get('suscripciones', [DashboardController::class, 'listaSuscripcionesAdmin'])->name('Suscripciones.suscripcionesAdmin');
        });

/*-------- USUARIO PANEL--------*/
Route::middleware(['auth', 'role:user', 'cuenta_activa'])->prefix('usuarios/panel')
        ->name('pages.Dashboard.Usuarios.')
        ->group(function () {

                // Ruta panel principal del usuario
                Route::get('/', [DashboardController::class, 'panelUsuario'])->name('panelUsuario');

                // Rutas perfil del usuario
                Route::get('/perfil', [DashboardController::class, 'perfilUsuario'])->name('Perfil.perfilUsuario');


                // Route::get('/editarPerfil', function () {
                //     $user = auth()->user();
                //     if ($user && $user->rol !== 'admin') abort(403);
                //     return view('pages.Dashboard.Usuarios.Perfil.editarPerfil');
                // })->name('pages.Dashboard.Usuarios.Perfil.editarPerfil');

                // Route::get('/historialMovimientos', function () {
                //     $user = auth()->user();
                //     if ($user && $user->rol !== 'admin') abort(403);
                //     return view('pages.Dashboard.Usuarios.historialMovimientos');
                // })->name('pages.Dashboard.Usuarios.historialMovimientos');

                // Route::get('/saldo', function () {
                //     $user = auth()->user();
                //     if ($user && $user->rol !== 'admin') abort(403);
                //     return view('pages.Dashboard.Usuarios.saldo');
                // })->name('pages.Dashboard.Usuarios.saldo');

                // Route::get('/informe', function () {
                //     $user = auth()->user();
                //     if ($user && $user->rol !== 'admin') abort(403);
                //     return view('pages.Dashboard.Usuario.informe');
                // })->name('pages.Dashboard.Usuario.informe');
        });