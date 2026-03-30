<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

use App\Models\Pedido;
use App\Models\PedidoProducto;
use App\Models\Carrito;
use App\Models\CarritoProducto;
use App\Models\Producto;
use App\Models\Usuario;
use App\Models\UsuarioRol;
use App\Models\Rol;
use App\Models\MovimientoSaldo;

use App\Enums\NombreRoles;
use App\Enums\EstadoPedido;
use App\Enums\TipoMovimientoSaldo;

use App\Mail\MailEnviarFactura;

class PedidoController
{

  // Función para obtener el id del usuario autenticado
  public static function idUsuarioAuth()
  {
    return auth()->user()->id_user;
  }

  // Función para obtener la lista de pedidos
  private function getPedidos($idUser = null)
  {
    $pedidos = $idUser ? Pedido::with(['pedidoProductos', 'usuario.cuenta'])->where('id_usuario', $idUser)->get()
      : Pedido::with(['pedidoProductos', 'usuario.cuenta'])->get();

    if (!$pedidos) {
      $data = [
        'message' => 'Error al obtener los pedidos',
        'status' => 500
      ];
      return $data;
    }

    if ($idUser) {
      // Usuario autenticado
      $pedidos = $pedidos->map(function ($pedido) {
        return [
          'id' => $pedido->id,
          'id_usuario' => $pedido->id_usuario,
          'codigo' => $pedido->codigo,
          'estado' => $pedido->estado,
          'suscripcion' => (int) $pedido->suscripcion,
          'productos' => $pedido->pedidoProductos->map(function ($producto) {
            return [
              'id_producto' => $producto->id_producto,
              'cantidad' => $producto->cantidad
            ];
          })
        ];
      });
    } else {
      // Administrador
      $pedidos = $pedidos->map(function ($pedido) {
        return [
          'id' => $pedido->id,
          'id_usuario' => $pedido->id_usuario,
          'codigo' => $pedido->codigo,
          'nombre' => $pedido->usuario->cuenta->nombre ?? null,
          'apellidos' => $pedido->usuario->cuenta->apellidos ?? null,
          'email' => $pedido->usuario->email ?? null,
          'estado' => $pedido->estado,
          'suscripcion' => (int) $pedido->suscripcion
        ];
      });
    }

    $data = [
      'message' => 'Pedidos obtenidos correctamente',
      'pedidos' => $pedidos,
      'status' => 200
    ];

    return $data;
  }

  // Función para obtener un pedido enconcreto
  private function getPedido($idUser = null, $id)
  {
    $pedido = $idUser ? Pedido::with(['pedidoProductos', 'usuario.cuenta'])->where('id', $id)->where('id_usuario', $idUser)->first()
      : Pedido::with(['pedidoProductos', 'usuario.cuenta'])->where('id', $id)->first();

    if (!$pedido) {
      $data = [
        'message' => 'Pedido no encontrado',
        'status' => 404
      ];
      return $data;
    }

    if ($idUser) {
      // Usuario autenticado
      $pedido = [
        'id' => $pedido->id,
        'id_usuario' => $pedido->id_usuario,
        'codigo' => $pedido->codigo,
        'estado' => $pedido->estado,
        'suscripcion' => (int) $pedido->suscripcion,
        'productos' => $pedido->pedidoProductos->map(function ($producto) {
          return [
            'id_producto' => $producto->id_producto,
            'cantidad' => $producto->cantidad
          ];
        })
      ];
    } else {
      // Administrador
      $pedido = [
        'id' => $pedido->id,
        'id_usuario' => $pedido->id_usuario,
        'codigo' => $pedido->codigo,
        'nombre' => $pedido->usuario->cuenta->nombre ?? null,
        'apellidos' => $pedido->usuario->cuenta->apellidos ?? null,
        'email' => $pedido->usuario->email ?? null,
        'estado' => $pedido->estado,
        'suscripcion' => (int) $pedido->suscripcion
      ];
    }

    $data = [
      'message' => 'Pedidos obtenidos correctamente',
      'pedidos' => $pedido,
      'status' => 200
    ];

    return $data;
  }

