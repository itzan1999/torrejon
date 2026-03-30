<?php

namespace App\Http\Controllers\Api;

use App\Models\Permiso;
use App\Models\RolPermiso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\UsuarioRol;
use App\Models\Rol;

use App\Enums\NombreRoles;

class PermisoController
{
    /*---- ESTE CONTROLADOR SOLO PUEDEN ACCEDER LOS ADMINISTRADORES ----*/

    // Función estática para comprobar le rol del usuario
    public static function identificacion($rolComprobar)
    {
        $usuarioAuth = auth()->user();
        $idRol = UsuarioRol::where('id_usuario', $usuarioAuth->id_user)->first()->id_rol;
        $rol = Rol::where('id', $idRol)->first()->nombre_rol;

        return $rol == $rolComprobar;
    }

    // Función para obtener todos los permisos (admin y según el usuario autenticado)
    public function index()
    {
        try {
            if (self::identificacion(NombreRoles::ADMIN->value)) {
                $permisos =  Permiso::all();

                if (!$permisos) {
                    $data = [
                        'message' => "Error en la obtención de los permisos",
                        'status' => 500
                    ];
                    return response()->json($data, 500);
                }

                $permisos = $permisos->map(function ($permiso) {
                    return [
                        'id' => $permiso->id,
                        'desc_permiso' => $permiso->desc_permiso
                    ];
                });

                $data = [
                    'message' => 'Permisos obtenidos correctamente',
                    'permisos' => $permisos,
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

    // Función para obtener el permiso según el id (solo admin)
    public function show($id)
    {
        try {
            if (self::identificacion(NombreRoles::ADMIN->value)) {
                $permiso = Permiso::find($id);

                if (!$permiso) {
                    $data = [
                        'message' => "Permiso no encontrado",
                        'status' => 404
                    ];
                    return response()->json($data, 404);
                }

                $permiso =  [
                    'id' => $permiso->id,
                    'desc_permiso' => $permiso->desc_permiso
                ];

                $data = [
                    'message' => 'Permiso obtenido correctamente',
                    'permisos' => $permiso,
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

    // Función para crear un permiso (solo administrador)
    public function store(Request $request)
    {
        try {
            if (self::identificacion(NombreRoles::ADMIN->value)) {
                $validator = Validator::make($request->all(), [
                    'desc_permiso' => 'required|string'
                ]);

                if ($validator->fails()) {
                    $data = [
                        'message' => 'Error en la validación de los datos',
                        'errors' => $validator->errors(),
                        'status' => 400
                    ];
                    return response()->json($data, 400);
                }

                $permiso = Permiso::create([
                    'desc_permiso' => $request->desc_permiso
                ]);

                if (!$permiso) {
                    $data = [
                        'message' => 'Error al crear el permiso',
                        'status' => 500
                    ];
                    return response()->json($data, 500);
                }

                // Transformamos el JSON correspondiente
                $permiso = [
                    'id' => $permiso->id,
                    'desc_permiso' => $permiso->desc_permiso
                ];

                $data = [
                    'message' => 'Permiso creado correctamente',
                    'permiso' => $permiso,
                    'status' => 201
                ];

                return response()->json($data, 201);
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

    // Función para actualizr un permiso según el id (solo administrador)
    public function update(Request $request, $id)
    {
        try {
            if (self::identificacion(NombreRoles::ADMIN->value)) {

                $permiso = Permiso::find($id);

                if (!$permiso) {
                    $data = [
                        'message' => 'Error en la obtención del permiso',
                        'status' => 404
                    ];
                    return response()->json($data, 404);
                }

                $validator = Validator::make($request->all(), [
                    'desc_permiso' => 'required|string'
                ]);

                if ($validator->fails()) {
                    $data = [
                        'message' => 'Error en la validación de los datos',
                        'errors' => $validator->errors(),
                        'status' => 400
                    ];
                    return response()->json($data, 400);
                }

                $permiso->desc_permiso = $request->desc_permiso;

                $permiso->save();

                // Transformamos el JSON correspondiente
                $permiso = [
                    'id' => $permiso->id,
                    'desc_permiso' => $permiso->desc_permiso
                ];

                $data = [
                    'message' => 'Permiso actualizado correctamente',
                    'permiso' => $permiso,
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


    // Función para eliminar un permiso según el id (solo administrador)
    public function destroy($id)
    {
        try {
            if (self::identificacion(NombreRoles::ADMIN->value)) {
                $permiso = Permiso::find($id);

                if (!$permiso) {
                    $data = [
                        'message' => 'Error en el borrado del permiso',
                        'status' => 400
                    ];
                    return response()->json($data, 400);
                }

                $permiso->roles()->detach();
                $permiso->delete();

                // Transformamos el JSON correspondiente
                $permiso = [
                    'id' => $permiso->id,
                    'desc_permiso' => $permiso->desc_permiso
                ];

                $data = [
                    'message' => 'Permiso eliminado correctamente',
                    'permiso' => $permiso,
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
