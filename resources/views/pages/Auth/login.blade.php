@extends('pages.Layouts.plantillaAuth')

@section('titulo','Accede a tu cuenta | Leche El Torrejon')

@push('js')
  <script type="module" src="{{ asset('resources/js/pages/validations/Auth/validacionLogin.js') }}"></script>
@endpush

@push('styles')
  <link rel="stylesheet" href="{{ asset('resources/css/pages/Auth/auth.css') }}">
@endpush

@section('contenido')

<div class="container min-vh-100 d-flex justify-content-center align-items-center py-5">
    <div class="card border-0 shadow-sm p-5" style="max-width: 500px; width: 100%; border-radius: 12px;">

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
        <h1 class="h4 fw-bold mb-3 text-center">Iniciar sesión</h1>
        <p class="text-center text-muted mb-3">
            Introduce tus credenciales para acceder a tu cuenta.
        </p>

        <form action="{{ route('verficarLogin') }}" method="POST" id="loginForm">
            @csrf

            {{-- Usuario --}}
            <div class="mb-3">
                <label for="username" class="form-label">Nombre de usuario*</label>
                <input type="text" name="username" id="username" class="form-control" value="{{ old('username') }}" placeholder="Nombre de usuario*">
                <div class="error text-danger small hidden"></div>
            </div>

            {{-- Contraseña --}}
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña*</label>
                <div class="position-relative">
                    <input type="password" name="password" id="password" class="form-control" placeholder="Contraseña*">
                    <button type="button" class="btn btn-sm position-absolute top-50 end-0 translate-middle-y me-2 toggle-password" data-target="password">
                        <span class="icon-eye">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24">
                                <path fill="#000000" fill-rule="evenodd" d="M12 17.8c4.034 0 7.686-2.25 9.648-5.8C19.686 8.45 16.034 6.2 12 6.2S4.314 8.45 2.352 12c1.962 3.55 5.614 5.8 9.648 5.8M12 5c4.808 0 8.972 2.848 11 7c-2.028 4.152-6.192 7-11 7s-8.972-2.848-11-7c2.028-4.152 6.192-7 11-7m0 9.8a2.8 2.8 0 1 0 0-5.6a2.8 2.8 0 0 0 0 5.6m0 1.2a4 4 0 1 1 0-8a4 4 0 0 1 0 8"/>
                            </svg>
                        </span>
                    </button>
                </div>
                <div class="error text-danger small hidden"></div>
            </div>

                {{-- Error Laravel --}}
                @if ($errors->has('login'))
                    <div class="error text-danger small mb-3">
                        *{{ $errors->first('login') }}
                    </div>
                @endif

            {{-- Botón principal igual al registro --}}
            <div class="d-grid mt-3">
                <button type="button" id="loginBtn" class="btn btn-dark">
                    Iniciar sesión
                </button>
            </div>
        </form>

        {{-- Enlace a registro igual estilo botón --}}
        <div class="d-grid mt-3">
            <a href="{{ route('pages.register') }}" class="btn btn-outline-secondary">
                ¿No tienes cuenta? Regístrate
            </a>
        </div>

    </div>
</div>

@endsection