  // Obtener todos los pedidos del usuario autenticado
  public function index()
  {
    try {
      $idUser = $this->idUsuarioAuth();
      $data = $this->getPedidos($idUser);
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

  // Obtener todos los pedidos de los usuarios (administrador)
  public function adminIndex()
  {
    try {
      $data = $this->getPedidos();
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

  // Función para obtener los pedidos mediante un filtro
  public function pedidoFiltro(Request $request)
  {
    try {
      $userAuth = auth()->user();
      $idRol = UsuarioRol::where('id_usuario', $userAuth->id_user)->first()->id_rol;
      $rol = Rol::where('id', $idRol)->first()->nombre_rol;

      $buscar = $request->query('buscar');

      $pedidos = Pedido::with(['pedidoProductos', 'usuario.cuenta'])
        ->when($buscar, function ($query, $buscar) {

          $palabras = explode(' ', $buscar);

          $query->where(function ($q) use ($palabras, $buscar) {
            $q->whereHas('usuario.cuenta', function ($q2) use ($palabras) {
              foreach ($palabras as $palabra) {
                $q2->where(function ($sub) use ($palabra) {
                  $sub->where('nombre', 'like', "%{$palabra}%")
                    ->orWhere('apellidos', 'like', "%{$palabra}%");
                });
              }
            })
              ->orWhere('codigo', 'like', "%{$buscar}%")
              ->orWhere('estado', 'like', "%{$buscar}%")
              ->orWhere('suscripcion', 'like', "%{$buscar}%")

              ->orWhereHas('usuario', function ($q3) use ($buscar) {
                $q3->where('email', 'like', "%{$buscar}%");
              });
          });
        })
        ->get();

      if (!$pedidos) {
        $data = [
          'message' => 'Error al obtener los pedidos',
          'status' => 500
        ];
        return response()->json($data, 500);
      }

      $datosPedido = $pedidos->map(function ($pedido) {
        return [
          'id' => $pedido->id,
          'id_usuario' => $pedido->id_usuario,
          'codigo' => $pedido->codigo,
          'nombre' => $pedido->usuario->cuenta->nombre ?? null,
          'apellidos' => $pedido->usuario->cuenta->apellidos ?? null,
          'email' => $pedido->usuario->email ?? null,
          'estado' => $pedido->estado,
          'suscripcion' => $pedido->suscripcion
        ];
      });

      $data = [
        'message' => 'Pedidos obtenidos correctamente',
        'pedidos' => $datosPedido,
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

  // Obtener un pedido específico
  public function show($id)
  {
    try {
      $idUser = $this->idUsuarioAuth();
      $data = $this->getPedido($idUser, $id);
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

  // Función para obtener un pedido en concreto (administrador)
  public function adminShow($id)
  {
    try {
      $data = $this->getPedido(null, $id);
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

  // Crear un nuevo pedido a partir del carrito del usuario autenticado
  public function store(Request $request)
  {
    try {
      $userAuth = auth()->user();
      $carrito = Carrito::where('id_usuario', $userAuth->id_user)->first();

      // Verificar si el usuario tiene saldo suficiente
      if ($carrito->precio_total > $userAuth->usuario->saldo) {
        $data = [
          'message' => 'Saldo insuficiente para realizar el pedido',
          'status' => 400
        ];
        return response()->json($data, 400);
      }

      $carritoProductos = CarritoProducto::where('id_carrito', $carrito->id)->get();

      // Verificar si el carrito tiene productos
      if ($carritoProductos->isEmpty()) {
        $data = [
          'message' => 'El carrito está vacío',
          'status' => 400
        ];
        return response()->json($data, 400);
      }

      // Generar codigo para el pedido
      $timestamp = (int)now()->diffInSeconds(now()->startOfDay(), true);
      $codigoPedido = $timestamp . random_int(1000, 9999);

      // Crear el pedido
      $pedido = Pedido::create([
        'codigo' => $codigoPedido,
        'id_usuario' => $userAuth->id_user,
        'estado' => EstadoPedido::CREADO->value,
        'suscripcion' => false,
        'precio_total' => 0.0
      ]);

      // Comprobar que hay suficiente stock para cada producto
      $productosSinStock = [];
      foreach ($carritoProductos as $cp) {
        $producto = Producto::where('id', $cp->id_producto)->first();
        if ($producto->stock < $cp->cantidad) {
          $productosSinStock[] = $producto;
        }
      }

      if (!empty($productosSinStock)) {
        $pedido->delete(); // Eliminar el pedido creado si no hay stock
        $data = [
          'message' => 'No hay suficiente stock para los siguientes productos',
          'productos' => $productosSinStock,
          'status' => 400
        ];
        return response()->json($data, 400);
      }

      // Asociar los productos del carrito al pedido
      foreach ($carritoProductos as $cp) {
        PedidoProducto::create([
          'id_pedido' => $pedido->id,
          'id_producto' => $cp->id_producto,
          'cantidad' => $cp->cantidad
        ]);

        // Actualizar el stock del producto
        $producto = Producto::where('id', $cp->id_producto)->first();
        $producto->stock -= $cp->cantidad;
        $producto->save();

        // Calcular el precio del producto con descuento
        $precioConDescuento = $producto->precio * (1 - ($producto->oferta / 100));
        $pedido->precio_total += $precioConDescuento * $cp->cantidad;
        $pedido->save();

        // Eliminar el producto del carrito
        CarritoProducto::where('id_carrito', $carrito->id)
          ->where('id_producto', $cp->id_producto)
          ->delete();
      }

      // Vaciar el carrito
      $carrito->precio_total = 0.0;
      $carrito->save();

      // Actualizar el saldo del usuario
      $userAuth->usuario->saldo -= $pedido->precio_total;
      $userAuth->usuario->save();

      // Registrar el movimiento de saldo
      MovimientoSaldo::create([
        'id_usuario' => $userAuth->id_user,
        'fecha' => now(),
        'importe' => -$pedido->precio_total,
        'tipo' => TipoMovimientoSaldo::PAGO->value,
        'descripcion' => 'Pago del pedido con código ' . $pedido->codigo
      ]);

      // Enviar email con la factura
      Mail::send(new MailEnviarFactura($pedido));

      // Cargar los productos del pedido para devolverlos en la respuesta
      $pedido->with('pedidoProductos')->first();

      // Transforma los pedidos en el JSON que debe devolver la API
      $pedido =  [
        'id' => $pedido->id,
        'id_usuario' => $pedido->id_usuario,
        'codigo' => $pedido->codigo,
        'estado' => $pedido->estado,
        'subcripcion' => (int) $pedido->subcripcion,
        'precio_total' => $pedido->precio_total,
        'productos' => $pedido->pedidoProductos->map(function ($producto) {
          return [
            'id_producto' => $producto->id_producto,
            'cantidad' => $producto->cantidad
          ];
        })
      ];

      $data = [
        'message' => 'Pedido creado exitosamente',
        'pedido' => $pedido,
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

  // Actualizar el estado de un pedido (solo para admin o el propio usuario)
  public function estadoUpdate(Request $request, $id)
  {
    try {
      $userAuth = auth()->user();
      $idRol = UsuarioRol::where('id_usuario', $userAuth->id_user)->first()->id_rol;
      $rol = Rol::where('id', $idRol)->first()->nombre_rol;

      $validator = Validator::make($request->all(), [
        'estado' => ['required', Rule::in(EstadoPedido::values())]
      ]);

      if ($validator->fails()) {
        $data = [
          'message' => 'Datos inválidos',
          'errors' => $validator->errors(),
          'status' => 400
        ];
        return response()->json($data, 400);
      }

      // Lógica para actualizar el estado del pedido por un administrador
      if ($rol == NombreRoles::ADMIN->value) {
        $pedido = Pedido::where('id', $id)->first();

        if (!$pedido) {
          $data = [
            'message' => 'Pedido no encontrado',
            'status' => 404
          ];
          return response()->json($data, 404);
        }

        if ($pedido->estado == $request->estado) {
          $data = [
            'message' => 'El pedido ya está en el estado solicitado',
            'status' => 400
          ];
          return response()->json($data, 400);
        }

        // Estados no permitidos para modificar
        if ($pedido->estado == EstadoPedido::CANCELADO->value || $pedido->estado == EstadoPedido::DEVUELTO->value) {
          $data = [
            'message' => 'El pedido no puede ser modificado en su estado actual',
            'status' => 403
          ];
          return response()->json($data, 403);
        }

        // Cambios de estado permitidos para el estado de CREADO
        if ($pedido->estado == EstadoPedido::CREADO->value && $request->estado != EstadoPedido::PROCESADO->value && $request->estado != EstadoPedido::CANCELADO->value && $request->estado != EstadoPedido::PERDIDO->value) {
          $data = [
            'message' => 'El pedido solo puede ser cambiado a PROCESADO, CANCELADO o PERDIDO desde el estado CREADO',
            'status' => 403
          ];
          return response()->json($data, 403);
        }

        // Cambios de estado permitidos para el estado de PROCESADO
        if ($pedido->estado == EstadoPedido::PROCESADO->value && $request->estado != EstadoPedido::REPARTO->value && $request->estado != EstadoPedido::CANCELADO->value && $request->estado != EstadoPedido::PERDIDO->value) {
          $data = [
            'message' => 'El pedido solo puede ser cambiado a REPARTO, CANCELADO o PERDIDO desde el estado PROCESADO',
            'status' => 403
          ];
          return response()->json($data, 403);
        }

        // Cambios de estado permitidos para el estado de REPARTO
        if ($pedido->estado == EstadoPedido::REPARTO->value && $request->estado != EstadoPedido::ENTREGADO->value && $request->estado != EstadoPedido::CANCELADO->value && $request->estado != EstadoPedido::PERDIDO->value) {
          $data = [
            'message' => 'El pedido solo puede ser cambiado a ENTREGADO, CANCELADO o PERDIDO desde el estado REPARTO',
            'status' => 403
          ];
          return response()->json($data, 403);
        }

        // Cambios de estado permitidos para el estado de ENTREGADO
        if ($pedido->estado == EstadoPedido::ENTREGADO->value && $request->estado != EstadoPedido::EN_DEVOLUCION->value) {
          $data = [
            'message' => 'El pedido solo puede ser cambiado a EN_DEVOLUCION desde el estado ENTREGADO',
            'status' => 403
          ];
          return response()->json($data, 403);
        }

        if ($pedido->estado == EstadoPedido::EN_DEVOLUCION->value && $request->estado != EstadoPedido::DEVUELTO->value && $request->estado != EstadoPedido::PERDIDO->value) {
          $data = [
            'message' => 'El pedido solo puede ser cambiado a DEVUELTO o PERDIDO desde el estado EN_DEVOLUCION',
            'status' => 403
          ];
          return response()->json($data, 403);
        }

        $pedido->estado = $request->estado;
        $pedido->save();
      }

      // Lógica para actualizar el estado del pedido por un usuario
      if ($rol == NombreRoles::USER->value) {
        $pedido = Pedido::where('id', $id)->where('id_usuario', $userAuth->id_user)->first();

        if (!$pedido) {
          $data = [
            'message' => 'Pedido no encontrado',
            'status' => 404
          ];
          return response()->json($data, 404);
        }

        if ($request->estado == EstadoPedido::CANCELADO->value) {
          // Lógica para cancelar el pedido
          if (
            $pedido->estado != EstadoPedido::CREADO->value
            && $pedido->estado != EstadoPedido::PROCESADO->value
            && $pedido->estado != EstadoPedido::REPARTO->value
          ) {
            $data = [
              'message' => 'El pedido no puede ser cancelado en su estado actual',
              'status' => 403
            ];
            return response()->json($data, 403);
          }

          $pedido->estado = EstadoPedido::CANCELADO->value;
          $pedido->save();
        } else if ($request->estado == EstadoPedido::EN_DEVOLUCION->value) {
          // Lógica para devolver el pedido
          if ($pedido->estado != EstadoPedido::ENTREGADO->value) {
            $data = [
              'message' => 'El pedido no puede ser devuelto en su estado actual',
              'status' => 403
            ];
            return response()->json($data, 403);
          }

          $pedido->estado = EstadoPedido::EN_DEVOLUCION->value;
          $pedido->save();
        } else {
          $data = [
            'message' => 'Estado no permitido para el usuario',
            'status' => 403
          ];
          return response()->json($data, 403);
        }
      }

      $data = [
        'message' => 'Estado del pedido actualizado',
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

  // Función para eliminar un pedido de un usuario (admin como el propio usuario)
  // Si el que hace la petición es el usuario cancela el pedido, si lo hace el admin primero el pedido no debe estar en ningún estado de los prohibido para poder eliminarlo
  public function destroy(Request $request, $id)
  {
    try {
      DB::beginTransaction();
      $userAuth = auth()->user();
      $idRol = UsuarioRol::where('id_usuario', $userAuth->id_user)->first()->id_rol;
      $rol = Rol::where('id', $idRol)->first()->nombre_rol;

      // Buscar el pedido con sus productos
      $pedido = Pedido::with('pedidoProductos')->where('id', $id)->first();

      if (!$pedido) {
        $data = [
          'message' => 'Pedido no encontrado',
          'status' => 404
        ];
        return response()->json($data, 404);
      }

      // Lógica para usuarios
      if ($pedido->id_usuario == $userAuth->id_user && $rol != NombreRoles::ADMIN->value) {
        if ($request->estado == EstadoPedido::CANCELADO->value) {
          if (!in_array($pedido->estado, [
            EstadoPedido::CREADO->value,
            EstadoPedido::PROCESADO->value,
            EstadoPedido::REPARTO->value
          ])) {
            $data = [
              'message' => 'El pedido no puede ser cancelado en su estado actual',
              'estado' => $pedido->estado,
              'status' => 403
            ];
            return response()->json($data, 403);
          }
          $pedido->estado = EstadoPedido::CANCELADO->value;
          $pedido->save();
        } elseif ($request->estado == EstadoPedido::EN_DEVOLUCION->value) {
          if ($pedido->estado != EstadoPedido::ENTREGADO->value) {
            $data = [
              'message' => 'El pedido no puede ser devuelto en su estado actual',
              'estado' => $pedido->estado,
              'status' => 403
            ];
            return response()->json($data, 403);
          }
          $pedido->estado = EstadoPedido::EN_DEVOLUCION->value;
          $pedido->save();
        } else {
          $data = [
            'message' => 'Estado no permitido para el usuario',
            'status' => 403
          ];
          return response()->json($data, 403);
        }

        // Restaurar stock y eliminar productos del pedido
        $productosPedido = [];
        foreach ($pedido->pedidoProductos as $pp) {
          $productosPedido[] = [
            'idProducto' => $pp->id_producto,
            'cantidad' => $pp->cantidad
          ];

          $producto = Producto::find($pp->id_producto);
          if ($producto) {
            $producto->stock += $pp->cantidad;
            $producto->save();
          }

          // Eliminar cada registro individualmente (clave primaria compuesta)
          PedidoProducto::where('id_pedido', $pp->id_pedido)
            ->where('id_producto', $pp->id_producto)
            ->delete();
        }

        // Reembolsar saldo al usuario
        $userAuth->usuario->saldo += $pedido->precio_total;
        $userAuth->usuario->save();

        MovimientoSaldo::create([
          'id_usuario' => $userAuth->id_user,
          'fecha' => now(),
          'importe' => $pedido->precio_total,
          'tipo' => TipoMovimientoSaldo::DEVOLUCION->value,
          'descripcion' => 'Reembolso del pedido con código ' . $pedido->codigo
        ]);

        DB::commit();

        $data = [
          'message' => 'Pedido actualizado correctamente',
          'pedido' => [
            'id' => $pedido->id,
            'estado' => $pedido->estado,
            'productos' => $productosPedido
          ],
          'status' => 200
        ];
        return response()->json($data, 200);
      }

      // Lógica para administradores
      elseif ($rol == NombreRoles::ADMIN->value) {

        $estadosNoEliminables = [
          EstadoPedido::PROCESADO->value,      // ya se está preparando
          EstadoPedido::REPARTO->value,        // ya está en tránsito
          EstadoPedido::ENTREGADO->value,      // ya llegó al cliente
          EstadoPedido::EN_DEVOLUCION->value,  // en proceso de devolución
          EstadoPedido::DEVUELTO->value,       // ya devuelto
          EstadoPedido::PERDIDO->value         // perdido
        ];

        if (in_array($pedido->estado, $estadosNoEliminables)) {
          $data = [
            'message' => 'No se puede eliminar el pedido en su estado actual',
            'estado' => $pedido->estado,
            'status' => 403
          ];
          return response()->json($data, 403);
        }

        $usuarioPedido = Usuario::find($pedido->id_usuario);
        $productosPedido = [];

        // Restaurar stock y eliminar productos del pedido
        foreach ($pedido->pedidoProductos as $pp) {
          $productosPedido[] = [
            'idProducto' => $pp->id_producto,
            'cantidad' => $pp->cantidad
          ];

          $producto = Producto::find($pp->id_producto);
          if ($producto) {
            $producto->stock += $pp->cantidad;
            $producto->save();
          }

          PedidoProducto::where('id_pedido', $pp->id_pedido)
            ->where('id_producto', $pp->id_producto)
            ->delete();
        }

        // Reembolsar saldo al usuario
        if ($usuarioPedido) {
          $usuarioPedido->saldo += $pedido->precio_total;
          $usuarioPedido->save();

          MovimientoSaldo::create([
            'id_usuario' => $usuarioPedido->id,
            'fecha' => now(),
            'importe' => $pedido->precio_total,
            'tipo' => TipoMovimientoSaldo::DEVOLUCION->value,
            'descripcion' => 'Reembolso del pedido eliminado con código ' . $pedido->codigo
          ]);
        }

        // Eliminar el pedido
        $pedido->delete();

        DB::commit();

        $data = [
          'message' => 'Pedido eliminado correctamente',
          'pedido' => [
            'id' => $pedido->id,
            'productos' => $productosPedido
          ],
          'status' => 200
        ];
        return response()->json($data, 200);
      }
    } catch (\Throwable $e) {
      DB::rollBack();
      $data = [
        'error' => $e->getMessage(),
        'line' => $e->getLine(),
        'file' => $e->getFile()
      ];
      return response()->json($data, 500);
    }
  }
}
