<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use App\Models\Usuario;
use App\Models\Permiso;
use App\Models\Carrito;
use App\Models\ActivarCuenta;
use App\Models\CambiarPassword;
use App\Models\Cuenta;
use App\Models\UsuarioRol;
use App\Models\Rol;

use App\Enums\NombreRoles;

use App\Mail\MailActivarCuenta;
use App\Mail\MailCambiarPassword;
use Illuminate\Validation\Rules\Password;

class AuthController
{
  // Función para registarse 
  public function register(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'username' => 'required|string|unique:usuario,username',
      'nombre' => 'required|string',
      'apellidos' => 'required|string',
      'email' => 'required|email|unique:usuario,email',
      'password' => [
        'required',
        'confirmed',
        'string',
        Password::min(8)
          ->letters()
          ->mixedCase()
          ->numbers()
          ->symbols()
      ],
      'direccion' => 'required|string'
    ]);

    if ($validator->fails()) {
      $data = [
        'message' => 'Error de validación de los datos',
        'errors' => $validator->errors(),
        'status' => 422
      ];
      return response()->json($data, 422);
    }

    // Usamos transacciones para que se cree todo a la vez, si no se crea a la vez retrocede
    DB::beginTransaction();

    try {
      // Crear el usuario
      $usuario = Usuario::create([
        'username' => strtolower($request->username),
        'email' => $request->email,
        'saldo' => 0.0,
        'direccion' => $request->direccion
      ]);

      // Crear la cuenta asociada al usuario
      Cuenta::create([
        'id_user' => $usuario->id,
        'nombre' => $request->nombre,
        'apellidos' => $request->apellidos,
        'password' => bcrypt($request->password),
        'activa' => false
      ]);

      // Crear registro de activación de cuenta
      $token = Str::random(32);
      $tokenHash = Hash::make($token);

      ActivarCuenta::create([
        'id_user' => $usuario->id,
        'token' => $tokenHash,
        'fecha_creacion' => now(),
        'fecha_expiracion' => now()->addHours(24),
        'usado' => false
      ]);

      // Enviar email de activación
      $nombreCompleto = $request->nombre . ' ' . $request->apellidos;
      Mail::send(new MailActivarCuenta($nombreCompleto, $token, $request->email));

      // Crear Carrito asociado al usuario
      Carrito::create([
        'id_usuario' => $usuario->id,
        'precio_total' => 0.0
      ]);

      // Asignar rol por defecto
      $idUserRol = Rol::where('nombre_rol', NombreRoles::USER->value)->first()->id;
      UsuarioRol::create([
        'id_usuario' => $usuario->id,
        'id_rol' => $idUserRol
      ]);

      $data = [
        'message' => 'Usuario registrado correctamente. Por favor, activa tu cuenta mediante el enlace enviado a tu correo electrónico.',
        'status' => 201
      ];

      DB::commit();
      return response()->json($data, 201);
    } catch (\Throwable $e) {

      DB::rollBack();

      $data = [
        'message' => 'Error al registrar el usuario',
        'error' => $e->getMessage(),
        'status' => 500
      ];

      return response()->json($data, 500);
    }
  }

  // Función para activar la cuenta
  public function activateAccount($token)
  {

    if (!$token) {
      $data = [
        'message' => 'Token de activación requerido',
        'status' => 400
      ];
      return response()->json($data, 400);
    }

    $activacion = ActivarCuenta::where('fecha_expiracion', '>', now())->where('usado', false)->get();

    if ($activacion->isEmpty()) {
      $data = [
        'message' => 'No hay ninguna solicitud de activación pendiente',
        'status' => 400
      ];
      return response()->json($data, 400);
    }

    $activacionEncontrada = null;

    foreach ($activacion as $act) {
      if (Hash::check($token, $act->token)) {
        $activacionEncontrada = $act;
        break;
      }
    }

    if (!$activacionEncontrada) {
      $data = [
        'message' => 'Token de activación inválido o expirado',
        'status' => 400
      ];
      return response()->json($data, 400);
    }

    $cuenta = Cuenta::where('id_user', $activacionEncontrada->id_user)->first();

    // Debug: verificar si existe la cuenta
    if (!$cuenta) {
      $data = [
        'message' => 'Cuenta no encontrada para este usuario',
        'id_user_buscado' => $activacionEncontrada->id_user,
        'status' => 400
      ];
      return response()->json($data, 400);
    }

    DB::beginTransaction();

    try {

      // Actualizar la cuenta
      $cuenta->activa = true;
      $cuenta->save();

      // Actualizar el registro de activación
      $activacionEncontrada->usado = true;
      $activacionEncontrada->save();

      $data = [
        'message' => 'Cuenta activada correctamente',
        'status' => 200
      ];

      DB::commit();
      return response()->json($data, 200);
    } catch (\Throwable $e) {

      DB::rollBack();

      $data = [
        'message' => 'Error al activar la cuenta',
        'status' => 500
      ];

      return response()->json($data, 500);
    }
  }

  // Función para crear el reset del token
  public function createPasswordResetToken(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'email' => 'required|email|exists:usuario,email'
    ]);

    if ($validator->fails()) {
      $data = [
        'message' => 'Error de validación de los datos',
        'errors' => $validator->errors(),
        'status' => 400
      ];
      return response()->json($data, 400);
    }

    DB::beginTransaction();
    try {

      // Generar token de restablecimiento de contraseña
      $token = Str::random(32);
      $tokenHash = Hash::make($token);

      $usuario = Usuario::where('email', $request->email)->first();
      $registroExistente = CambiarPassword::where('id_user', $usuario->id)->first();

      if ($registroExistente) {
        CambiarPassword::where('id_user', $usuario->id)->where('usado', false)->delete();
      }

      CambiarPassword::create([
        'id_user' => $usuario->id,
        'token' => $tokenHash,
        'fecha_creacion' => now(),
        'fecha_expiracion' => now()->addHours(24),
        'usado' => false
      ]);

      DB::commit();
    } catch (\Throwable $e) {

      DB::rollBack();

      $data = [
        'message' => 'Error al generar el token',
        'error' => $e->getMessage(),
        'status' => 500
      ];
      return response()->json($data, 500);
    }
    // Enviar email con el token de restablecimiento
    $cuenta = Cuenta::where('id_user', $usuario->id)->first();

    if (!$cuenta) {
      $data = [
        'message' => 'Cuenta no encontrada pa este usuario',
        'status' => 400
      ];
      return response()->json($data, 400);
    }

    $nombreCompleto = $cuenta->nombre . ' ' . $cuenta->apellidos;
    Mail::send(new MailCambiarPassword($nombreCompleto, $token, $usuario->email));

    $data = [
      'message' => 'Email de restablecimiento enviado',
      'status' => 200
    ];

    return response()->json($data, 200);
  }

  // Función para restablecer la contraseña
  public function changePassword(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'token' => 'required|string',
      'password' => 'required|string|min:6|confirmed'
    ]);

    if ($validator->fails()) {
      $data = [
        'message' => 'Error de validación de los datos',
        'errors' => $validator->errors(),
        'status' => 400
      ];
      return response()->json($data, 400);
    }

    // Obtener todos los cambios pendientes
    $cambios = CambiarPassword::where('fecha_expiracion', '>', now())
      ->where('usado', false)
      ->get();

    if ($cambios->isEmpty()) {
      $data = [
        'message' => 'No hay solicitudes de cambio de contraseña pendientes',
        'status' => 400
      ];
      return response()->json($data, 400);
    }

    // Buscar el token correcto
    $cambioEncontrado = null;

    foreach ($cambios as $cambio) {
      if (Hash::check($request->token, $cambio->token)) {
        $cambioEncontrado = $cambio;
        break;
      }
    }

    if (!$cambioEncontrado) {
      $data = [
        'message' => 'Token de cambio de contraseña inválido o expirado',
        'status' => 400
      ];
      return response()->json($data, 400);
    }

    // Obtener y actualizar la cuenta
    $cuenta = Cuenta::where('id_user', $cambioEncontrado->id_user)->first();

    if (!$cuenta) {
      $data = [
        'message' => 'Cuenta no encontrada para este usuario',
        'status' => 400
      ];
      return response()->json($data, 400);
    }

    DB::beginTransaction();
    try {
      // Actualizar la contraseña
      $cuenta->password = bcrypt($request->password);
      $cuenta->save();

      // Marcar como usado
      $cambioEncontrado->usado = true;
      $cambioEncontrado->save();

      $data = [
        'message' => 'Contraseña cambiada correctamente',
        'status' => 200
      ];

      DB::commit();

      return response()->json($data, 200);
    } catch (\Throwable $e) {
      DB::rollBack();
      $data = [
        'message' => 'Error al cambiar la contraseña',
        'status' => 500
      ];
      return response()->json($data, 500);
    }
  }
}
