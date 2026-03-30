<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use App\Models\Cuenta;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController
{
  /*
    |--------------------------------------------------------------------------
    | FUNCIÓN PARA LOGIN
    |--------------------------------------------------------------------------
  */
  // Función para verificar el login y crear la sesión
  public function login(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'username' => 'required|string|exists:usuario,username',
      'password' => 'required|string'
    ]);

    if ($validator->fails()) {
      return redirect()
        ->route('login')
        ->withErrors(['login' => 'Usuario o contraseña incorrectos'])
        ->withInput();
    }

    $cuenta = Cuenta::whereHas('usuario', function ($q) use ($request) {
      $q->where('username', $request->username);
    })->first();

    if (!$cuenta || !Hash::check($request->password, $cuenta->password)) {
      return redirect()
        ->route('login')
        ->withErrors(['login' => 'Usuario o contraseña incorrectos'])
        ->withInput();
    }

    if (!$cuenta->activa) {
      return redirect()
        ->route('login')
        ->withErrors(['login' => 'Debes activar tu cuenta antes de iniciar sesión.'])
        ->withInput();
    }

    Auth::guard('web')->login($cuenta);
    $request->session()->regenerate();

    $esAdmin = $cuenta->usuario->roles()->where('nombre_rol', 'admin')->exists();
    if ($esAdmin) {
      return redirect()->route('pages.Dashboard.Admin.panelAdmin');
    }
    return redirect()->route('pages.tienda');
  }

  /*
    |--------------------------------------------------------------------------
    | FUNCIONES PARA LA VISTA DE registro y activarCuenta
    |--------------------------------------------------------------------------
  */
  // Función para devolver la vista del registro
  public function register()
  {
    return view('pages.Auth.register');
  }

  // Función para devolver la vista de activar la cuenta en función del token
  public function activarCuenta(Request $request)
  {
    $token = $request->query('token');
    if (!$token) {
      return redirect()->route('pages.index');
    }
    return view('pages.Auth.activarCuenta');
  }


  /*
    |--------------------------------------------------------------------------
    | FUNCIONES PARA RESTABLECER LA CONTRASEÑA
    |--------------------------------------------------------------------------
  */
  // Función para solicitar cambio de contraseña
  public function solicitarCambiarPassword()
  {
    return view('pages.Auth.solicitarCambioPassword');
  }

  // Función para devolver vista cambio de contraseña
  public function cambiarPassword(Request $request)
  {
    $token = $request->query('token');
    if (!$token) {
      return redirect()->route('pages.index');
    }
    return view('pages.Auth.cambiarPassword');
  }

  /*
    |--------------------------------------------------------------------------
    | FUNCIONES PARA DESLOGUEARSE
    |--------------------------------------------------------------------------
  */
  // Función para cerrar sesión
  public function logout(Request $request)
  {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('pages.index');
  }
}
