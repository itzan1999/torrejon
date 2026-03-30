@extends('pages.Layouts.plantillaDefault')

@section('titulo','Receta Chocolate a la taza - Leche El Torrejon')

@push('styles')
    <link rel="stylesheet" href="{{ asset('resources/css/pages/Noticias/recetas.css') }}">
@endpush

@section('contenido')

<!-- IMAGEN FIJA SUPERIOR --> 
<div class="container-fluid receta-fija p-0"> 
    <img src="{{ asset('resources/images/torrejon-cabecera-recetas-2000x800-recortada.jpg') }}" 
         alt="Receta Chocolate a la taza" 
         class="img-fluid receta-fija-img"> 
</div>

<!-- CONTENEDOR DE LA RECETA -->
<div class="container receta-container py-5">
    <div class="row g-5 align-items-stretch">
        <!-- COLUMNA IZQUIERDA -->
        <div class="col-12 col-md-5 d-flex flex-column gap-3 border-end border-gray">

            <h1 class="receta-titulo">Receta Chocolate a la taza</h1>

            <p class="receta-fecha">3-Feb-2022</p>

            <!-- Redes sociales -->
            <div class="d-flex gap-2 receta-redes">
                <a href="https://www.facebook.com/Leche-El-Torrej%C3%B3n-106144608554513"
                   target="_blank"
                   class="social-btnChocolateConsumo">
                    <svg viewBox="0 0 24 24" fill="#000" width="20" height="20">
                        <path d="M22 12a10 10 0 1 0-11.5 9.9v-7H8v-3h2.5V9.5A3.5 3.5 0 0 1 14 6h3v3h-3c-.5 0-1 .5-1 1V12H17l-.5 3h-2.5v7A10 10 0 0 0 22 12"/>
                    </svg>
                </a>

                <a href="https://x.com/intent/post?url=https%3A%2F%2Flecheeltorrejon.com%2Farticle%2Freceta-chocolate-a-la-taza%2F&text=Receta%20Chocolate%20a%20la%20taza"
                   target="_blank"
                   class="social-btnChocolateConsumo">
                    <svg viewBox="0 0 512 512" width="20" height="20">
                        <path d="M256,0c141.29,0 256,114.71 256,256c0,141.29 -114.71,256 -256,256c-141.29,0 -256,-114.71 -256,-256c0,-141.29 114.71,-256 256,-256Zm-45.091,392.158c113.283,0 175.224,-93.87 175.224,-175.223c0,-2.682 0,-5.364 -0.128,-7.919c12.005,-8.684 22.478,-19.54 30.779,-31.928c-10.983,4.853 -22.861,8.174 -35.377,9.706c12.772,-7.663 22.478,-19.668 27.076,-34.099c-11.878,-7.024 -25.032,-12.132 -39.081,-14.942c-11.239,-12.005 -27.203,-19.412 -44.955,-19.412c-33.972,0 -61.558,27.586 -61.558,61.558c0,4.853 0.511,9.578 1.66,14.048c-51.213,-2.554 -96.552,-27.075 -126.947,-64.368c-5.237,9.068 -8.302,19.668 -8.302,30.907c0,21.328 10.856,40.23 27.459,51.213c-10.09,-0.255 -19.541,-3.065 -27.842,-7.662l0,0.766c0,29.885 21.2,54.661 49.425,60.409c-5.108,1.404 -10.6,2.171 -16.219,2.171c-3.96,0 -7.791,-0.383 -11.622,-1.15c7.79,24.521 30.523,42.274 57.471,42.784c-21.073,16.476 -47.637,26.31 -76.501,26.31c-4.981,0 -9.834,-0.256 -14.687,-0.894c26.948,17.624 59.387,27.841 94.125,27.841Z"/>
                    </svg>
                </a>
            </div>

            <!-- Imagen -->
            <div class="receta-imagen">
                <img src="{{ asset('resources/images/torrejon-feed-ene-04-1024x1024.jpg') }}"
                     alt="Chocolate a la taza"
                     class="img-fluid shadow-sm">
            </div>

        </div>

        <!-- COLUMNA DERECHA -->
        <div class="col-12 col-md-7 receta-texto">

            <p>
                <strong>Cómo hacer chocolate a la taza.</strong>
                En invierno, cuando las temperaturas bajan, una taza de chocolate caliente
                nos ayuda a calmar el cuerpo y entrar en calor. Hoy os explicamos cómo
                prepararlo de forma fácil y rápida.
            </p>

            <p>
                El chocolate que se suele encontrar en cafeterías y chocolaterías suele
                prepararse con agua, pero en esta receta lo preparamos al estilo francés,
                con nuestra <strong>leche fresca entera El Torrejón</strong>, añadiendo ese
                toque graso para que quede más espeso y con más sabor.
            </p>

            <h5 class="fw-bold mt-4">Ingredientes</h5>
            <ul>
                <li>400 g de chocolate negro en tableta ó 250 g de cacao en polvo</li>
                <li>1 litro de leche fresca entera El Torrejón</li>
                <li>Sal</li>
                <li>Azúcar al gusto</li>
            </ul>

            <h5 class="fw-bold mt-4">Preparación del chocolate a la taza</h5>
            <ol>
                <li>
                    Calentamos la <strong>leche fresca entera El Torrejón</strong> en una
                    cazuela. Si vamos a utilizar un chocolate muy puro, añadiremos el azúcar
                    en este punto para endulzar la leche. Añadimos el chocolate y una pizca de sal. 
                    Puedes darle un toque aromatizado añadiendo en este momento una rama de canela o vainilla.
                </li>
                <li>
                    Removemos con una cuchara de madera, hasta que el cacao se integre bien mientras se funde.
                </li>
                <li>
                    Cuando empiece a hervir lo retiramos del fuego y ¡listo!
                </li>
                <li>
                    Si quieres que tenga más consistencia, lo puedes poner de nuevo al fuego hasta que comience a hervir otra vez, con cuidado de que no se queme. De esta forma quedará más espeso.
                </li>
            </ol>
        </div>

    </div>
</div>

<!-- NAVEGACIÓN INFERIOR A ANCHO COMPLETO -->
<div class="container-fluid p-0">
    <div class="receta-navegacion-full border-top py-4">
        <div class="d-flex justify-content-center align-items-center gap-3">

            <a href="{{ route('pages.noticias') }}" class="receta-volver">
                Volver a Noticias
            </a>

            <a href="{{ route('pages.Noticias.aumentoConsumoLeche') }}" class="receta-icono">
                <svg viewBox="0 0 48 48" width="48" height="48" fill="none" stroke="#000" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="24" cy="24" r="22"></circle>
                    <path d="M20 16 L32 24 L20 32"></path>
                </svg>
            </a>

        </div>
    </div>
</div>

@endsection
