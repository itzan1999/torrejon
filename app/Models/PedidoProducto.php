<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PedidoProducto extends Model
{
  use HasFactory;

  protected $table = 'pedido_producto';
  protected $primaryKey = ['id_pedido', 'id_producto'];
  public $incrementing = false;
  protected $keyType = 'string';

  protected $fillable = ['id_pedido','id_producto','cantidad'];

  // Relaciones
  public function pedido()
  {
    return $this->belongsTo(Pedido::class, 'id_pedido', 'id');
  }

  public function producto()
  {
    return $this->belongsTo(Producto::class, 'id_producto', 'id');
  }
}