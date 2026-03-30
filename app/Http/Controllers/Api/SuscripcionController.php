<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

//Modelos
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Enums\TipoSuscripcion;
use App\Models\Suscripcion;
use App\Models\ProductoSuscripcion;
use App\Models\UsuarioRol;
use App\Models\Rol;
use App\Enums\NombreRoles;

class SuscripcionController
{
    // Función estática para comprobar le rol del usuario
    public static function identificacion($rolComprobar)
    {
        $usuarioAuth = auth()->user();
        $idRol = UsuarioRol::where('id_usuario', $usuarioAuth->id_user)->first()->id_rol;
        $rol = Rol::where('id', $idRol)->first()->nombre_rol;

        return $rol == $rolComprobar;
    }

    // Función para obtener el id del usuario autenticado
    public static function idUsuarioAuth()
    {
        return auth()->user()->id_user;
    }

    // Función para obtener las suscripciones
    private function getSuscripciones($idUser = null)
    {
        $listaSuscripciones = [];
        $suscripciones = $idUser ? Suscripcion::where('id_usuario', $idUser)->get() : Suscripcion::all();

        if (!$suscripciones) {
            $data = [
                'message' => 'Error al obtener las suscripciones',
                'status' => 500
            ];
            return $data;
        }

        foreach ($suscripciones as $suscripcion) {
            $productos = ProductoSuscripcion::where('id_suscripcion', $suscripcion->id)->get();
            $listaProductos = [];

            foreach ($productos as $producto) {
                $listaProductos[] = [
                    'id_producto' => $producto->id_producto,
                    'cantidad' => $producto->cantidad
                ];
            }

            $listaSuscripciones[] = [
                'id' => $suscripcion->id,
                'tipo' => $suscripcion->tipo,
                'fecha_inicio' => $suscripcion->fecha_inicio,
                'id_usuario' => $suscripcion->id_usuario,
                'productos' => $listaProductos
            ];
        }

        $data = [
            'message' => 'Suscripciones obtenidas correctamente',
            'suscripciones' => $listaSuscripciones,
            'status' => 200
        ];
        return $data;
    }

    // Función para devolver todas las suscripciones, devuelve las suscripciones del usuario autenticado
    public function index()
    {
        try {
            $idUser = $this->idUsuarioAuth();

            $data = $this->getSuscripciones($idUser);
            $code = $data['status'];

            return response()->json($data, $code);
        } catch (\Throwable $e) {
            $data = [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ];
            return response()->json($data, 500);
        }
    }

    // Función para devolver todas las suscripciones siendo administrador
    public function adminIndex()
    {
        $usuarioAuth = auth()->user();
        $idRol = UsuarioRol::where('id_usuario', $usuarioAuth->id_user)->first()->id_rol;
        $rol = Rol::find($idRol)->nombre_rol;

        if ($rol != NombreRoles::ADMIN->value) {
            $data = [
                'message' => 'Acceso no autorizado, debe ser adminstrador para realizar la petición.',
                'status' => 401
            ];
            return response()->json($data, 401);
        }

        $data = $this->getSuscripciones();
        $code = $data['status'];

        return response()->json($data, $code);
    }

