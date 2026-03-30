<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Validate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Illuminate\Validation\Rule;

// Modelos
use App\Models\Rol;
use App\Models\RolPermiso;
use App\Models\Permiso;
use App\Models\UsuarioRol;

use App\Enums\NombreRoles;

class RolController
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

    //Función que devuelve todos los roles
    public function index()
    {
        try {
            if (self::identificacion(NombreRoles::ADMIN->value)) {
                $roles = Rol::all();

                if (!$roles) {
                    $data = [
                        'message' => 'Error al obtener los roles',
                        'status' => 500
                    ];
                    return response()->json($data, 500);
                }

                // Transformados al JSON correspondiente
                $roles = $roles->map(function ($rol) {
                    return [
                        'id' => $rol->id,
                        'nombre_rol' => $rol->nombre_rol
                    ];
                });


                $data = [
                    'message' => 'Roles obtenidos correctamente',
                    'roles' => $roles,
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

    // Función para crear el rol
    public function store(Request $request)
    {
        try {
            if (self::identificacion(NombreRoles::ADMIN->value)) {
                $validator = Validator::make($request->all(), [
                    'nombre_rol' => ['required', 'string', 'unique:rol,nombre_rol', Rule::enum(NombreRoles::class)]
                ]);

                if ($validator->fails()) {
                    $data = [
                        'message' => 'Error de validación de los datos',
                        'errors' => $validator->errors(),
                        'status' => 400
                    ];
                    return response()->json($data, 400);
                }

                $rol = Rol::create([
                    'nombre_rol' => $request->nombre_rol
                ]);

                if (!$rol) {
                    $data = [
                        'message' => 'Error al crear el rol',
                        'status' => 500
                    ];
                    return response()->json($data, 500);
                }

                // Transformado al JSON correspondiente
                $rol = [
                    'id' => $rol->id,
                    'nombre_rol' => $rol->nombre_rol
                ];

                $data = [
                    'message' => 'Rol creado correctamente',
                    'rol' => $rol,
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

    // Función para actualizar el rol pasandole un id
    public function update(Request $request, $id)
    {
        try {
            if (self::identificacion(NombreRoles::ADMIN->value)) {

                $rol = Rol::find($id);

                if (!$rol) {
                    $data = [
                        'message' => 'Rol no encontrado',
                        'status' => 404
                    ];
                    return response()->json($data, 404);
                }

                $validator = Validator::make($request->all(), [
                    'nombre_rol' => ['required', 'string', 'unique:rol,nombre_rol,' . $id, Rule::enum(NombreRoles::class)]
                ]);

                if ($validator->fails()) {
                    $data = [
                        'message' => 'Error de validación de los datos',
                        'errors' => $validator->errors(),
                        'status' => 400
                    ];
                    return response()->json($data, 400);
                }

                $rol->update([
                    'nombre_rol' => $request->nombre_rol
                ]);

                // Transformado al JSON correspondiente
                $rol = [
                    'id' => $rol->id,
                    'nombre_rol' => $rol->nombre_rol
                ];

                $data = [
                    'message' => 'Rol actualizado correctamente',
                    'rol' => $rol,
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

    // Función para eliminar el rol pasado por id
    public function destroy($id)
    {
        try {
            if (self::identificacion(NombreRoles::ADMIN->value)) {
                $rol = Rol::find($id);

                if (!$rol) {
                    $data = [
                        'message' => 'Rol no encontrado',
                        'status' => 404
                    ];
                    return response()->json($data, 404);
                }

                $rol->permisos()->detach();
                $rol->usuarios()->detach();
                $rol->delete();

                // Transformado al JSON correspondiente
                $rol = [
                    'id' => $rol->id,
                    'nombre_rol' => $rol->nombre_rol
                ];

                $data = [
                    'message' => 'Rol eliminado correctamente',
                    'rol' => $rol,
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

    /*---- FUNCIÓNES DE ROLES ESPECIALES ----*/
    //Función para obtener la información de un rol y sus permisos pasandole el id del rol
    public function obtenerInfoRolPermiso($id)
    {
        try {
            if (self::identificacion(NombreRoles::ADMIN->value)) {
                $rol = Rol::with('permisos')->find($id);

                if (!$rol) {
                    $data = [
                        'message' => 'Rol no encontrado',
                        'status' => 404
                    ];
                    return response()->json($data, 404);
                }

                // Transformado al JSON correspondiente del rol para poder mostrarlo
                $rolMostrado = [
                    'id' => $rol->id,
                    'nombre_rol' => $rol->nombre_rol
                ];

                // Transformamos al JSON correspondiente los permisos del rolMostrado
                $permisosRolMostrado = $rol->permisos->map(function ($permiso) {
                    return [
                        'id' => $permiso->id,
                        'desc_permiso' => $permiso->desc_permiso
                    ];
                });

                $data = [
                    'message' => 'Información del rol y sus permisos obtenida correctamente',
                    'rol' => $rolMostrado,
                    'permisos' => $permisosRolMostrado,
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

    // Función para asignar un rol a un usuario pasandole el id del usuario y el id del rol a asignar
    public function asignarRolUsuario(Request $request, $idUsuario)
    {
        try {
            if (self::identificacion(NombreRoles::ADMIN->value)) {
                $validator = Validator::make($request->all(), [
                    'id_rol' => ['required', 'integer', 'exists:rol,id']
                ]);

                if ($validator->fails()) {
                    $data = [
                        'message' => 'Error en la validación de los datos',
                        'errors' => $validator->errors(),
                        'status' => 400
                    ];
                    return response()->json($data, 400);
                }

                $id_rol = $request->id_rol;

                if (UsuarioRol::where('id_usuario', $idUsuario)->where('id_rol', $id_rol)->exists()) {
                    $data = [
                        'message' => 'El rol ya está asignado al usuario',
                        'status' => 400
                    ];
                    return response()->json($data, 400);
                }

                UsuarioRol::create([
                    'id_usuario' => $idUsuario,
                    'id_rol' => $id_rol
                ]);

                $data = [
                    'message' => 'Rol asignado al usuario correctamente',
                    'rol_Usuario' => [
                        'id_rol' => $id_rol,
                        'id_usuario' => $idUsuario
                    ],
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

    // Función para asignar un permiso a un rol pasandole el id del rol y el id del permiso a asignar
    public function asignarPermisoRol(Request $request, $idRol)
    {
        try {
            if (self::identificacion(NombreRoles::ADMIN->value)) {

                $rol = Rol::find($idRol);

                if (!$rol) {
                    $data = [
                        'message' => 'Rol no encontrado',
                        'status' => 404
                    ];
                    return response()->json($data, 404);
                }

                $validator = Validator::make($request->all(), [
                    'id_permiso' => ['required', 'integer', 'exists:permiso,id']
                ]);

                if ($validator->fails()) {
                    $data = [
                        'message' => 'Error en lavalidación de los datos',
                        'errors' => $validator->errors(),
                        'status' => 400
                    ];
                    return response()->json($data, 400);
                }

                $id_permiso = $request->id_permiso;

                if ($rol->permisos()->where('id_permiso', $id_permiso)->exists()) {
                    $data = [
                        'message' => 'El permiso ya está asignado al rol',
                        'status' => 400
                    ];
                    return response()->json($data, 400);
                }

                $rol->permisos()->attach($id_permiso);

                $data = [
                    'message' => 'Permiso asignado al rol',
                    'permiso_Rol' => [
                        'rol' => [
                            'id' => $rol->id,
                            'nombre_rol' => $rol->nombre_rol
                        ],
                        'permiso' => [
                            'id' => $id_permiso,
                            'desc_permiso' => Permiso::find($id_permiso)->desc_permiso
                        ]
                    ],
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

    // Función para eliminar un permiso de un rol pasandole el id del rol y el id del permiso a eliminar
    public function eliminarPermisoRol($idRol, $idPermiso)
    {
        try {
            if (self::identificacion(NombreRoles::ADMIN->value)) {

                $rol = Rol::find($idRol);

                if (!$rol) {
                    $data = [
                        'message' => 'Rol no encontrado',
                        'status' => 404
                    ];
                    return response()->json($data, 404);
                }

                if (!$rol->permisos()->where('id_permiso', $idPermiso)->exists()) {
                    $data = [
                        'message' => 'El permiso no está asignado al rol',
                        'status' => 404
                    ];
                    return response()->json($data, 404);
                }

                $rol->permisos()->detach($idPermiso);

                $data = [
                    'message' => 'Permiso eliminado del rol correctamente',
                    'permiso_Rol' => [
                        'rol' => [
                            'id' => $rol->id,
                            'nombre_rol' => $rol->nombre_rol
                        ],
                        'permiso' => [
                            'id' => Permiso::find($idPermiso)->id,
                            'desc_permiso' => Permiso::find($idPermiso)->desc_permiso
                        ]
                    ],
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
