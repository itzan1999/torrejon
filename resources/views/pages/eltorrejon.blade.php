@extends('pages.Layouts.plantillaDefault')


@section('titulo','El Torrejón - Leche El Torrejon')


@push('styles')
    <link rel="stylesheet" href="{{ asset('resources/css/pages/Extras/elTorrejon.css') }}"/>
    <link rel="stylesheet" href="{{ asset('resources/css/ListadoProductos.css') }}"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>
@endpush


@push('js')
    <script type="module" src="{{ asset('resources/js/ListadoProductos.js') }}"></script>
@endpush


@section('contenido')




<!--
    PRIMERA CUADRANTE
-->
<div class="container-fluid p-0 seccion-principal fondo-gris">
    <!--
        PRIMERA SECCIÓN
    -->
    <div class="row g-0 align-items-center fila-ajuste-superior">
        <div class="col-md-6">
            <img src="{{ asset('resources/images/contenido1-1.jpg') }}" class="img-fluid w-100 imagen-seccion-ajustada" alt="Más de 30 años">
        </div>
       
        <div class="col-md-6 p-4 p-md-5 d-flex justify-content-center">
            <div class="limite-ancho-texto">
                <p class="text-uppercase small text-muted mb-5 subtitulo-tradicion">SABOR A TRADICIÓN</p>
               
                <h2 class="fw-bold mb-5 display-5 titulo-estilo-serif">
                    Más de 30 años de valores saludables
                </h2>
                <div class="texto-contenido-formateado text-justify">
                    <p class="mb-3">
                        El Torrejón es una empresa nacida del esfuerzo y el sacrificio familiar en el año 1988, trasladando nuestros valores y pasión por los lácteos de generación en generación.
                    </p>
                    &nbsp;
                    <p class="mb-3">
                        Una ganadería con poco más de una veintena de animales, instalaciones con los elementos más básicos para empezar a producir, elaboraciones artesanales y la bicicleta como nuestro aliado a la hora de realizar los transportes. Nuestros inicios estuvieron marcados por limitaciones en cuanto a medios pero nuestras ganas e ilusión estaban por encima de todo.
                    </p>
                    &nbsp;
                    <p class="mb-3">
                        Gracias al incansable trabajo de los pioneros de El Torrejón, actualmente contamos con la confianza de muchas familias que nos han hecho un hueco en su cocina. Calidad, tradición y familia siempre serán nuestros valores más preciados y seguiremos luchando para que se reflejen en nuestra leche.
                    </p>
                    &nbsp;
                    <p>
                        A finales de ese mismo año comenzamos a fabricar, elaborar, transformar, distribuir y almacenar leche tratada térmicamente poniendo de manifiesto la filosofía que mantenemos a día de hoy:
                        <strong>“Buscar siempre la ilusión por hacer las cosas bien y crear el producto de mejor calidad”.</strong>
                    </p>
                </div>
                <div class="mb-4 mt-5 pt-5">
                    <img src="{{ asset('resources/icons/flecha-1.png') }}" alt="Flecha" class="icono-flecha-guia">
                </div>
            </div>
        </div>
    </div>


    <!--
        SEGUNDA SECCIÓN
    -->
    <div class="row g-0 align-items-center fila-ajuste-inferior">
        <div class="col-md-6 p-4 p-md-5 d-flex justify-content-center">
            <div class="limite-ancho-texto">
                <h2 class="fw-bold mb-5 display-5 titulo-estilo-serif">
                    Somos equipo, somos familia
                </h2>
                <div class="texto-contenido-formateado text-justify">
                    <p class="mb-3">
                        En El Torrejón cuidamos nuestro sector y nuestro entorno con el constante propósito de poner a disposición de los consumidores la mejor leche del mercado.
                    </p>
                    &nbsp;
                    <p class="mb-3">
                        La elaboración es un procedimiento en el que participan equipos y cadenas de personal dispuestos a dar lo mejor de sí mismos día tras día para un producto inigualable. <strong>Nuestros trabajadores disfrutan el reto de enamorar al consumidor a través de nuestros productos y valores.</strong> Su pasión, integridad y cercanía es lo que nos ha otorgado la importancia de la que hoy en día puede presumir El Torrejón.
                    </p>
                    &nbsp;
                    <p class="mb-3">
                        Cabe destacar la importancia de la salud y el bienestar de toda la plantilla, independientemente de su ocupación y modalidad de contratación. De la misma manera, tenemos en cuenta y garantizamos la seguridad de visitas y otras partes interesadas.
                    </p>
                    &nbsp;
                    <p>
                        <strong>El Torrejón lo formamos todos.</strong>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-6 mt-5 pt-5">
            <img src="{{ asset('resources/images/contenido2.jpg') }}" class="img-fluid w-100 imagen-seccion-ajustada" alt="Somos equipo">
        </div>
    </div>
</div>




<!--
    SEGUNDO CUADRANTE