    // Función para devolver las suscripcione  según el filtro por tipo, fecha o nombre y apellidos de usuario
    public function adminFiltro(Request $request)
    {
        try {
            $buscar = $request->query('buscar');

            $query = Suscripcion::query();

            // Filtramos por tipo
            $query->where('tipo', 'like', "%{$buscar}%");

            // Filtramos por año si el usuario escribió 4 dígitos
            if (preg_match('/^\d{4}$/', $buscar)) {
                $query->orWhereYear('fecha_inicio', $buscar); // Eloquent: filtra solo por año
            }

            // Filtramos por usuario → cuenta
            $query->orWhereHas('usuario.cuenta', function ($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%")
                    ->orWhere('apellidos', 'like', "%{$buscar}%")
                    ->orWhereRaw("CONCAT(nombre,' ',apellidos) LIKE ?", ["%{$buscar}%"]);
            });

            // Obtenemos los resultados
            $suscripciones = $query->get()->map(function ($suscripcion) {
                $productos = ProductoSuscripcion::where('id_suscripcion', $suscripcion->id)->get();
                return [
                    'id' => $suscripcion->id,
                    'tipo' => $suscripcion->tipo,
                    'fecha_inicio' => $suscripcion->fecha_inicio,
                    'id_usuario' => $suscripcion->id_usuario,
                    'productos' => $productos->map(fn($p) => [
                        'id_producto' => $p->id_producto,
                        'cantidad' => $p->cantidad
                    ]),
                ];
            });

            $data = [
                'message' => 'Suscripciones obtenidas correctamente',
                'suscripciones' => $suscripciones,
                'status' => 200
            ];
            return response()->json($data, 200);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ], 500);
        }
    }

    // Función para crear una suscripción, puede crear una suscripción el usuario autenticado y el administrador, el usuario autenticado solo puede crear una suscripción para él mismo
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id_usuario' => 'required|string|exists:usuario,id',
                'tipo' => ['required', Rule::enum(TipoSuscripcion::class)],
                'fecha_inicio' => 'required|date',
                'productos' => 'required|array',
                'productos.*.id_producto' => 'required|integer|exists:producto,id',
                'productos.*.cantidad' => 'required|integer|min:1'
            ]);

            if ($validator->fails()) {
                $data = [
                    'message' => 'Error en la validación de los datos',
                    'errors' => $validator->errors(),
                    'status' => 400
                ];
                return response()->json($data, 400);
            }

            // Creación de la suscripción
            $suscripcion = Suscripcion::create([
                'id_usuario' => $request->id_usuario,
                'tipo' => $request->tipo,
                'fecha_inicio' => $request->fecha_inicio
            ]);

            if (!$suscripcion) {
                $data = [
                    'message' => 'Error al crear la suscripcion',
                    'status' => 500
                ];
                return response()->json($data, 500);
            }

            // Creamos la lista de los productos que devuelve la request y una lista nueva de productos que va a devolver el json
            $listaProductos = $request->productos;
            $listaProductosAñadidos = [];

            foreach ($listaProductos as $producto) {
                $productoCreado = ProductoSuscripcion::create([
                    'id_producto' => $producto['id_producto'],
                    'id_suscripcion' => $suscripcion->id,
                    'cantidad' => $producto['cantidad']
                ]);
                $listaProductosAñadidos[] = [
                    'id_producto' => $productoCreado->id_producto,
                    'cantidad' => $productoCreado->cantidad
                ];
            }

            // Transformamos la JSON adecuado para devolver la suscripción
            $suscripcionCreada = [
                'id' => $suscripcion->id,
                'tipo' => $suscripcion->tipo,
                'fecha_inicio' => $suscripcion->fecha_inicio,
                'id_usuario' => $suscripcion->id_usuario,
                'productos' => $listaProductosAñadidos
            ];

            $data = [
                'message' => 'Suscripción creada correctamente',
                'suscripcion' => $suscripcionCreada,
                'status' => 201
            ];
            return response()->json($data, 201);
        } catch (\Throwable $e) {
            $data = [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ];
            return response()->json($data, 500);
        }
    }

    // Función para actualizar una suscripción, puede actualizar una suscripción el usuario autenticado y el administrador, el usuario autenticado solo puede actualizar una suscripción para él mismo
    public function update(Request $request, $id)
    {
        try {
            $suscripcion = Suscripcion::find($id);

            if (!$suscripcion) {
                $data = [
                    'message' => 'Suscripción no encontrada',
                    'status' => 404,
                ];
                return response()->json($data, 404);
            }

            $validator = Validator::make($request->all(), [
                'tipo' => ['required', Rule::enum(TipoSuscripcion::class)],
                'fecha_inicio' => 'required|date',
                'productos' => 'required|array',
                'productos.*.id_producto' => 'required|integer|exists:producto,id',
                'productos.*.cantidad' => 'required|integer|min:1'
            ]);

            if ($validator->fails()) {
                $data = [
                    'message' => 'Error en la validación de los datos',
                    'errors' => $validator->errors(),
                    'status' => 400
                ];
                return response()->json($data, 400);
            }

            // Actualizar tipo y fecha
            $suscripcion->tipo = $request->tipo;
            $suscripcion->fecha_inicio = $request->fecha_inicio;
            $suscripcion->save();

            // Actualizar o crear productos
            $listaProductosActualizados = [];
            foreach ($request->productos as $producto) {

                $id_producto = (int) $producto['id_producto'];
                $cantidad = (int) $producto['cantidad'];

                $producto = ProductoSuscripcion::where('id_suscripcion', $suscripcion->id)
                    ->where('id_producto', $id_producto)
                    ->first();

                if ($producto) {
                    // Si el producto ya existe para esa suscripción, lo actualizamos
                    ProductoSuscripcion::where('id_suscripcion', $suscripcion->id)
                        ->where('id_producto', $id_producto)
                        ->update(['cantidad' => $cantidad]);
                } else {
                    // Si no existe el producto para esa suscripción, lo creamos
                    $producto = ProductoSuscripcion::create([
                        'id_suscripcion' => $suscripcion->id,
                        'id_producto' => $id_producto,
                        'cantidad' => $cantidad
                    ]);
                }
                $listaProductosActualizados[] = [
                    'id_producto' => $id_producto,
                    'cantidad' => $cantidad
                ];
            }

            // Creamos la suscripción que vamos a devolver
            $suscripcionDevuelta = [
                'id' => $id,
                'tipo' => $request->tipo,
                'fecha_inicio' => $request->fecha_inicio,
                'productos' => $listaProductosActualizados
            ];

            $data = [
                'message' => 'Suscripción actualizada correctamente',
                'suscripcion' => $suscripcionDevuelta,
                'status' => 201
            ];
            return response()->json($data, 201);
        } catch (\Throwable $e) {
            $data = [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ];
            return response()->json($data, 500);
        }
    }

    // Función para eliminar una suscripción, puede eliminar una suscripción el usuario autenticado y el administrador, el usuario autenticado solo puede eliminar una suscripción para él mismo
    public function destroy($id)
    {
        try {
            $suscripcion = Suscripcion::find($id);

            if (!$suscripcion) {
                $data = [
                    'message' => 'Error al eliminar la suscripción',
                    'status' => 400
                ];
                return response()->json($data, 400);
            }

            // Obtenemos los productos de la suscripción antes de eliminarla para devolverlos en la respuesta
            $productos = ProductoSuscripcion::where("id_suscripcion", $suscripcion->id)->get();

            // Borramos todos los productos relacionados con esa suscripción y borramos la suscripción
            ProductoSuscripcion::where("id_suscripcion", $suscripcion->id)->delete();
            $suscripcion->delete();

            // Transformamos la JSON adecuado para devolver la suscripción eliminada
            $suscripcionEliminada = [
                'id' => $suscripcion->id,
                'tipo' => $suscripcion->tipo,
                'fecha_inicio' => $suscripcion->fecha_inicio,
                'id_usuario' => $suscripcion->id_usuario,
                'productos' => $productos->map(function ($producto) {
                    return [
                        'id_producto' => $producto->id_producto,
                        'cantidad' => $producto->cantidad
                    ];
                })
            ];

            $data = [
                'message' => 'Suscripción eliminada corectamente',
                'suscripcion' => $suscripcionEliminada,
                'status' => 200
            ];
            return response()->json($data, 200);
        } catch (\Throwable $e) {
            $data = [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ];
            return response()->json($data, 500);
        }
    }
}
