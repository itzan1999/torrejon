<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarritoProducto extends Model
{
  use HasFactory;

  protected $table = 'carrito_producto';
  protected $primaryKey = ['id_carrito', 'id_producto'];
  public $incrementing = false;

  protected $fillable = [
    'id_carrito',
    'id_producto',
    'cantidad'
  ];

  // Relaciones
  public function carrito()
  {
    return $this->belongsTo(Carrito::class, 'id_carrito', 'id');
  }

  public function producto()
  {
    return $this->belongsTo(Producto::class, 'id_producto', 'id');
  }
}