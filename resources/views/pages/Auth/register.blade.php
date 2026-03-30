@extends('pages.Layouts.plantillaAuth')

@section('titulo','Registrate | Leche El Torrejon')

@push('js')
    <script type="module" src="{{ asset('resources/js/pages/validations/Auth/validacionRegister.js') }}"></script>
@endpush

@push('styles')
    <link rel="stylesheet" href="{{ asset('resources/css/pages/Auth/auth.css') }}">
@endpush

@section('contenido')

{{-- Div contenedor --}}
<div class="container min-vh-100 d-flex justify-content-center align-items-center py-5">
    {{-- Div de la carta --}}
    <div class="card border-0 shadow-sm p-5"  style="max-width: 900px; width: 100%; border-radius: 12px;">
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
            Crear cuenta
        </h1>

        <p class="text-center text-muted mb-3">
            Introduce tus datos para poder registrarte.
        </p>

        {{-- Formulario --}}
        <form id="formRegister" method="POST">
            @csrf
            <div class="row">

                {{-- Nombre de usuario --}}
                <div class="mb-3 col-md-6">
                    <label for="username" class="form-label">Nombre de usuario*</label>
                    <input type="text" name="username" id="username" class="form-control" placeholder="Nombre de usuario*">
                    <div class="error text-danger small hidden"></div>
                </div>

                {{-- Nombre --}}
                <div class="mb-3 col-md-6">
                    <label for="nombre" class="form-label">Nombre*</label>
                    <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Nombre*">
                    <div class="error text-danger small hidden"></div>
                </div>

                {{-- Apellidos --}}
                <div class="mb-3 col-md-6">
                    <label for="apellidos" class="form-label">Apellidos*</label>
                    <input type="text" name="apellidos" id="apellidos" class="form-control" placeholder="Apellidos*">
                    <div class="error text-danger small hidden"></div>
                </div>

                {{-- Email --}}
                <div class="mb-3 col-md-6">
                    <label for="email" class="form-label">Email*</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="Email*">
                    <div class="error text-danger small hidden"></div>
                </div>

                {{-- Dirección --}}
                <div class="mb-3 col-12">
                    <label for="direccion" class="form-label">Dirección*</label>
                    <input type="text" name="direccion" id="direccion" class="form-control" placeholder="Dirección*">
                    <div class="error text-danger small hidden"></div>
                </div>

                {{-- Contraseña --}}
                <div class="mb-3 col-md-6">
                    <label for="password" class="form-label">Contraseña*</label>
                    <div class="position-relative">
                        <input type="password" name="password" id="password" class="form-control" placeholder="Contraseña*">

                        <button type="button"
                            class="btn btn-sm position-absolute top-50 end-0 translate-middle-y me-2 toggle-password"
                            data-target="password">

                            <!-- OJO CERRADO (contraseña oculta) -->
                            <span class="icon-eye">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24">
                                    <path fill="#000000" fill-rule="evenodd"
                                        d="M12 17.8c4.034 0 7.686-2.25 9.648-5.8C19.686 8.45 16.034 6.2 12 6.2S4.314 8.45 2.352 12c1.962 3.55 5.614 5.8 9.648 5.8M12 5c4.808 0 8.972 2.848 11 7c-2.028 4.152-6.192 7-11 7s-8.972-2.848-11-7c2.028-4.152 6.192-7 11-7m0 9.8a2.8 2.8 0 1 0 0-5.6a2.8 2.8 0 0 0 0 5.6m0 1.2a4 4 0 1 1 0-8a4 4 0 0 1 0 8" />
                                </svg>
                            </span>

                        </button>
                    </div>
                    <div class="error text-danger small hidden"></div>
                </div>


                {{-- Confirmar contraseña --}}
                <div class="mb-3 col-md-6">
                    <label for="password_confirmation" class="form-label">Confirmar contraseña*</label>
                    <div class="position-relative">
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Confirmar contraseña*">

                        <button type="button"
                            class="btn btn-sm position-absolute top-50 end-0 translate-middle-y me-2 toggle-password"
                            data-target="password_confirmation">

                            <!-- OJO CERRADO (contraseña oculta) -->
                            <span class="icon-eye">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24">
                                    <path fill="#000000" fill-rule="evenodd"
                                        d="M12 17.8c4.034 0 7.686-2.25 9.648-5.8C19.686 8.45 16.034 6.2 12 6.2S4.314 8.45 2.352 12c1.962 3.55 5.614 5.8 9.648 5.8M12 5c4.808 0 8.972 2.848 11 7c-2.028 4.152-6.192 7-11 7s-8.972-2.848-11-7c2.028-4.152 6.192-7 11-7m0 9.8a2.8 2.8 0 1 0 0-5.6a2.8 2.8 0 0 0 0 5.6m0 1.2a4 4 0 1 1 0-8a4 4 0 0 1 0 8" />
                                </svg>
                            </span>

                        </button>
                    </div>
                    <div class="error text-danger small hidden"></div>
                </div>


                {{-- Política de privacidad --}}
                <div class="form-check mb-4 col-12">
                    <input type="checkbox" name="politicaPrivacidad" id="politicaPrivacidad" class="form-check-input">
                    <label for="politicaPrivacidad" class="form-check-label">
                        He leído y acepto la 
                        <a href="{{ route('pages.Legal.politicaPrivacidad') }}">Política de privacidad</a>
                    </label>
                    <div class="error text-danger small mt-1 hidden"></div>
                </div>

            </div>

            <div id="msg" class="mb-3"></div>

            {{-- Botón --}}
            <div class="d-grid">
                <button type="button" id="btnRegister" class="btn btn-dark">
                    Registrarse
                </button>
            </div>
        </form>

        {{-- Si el usuario tiene cuenta --}}
        <div class="d-grid mt-3">
            <a href="{{ route('login') }}" class="btn btn-outline-secondary">
                ¿Ya tienes cuenta? Inicia sesión
            </a>
        </div>

    </div>
</div>
@endsection