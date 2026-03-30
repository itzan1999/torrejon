@extends('pages.Layouts.plantillaAuth')

@section('titulo','Activar Cuenta | Leche El Torrejon')

@push('js')
    <script type="module" src="{{ asset('resources/js/pages/validations/Auth/validacionActivarCuenta.js') }}"></script>
@endpush

@push('styles')
    <link rel="stylesheet" href="{{ asset('resources/css/pages/Auth/auth.css') }}">
@endpush

@section('contenido')

<div id="modal" class="hidden">
    <div class="container min-vh-100 d-flex justify-content-center align-items-center py-5">
        <div class="card border-0 shadow-sm text-center p-5" style="max-width: 500px; width: 100%; border-radius: 12px;">

            {{-- Logo --}}
            <div class="mb-4">
                <img src="{{ asset('resources/images/logo_main_black.png') }}" 
                alt="El Torrejón" 
                class="img-fluid"
                style="max-height: 70px;">
            </div>

            {{-- Icono dinámico --}}
            <div class="mb-4">
                <div class="icon-circle d-flex justify-content-center align-items-center mx-auto"
                    style="width: 70px; height: 70px;">
                </div>
            </div>

            {{-- Título dinámico --}}
            <h1 class="h4 fw-bold mb-3"></h1>

            {{-- Texto dinámico --}}
            <p class="text-muted mb-4"></p>

            {{-- Botón dinámico --}}
            <a class="action-btn btn px-4 py-2"></a>
        </div>
    </div>
</div>

@endsection