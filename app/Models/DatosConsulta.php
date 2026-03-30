<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatosConsulta extends Model
{
    use HasFactory;

    protected $table = 'datos_consultas';

    protected $fillable = [
        'nombre',
        'email',
        'telefono',
        'consulta'
    ];
}