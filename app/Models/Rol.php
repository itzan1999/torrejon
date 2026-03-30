<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
  use HasFactory;

  protected $table = 'rol';

  protected $fillable = ['nombre_rol'];

  // Relaciones
  public function usuarioRoles()
  {
    return $this->hasMany(UsuarioRol::class, 'id_rol', 'id');
  }

  public function usuarios()
  {
    return $this->belongsToMany(Usuario::class, 'usuario_rol', 'id_rol', 'id_usuario');
  }

  public function permisos()
  {
    return $this->belongsToMany(Permiso::class, 'rol_permiso', 'id_rol', 'id_permiso');
  }
}