<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuarioRol extends Model
{
  use HasFactory;

  protected $table = 'usuario_rol';
  protected $primaryKey = ['id_usuario', 'id_rol'];
  public $incrementing = false;
  protected $keyType = 'string';

  protected $fillable = ['id_usuario','id_rol'];

  // Relaciones
  public function rol()
  {
    return $this->belongsTo(Rol::class, 'id_rol', 'id');
  }

  public function usuario()
  {
    return $this->belongsTo(Usuario::class, 'id_usuario', 'id');
  }
}