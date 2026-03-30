@extends('pages.Layouts.plantillaAuth')

@section('titulo','Restablecer contraseña | Leche El Torrejon')

@push('js')
    <script type="module" src="{{ asset('resources/js/pages/validations/Auth/validacionSolicitarCambiarPassword.js') }}"></script>
@endpush

@push('styles')
    <link rel="stylesheet" href="{{ asset('resources/css/pages/Auth/auth.css') }}">
@endpush

@section('contenido')

{{-- Contenedor principal --}}
<div class="container min-vh-100 d-flex justify-content-center align-items-center py-5">

    {{-- Tarjeta --}}
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
            Restablecer contraseña
        </h1>

        <p class="text-center text-muted mb-4">
            Introduce tu email y te enviaremos un enlace para restablecer tu contraseña.
        </p>

        {{-- Formulario --}}
        <form id="formForgot" method="POST">
            @csrf

            {{-- Email --}}
            <div class="mb-3">
                <label for="email" class="form-label">Email*</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="Email*">
                <div class="error text-danger small hidden"></div>
            </div>

            <div id="msg" class="mb-4"></div>

            {{-- Botón --}}
            <div class="d-grid">
                <button type="button" id="btnForgot" class="btn btn-dark">
                    Restablecer contraseña
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
