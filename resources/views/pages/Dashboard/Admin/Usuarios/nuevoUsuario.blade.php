@extends('pages.Dashboard._Layouts.dashboardDefault')

@section('titulo','Nuevo Usuario | Leche El Torrejon')

@push('styles')
    <link rel="stylesheet" href="{{ asset('resources/css/pages/Dashboard/Admin/Usuarios/usuarios.css') }}">
@endpush

@push('js')
    <script type="module" src="{{ asset('resources/js/pages/Dashboard/Admin/Usuarios/nuevoUsuario.js') }}"></script>
@endpush

@section('contenidoDashboard')

<h1>Nuevo Usuario</h1>

<form id="formNuevoUsuario">
    @csrf

    <div class="row">

        {{-- Username --}}
        <div class="mb-3 col-md-6">
            <label class="form-label">Nombre de usuario*</label>
            <input type="text" name="username" id="username" class="form-control" placeholder="Nombre de usuario*">
            <div class="error text-danger small hidden"></div>
        </div>

        {{-- Nombre --}}
        <div class="mb-3 col-md-6">
            <label class="form-label">Nombre*</label>
            <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Nombre*">
            <div class="error text-danger small hidden"></div>
        </div>

        {{-- Apellidos --}}
        <div class="mb-3 col-md-6">
            <label class="form-label">Apellidos*</label>
            <input type="text" name="apellidos" id="apellidos" class="form-control" placeholder="Apellidos*">
            <div class="error text-danger small hidden"></div>
        </div>

        {{-- Email --}}
        <div class="mb-3 col-md-6">
            <label class="form-label">Email*</label>
            <input type="email" name="email" id="email" class="form-control" placeholder="Email*">
            <div class="error text-danger small hidden"></div>
        </div>

        {{-- Dirección --}}
        <div class="mb-3 col-12">
            <label class="form-label">Dirección*</label>
            <input type="text" name="direccion" id="direccion" class="form-control" placeholder="Dirección*">
            <div class="error text-danger small hidden"></div>
        </div>

        {{-- Saldo --}}
        <div class="mb-3 col-md-6">
            <label class="form-label">Saldo inicial (opcional)</label>
            <input type="number" name="saldo" id="saldo" class="form-control" placeholder="Saldo inicial (opcional)" min="0" step="0.01">
            <div class="error text-danger small hidden"></div>
        </div>

        {{-- Contraseña --}}
        <div class="mb-3 col-md-6">
            <label class="form-label">Contraseña*</label>
            <div class="position-relative">
                <input type="password" name="password" id="password" class="form-control" placeholder="Contraseña*">
                <button type="button"
                    class="btn btn-sm position-absolute top-50 end-0 translate-middle-y me-2 toggle-password"
                    data-target="password">

                    <span class="icon-eye">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="eye-icon">
                        <path fill="currentColor" fill-rule="evenodd"
                        d="M12 17.8c4.034 0 7.686-2.25 9.648-5.8C19.686 8.45 16.034 6.2 12 6.2S4.314 8.45 2.352 12c1.962 3.55 5.614 5.8 9.648 5.8M12 5c4.808 0 8.972 2.848 11 7c-2.028 4.152-6.192 7-11 7s-8.972-2.848-11-7c2.028-4.152 6.192-7 11-7m0 9.8a2.8 2.8 0 1 0 0-5.6a2.8 2.8 0 0 0 0 5.6m0 1.2a4 4 0 1 1 0-8a4 4 0 0 1 0 8"/>
                        </svg>
                    </span>

                </button>
            </div>
            <div class="error text-danger small hidden"></div>
        </div>

        {{-- Confirmar contraseña --}}
        <div class="mb-3 col-md-6">
            <label class="form-label">Confirmar contraseña*</label>
            <div class="position-relative">
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Confirmar contraseña*">
                   <button type="button"
                        class="btn btn-sm position-absolute top-50 end-0 translate-middle-y me-2 toggle-password"
                        data-target="password_confirmation">

                        <span class="icon-eye">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="eye-icon">
                            <path fill="currentColor" fill-rule="evenodd"
                            d="M12 17.8c4.034 0 7.686-2.25 9.648-5.8C19.686 8.45 16.034 6.2 12 6.2S4.314 8.45 2.352 12c1.962 3.55 5.614 5.8 9.648 5.8M12 5c4.808 0 8.972 2.848 11 7c-2.028 4.152-6.192 7-11 7s-8.972-2.848-11-7c2.028-4.152 6.192-7 11-7m0 9.8a2.8 2.8 0 1 0 0-5.6a2.8 2.8 0 0 0 0 5.6m0 1.2a4 4 0 1 1 0-8a4 4 0 0 1 0 8"/>
                            </svg>
                        </span>
                    </button>
            </div>
            <div class="error text-danger small hidden"></div>
        </div>

        {{-- Rol --}}
        <div class="mb-4 col-md-6">
            <label class="form-label">Rol*</label>
            <select name="rol" id="rol" class="form-select">
                <option value="" disabled selected>Seleccionar rol</option>
                <option value="user">Usuario</option>
                <option value="admin">Administrador</option>
            </select>
            <div class="error text-danger small hidden"></div>
        </div>

    </div>

    <div id="alert" class="alert alert-success alert-dismissible fade" role="alert" style="display: none;">
        <span id="alertMsg"></span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>

    <button type="button" id="btnVolver" class="btn btn-secondary">
        Volver atrás
    </button>
    <button type="button" id="btnCrearUsuario" class="btn btn-dark">
        Crear Usuario
    </button>

</form>

<br>

@endsection