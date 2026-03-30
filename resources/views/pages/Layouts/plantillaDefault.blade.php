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
    <script src="{{ asset('resources/js/components/cookieConsent.js') }}"></script>
    <script src="{{ asset('resources/js/scriptsGeneralUnaCarga.js') }}"></script>
    <script type="module" src="{{ asset('resources/js/scriptsGeneral.js') }}"></script>
    {{-- Dirección CSS e iconos de Bootstrap --}}
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
    {{-- Incluye la cabecera --}}
    @include('pages.Layouts._partials.header')
    {{-- Contenido dinámico que va a cambiar con la página  --}}
    @include('pages.Layouts._partials.main')

    {{-- SIDEBAR PARA LA CESTA --}}
    <div id="sidebarCesta" class="sidebar-cesta">
        <div class="sidebar-header d-flex justify-content-between align-items-center p-3 border-bottom">
            <h5 class="mb-0 fw-bold">Mi cesta</h5>
            <button type="button" class="btn-close" onclick="toggleCesta()" aria-label="Cerrar"></button>
        </div>
        
        <div id="contenidoCesta" class="sidebar-body p-3">
            {{-- Aquí se muestran los productos --}}
            <div class="text-center mt-4 text-muted">Cargando cesta...</div>
        </div>

        <div class="sidebar-footer p-3 border-top mt-auto">
            <div class="d-flex justify-content-between mb-3 align-items-center">
                <span class="fw-bold">Total (IVA incluido)</span>
                <span id="totalCesta" class="fw-bold fs-5">0.00€</span>
            </div>
            
            <a href="{{route('pages.Carrito.verCarrito')}}" class="btn btn-verCesta w-100 fw-bold py-2 text-center text-decoration-none">
                Ver artículos en tu cesta
            </a>
        </div>
    </div>
    {{-- (El JS ya se encarga de todo) --}}
    <div id="overlayCesta" class="overlay-cesta"></div>

    {{-- Incluye el footer --}}
    @include('pages.Layouts._partials.footer')
    <x-cookie-consent />
</body>
</html>
