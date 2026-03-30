<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, $rol)
    {
        $cuenta = auth()->user(); // Esto devuelve la Cuenta

        if (!$cuenta) {
            abort(403, 'No autorizado');
        }

        // Accedemos al usuario relacionado
        $usuario = $cuenta->usuario; // usa la relación que tienes en el modelo Cuenta

        if (!$usuario) {
            abort(403, 'No autorizado');
        }

        // Obtenemos todos los roles del usuario
        $roles = $usuario->roles->pluck('nombre_rol')->toArray();


        if (!in_array($rol, $roles)) {
            abort(403, 'No tienes permiso para acceder a esta sección');
        }

        return $next($request);
    }
}
