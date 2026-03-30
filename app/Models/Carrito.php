<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrito extends Model
{
  use HasFactory;

  protected $table = 'carrito';

  protected $fillable = [
    'id_usuario',
    'precio_total'
  ];

  // Relaciones
  public function usuario()
  {
    return $this->belongsTo(Usuario::class, 'id_usuario', 'id');
  }

  public function carritoProductos()
  {
    return $this->hasMany(CarritoProducto::class, 'id_carrito', 'id');
  }

  public function productos()
  {
    return $this->belongsToMany(Producto::class, 'carrito_producto', 'id_carrito', 'id_producto')
                 ->withPivot('cantidad')
                 ->withTimestamps();
  }
}