-->
<div class="container-fluid p-0 seccion-principal fondo-blanco">
    <!--
        PRIMERA SECCIÓN
    -->
    <div class="row g-0 align-items-start fila-ajuste-superior">
        <div class="col-md-6">
            <img src="{{ asset('resources/images/contenido3.jpg') }}" class="img-fluid w-100 imagen-seccion-ajustada" alt="Origen 100% natural">
        </div>


        <div class="col-md-6 p-4 p-md-5 d-flex justify-content-center pt-md-4">
            <div class="limite-ancho-texto">
                <p class="text-uppercase small text-muted mb-5 subtitulo-tradicion">ELEGIR EL TORREJÓN ES ELEGIR GARANTÍA DE SALUD</p>
               
                <h2 class="fw-bold mb-5 display-5 titulo-estilo-serif">
                    Leche de origen 100% natural
                </h2>
               
                <div class="texto-contenido-formateado text-justify">
                    <p class="mb-3">
                        El 98% de la leche que elaboramos procede de las granjas de la Región de Murcia. Una vez obtenemos la materia prima comienza el proceso de calidad de nuestro producto con un estricto control de temperatura. De esta forma garantizamos la máxima frescura y naturalidad.
                    </p>
                    &nbsp;
                    <p class="mb-3">
                        El rigor y la disciplina están presentes en la evolución del tratamiento de la leche. Tras la superación de análisis, se lleva a cabo la pasteurización, fase clave del secreto de la calidad, el sabor y la textura de El Torrejón.
                    </p>
                    &nbsp;
                    <p class="mb-3">
                        La pasteurización da paso al envasado de la leche entera, mientras que la nata es separada de la leche en un proceso natural para las variedades desnatada y semidesnatada.
                    </p>
                    &nbsp;
                    <p class="mb-3">
                        En nuestra compañía consideramos que solo respetando el origen natural de lo que consumimos, la población disfrutará de una mejor alimentación y mejor condición vital.
                    </p>
                    &nbsp;
                    <p class="mb-4">
                        <strong>¡Consumir El Torrejón es consumir el sabor, la textura y el aroma de la leche de calidad!</strong>
                    </p>
                </div>


                <div class="mb-4 mt-5 pt-5">
                    <img src="{{ asset('resources/icons/flecha-1.png') }}" alt="Flecha" class="icono-flecha-guia">
                </div>
            </div>
        </div>
    </div>


    <!--
        SEGUNDA SECCIÓN
    -->
    <div class="row justify-content-center bloque-frase-destacada">
        <div class="col-md-10 text-center">
            <h3 class="lh-base mb-5 frase-innovacion-enfasis">
                Gracias a nuestra innovación e inversión en las últimas tecnologías, El Torrejón alcanza una vida útil de 18 días en la nevera sin utilizar aditivos ni conservantes.
            </h3>
            <div class="linea-decorativa-corta"></div>
        </div>
    </div>
</div>




<!--
    TERCER CUADRANTE
-->
<div class="container-fluid p-0 seccion-principal fondo-gris linea-separadora-productos">
    <!--
        PRIMERA SECCIÓN
    -->
    <div class="row g-0 align-items-center fila-ajuste-superior">
        <div class="col-md-6 p-4 p-md-5 d-flex justify-content-center">
            <div class="limite-ancho-texto">
                <h2 class="fw-bold mb-5 display-5 titulo-estilo-serif">
                    Cualificación e innovación, valor seguro
                </h2>
               
                <div class="texto-contenido-formateado text-justify">
                    <p class="mb-3 ">
                        Los valores de la compañía tienen como epicentro nuestro bien más preciado, la leche. Todo oscila alrededor del producto lácteo, a partir del cual se toman las decisiones más relevantes para su optimización.
                    </p>
                    &nbsp;
                    <p class="mb-3 ">
                        La experiencia nos ha enseñado que para conseguir un buen producto es necesario cuidar todos los detalles. La calidad de nuestra leche viene dada por la trazabilidad del proceso productivo. Esta condición no hubiera sido posible sin nuestros <strong>más de 30 años de trayectoria</strong> en el sector lácteo.
                    </p>
                    &nbsp;
                    <p>
                        La tradición y la calidad se ven implementadas en el momento en que la innovación se abre paso en nuestras instalaciones. Contamos con un equipo de profesionales altamente cualificado y tecnología innovadora.
                    </p>
                </div>
            </div>
        </div>


        <div class="col-md-6">
            <img src="{{ asset('resources/images/img_contenido_6.jpg') }}" class="img-fluid w-100 imagen-seccion-ajustada" alt="Cualificación e innovación">
        </div>
    </div>


    <!--
        SEGUNDA SECCIÓN
    -->
    <div class="row g-0 align-items-center fila-ajuste-inferior">
        <div class="col-md-6 mt-5 pt-5">
            <img src="{{ asset('resources/images/contenido7.jpg') }}" class="img-fluid w-100 imagen-seccion-ajustada" alt="A la vanguardia">
        </div>


        <div class="col-md-6 p-4 p-md-5 d-flex justify-content-center">
            <div class="limite-ancho-texto">
                <h2 class="fw-bold mb-5 display-5 titulo-estilo-serif">
                    A la vanguardia del sector
                </h2>
               
                <div class="texto-contenido-formateado text-justify">
                    <p class="mb-3">
                        En El Torrejón estamos al tanto de los progresos tecnológicos y científicos para implantarlos en el tratamiento y la transformación de la materia prima. <strong>Con la vanguardia por bandera, conseguimos desarrollar el producto de una forma más adaptada a las necesidades del consumidor.</strong>
                    </p>
                    &nbsp;
                    <p class="mb-3">
                        Adecuar instalaciones al rápido y cambiante estilo de vida actual nos proporciona la posibilidad de ser más atrayentes en el punto de venta.
                    </p>
                    &nbsp;
                    <p>
                        Al mismo ritmo que renovamos procesos y equipamiento, nuestro transporte se habitúa a esta normalidad para garantizar unas condiciones óptimas para su consumo final.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>




<!--
   LISTA DE PRODUCTOS
-->
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
