<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivarCuenta extends Model
{
  use HasFactory;

  protected $table = 'activar_cuenta';
  protected $primaryKey = 'id_user';
  protected $keyType = 'string';
  public $incrementing = false;

  protected $fillable = [
    'id_user',
    'token',
    'fecha_creacion',
    'fecha_expiracion',
    'usado'
  ];

  // Relaciones
  public function usuario()
  {
    return $this->belongsTo(Usuario::class, 'id_user', 'id');
  }
}