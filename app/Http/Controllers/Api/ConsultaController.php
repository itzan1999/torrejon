<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\DatosConsulta;
use App\Models\UsuarioRol;
use App\Models\Rol;
use App\Enums\NombreRoles;

class ConsultaController
{
    // Función estática para comprobar le rol del usuario
    public static function identificacion($rolComprobar)
    {
        $usuarioAuth = auth()->user();
        $idRol = UsuarioRol::where('id_usuario', $usuarioAuth->id_user)->first()->id_rol;
        $rol = Rol::where('id', $idRol)->first()->nombre_rol;

        return $rol == $rolComprobar;
    }

    // Función para obtener todas la consultas
    public function index()
    {
        try {
            if (self::identificacion(NombreRoles::ADMIN->value)) {
                $consultas = DatosConsulta::all();

                if (!$consultas) {
                    $data = [
                        'message' => 'Error al obtener las consultas',
                        'status' => 500
                    ];
                    return response()->json($data, 500);
                }

                // Transformamos al JSON adecuado para devolver las consultas
                $consultas = $consultas->map(function ($consulta) {
                    return [
                        'id' => $consulta->id,
                        'nombre' => $consulta->nombre,
                        'email' => $consulta->email,
                        'telefono' => $consulta->telefono,
                        'consulta' => $consulta->consulta,
                        'estado' => $consulta->estado
                    ];
                });

                $data = [
                    'message' => 'Consultas obtenidas correctamente',
                    'consultas' => $consultas,
                    'status' => 200
                ];
                return response()->json($data, 200);
            }

            $data = [
                'message' => 'No tienes permisos para realizar esta acción',
                'status' => 401
            ];
            return response()->json($data, 401);
        } catch (\Throwable $e) {
            $data = [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ];
            return response()->json($data, 500);
        }
    }

    // Función para obtener una consulta por su id
    public function show($id)
    {
        try {
            if (self::identificacion(NombreRoles::ADMIN->value)) {
                $consulta = DatosConsulta::find($id);

                if (!$consulta) {
                    $data = [
                        'message' => 'Error al obtener la consulta',
                        'status' => 404
                    ];
                    return response()->json($data, 404);
                }

                // Transformamos al JSON adecuado para devolver la consulta
                $consulta = [
                    'id' => $consulta->id,
                    'nombre' => $consulta->nombre,
                    'email' => $consulta->email,
                    'telefono' => $consulta->telefono,
                    'consulta' => $consulta->consulta,
                    'estado' => $consulta->estado
                ];

                $data = [
                    'message' => 'Consulta obtenida correctamente',
                    'consulta' => $consulta,
                    'status' => 200
                ];
                return response()->json($data, 200);
            }

            $data = [
                'message' => 'No tienes permisos para realizar esta acción',
                'status' => 401
            ];
            return response()->json($data, 401);
        } catch (\Throwable $e) {
            $data = [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ];
            return response()->json($data, 500);
        }
    }

    // Función para crear una consulta, esta función es pública
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nombre' => ['required', 'string', 'min:2', 'regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/'],
                'email' => 'required|string|email:rfc|max:255',
                'telefono' => ['required', 'string', 'regex:/^(\+\d{1,3})?[6789]\d{8}$/'],
                'consulta' => 'required|string|min:5'
            ]);


            if ($validator->fails()) {
                $data = [
                    'message' => 'Error en la validación de los datos',
                    'errors' => $validator->errors(),
                    'status' => 400
                ];
                return response()->json($data, 400);
            }

            $consulta = DatosConsulta::create([
                'nombre' => $request->nombre,
                'email' => $request->email,
                'telefono' => $request->telefono,
                'consulta' => $request->consulta
            ]);

            if (!$consulta) {
                $data = [
                    'message' => 'Error al crear la consulta',
                    'status' => 500
                ];
                return response()->json($data, 500);
            }

            // Transformamos al JSON adecuado para devolver la consulta creada
            $consulta = [
                'id' => $consulta->id,
                'nombre' => $consulta->nombre,
                'email' => $consulta->email,
                'telefono' => $consulta->telefono,
                'consulta' => $consulta->consulta,
            ];

            $data = [
                'message' => 'Consulta creada correctamente',
                'consulta' => $consulta,
                'status' => 200
            ];
            return response()->json($data, 200);
        } catch (\Throwable $e) {
            $data = [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ];
            return response()->json($data, 500);
        }
    }

    public function statusUpdate(Request $request, $id)
    {
        try {
            if (self::identificacion(NombreRoles::ADMIN->value)) {
                $validator = Validator::make($request->all(), [
                    'estado' => ['required', 'string', Rule::in(['pendiente', 'en proceso', 'resuelta'])]
                ]);

                if ($validator->fails()) {
                    $data = [
                        'message' => 'Error en la validación de los datos',
                        'errors' => $validator->errors(),
                        'status' => 400
                    ];
                    return response()->json($data, 400);
                }

                $consulta = DatosConsulta::find($id);

                if (!$consulta) {
                    $data = [
                        'message' => 'Error al obtener la consulta',
                        'status' => 404
                    ];
                    return response()->json($data, 404);
                }

                $consulta->estado = $request->estado;
                $consulta->save();

                // Transformamos al JSON adecuado para devolver la consulta actualizada
                $consulta = [
                    'id' => $consulta->id,
                    'nombre' => $consulta->nombre,
                    'email' => $consulta->email,
                    'telefono' => $consulta->telefono,
                    'consulta' => $consulta->consulta,
                    'estado' => $consulta->estado
                ];

                $data = [
                    'message' => 'Estado de la consulta actualizado correctamente',
                    'consulta' => $consulta,
                    'status' => 200
                ];
                return response()->json($data, 200);
            }

            $data = [
                'message' => 'No tienes permisos para realizar esta acción',
                'status' => 401
            ];
            return response()->json($data, 401);
        } catch (\Throwable $e) {
            $data = [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ];
            return response()->json($data, 500);
        }
    }

    // Función para eliminar una consulta, solo puede ser usada por el administrador
    public function destroy($id)
    {
        try {
            if (self::identificacion(NombreRoles::ADMIN->value)) {
                $consulta = DatosConsulta::find($id);

                if (!$consulta) {
                    $data = [
                        'message' => 'Error al eliminar la consulta',
                        'status' => 400
                    ];
                    return response()->json($data, 400);
                }
                // Eliminamos la consulta
                $consulta->delete();

                // Transformamos al JSON adecuado para devolver la consulta eliminada
                $consulta = [
                    'id' => $consulta->id,
                    'nombre' => $consulta->nombre,
                    'email' => $consulta->email,
                    'telefono' => $consulta->telefono,
                    'consulta' => $consulta->consulta,
                ];

                $data = [
                    'message' => 'Consulta eliminada correctamente',
                    'consulta' => $consulta,
                    'status' => 200
                ];
                return response()->json($data, 200);
            }

            $data = [
                'message' => 'No tienes permisos para realizar esta acción',
                'status' => 401
            ];
            return response()->json($data, 401);
        } catch (\Throwable $e) {
            $data = [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ];
            return response()->json($data, 500);
        }
    }
}