<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- Token del usuario --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
     {{-- Icono de la aplicación --}}
    <link rel="shortcut icon"  type="image/x-icon" href="{{ asset('resources/icons/lecheeltorrejon.ico') }}">
    <link rel="preload" href="{{ asset('resources/images/logo_main_white.png') }}" as="image">
    {{-- Dirección del los CSS principales --}}
    <!--CSS General -->
    <link rel="stylesheet" href="{{ asset('resources/css/styles.css') }}">
    {{-- Dirección JS Específico y jQuery --}}
    <script src="{{ asset('resources/js/lib/jquery-version-4.0.0.js') }}"></script>
    <script src="{{ asset('resources/js/scriptsGeneralUnaCarga.js') }}"></script>
    {{-- Dirección CSS de Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    {{-- Dirección JS de Bootstrap --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <!--CSS Específico -->
    @stack('styles')
    <!-- JS Específico-->
    @stack('js')
    {{-- Titulo cabecera --}}
    <title>
        @yield('titulo')
    </title>
</head>
{{-- Cuerpo del html --}}
<body>
    {{-- Esta plantilla en principio solo es para el login, el registro etc (partes de la autenticación) --}}
    {{-- Contenido dinámico que va a cambiar con la página  --}}
    @include('pages.Layouts._partials.main')
</body>
</html>
