<?php

namespace App\Http\Controllers\Api;

use App\Models\Carrito;
use App\Models\CarritoProducto;
use App\Models\Producto;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class CarritoController
{
  // Función para obtener el carrito del usuario autenticado
  public function show()
  {
    try {
      $userId = auth()->user()->id_user; // Obtener ID del usuario autenticado

      $carrito = Carrito::where('id_usuario', $userId)->first();

      if (!$carrito) {
        $data = [
          'message' => 'Error al obtener el carrito',
          'status' => 404
        ];

        return response()->json($data, 404);
      }

      $productosCarrito = CarritoProducto::where('id_carrito', $carrito->id)->get();
      $productos_detalle = [];
      $precio_total = 0;

      foreach ($productosCarrito as $productoCarrito) {
        $productos_detalle[] = [
          'idProducto' => $productoCarrito->id_producto,
          'nombre' => $productoCarrito->producto->nombre,
          'precio' => number_format($productoCarrito->producto->precio, 2),
          'oferta' => $productoCarrito->producto->oferta,
          'path_imagen' => $productoCarrito->producto->path_imagen,
          'cantidad' => $productoCarrito->cantidad
        ];

        $precio_total += $productoCarrito->cantidad * (1 - $productoCarrito->producto->oferta / 100) * $productoCarrito->producto->precio;
      }

      $precio_total = number_format($precio_total, 2);

      $carrito->precio_total = $precio_total;
      $carrito->save();

      $data = [
        'id' => $carrito->id,
        'precio_total' => $precio_total,
        'productosCarrito' => $productos_detalle
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

  // Almacena un productoCarrito en el carrito
  public function store(Request $request)
  {
    try {
      $validator = Validator::make($request->all(), [
        'idProducto' => 'required|integer|exists:producto,id',
        'cantidad' => 'required|integer|min:1'
      ]);

      if ($validator->fails()) {
        $data = [
          'message' => 'Error de validación de los datos',
          'errors' => $validator->errors(),
          'status' => 400
        ];

        return response()->json($data, 400);
      }

      $userId = auth()->user()->id_user; // Obtener ID del usuario autenticado
      $carrito = Carrito::where('id_usuario', $userId)->first();
      $carrito_id = $carrito->id;

      $carrito_producto = CarritoProducto::where('id_carrito', $carrito_id)
        ->where('id_producto', $request->input('idProducto'))
        ->first();

      if ($carrito_producto) {
        $data = [
          'message' => 'El productoCarrito ya está en el carrito',
          'status' => 409
        ];

        return response()->json($data, 409);
      } else {
        $carrito_producto = new CarritoProducto();
        $carrito_producto->id_carrito = $carrito_id;
        $carrito_producto->id_producto = $request->input('idProducto');
        $carrito_producto->cantidad = $request->input('cantidad');
        $carrito_producto->save();
      }

      $data = [
        'message' => 'Producto añadido al carrito correctamente',
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

  // Modificar la cantidad de un productoCarrito en el carrito
  public function update(Request $request, $id)
  {
    try {
      $validator = Validator::make($request->all(), [
        'cantidad' => 'required|integer|min:1'
      ]);

      if ($validator->fails()) {
        $data = [
          'message' => 'Error de validación de los datos',
          'errors' => $validator->errors(),
          'status' => 400
        ];

        return response()->json($data, 400);
      }

      $userId = auth()->user()->id_user; // Obtener ID del usuario autenticado
      $carrito = Carrito::where('id_usuario', $userId)->first();
      $carrito_id = $carrito->id;

      $carrito_producto = CarritoProducto::where('id_carrito', $carrito_id)
        ->where('id_producto', $id)
        ->first();

      if (!$carrito_producto) {
        $data = [
          'message' => 'El productoCarrito no está en el carrito',
          'status' => 404
        ];

        return response()->json($data, 404);
      }

      CarritoProducto::where('id_carrito', $carrito->id)
        ->where('id_producto', $id)
        ->update([
          'cantidad' => $request->input('cantidad')
        ]);

      $data = [
        'message' => 'Cantidad del productoCarrito actualizada correctamente',
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

  // Eliminar un productoCarrito del carrito
  public function destroy($id)
  {
    try {
      $userId = auth()->user()->id_user; // Obtener ID del usuario autenticado
      $carrito = Carrito::where('id_usuario', $userId)->first();
      $carrito_id = $carrito->id;

      $carrito_producto = CarritoProducto::where('id_carrito', $carrito_id)
        ->where('id_producto', $id)
        ->first();

      if (!$carrito_producto) {
        $data = [
          'message' => 'El productoCarrito no está en el carrito',
          'status' => 404
        ];

        return response()->json($data, 404);
      }

      CarritoProducto::where('id_carrito', $carrito->id)
        ->where('id_producto', $id)
        ->delete();

      $data = [
        'message' => 'Producto eliminado del carrito correctamente',
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

  // Eliminar todos los productos del carrito del usuari
  public function destroyAll()
  {
    try {
      $userId = auth()->user()->id_user; // Obtener ID del usuario autenticado
      $carrito = Carrito::where('id_usuario', $userId)->first();

      if (!$carrito) {
        $data = [
          'message' => 'Carrito del usuario no encontrado',
          'status' => 404
        ];

        return response()->json($data, 404);
      }

      // Elimina los productos del carrito del usuario autenticado
      $carrito->productos()->detach();

      $data = [
        'message' => 'Productos eliminado del carrito correctamente',
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
}
