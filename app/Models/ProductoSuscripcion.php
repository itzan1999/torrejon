<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductoSuscripcion extends Model
{
  use HasFactory;

  protected $table = 'producto_suscripcion';
  protected $primaryKey = ['id_producto', 'id_suscripcion'];
  public $incrementing = false;
  protected $keyType = 'string';

  protected $fillable = ['id_producto','id_suscripcion','cantidad'];

  // Relaciones
  public function producto()
  {
    return $this->belongsTo(Producto::class, 'id_producto', 'id');
  }

  public function suscripcion()
  {
    return $this->belongsTo(Suscripcion::class, 'id_suscripcion', 'id');
  }
}