<?php

namespace App\Http\Controllers\Api;

use App\Models\Producto;
use App\Enums\UnidadMedida;
use App\Models\CarritoProducto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\UsuarioRol;
use App\Models\Rol;
use App\Enums\NombreRoles;
use App\Models\PedidoProducto;

class ProductoController
{
  // Función estática para comprobar le rol del usuario
  public static function identificacion($rolComprobar)
  {
    $usuarioAuth = auth()->user();
    $idRol = UsuarioRol::where('id_usuario', $usuarioAuth->id_user)->first()->id_rol;
    $rol = Rol::where('id', $idRol)->first()->nombre_rol;

    return $rol == $rolComprobar;
  }

  // Función para obtener todos los productos
  public function index()
  {
    try {
      $productos = Producto::all();

      if (!$productos) {
        $data = [
          'message' => 'Error al obtener los productos',
          'status' => 500
        ];
        return response()->json($data, 500);
      }

      // Transformamos al JSON correspondiente
      $productos = $productos->map(function ($producto) {
        return [
          'id' => $producto->id,
          'nombre' => $producto->nombre,
          'precio' => $producto->precio,
          'stock' => $producto->stock,
          'descripcion' => $producto->descripcion,
          'oferta' => $producto->oferta,
          'informacion_nutricional' => $producto->informacion_nutricional,
          'tamanyo' => $producto->tamanyo,
          'path_imagen' => $producto->path_imagen,
          'unidad_medida' => $producto->unidad_medida
        ];
      });

      $data = [
        'message' => 'Productos obtenidos correctamente',
        'productos' => $productos,
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

  // Función para obtener el producto según el id
  public function show($id)
  {
    try {
      $producto = Producto::find($id);

      if (!$producto) {
        $data = [
          'message' => 'Error al obtener el producto',
          'status' => 404
        ];

        return response()->json($data, 404);
      }

      // Transformamos al JSON correspondiente
      $producto = [
        'id' => $producto->id,
        'nombre' => $producto->nombre,
        'precio' => $producto->precio,
        'stock' => $producto->stock,
        'descripcion' => $producto->descripcion,
        'oferta' => $producto->oferta,
        'informacion_nutricional' => $producto->informacion_nutricional,
        'tamanyo' => $producto->tamanyo,
        'path_imagen' => $producto->path_imagen,
        'unidad_medida' => $producto->unidad_medida
      ];


      $data = [
        'message' => 'Producto obtenido correctamente',
        'producto' => $producto,
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

  // Función para buscar un producto según el filtro
  public function productoFiltro(Request $request)
  {
    try {
      $buscar = $request->query('buscar');

      $productos = Producto::when($buscar, function ($query, $buscar) {
        $query->where(function ($q) use ($buscar) {
          // Buscar en nombre y descripción
          $q->where('nombre', 'like', "%{$buscar}%")
            ->orWhere('descripcion', 'like', "%{$buscar}%");

          // Si el valor es numérico, buscar también por precio
          if (is_numeric($buscar)) {
            $q->orWhere('precio', $buscar);
          }
        });
      })->get();

      if ($productos->isEmpty()) {
        $data = [
          'message' => 'No se encontraron productos con ese filtro',
          'status' => 404
        ];
        return response()->json($data, 404);
      }

      // Transformamos los productos al JSON correspondiente
      $productosDevueltos = $productos->map(function ($producto) {
        return [
          'id' => $producto->id,
          'nombre' => $producto->nombre,
          'precio' => $producto->precio,
          'stock' => $producto->stock,
          'descripcion' => $producto->descripcion,
          'oferta' => $producto->oferta,
          'informacion_nutricional' => $producto->informacion_nutricional,
          'tamanyo' => $producto->tamanyo,
          'path_imagen' => $producto->path_imagen,
          'unidad_medida' => $producto->unidad_medida
        ];
      });

      $data = [
        'message' => 'Productos obtenidos correctamente',
        'productos' => $productosDevueltos,
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

  // Función para crear un producto
  public function store(Request $request)
  {
    try {
      if (self::identificacion(NombreRoles::ADMIN->value)) {
        $validator = Validator::make($request->all(), [
          'nombre' => 'required|string',
          'precio' => 'required|numeric',
          'stock' => 'required|integer',
          'descripcion' => 'string',
          'oferta' => 'numeric',
          'path_imagen' => 'required|file|mimes:jpg,jpeg,png,gif|max:2048',
          'tamanyo' => 'required|numeric',
          'unidad_medida' => ['required', Rule::enum(UnidadMedida::class)],
          'informacion_nutricional' => ['required', 'array'],
          'informacion_nutricional.calorias' => ['required', 'numeric'],
          'informacion_nutricional.grasas' => ['required', 'numeric'],
          'informacion_nutricional.grasas_saturadas' => ['required', 'numeric'],
          'informacion_nutricional.hidratos' => ['required', 'numeric'],
          'informacion_nutricional.azucares' => ['required', 'numeric'],
          'informacion_nutricional.proteinas' => ['required', 'numeric'],
          'informacion_nutricional.sal' => ['required', 'numeric']
        ]);

        if ($validator->fails()) {
          $data = [
            'message' => 'Error en la validación de los datos',
            'errors' => $validator->errors(),
            'status' => 400
          ];

          return response()->json($data, 400);
        }

        //Intentamos subir el archivo a la carpeta correspondiente
        try {
          //Comprobamos que se haya puesto una imagen
          if ($request->hasFile('path_imagen')) {
            $archivo = $request->file('path_imagen');
            $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
            $archivo->move(public_path('resources/images/productos'), $nombreArchivo);
            $pathImagen = 'resources/images/productos/' . $nombreArchivo;
          }
        } catch (\Throwable $e) {
          $data = [
            'message' => 'Error al subir la imagen',
            'error' => $e->getMessage(),
            'status' => 400
          ];
          return response()->json($data, 400);
        }

        $producto = Producto::create([
          'nombre' => $request->nombre,
          'precio' => $request->precio,
          'stock' => $request->stock,
          'descripcion' => $request->descripcion,
          'oferta' => $request->oferta,
          'tamanyo' => $request->tamanyo,
          'unidad_medida' => $request->unidad_medida,
          'informacion_nutricional' => $request->informacion_nutricional,
          'path_imagen' => $pathImagen
        ]);

        if (!$producto) {
          $data = [
            'message' => 'Error al crear el producto',
            'status' => 500
          ];

          return response()->json($data, 500);
        }

        // Transformamos al JSON correspondiente
        $producto = [
          'id' => $producto->id,
          'nombre' => $producto->nombre,
          'precio' => $producto->precio,
          'stock' => $producto->stock,
          'descripcion' => $producto->descripcion,
          'oferta' => $producto->oferta,
          'informacion_nutricional' => $producto->informacion_nutricional,
          'tamanyo' => $producto->tamanyo,
          'path_imagen' => $producto->path_imagen,
          'unidad_medida' => $producto->unidad_medida
        ];

        $data = [
          'message' => 'Producto creado correctamente',
          'producto' => $producto,
          'status' => 201
        ];

        return response()->json($data, 201);
      }

      $data = [
        'message' => 'No tienes permisos para realizar esta acción',
        'status' => 401
      ];
      return response()->json($data, 401);
    } catch (\Throwable $e) {
      $data = [
        'error' => $e->getMessage(),
        'line' => $e->getLine(),
        'file' => $e->getFile()
      ];
      return response()->json($data, 500);
    }
  }

  // Función para actualizar el producto
  public function update(Request $request, $id)
  {
    try {
      if (self::identificacion(NombreRoles::ADMIN->value)) {
        $producto = Producto::find($id);

        if (!$producto) {
          $data = [
            'message' => 'Producto no encontrado',
            'status' => 404
          ];

          return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(), [
          'nombre' => 'required|string',
          'precio' => 'required|numeric',
          'stock' => 'required|integer',
          'descripcion' => 'nullable|string',
          'oferta' => 'nullable|numeric',
          'path_imagen' => 'nullable|file|mimes:jpg,jpeg,png,gif|max:2048',
          'tamanyo' => 'required|numeric',
          'unidad_medida' => ['required', Rule::enum(UnidadMedida::class)],
          'informacion_nutricional' => ['required', 'array'],
          'informacion_nutricional.calorias' => ['required', 'numeric'],
          'informacion_nutricional.grasas' => ['required', 'numeric'],
          'informacion_nutricional.grasas_saturadas' => ['required', 'numeric'],
          'informacion_nutricional.hidratos' => ['required', 'numeric'],
          'informacion_nutricional.azucares' => ['required', 'numeric'],
          'informacion_nutricional.proteinas' => ['required', 'numeric'],
          'informacion_nutricional.sal' => ['required', 'numeric']
        ]);

        if ($validator->fails()) {
          $data = [
            'message' => 'Error en la validación de los datos',
            'errors' => $validator->errors(),
            'status' => 400
          ];

          return response()->json($data, 400);
        }

        $producto->nombre = $request->nombre;
        $producto->precio = $request->precio;
        $producto->stock = $request->stock;
        $producto->descripcion = $request->descripcion;
        $producto->oferta = $request->oferta;
        $producto->tamanyo = $request->tamanyo;
        $producto->unidad_medida = $request->unidad_medida;
        $producto->informacion_nutricional = $request->informacion_nutricional;
        // Actualizar imagen si se envía una nueva
        if ($request->hasFile('path_imagen')) {
          // Eliminar imagen antigua
          if ($producto->path_imagen && file_exists(public_path($producto->path_imagen))) {
            unlink(public_path($producto->path_imagen));
          }
          $imagen = $request->file('path_imagen');
          $nombreImagen = time() . '_' . $imagen->getClientOriginalName();
          $imagen->move(public_path('resources/images/productos'), $nombreImagen);
          $producto->path_imagen = 'resources/images/productos/' . $nombreImagen;
        }


        // Guardamos el producto
        $producto->save();

        // Transformamos al JSON correspondiente
        $producto = [
          'id' => $producto->id,
          'nombre' => $producto->nombre,
          'precio' => $producto->precio,
          'stock' => $producto->stock,
          'descripcion' => $producto->descripcion,
          'oferta' => $producto->oferta,
          'informacion_nutricional' => $producto->informacion_nutricional,
          'tamanyo' => $producto->tamanyo,
          'path_imagen' => $producto->path_imagen,
          'unidad_medida' => $producto->unidad_medida
        ];


        $data = [
          'message' => 'Producto actualizado correctamente',
          'producto' => $producto,
          'status' => 200
        ];

        return response()->json($data, 200);
      }

      $data = [
        'message' => 'No tienes permisos para realizar esta acción',
        'status' => 401
      ];
      return response()->json($data, 401);
    } catch (\Throwable $e) {
      $data = [
        'error' => $e->getMessage(),
        'line' => $e->getLine(),
        'file' => $e->getFile()
      ];
      return response()->json($data, 500);
    }
  }

  // Función para actualizar el producto de forma parcial (un dato en concreto)
  public function adminPartialUpdate(Request $request, $id)
  {
    try {
      // Lógica para la actualizión del producto solo por el admin
      if (self::identificacion(NombreRoles::ADMIN->value)) {
        $producto = Producto::find($id);

        if (!$producto) {
          $data = [
            'message' => 'Producto no encontrado',
            'status' => 404
          ];

          return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(), [
          'nombre' => 'string',
          'precio' => 'numeric',
          'stock' => 'integer',
          'descripcion' => 'string',
          'oferta' => 'numeric',
          'path_imagen' => 'file|mimes:jpg,jpeg,png,gif|max:2048',
          'tamanyo' => 'numeric',
          'unidad_medida' => [Rule::enum(UnidadMedida::class)],
          'informacion_nutricional' => ['array'],
          'informacion_nutricional.calorias' => ['numeric'],
          'informacion_nutricional.grasas' => ['numeric'],
          'informacion_nutricional.grasas_saturadas' => ['numeric'],
          'informacion_nutricional.hidratos' => ['numeric'],
          'informacion_nutricional.azucares' => ['numeric'],
          'informacion_nutricional.proteinas' => ['numeric'],
          'informacion_nutricional.sal' => ['numeric']
        ]);

        if ($validator->fails()) {
          $data = [
            'message' => 'Error en la validación de los datos',
            'errors' => $validator->errors(),
            'status' => 400
          ];

          return response()->json($data, 400);
        }

        if ($request->has('nombre')) {
          $producto->nombre = $request->nombre;
        }
        if ($request->has('precio')) {
          $producto->precio = $request->precio;
        }
        if ($request->has('stock')) {
          $producto->stock = $request->stock;
        }
        if ($request->has('descripcion')) {
          $producto->descripcion = $request->descripcion;
        }
        if ($request->has('oferta')) {
          $producto->oferta = $request->oferta;
        }
        // ACTUALIZAR IMAGEN
        if ($request->hasFile('path_imagen')) {
          // eliminar imagen antigua
          if ($producto->path_imagen && file_exists(public_path($producto->path_imagen))) {
            unlink(public_path($producto->path_imagen));
          }
          $imagen = $request->file('path_imagen');
          $nombreImagen = time() . '_' . $imagen->getClientOriginalName();
          $imagen->move(public_path('resources/images/productos'), $nombreImagen);
          $producto->path_imagen = 'resources/images/productos/' . $nombreImagen;
        }
        if ($request->has('tamanyo')) {
          $producto->tamanyo = $request->tamanyo;
        }
        if ($request->has('unidad_medida')) {
          $producto->unidad_medida = $request->unidad_medida;
        }
        if ($request->has('informacion_nutricional')) {
          $producto->informacion_nutricional = $request->informacion_nutricional;
        }

        $producto->save();

        // Transformamos al JSON correspondiente
        $producto = [
          'id' => $producto->id,
          'nombre' => $producto->nombre,
          'precio' => $producto->precio,
          'stock' => $producto->stock,
          'descripcion' => $producto->descripcion,
          'oferta' => $producto->oferta,
          'informacion_nutricional' => $producto->informacion_nutricional,
          'tamanyo' => $producto->tamanyo,
          'path_imagen' => $producto->path_imagen,
          'unidad_medida' => $producto->unidad_medida
        ];

        $data = [
          'message' => 'Producto actualizado correctamente',
          'producto' => $producto,
          'status' => 200
        ];

        return response()->json($data, 200);
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

  // Función para actualizar el producto de forma parcial (stock) -- USUARIO NORMAL
  public function partialUpdate(Request $request, $id)
  {
    try {
      // Lógica para la actualización del producto de solo el stock, cuando añadimos un producto al carrito,
      // se debe actualizar el stock de ese producto: ej-> habia 500 antes de meterlo, después de meter 499
      if (self::identificacion(NombreRoles::USER->value)) {
        $producto = Producto::find($id);

        if (!$producto) {
          $data = [
            'message' => 'Producto no encontrado',
            'status' => 404
          ];

          return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(), [
          'stock' => 'integer',
        ]);

        if ($validator->fails()) {
          $data = [
            'message' => 'Error en la validación de los datos',
            'errors' => $validator->errors(),
            'status' => 400
          ];
          return response()->json($data, 400);
        }

        //Resta el stock pasado por el validator al del producto
        $producto->stock -= $request->stock;

        // Actualiza el producto
        $producto->save();

        // Transformamos al JSON correspondiente
        $producto = [
          'id' => $producto->id,
          'nombre' => $producto->nombre,
          'precio' => $producto->precio,
          'stock' => $producto->stock,
          'descripcion' => $producto->descripcion,
          'oferta' => $producto->oferta,
          'informacion_nutricional' => $producto->informacion_nutricional,
          'tamanyo' => $producto->tamanyo,
          'path_imagen' => $producto->path_imagen,
          'unidad_medida' => $producto->unidad_medida
        ];

        $data = [
          'message' => 'Stock del producto actualizado correctamente',
          'producto' => $producto,
          'status' => 200
        ];

        return response()->json($data, 200);
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

  // Función para eliminar el producto
  public function destroy($id)
  {
    try {
      if (self::identificacion(NombreRoles::ADMIN->value)) {
        $producto = Producto::find($id);

        if (!$producto) {
          $data = [
            'message' => 'Error al eliminar el producto',
            'status' => 400
          ];

          return response()->json($data, 400);
        }

        // Eliminamos todas las filas en pedido_producto relacionadas con este producto
        $pedidoProductos = PedidoProducto::where('id_producto', $id)->get();
        foreach ($pedidoProductos as $pp) {
          // Borrar cada registro usando PK compuesta
          PedidoProducto::where('id_pedido', $pp->id_pedido)
            ->where('id_producto', $pp->id_producto)
            ->delete();
        }

        // Desvinculamos de carritos y suscripciones
        $producto->carritos()->detach();
        $producto->suscripciones()->detach();

        // Eliminamos el producto
        $producto->delete();

        // Transformamos al JSON correspondiente
        $producto = [
          'id' => $producto->id,
          'nombre' => $producto->nombre,
          'precio' => $producto->precio,
          'stock' => $producto->stock,
          'descripcion' => $producto->descripcion,
          'oferta' => $producto->oferta,
          'informacion_nutricional' => $producto->informacion_nutricional,
          'tamanyo' => $producto->tamanyo,
          'path_imagen' => $producto->path_image,
          'unidad_medida' => $producto->unidad_medida
        ];

        $data = [
          'message' => 'Producto eliminado correctamente',
          'producto' => $producto,
          'status' => 200
        ];

        return response()->json($data, 200);
      }

      $data = [
        'message' => 'No tienes permisos para realizar esta acción',
        'status' => 401
      ];
      return response()->json($data, 401);
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
