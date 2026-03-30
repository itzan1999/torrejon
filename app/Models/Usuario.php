<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class Usuario extends Model
{
  use HasFactory;
  use HasUuids;
  use HasApiTokens;
  use SoftDeletes;

  protected $keyType = 'string';
  public $incrementing = false;

  protected $table = 'usuario';

  protected $fillable = ['id', 'username', 'email', 'saldo', 'direccion'];

  // Relaciones
  public function cuenta()
  {
    return $this->hasOne(Cuenta::class, 'id_user', 'id');
  }

  public function pedidos()
  {
    return $this->hasMany(Pedido::class, 'id_usuario', 'id');
  }

  public function carrito()
  {
    return $this->hasOne(Carrito::class, 'id_usuario', 'id');
  }

  public function movimientosSaldo()
  {
    return $this->hasMany(MovimientoSaldo::class, 'id_usuario', 'id');
  }

  public function usuarioRoles()
  {
    return $this->hasMany(UsuarioRol::class, 'id_usuario', 'id');
  }

  public function roles()
  {
    return $this->belongsToMany(Rol::class, 'usuario_rol', 'id_usuario', 'id_rol');
  }

  public function suscripciones()
  {
    return $this->hasMany(Suscripcion::class, 'id_usuario', 'id');
  }

  public function activarCuenta()
  {
    return $this->hasOne(ActivarCuenta::class, 'id_user', 'id');
  }

  public function cambiarPassword()
  {
    return $this->hasOne(CambiarPassword::class, 'id_user', 'id');
  }
}