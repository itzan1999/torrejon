@extends('pages.Layouts.plantillaDefault')

@section('titulo','Home - Leche El Torrejon')

@push('styles')
    <link rel="stylesheet" href="{{ asset('resources/css/pages/Extras/index.css') }}"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>

    <link rel="stylesheet" href="{{ asset('resources/css/ListadoProductos.css') }}"/>
@endpush

@push('js') 
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="module" src="{{ asset('resources/js/pages/index.js') }}"></script>
    <script type="module" src="{{ asset('resources/js/CarruselProductos.js') }}"></script>
    <script type="module" src="{{ asset('resources/js/ListadoProductos.js') }}"></script>
@endpush

@section("contenido")

<section class="carrusel-hero">
    <div class="container-fluid p-0 h-100 position-relative">
        <div class="carrusel-inner h-100" id="carrusel-hero-track"></div>
        <div class="nav-lateral-puntos">
            <div class="punto-wrapper active" data-slide="0"><div class="punto-core"></div><div class="punto-outer"></div></div>
            <div class="punto-wrapper" data-slide="1"><div class="punto-core"></div><div class="punto-outer"></div></div>
            <div class="punto-wrapper" data-slide="2"><div class="punto-core"></div><div class="punto-outer"></div></div>
        </div>
    </div>
</section>
{{-- 
    LISTA DE PRODUCTOS
--}}
<div class="seccion-productos bg-white">
    <div class="contenedor-titulo-carrusel">
        <div class="container py-4">
            <div class="d-flex align-items-center justify-content-center">
                <button id="btn-prev" class="btn-carrusel-circular"><i class="fas fa-chevron-left"></i></button>
                <h2 class="titulo-carrusel-ajustado fs-5 px-5 mb-0">Nuestros productos</h2>
                <button id="btn-next" class="btn-carrusel-circular"><i class="fas fa-chevron-right"></i></button>
            </div>
        </div>
    </div>

    <div class="container-fluid py-5 overflow-hidden">
        <div class="carrusel-wrapper">
            <div class="carrusel-track" id="carrusel-track">
            </div>
        </div>
    </div>
</div>
<section class="seccion-manifiesto-dark d-flex align-items-center justify-content-center">
    <div class="container py-5 text-center position-relative z-index-2">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <p class="small tracking-widest text-white-50 mb-4">MÁS DE 30 AÑOS DE VALORES SALUDABLES</p>
                <h1 class="display-5 fw-bold text-serif text-white">El Torrejón es una empresa nacida del esfuerzo y el sacrificio familiar en el año 1988. Desde entonces, trasladamos nuestros valores y pasión por los lácteos de generación en generación.</h1>
                <hr class="mx-auto mt-5 line-separator-white" />
            </div>
        </div>
    </div>
</section>

