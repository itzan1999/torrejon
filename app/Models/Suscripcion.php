<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Suscripcion extends Model
{
  use HasFactory;
  use HasUuids;

  protected $table = 'suscripcion';

  protected $keyType = 'string';
  public $incrementing = false;

  protected $fillable = ['id','id_usuario','tipo','fecha_inicio'];

  // Relaciones
  public function usuario()
  {
    return $this->belongsTo(Usuario::class, 'id_usuario', 'id');
  }

  public function productoSuscripciones()
  {
    return $this->hasMany(ProductoSuscripcion::class, 'id_suscripcion', 'id');
  }

  public function productos()
  {
    return $this->belongsToMany(Producto::class, 'producto_suscripcion', 'id_suscripcion', 'id_producto')
                 ->withPivot('cantidad')
                 ->withTimestamps();
  }
}