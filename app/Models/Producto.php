<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
  use HasFactory;

  protected $table = 'producto';

  protected $casts = [
    'informacion_nutricional' => 'array'
  ];

  protected $fillable = [
    'nombre',
    'precio',
    'stock',
    'descripcion',
    'oferta',
    'informacion_nutricional',
    'tamanyo',
    'unidad_medida',
    'path_imagen'
  ];

  // Relaciones
  public function pedidoProductos()
  {
    return $this->hasMany(PedidoProducto::class, 'id_producto', 'id');
  }

  public function pedidos()
  {
    return $this->belongsToMany(Pedido::class, 'pedido_producto', 'id_producto', 'id_pedido')
                 ->withPivot('cantidad')
                 ->withTimestamps();
  }

  public function carritoProductos()
  {
    return $this->hasMany(CarritoProducto::class, 'id_producto', 'id');
  }

  public function carritos()
  {
    return $this->belongsToMany(Carrito::class, 'carrito_producto', 'id_producto', 'id_carrito')
                 ->withPivot('cantidad')
                 ->withTimestamps();
  }

  public function productoSuscripciones()
  {
    return $this->hasMany(ProductoSuscripcion::class, 'id_producto', 'id');
  }

  public function suscripciones()
  {
    return $this->belongsToMany(Suscripcion::class, 'producto_suscripcion', 'id_producto', 'id_suscripcion')
                 ->withPivot('cantidad')
                 ->withTimestamps();
  }
}