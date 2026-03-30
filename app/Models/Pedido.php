<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
  use HasFactory;
  use HasUuids;

  protected $table = 'pedido';
  protected $keyType = 'string';
  public $incrementing = false;

  protected $fillable = [
    'id',
    'codigo',
    'id_usuario',
    'estado',
    'suscripcion',
    'precio_total'
  ];

  // Relaciones
  public function usuario()
  {
    return $this->belongsTo(Usuario::class, 'id_usuario', 'id');
  }

  public function pedidoProductos()
  {
    return $this->hasMany(PedidoProducto::class, 'id_pedido', 'id');
  }

  public function productos()
  {
    return $this->belongsToMany(Producto::class, 'pedido_producto', 'id_pedido', 'id_producto')
                 ->withPivot('cantidad')
                 ->withTimestamps();
  }
}