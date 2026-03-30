<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class MovimientoSaldo extends Model
{
  use HasFactory;
  use HasUuids;

  protected $table = 'movimiento_saldo';
  protected $keyType = 'string';
  public $incrementing = false;

  protected $fillable = [
    'id',
    'id_usuario',
    'fecha',
    'importe',
    'tipo',
    'descripcion'
  ];

  // Relaciones
  public function usuario()
  {
    return $this->belongsTo(Usuario::class, 'id_usuario', 'id');
  }
}