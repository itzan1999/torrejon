<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use App\Enums\EstadoPedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Str;

use App\Enums\NombreRoles;
use Illuminate\Validation\Rule;
// Modelos
use App\Models\Usuario;
use App\Models\MovimientoSaldo;
use App\Models\Cuenta;
use App\Models\ActivarCuenta;
use App\Models\CambiarPassword;
use App\Models\Carrito;
use App\Models\CarritoProducto;
use App\Models\UsuarioRol;
use App\Models\Rol;
use App\Models\Suscripcion;
use App\Models\ProductoSuscripcion;
use App\Models\Pedido;

class UsuarioController
{
  // Función estática para comprobar le rol del usuario
  public static function identificacion($rolComprobar)
  {
    try {
      $usuarioAuth = auth()->user();
      $idRol = UsuarioRol::where('id_usuario', $usuarioAuth->id_user)->first()->id_rol;
      $rol = Rol::where('id', $idRol)->first()->nombre_rol;

      if ($rol != NombreRoles::ADMIN->value) {
        $data = [
          'message' => 'Acceso no autorizado, debe ser adminstrador para realizar la petición.',
          'status' => 401
        ];
        return response()->json($data, 401);
      }

      $datosUsuario = [];
      $usuarios = Usuario::all();

      if (!$usuarios) {
        $data = [
          'message' => 'Error al obtener los usuarios',
          'status' => 500
        ];
        return response()->json($data, 500);
      }

      foreach ($usuarios as $usuario) {
        $cuenta = Cuenta::find($usuario->id);

        $datosUsuario[] = [
          'id' => $usuario->id,
          'username' => $usuario->username,
          'nombre' => $cuenta->nombre,
          'apellidos' => $cuenta->apellidos,
          'email' => $usuario->email,
          'saldo' => $usuario->saldo,
          'direccion' => $usuario->direccion
        ];
      }

      $data = [
        'message' => 'Usuarios obtenidos correctamente',
        'usuarios' => $datosUsuario,
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

  // Función para obtener el id del usuario autenticado
  public static function idUsuarioAuth()
  {
    return auth()->user()->id_user;
  }

  public function index()
  {
    try {
      $usuarioAuth = auth()->user();

      // Obtener rol del usuario autenticado
      $idRol = UsuarioRol::where('id_usuario', $usuarioAuth->id_user)->first()?->id_rol;
      $rol = Rol::find($idRol)?->nombre_rol;

      if ($rol != NombreRoles::ADMIN->value) {
        $data = [
          'message' => 'Acceso no autorizado, debe ser administrador para realizar la petición.',
          'status' => 401
        ];
        return response()->json($data, 401);
      }

      $datosUsuario = [];

      // Cargar usuarios con relaciones
      $usuarios = Usuario::with(['cuenta', 'pedidos', 'roles'])->get();

      if (!$usuarios) {
        $data = [
          'message' => 'Error al obtener los usuarios',
          'status' => 500
        ];
        return response()->json($data, 500);
      }

      foreach ($usuarios as $usuario) {
        $pedidosActivos = $usuario->pedidos
          ->whereNotIn('estado', [
            EstadoPedido::ENTREGADO->value,
            EstadoPedido::CANCELADO->value,
            EstadoPedido::DEVUELTO->value,
            EstadoPedido::PERDIDO->value
          ])
          ->count();

        $datosUsuario[] = [
          'id' => $usuario->id,
          'username' => $usuario->username,
          'nombre' => $usuario->cuenta?->nombre,
          'apellidos' => $usuario->cuenta?->apellidos,
          'email' => $usuario->email,
          'saldo' => $usuario->saldo,
          'direccion' => $usuario->direccion,
          'nPedidos' => $usuario->pedidos->count(),
          'pedidosActivos' => $pedidosActivos,
          'roles' => $usuario->roles->pluck('nombre_rol')->toArray() // array de roles
        ];
      }

      $data = [
        'message' => 'Usuarios obtenidos correctamente',
        'usuarios' => $datosUsuario,
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

  // Devuelve un usuario según el filtro por nombre o apellido
  public function usuarioFiltro(Request $request)
  {
    try {
      $usuarioAuth = auth()->user();

      $idRol = $usuarioAuth->usuario->usuarioRoles()->first()->id_rol;
      $rolNombre = Rol::find($idRol)?->nombre_rol;

      if ($rolNombre != NombreRoles::ADMIN->value) {
        $data = [
          'message' => 'Acceso no autorizado, debe ser administrador.',
          'status' => 401
        ];
        return response()->json($data, 401);
      }

      $buscar = $request->query('buscar');

      $usuarios = Usuario::with(['cuenta', 'pedidos', 'roles'])
        ->when($buscar, function ($query) use ($buscar) {
          $query->where(function ($q) use ($buscar) {
            // username
            $q->where('username', 'like', "%{$buscar}%");

            // email
            $q->orWhere('email', 'like', "%{$buscar}%");

            // nombre o apellidos
            $q->orWhereHas('cuenta', function ($q2) use ($buscar) {
              $q2->where('nombre', 'like', "%{$buscar}%")
                ->orWhere('apellidos', 'like', "%{$buscar}%")
                ->orWhereRaw("CONCAT(nombre,' ',apellidos) LIKE ?", ["%{$buscar}%"]);
            });

            // rol
            $q->orWhereHas('roles', function ($q3) use ($buscar) {
              $q3->where('nombre_rol', 'like', "%{$buscar}%");
            });
          });
        })

        ->get();

      $datosUsuario = $usuarios->map(function ($usuario) {
        $pedidosActivos = $usuario->pedidos
          ->whereNotIn('estado', [
            EstadoPedido::ENTREGADO->value,
            EstadoPedido::CANCELADO->value,
            EstadoPedido::DEVUELTO->value,
            EstadoPedido::PERDIDO->value
          ])
          ->count();
        return [
          'id' => $usuario->id,
          'username' => $usuario->username,
          'nombre' => $usuario->cuenta?->nombre,
          'apellidos' => $usuario->cuenta?->apellidos,
          'email' => $usuario->email,
          'saldo' => $usuario->saldo,
          'direccion' => $usuario->direccion,
          'nPedidos' => $usuario->pedidos->count(),
          'pedidosActivos' => $pedidosActivos,
          'roles' => $usuario->roles->pluck('nombre_rol')
        ];
      });

      $data = [
        'message' => 'Usuarios obtenidos correctamente',
        'usuarios' => $datosUsuario,
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

  // Devuelve el usuario conectado por id (admin o el propio usuario)
  public function show()
  {
    try {
      $idUser = $this->idUsuarioAuth();

      $data = $this->getUserById($idUser);

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

  // Devuelve cualquier usuario por id
  public function adminShow($id)
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

    $data = $this->getUserById($id);

    return response()->json($data, 200);
  }

  private function getUserById($id)
  {
    $usuario = Usuario::with('roles')->with('pedidos')->find($id);
    $cuenta = Cuenta::find($id);

    if (!$usuario) {
      $data = [
        'message' => 'Error al obtener el usuario.',
        'status' => 404
      ];
      return response()->json($data, 404);
    }

    $datosUsuario = [
      'id' => $usuario->id,
      'username' => $usuario->username,
      'nombre' => $cuenta->nombre,
      'apellidos' => $cuenta->apellidos,
      'email' => $usuario->email,
      'saldo' => $usuario->saldo,
      'rol' => $usuario->roles->pluck('nombre_rol')->toArray(),
      'nPedidos' => $usuario->pedidos->count(),
      'direccion' => $usuario->direccion
    ];

    $data = [
      'message' => 'Usuario obtenido correctamente',
      'usuario' => $datosUsuario,
      'status' => 200
    ];

    return $data;
  }

  public function create(Request $request)
  {
    if (self::identificacion(NombreRoles::ADMIN->value)) {
      try {
        // Verificar que el usuario autenticado sea ADMIN
        $usuarioAuth = auth()->user();
        $idRol = UsuarioRol::where('id_usuario', $usuarioAuth->id_user)->first()?->id_rol;
        $rolNombre = Rol::find($idRol)?->nombre_rol;

        if ($rolNombre != NombreRoles::ADMIN->value) {
          $data = [
            'message' => 'Acceso no autorizado. Solo un administrador puede crear usuarios.',
            'status' => 401
          ];
          return response()->json($data, 401);
        }

        // Validación
        $validator = Validator::make($request->all(), [
          'username' => 'required|string|unique:usuario,username',
          'nombre' => 'required|string',
          'apellidos' => 'required|string',
          'email' => 'required|email|unique:usuario,email',
          'password' => [
            'required',
            'confirmed',
            'string',
            Password::min(8)
              ->letters()
              ->mixedCase()
              ->numbers()
              ->symbols()
          ],
          'direccion' => 'required|string',
          'rol' => ['required', Rule::in([NombreRoles::USER->value, NombreRoles::ADMIN->value])],
          'saldo' => 'nullable|numeric|min:0'
        ]);

        if ($validator->fails()) {
          $data = [
            'message' => 'Error de validación de los datos',
            'errors' => $validator->errors(),
            'status' => 422
          ];
          return response()->json($data, 422);
        }

        // Crear Usuario
        $usuario = Usuario::create([
          'username' => strtolower($request->username),
          'email' => $request->email,
          'saldo' => $request->saldo ?? 0.0, // asigna 0 si no se envía
          'direccion' => $request->direccion
        ]);

        // Crear Cuenta ACTIVA directamente
        Cuenta::create([
          'id_user' => $usuario->id,
          'nombre' => $request->nombre,
          'apellidos' => $request->apellidos,
          'password' => bcrypt($request->password),
          'activa' => true
        ]);

        $token = Str::random(32);
        $tokenHash = Hash::make($token);

        // Crear registro en ActivarCuenta ya marcado como usado
        ActivarCuenta::create([
          'id_user' => $usuario->id,
          'token' => $tokenHash,
          'fecha_creacion' => now(),
          'fecha_expiracion' => now()->addHours(24),
          'usado' => true
        ]);

        // Crear carrito asociado
        Carrito::create([
          'id_usuario' => $usuario->id,
          'precio_total' => 0.0
        ]);

        // Asignar rol
        $rolId = Rol::where('nombre_rol', $request->rol)->first()?->id;
        if (!$rolId) {
          $data = [
            'message' => 'Rol no válido',
            'status' => 400
          ];
          return response()->json($data, 400);
        }

        UsuarioRol::create([
          'id_usuario' => $usuario->id,
          'id_rol' => $rolId
        ]);

        $data = [
          'message' => 'Usuario creado y activado correctamente',
          'usuario' => [
            'id' => $usuario->id,
            'username' => $usuario->username,
            'email' => $usuario->email,
            'rol' => $request->rol,
            'saldo' => $usuario->saldo
          ],
          'status' => 201
        ];
        return response()->json($data, 201);
      } catch (\Throwable $e) {
        $data = [
          'error' => $e->getMessage(),
          'line' => $e->getLine(),
          'file' => $e->getFile(),
        ];
        return response()->json($data, 500);
      }
    }
    $data = [
      'message' => 'No tienes permisos para realizar esta acción',
      'status' => 401
    ];
    return response()->json($data, 401);
  }

  // Actualiza el usuario conectado
  public function update(Request $request)
  {
    try {
      $idUser = $this->idUsuarioAuth();
      $data = $this->updateById($request, $idUser);
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

  // Actualizar un usuario por id (Admin)
  public function adminUpdate(Request $request, $id)
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

    $data = $this->updateById($request, $id);
    $code = $data['status'];

    return response()->json($data, $code);
  }

  // Actualiza un usuario por id
  private function updateById(Request $request, $id)
  {
    $validator = Validator::make($request->all(), [
      'username' => 'required|string|unique:usuario,username,' . $id . ',id',
      'nombre' => 'required|string',
      'apellidos' => 'required|string',
      'email' => 'required|string|email|unique:usuario,email,' . $id . ',id',
      'direccion' => 'required|string'
    ]);

    if ($validator->fails()) {
      $data = [
        'message' => 'Error en la validación de los datos.',
        'errors' => $validator->errors(),
        'status' => 400
      ];
      return $data;
    }

    $usuario = Usuario::find($id);
    $cuenta = Cuenta::find($id);

    if (!$usuario) {
      $data = [
        'message' => 'Usuario no encontrado',
        'status' => 404
      ];
      return $data;
    }


    //Modelo Usuario
    $usuario->username = $request->username;
    $usuario->email = $request->email;
    $usuario->direccion = $request->direccion;
    $usuario->save();

    //Modelo Cuenta
    $cuenta->nombre = $request->nombre;
    $cuenta->apellidos = $request->apellidos;
    $cuenta->save();

    $usuarioActualizado = [
      'id' => $usuario->id,
      'username' => $usuario->username,
      'nombre' => $cuenta->nombre,
      'apellidos' => $cuenta->apellidos,
      'email' => $usuario->email,
      'saldo' => $usuario->saldo,
      'direccion' => $usuario->direccion
    ];

    $data = [
      'message' => 'Usuario actualizado correctamente',
      'usuario' => $usuarioActualizado,
      'status' => 200
    ];

    return $data;
  }

  // Elimina un usuario por id (el propio usuario)
  public function destroy()
  {
    try {
      $idUser = $this->idUsuarioAuth();

      $data = $this->deleteById($idUser);
      $code = $data['status'];

      return response()->json($data, status: $code);
    } catch (\Throwable $e) {
      $data = [
        'error' => $e->getMessage(),
        'line' => $e->getLine(),
        'file' => $e->getFile()
      ];
      return response()->json($data, 500);
    }
  }

  public function adminDestroy($id)
  {
    $usuarioAuth = auth()->user();
    $idRol = UsuarioRol::where('id_usuario', $usuarioAuth->id_user)->first()->id_rol;
    $rol = Rol::find($idRol)->nombre_rol;

    if ($rol != NombreRoles::ADMIN->value) {
      $data = [
        'message' => 'Acceso no autorizado, debe ser adminstrador',
        'status' => 401,
        'role' => $rol,
        'idUsuarioAuth' => $usuarioAuth->id_user,
        'idParametro' => $id
      ];
      return response()->json($data, 401);
    }

    $data = $this->deleteById($id);
    $code = $data['status'];

    return response()->json($data, status: $code);
  }

  private function deleteById($id)
  {
    $usuario = Usuario::with('cuenta')->find($id);

    if (!$usuario) {
      $data = [
        'message' => 'Error al eliminar el usuario',
        'status' => 400
      ];
      return $data;
    }
    $idUsuario = $usuario->id;

    // Eliminación de los productos del carrito y el carrito
    $carrito = Carrito::where('id_usuario', $idUsuario)->first();
    if ($carrito) {
      $productosCarrito = CarritoProducto::where('id_carrito', $carrito->id)->get();
      foreach ($productosCarrito as $producto) {
        CarritoProducto::where('id_carrito', $producto->id_carrito)
          ->where('id_producto', $producto->id_producto)
          ->delete();
      }
      $carrito->delete();
    }

    // Eliminación de los productos de la suscripcion y de la suscripciones
    $suscripciones = Suscripcion::where('id_usuario', $idUsuario)->get();
    if ($suscripciones) {
      foreach ($suscripciones as $suscripcion) {
        $productosSuscripcion = ProductoSuscripcion::where('id_suscripcion', $suscripcion->id)->get();
        foreach ($productosSuscripcion as $productoSuscripcion) {
          ProductoSuscripcion::where('id_producto', $productoSuscripcion->id_producto)
            ->where('id_suscripcion', $productoSuscripcion->id_suscripcion)
            ->delete();
        }
        $suscripcion->delete();
      }
    }

    // Comprobar si el usuario tiene pedidos NO cerrados
    $tienePedidosActivos = Pedido::where('id_usuario', $idUsuario)
      ->whereNotIn('estado', [
        EstadoPedido::ENTREGADO->value,
        EstadoPedido::CANCELADO->value,
        EstadoPedido::DEVUELTO->value,
        EstadoPedido::PERDIDO->value
      ])
      ->exists();

    if ($tienePedidosActivos) {
      $data = [
        'message' => 'Error al eliminar el usuario, tiene pedidos activos',
        'status' => 403
      ];

      return $data;
    }

    // No elimina al usuario como tal, lo que hace es un SoftDelete (simula que esta eliminado, por si se quiere recuperar el usuario más adelantes)
    $usuario->delete();
    // Tanto Cuenta como ActivarCuenta se marcan como desactivado y usado, para que el usuario no pueda realizar nada
    Cuenta::where('id_user', $idUsuario)->update(['activa' => false]);
    ActivarCuenta::where('id_user', $idUsuario)->update(['usado' => true]);
    // Cambiar contraseña usa SoftDelete para no eliminar el cambia de contraseña por si el usuario se reactiva
    CambiarPassword::where('id_user', $idUsuario)->delete();

    //Transformamos el JSON que va a devolver al api para saber el usuario y la cuenta 
    $usuario = [
      'id' => $usuario->id,
      'username' => $usuario->username,
      'nombre' => $usuario->cuenta->nombre,
      'apellidos' => $usuario->cuenta->apellidos,
      'email' => $usuario->id,
      'saldo' => $usuario->saldo,
      'direccion' => $usuario->direccion
    ];

    $data = [
      'message' => 'Usuario eliminado correctamente',
      'usuario' => $usuario,
      'status' => 200
    ];

    return $data;
  }

  // Devuelve los movimientos del usuario conectado. Si es administrador devuelve todos los movimientos
  public function movimientoIndex()
  {
    try {
      $idUser = $this->idUsuarioAuth();

      $data = $this->getMovimientos($idUser);
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

  public function adminMovimientoIndex()
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

    $data = $this->getMovimientos();
    $code = $data['status'];

    return response()->json($data, $code);
  }

  private function getMovimientos($idUser = null)
  {
    $movimientos = $idUser
      ? MovimientoSaldo::where('id_usuario', $idUser)->get()
      : MovimientoSaldo::all();

    if ($movimientos->isEmpty()) {
      $data = [
        'message' => 'No existen movimientos' . $idUser ? ' del usuario' : '',
        'status' => 404
      ];
      return $data;
    }

    $movimientosData = [];

    foreach ($movimientos as $movimiento) {
      $movimientosData[] = [
        'id' => $movimiento->id,
        'id_usuario' => $movimiento->id_usuario,
        'fecha' => $movimiento->fecha,
        'importe' => $movimiento->importe,
        'tipo' => $movimiento->tipo
      ];
    }
    $data = [
      'message' => 'Obtenidos todos los movimientos de los usuarios correctamente',
      'movimientos' => $movimientosData,
      'status' => 200
    ];
    return $data;
  }

  // Devuelve los movimientos de un usuario por id (solo lo puede usar el admin)
  public function movimientoShow($id)
  {
    try {
      $usuarioAuth = auth()->user();
      $idRol = UsuarioRol::where('id_usuario', $usuarioAuth->id_user)->first()->id_rol;
      $rol = Rol::find($idRol)->nombre_rol;

      // Si el rol es el Admin
      if ($rol == NombreRoles::ADMIN->value) {
        $data = $this->getMovimientos($id);
        $code = $data['status'];

        return response()->json($data, $code);
      } else {
        $data = [
          'message' => 'Acceso no autorizado, debe ser adminstrador para realizar la petición',
          'status' => 401
        ];
        return response()->json($data, 401);
      }
    } catch (\Throwable $e) {
      $data = [
        'error' => $e->getMessage(),
        'line' => $e->getLine(),
        'file' => $e->getFile()
      ];
      return response()->json($data, 500);
    }
  }

  // Función para cambiar la contraseña del usuario
  public function cambiarPassword(Request $request)
  {
    try {
      $usuarioAuth = auth()->user();

      // Validación
      $validator = Validator::make($request->all(), [
        'old_password' => 'required',
        'new_password' => [
          'required',
          'confirmed',
          Password::min(8)
            ->letters()
            ->mixedCase()
            ->numbers()
            ->symbols()
        ]
      ]);

      if ($validator->fails()) {
        $data = [
          'message' => 'Error de validación de los datos',
          'errors' => $validator->errors(),
          'status' => 422
        ];
        return response()->json($data, 422);
      }

      // Obtener cuenta (donde tienes la contraseña)
      $cuenta = Cuenta::where('id_user', $usuarioAuth->id_user)->first();

      if (!$cuenta) {
        $data = [
          'message' => 'Cuenta no encontrada',
          'status' => 404
        ];
        return response()->json($data, 404);
      }

      // COMPARAR CONTRASEÑA ANTIGUA con la nueva
      if (!Hash::check($request->old_password, $cuenta->password)) {
        $data = [
          'message' => 'La contraseña actual no es correcta',
          'status' => 401
        ];
        return response()->json($data, 401);
      }

      // Evitar que ponga la misma contraseña
      if (Hash::check($request->new_password, $cuenta->password)) {
        $data = [
          'message' => 'La nueva contraseña no puede ser igual a la anterior',
          'status' => 400
        ];
        return response()->json($data, 400);
      }

      try {
        DB::beginTransaction();

        // ACTUALIZAR CONTRASEÑA
        $cuenta->password = Hash::make($request->new_password);
        $cuenta->save();

        DB::commit();

        $data = [
          'message' => 'Contraseña actualizada correctamente',
          'status' => 200
        ];
        return response()->json($data, 200);
      } catch (\Throwable $e) {
        DB::rollBack();
        $data = [
          'error' => $e->getMessage(),
          'line' => $e->getLine(),
          'file' => $e->getFile()
        ];
        return response()->json($data, 500);
      }
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
