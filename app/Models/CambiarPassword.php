<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class CambiarPassword extends Model
{
  use HasFactory;
  use SoftDeletes;

  protected $table = 'cambiar_password';
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
