<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Cuenta extends Authenticatable
{
  use HasFactory;
  use HasApiTokens;

  protected $table = 'cuenta';
  protected $primaryKey = 'id_user';
  protected $keyType = 'string';
  public $incrementing = false;

  protected $fillable = [
    'id_user',
    'nombre',
    'apellidos',
    'password',
    'activa',
    'fecha_alta'
  ];

  // Relaciones
  public function usuario()
  {
    return $this->belongsTo(Usuario::class, 'id_user', 'id');
  }
}
