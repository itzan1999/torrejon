@extends('pages.Layouts.plantillaDefault')

@section('titulo','Calidad - Leche El Torrejon')

@push('styles')
    <link rel="stylesheet" href="{{ asset('resources/css/pages/Extras/calidad.css') }}"/>
    <link rel="stylesheet" href="{{ asset('resources/css/ListadoProductos.css') }}"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>
@endpush

@push('js')
    <script type="module" src="{{ asset('resources/js/ListadoProductos.js') }}"></script>
@endpush

@section('contenido')

{{-- 
    PRIMERA SECCIÓN: TEXTO IZQUIERDA / IMAGEN DERECHA 
    Igualando el formato de la tercera sección (padding dual y dimensiones)
--}}
<div class="container-fluid p-0 seccion-principal fondo-gris">
    <div class="row g-0 align-items-center fila-ajuste-dual">
        {{-- Texto Izquierda --}}
        <div class="col-12 col-md-6 p-4 p-md-5 d-flex justify-content-center">
            <div class="limite-ancho-texto">
                <p class="text-uppercase small text-muted mb-5 subtitulo-tradicion">EXCELENCIA Y RECONOCIMIENTO</p>
                
                <h2 class="fw-bold mb-5 display-5 titulo-estilo-serif">
                    Recompensa por el trabajo bien hecho
                </h2>
                
                <div class="texto-contenido-formateado text-justify">
                    <p class="mb-3">
                        El paso del tiempo, acompañado de un trabajo minucioso y adecuadamente desarrollado, nos ha otorgado diferentes homologaciones y certificaciones. Estos reconocimientos nos han servido de valor diferencial, tanto en nuestros sistemas de producción, almacenamiento y distribución, como en la calidad de nuestros productos.
                    </p>
                    &nbsp;
                    <p class="mb-3">
                       Nuestro interés por ofrecer al mercado productos seguros, inocuos y en cumplimiento de los requisitos legales y las especificaciones de nuestros clientes, nos ha llevado a <strong>trabajar bajo los requisitos del estándar de seguridad alimentaria IFS Food</strong>, norma reconocida por la Iniciativa Global de Seguridad Alimentaria (GFSI) , de la cual obtuvimos el certificado en 2018.
                    </p>
                    &nbsp;
                    <p>
                        Además, y no menos importante, gracias al reconocimiento de nuestros clientes, tanto locales como grandes cadenas de supermercados, hemos ido consiguiendo una <strong>firme ampliación y desarrollo del negocio año tras año</strong>. La apertura de nuevas instalaciones, la adquisición de nuevos equipos, la automatización en los procesos de producción y el desarrollo de nuevos formatos son solo parte de la cadena de mejoras que hemos ido consiguiendo desde el nacimiento de El Torrejón.
                    </p>
                </div>

                <div class="mb-4 mt-5 pt-5">
                    <img src="{{ asset('resources/icons/flecha-1.png') }}" alt="Flecha" class="icono-flecha-guia">
                </div>
            </div>
        </div>
        {{-- Imagen Derecha --}}
        <div class="col-12 col-md-6">
            <img src="{{ asset('resources/images/contenido4.jpg') }}" class="img-fluid w-100 imagen-seccion-ajustada" alt="Instalaciones El Torrejón">
        </div>
    </div>
</div>

{{-- 
    SEGUNDA SECCIÓN: SOLO TEXTO (CON IMAGEN DE FONDO MILK.JPG)
--}}
<div class="container-fluid p-0 seccion-principal fondo">
    <div class="row justify-content-center bloque-frase-destacada">
        <div class="col-md-10 text-center">
            <h3 class="lh-base mb-5 frase-innovacion-enfasis">
                A lo largo de nuestra historia hemos ido viendo los resultados del trabajo bien hecho, pero no nos conformamos. El Torrejón sigue apostando por la calidad como máxima empresarial adaptando nuestro trabajo al mercado y a la normativa de la C.E.E.
            </h3>
            <div class="linea-decorativa-corta"></div>
        </div>
    </div>
</div>

{{-- 
    TERCERA SECCIÓN: IMAGEN IZQUIERDA / TEXTO DERECHA
    Aplica 'linea-separadora-productos' para el borde negro final.
--}}
<div class="container-fluid p-0 seccion-principal fondo-blanco linea-separadora-productos">
    <div class="row g-0 align-items-center fila-ajuste-dual">
        {{-- Imagen Izquierda --}}
        <div class="col-12 col-md-6">
            <img src="{{ asset('resources/images/img_contenido_5.jpg') }}" class="img-fluid w-100 imagen-seccion-ajustada" alt="Certificación IFS Food">
        </div>

        {{-- Texto Derecha --}}
        <div class="col-12 col-md-6 p-4 p-md-5 d-flex justify-content-center">
            <div class="limite-ancho-texto">
                <p class="text-uppercase small text-muted mb-5 subtitulo-tradicion">EXCELENCIA Y RECONOCIMIENTO</p>
                
                <h2 class="fw-bold mb-5 display-5 titulo-estilo-serif">
                    Avalados por la certificación IFS Food
                </h2>
                
                <div class="texto-contenido-formateado text-justify">
                    <p class="mb-3">
                        IFS Food es un estándar certificable cuya finalidad es auditar empresas que fabrican alimentos o que envasan productos alimentarios a granel. Esta norma se centra en la <strong> seguridad y calidad alimentaria de los productos, para proporcionar transparencia a lo largo de toda la cadena de suministro.</strong>
                    </p>
                    <p class="mb-3">
                        Fue en el año 2018 cuando recibimos esta certificación que avala nuestra leche y hace que cada día su consumo aumente gracias a la confianza que el mercado y los compradores depositan en nosotros.
                    </p>
                    <p class="mb-3">
                        Son numerosos los beneficios obtenidos a raíz de la implantación del sistema de calidad IFS Food, entre ellos cabe destacar:
                    </p>
                    <ul class="mb-5" id="lista">
                        <li class="mb-2">Incremento de la reputación de la empresa como fabricante de alta calidad.</li>
                        <li class="mb-2">El uso del logo IFS Food y certificado demuestran el cumplimiento de los más elevados estándares.</li>
                        <li class="mb-2">Mejora del uso de los recursos disponibles.</li>
                        <li class="mb-2">Seguimiento de la ejecución de las regulaciones alimentarias.</li>
                        <li class="mb-2">Mejora de la comprensión entre la dirección y el personal en cuanto a normas, procedimientos y buenas prácticas.</li>
                        <li class="mb-2">Mayor flexibilidad a través de la implementación individual gracias a un enfoque fundamentado en el riesgo.</li>
                    </ul>
                </div>
                
                <div class="mt-5">
                    <img id="logo" src="{{ asset('resources/images/logo_ifs.png') }}" alt="Logo IFS Food">
                </div>
            </div>
        </div>
    </div>
</div>

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
@endsection
