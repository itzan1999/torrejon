<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CuentaActiva
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $user = auth()->user();

        if ($user && !$user->activa) {

            // Si es API
            if ($request->expectsJson()) {
                $data = [
                    'message' => 'Cuenta no activada. Para poder activar tu cuenta, debes seguir el enlace que te hemos enviado por correo electrónico.',
                    'status' => 403
                ];
                return response()->json($data, 403);
            }

            // Si es WEB
            auth()->logout();

            return redirect()->route('login')
                ->withErrors(['email' => 'Debes activar tu cuenta.']);
        }

        return $next($request);
    }
}
