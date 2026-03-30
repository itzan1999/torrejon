@extends('pages.Layouts.plantillaAuth')

@section('titulo','Nueva contraseña | Leche El Torrejon')

@push('js')
    <script type="module" src="{{ asset('resources/js/pages/validations/Auth/validacionCambiarPassword.js') }}"></script>
@endpush

@push('styles')
    <link rel="stylesheet" href="{{ asset('resources/css/pages/Auth/auth.css') }}">
@endpush

@section('contenido')

<div class="container min-vh-100 d-flex justify-content-center align-items-center py-5">

    <div class="card border-0 shadow-sm p-5" style="max-width: 600px; width: 100%; border-radius: 12px;">

        {{-- Logo --}}
        <div class="mb-4 text-center">
            <a href="{{ route('pages.index') }}">
                <img src="{{ asset('resources/images/logo_main_black.png') }}" 
                     alt="El Torrejón" 
                     class="img-fluid"
                     style="max-height: 70px;">
            </a>
        </div>

        {{-- Título --}}
        <h1 class="h4 fw-bold mb-3 text-center">
            Establecer nueva contraseña
        </h1>

        <p class="text-center text-muted mb-4">
            Introduce tu nueva contraseña para completar el proceso de recuperación.
        </p>

        {{-- Formulario --}}
        <form id="formChangePassword" method="POST">
            @csrf
            
            {{-- Nueva contraseña --}}
            <label for="password" class="form-label">Nueva contraseña*</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="Nueva contraseña*">
            <div class="error text-danger small hidden"></div>
      
            {{-- Confirmar contraseña --}}
            <label for="password_confirmation" class="form-label">Confirmar contraseña*</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Confirmar contraseña*">
            <div class="error text-danger small hidden"></div>
      

            {{-- Mostrar la contraseña --}}
            <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" id="toggleAllPasswords">
                <label class="form-check-label" for="toggleAllPasswords">
                    Mostrar contraseña
                </label>
            </div>

            {{-- Div para el mensaje de confirmación --}}
            <div id="msg" class="mb-4"></div>

            {{-- Botón --}}
            <div class="d-grid">
                <button type="button" id="btnChangePassword" class="btn btn-dark">
                    Cambiar contraseña
                </button>
            </div>
        </form>

        {{-- Volver al login --}}
        <div class="d-grid mt-3">
            <a href="{{ route('login') }}" class="btn btn-outline-secondary">
                Iniciar sesión
            </a>
        </div>

    </div>
</div>

@endsection
