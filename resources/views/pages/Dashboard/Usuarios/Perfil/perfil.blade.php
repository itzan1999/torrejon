@extends('pages.Dashboard._Layouts.dashboardDefault')

@section('titulo','' . $nombreUsuario . ' | Leche El Torrejon')

@push('js')
    <script type="module" src="{{ asset('resources/js/pages/Dashboard/Usuarios/Perfil/cargarPerfil.js') }}"></script>
@endpush

@push('styles')
    <link rel="stylesheet" href="{{ asset('resources/css/pages/Dashboard/Usuarios/perfil.css') }}">
@endpush

@section('contenidoDashboard')
    <h1>Bienvenid@, {{ $nombreUsuario }}</h1>
    <p>Esta es la página de tu perfil.</p>

    <h3 class="mb-4">Datos de mi cuenta</h3>

    <form id="formChangePassword">

        <h3 class="mb-4">Contraseña</h3>
     
        <div class="row">

            <!-- Antigua contraseña -->
            <div class="mb-3 col-md-4">
                <label class="form-label">Antigua contraseña</label>
                <div class="position-relative">
                    <input type="password" id="old_password" name="old_password" class="form-control">
                    <button type="button"
                        class="btn btn-sm position-absolute top-50 end-0 translate-middle-y me-2 toggle-password"
                        data-target="old_password">
                        <span class="icon-eye"></span>
                    </button>
                </div>
                <div class="error text-danger small hidden"></div>
            </div>

            <!-- Nueva contraseña -->
            <div class="mb-3 col-md-4">
                <label class="form-label">Nueva contraseña</label>
                <div class="position-relative">
                    <input type="password" id="new_password" name="new_password" class="form-control">
                    <button type="button"
                        class="btn btn-sm position-absolute top-50 end-0 translate-middle-y me-2 toggle-password"
                        data-target="new_password">
                        <span class="icon-eye"></span>
                    </button>
                </div>
                <small class="text-muted">Tu contraseña debe tener al menos 8 caracteres</small>
                <div class="error text-danger small hidden"></div>
            </div>

            <!-- Repetir contraseña -->
            <div class="mb-3 col-md-4">
                <label class="form-label">Repetir contraseña</label>
                <div class="position-relative">
                    <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="form-control">
                    <button type="button"
                        class="btn btn-sm position-absolute top-50 end-0 translate-middle-y me-2 toggle-password"
                        data-target="new_password_confirmation">
                        <span class="icon-eye"></span>
                    </button>
                </div>
                <small class="text-muted">Tu contraseña debe tener al menos 8 caracteres</small>
                <div class="error text-danger small hidden"></div>
            </div>

        </div>

        {{-- Mensaje de confirmación o error --}}
        <div id="alert" class="alert alert-success alert-dismissible fade" role="alert" style="display: none;">
            <span id="alertMsg"></span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>

        <button type="button" id="btnChangePassword" class="btn btn-dark mt-3">
            Guardar
        </button>

    </form>
@endsection
