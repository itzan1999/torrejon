<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
  use HasFactory;

  protected $table = 'permiso';

  protected $fillable = ['desc_permiso'];

  // Relaciones
  public function roles()
  {
    return $this->belongsToMany(Rol::class, 'rol_permiso', 'id_permiso', 'id_rol');
  }
}