<div class="container-fluid p-0 seccion-principal fondo-gris">
    <div class="row g-0 align-items-center fila-ajuste-dual">
        <div class="col-12 col-md-6 order-1">
            <img src="{{ asset('resources/images/img_a.jpg') }}" class="img-fluid w-100 imagen-seccion-ajustada" alt="Calidad El Torrejón" loading="lazy" style="aspect-ratio:16/10;">
        </div>
        <div class="col-12 col-md-6 p-4 p-md-5 d-flex justify-content-center order-2">
            <div class="limite-ancho-texto">
                <h2 class="fw-bold mb-5 display-5 titulo-estilo-serif">Somos equipo, somos familia.</h2>
                <div class="texto-contenido-formateado text-justify">
                    <p>La elaboración es un procedimiento en el que participan equipos y cadenas de personal dispuestos a dar lo mejor de sí mismos día tras día para un producto inigualable.</p>
                    <p>Nuestros trabajadores disfrutan el reto de enamorar al consumidor a través de nuestros productos y valores. Su pasión, integridad y cercanía es lo que nos ha otorgado la importancia de la que hoy en día puede presumir El Torrejón.</p>
                </div>
                <div class="mb-4 mt-5 pt-5">
                    <a href="{{ route('pages.index') }}"><img src="{{ asset('resources/icons/icon_arrow_a.svg') }}" alt="Flecha" class="icono-flecha-guia"></a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-0 align-items-center fila-ajuste-dual">
        <div class="col-12 col-md-6 p-4 p-md-5 d-flex justify-content-center order-2 order-md-1">
            <div class="limite-ancho-texto">
                <h2 class="fw-bold mb-5 display-5 titulo-estilo-serif">A la vanguardia del sector</h2>
                <div class="texto-contenido-formateado text-justify">
                    <p>En El Torrejón estamos al tanto de los progresos tecnológicos y científicos para implantarlos en el tratamiento y la transformación de la materia prima. Con la vanguardia por bandera, conseguimos desarrollar el producto de una forma más práctica y adaptada, en todo momento, a las necesidades del consumidor.</p>
                </div>
                <div class="mb-4 mt-5 pt-5">
                    <a href="{{ route('pages.index') }}"><img src="{{ asset('resources/icons/icon_arrow_a.svg') }}" alt="Flecha" class="icono-flecha-guia"></a>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 order-1 order-md-2">
            <img src="{{ asset('resources/images/img_b.jpg') }}" class="img-fluid w-100 imagen-seccion-ajustada" alt="Vanguardia Tecnológica" loading="lazy" style="aspect-ratio:16/10;">
        </div>
    </div>
</div>

<div class="contenedor-titulo-carrusel">
    <div class="container py-4">
        <div class="d-flex align-items-center justify-content-center">
            <button id="btn-prev-news" class="btn-carrusel-circular"><i class="fas fa-chevron-left"></i></button>
            <h2 class="titulo-carrusel-ajustado fs-5 px-5 mb-0">El Torrejón al día</h2>
            <button id="btn-next-news" class="btn-carrusel-circular"><i class="fas fa-chevron-right"></i></button>
        </div>
    </div>
</div>

<div class="container py-5 overflow-hidden">
    <div class="carrusel-wrapper">
        <div class="carrusel-track con-animacion" id="news-track"></div>
    </div>
</div>

<section class="seccion-final-mixta">
    <div class="container-fluid p-0">
        <div class="row g-0">
            <div class="col-12 col-md-6 bg-white d-flex align-items-center justify-content-center p-5 border-bottom border-end" style="height:50vh;min-height:450px;">
                <div class="limite-ancho-texto">
                    <h2 class="titulo-hero-serif" style="font-size:2.2rem;">Apostamos por las energías renovables</h2>
                    <p class="texto-hero-muted">LECHE EL TORREJÓN, S. L., con el fin de reducir el impacto ambiental de nuestra actividad hemos realizado una instalación de generación fotovoltaica de 150,00 kWn para autoconsumo en nuestras instalaciones. Esta inversión se realiza con ayudas del programa operativo FEDER.</p>
                    <div class="mt-4"><img src="{{ asset('resources/icons/icon_arrow_a.svg') }}" style="width:50px;opacity:0.3;" alt=""></div>
                </div>
            </div>
            <div class="col-12 col-md-6 border-bottom" style="height:50vh;min-height:450px;">
                <div style="background-image:url('{{ asset('resources/images/certificado.png') }}');background-size:cover;background-position:center;width:100%;height:100%;"></div>
            </div>
        </div>

        <div class="row g-0 position-relative" style="height:50vh;min-height:450px;overflow:hidden;">
            <div id="carrusel-final-track" class="w-100 h-100"></div>
            <div class="nav-lateral-puntos">
                <div class="punto-wrapper active" data-slide="0"><div class="punto-core"></div><div class="punto-outer"></div></div>
                <div class="punto-wrapper" data-slide="1"><div class="punto-core"></div><div class="punto-outer"></div></div>
                <div class="punto-wrapper" data-slide="2"><div class="punto-core"></div><div class="punto-outer"></div></div>
            </div>
        </div>
    </div>
</section>
@endsection