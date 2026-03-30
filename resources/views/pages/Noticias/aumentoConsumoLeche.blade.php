@extends('pages.Layouts.plantillaDefault')

@section('titulo','Aumenta el consumo de leche fresca en España - Leche El Torrejon')

@push('styles')
    <link rel="stylesheet" href="{{ asset('resources/css/pages/Noticias/recetas.css') }}">
@endpush

@section('contenido')

<!-- IMAGEN FIJA SUPERIOR -->
<div class="container-fluid receta-fija p-0">
    <img src="{{ asset('resources/images/article-recortada.jpg') }}"
        alt="Aumenta el consumo de leche fresca en España"
        class="img-fluid receta-fija-img">
</div>

<!-- CONTENEDOR DE LA RECETA -->
<div class="container receta-container py-5">
    <div class="row g-5 align-items-stretch">
        <!-- COLUMNA IZQUIERDA -->
        <div class="col-12 col-md-5 d-flex flex-column gap-3 border-end border-gray">

            <h1 class="receta-titulo">Aumenta el consumo de leche fresca en España</h1>

            <p class="receta-fecha">3-Feb-2022</p>

            <!-- Redes sociales -->
            <div class="d-flex gap-2 receta-redes">
                <a href="https://www.facebook.com/Leche-El-Torrej%C3%B3n-106144608554513"
                    target="_blank"
                    class="social-btnChocolateConsumo">
                    <svg viewBox="0 0 24 24" fill="#000" width="20" height="20">
                        <path d="M22 12a10 10 0 1 0-11.5 9.9v-7H8v-3h2.5V9.5A3.5 3.5 0 0 1 14 6h3v3h-3c-.5 0-1 .5-1 1V12H17l-.5 3h-2.5v7A10 10 0 0 0 22 12" />
                    </svg>
                </a>

                <a href="https://x.com/intent/post?url=https%3A%2F%2Flecheeltorrejon.com%2Farticle%2Freceta-chocolate-a-la-taza%2F&text=Receta%20Chocolate%20a%20la%20taza"
                    target="_blank"
                    class="social-btnChocolateConsumo">
                    <svg viewBox="0 0 512 512" width="20" height="20">
                        <path d="M256,0c141.29,0 256,114.71 256,256c0,141.29 -114.71,256 -256,256c-141.29,0 -256,-114.71 -256,-256c0,-141.29 114.71,-256 256,-256Zm-45.091,392.158c113.283,0 175.224,-93.87 175.224,-175.223c0,-2.682 0,-5.364 -0.128,-7.919c12.005,-8.684 22.478,-19.54 30.779,-31.928c-10.983,4.853 -22.861,8.174 -35.377,9.706c12.772,-7.663 22.478,-19.668 27.076,-34.099c-11.878,-7.024 -25.032,-12.132 -39.081,-14.942c-11.239,-12.005 -27.203,-19.412 -44.955,-19.412c-33.972,0 -61.558,27.586 -61.558,61.558c0,4.853 0.511,9.578 1.66,14.048c-51.213,-2.554 -96.552,-27.075 -126.947,-64.368c-5.237,9.068 -8.302,19.668 -8.302,30.907c0,21.328 10.856,40.23 27.459,51.213c-10.09,-0.255 -19.541,-3.065 -27.842,-7.662l0,0.766c0,29.885 21.2,54.661 49.425,60.409c-5.108,1.404 -10.6,2.171 -16.219,2.171c-3.96,0 -7.791,-0.383 -11.622,-1.15c7.79,24.521 30.523,42.274 57.471,42.784c-21.073,16.476 -47.637,26.31 -76.501,26.31c-4.981,0 -9.834,-0.256 -14.687,-0.894c26.948,17.624 59.387,27.841 94.125,27.841Z" />
                    </svg>
                </a>
            </div>

            <!-- Imagen -->
            <div class="receta-imagen">
                <img src="{{ asset('resources/images/img_a.jpg') }}"
                    alt=" Ganadero dando alimento a las vacas"
                    class="img-fluid shadow-sm">

                <!-- FLECHAS DE NAVEGACIÓN DEBAJO DE LA IMAGEN -->
                <div class="receta-flechas d-flex justify-content-center align-items-center mt-3">
                    <!-- Flecha izquierda -->
                    <a href="#">
                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg"
                            width="91.607px" height="29.621px" viewBox="0 0 91.607 29.621">
                            <line fill="none" stroke="#030104" stroke-width="0.5" stroke-miterlimit="10"
                                x1="91.254" y1="14.806" x2="0" y2="14.806"/>
                            <polyline fill="none" stroke="#030104" stroke-width="0.5" stroke-miterlimit="10"
                                    points="14.985,0.177 0.354,14.809 0.354,14.812 14.985,29.445"/>
                        </svg>
                    </a>

                    <!-- Flecha derecha -->
                    <a href="#">
                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg"
                            width="91.607px" height="29.621px" viewBox="0 0 91.607 29.621">
                            <line fill="none" stroke="#030104" stroke-width="0.5" stroke-miterlimit="10"
                                x1="0" y1="14.806" x2="91.254" y2="14.806"/>
                            <polyline fill="none" stroke="#030104" stroke-width="0.5" stroke-miterlimit="10"
                                    points="76.622,0.177 91.253,14.809 91.253,14.812 76.622,29.445"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- COLUMNA DERECHA -->
        <div class="col-12 col-md-7 receta-texto">
            <p>
                El consumo de leche fresca se dispara un 30% en el primer semestre del año, 
                mientras las demás leches sólo crecen un 2,4% en sus ventas. Estas impactantes cifras ilustran que, 
                incluso en tiempos de crisis económica, los consumidores siguen solicitando productos de calidad. 
                La leche fresca es uno de esos productos y por ello es cada vez más demandada por los consumidores que buscan 
                recuperar el auténtico sabor de la leche de siempre, conservado gracias a su tratamiento térmico más suave.
            </p>

            <p>
                El mercado de la leche experimenta la demanda de productos naturales por parte de los consumidores, que apuestan por la leche fresca.
                 La zona centro (Madrid y las dos Castillas) es la que más leche fresca consume de España.
                Segón el informe realizado por la consultora Nielsen para Lauki-Lactel, 
                en los seis primeros meses de 2009 el consumo de leche fresca ha experimentado un aumento del 30% en toda España 
                (en comparación con este mismo período en el 2008). Esta cifra contrasta con el resto de la categoría de leche, 
                que ha crecido sólo un 2,4 % en el mismo período. En el mes de junio se vendieron en toda España 3,6 millones de litros de leche 
                fresca, lo que supone que cada minuto se venden en España 22 litros de leche fresca.
            </p>

            <p>La leche fresca combina un cuidado proceso de elaboración que comienza en las granjas y 
                un tratamiento térmico muy suave en fábrica: la pasterización suave. 
                En el proceso UHT, la pasterización se lleva a cabo a temperaturas de hasta 145oC durante al menos dos segundos, 
                en el caso de la leche fresca, la pasterización se realiza entre 72oC y 90oC durante 15 segundos.
            </p>
        </div>

    </div>
</div>

<!-- NAVEGACIÓN INFERIOR A ANCHO COMPLETO -->
<div class="container-fluid p-0">
    <div class="receta-navegacion-full border-top py-4">
        <div class="d-flex justify-content-center align-items-center gap-3">

            <a href="{{ route('pages.Noticias.recetaChocolate') }}" class="receta-icono">
                <svg viewBox="0 0 48 48" width="48" height="48" fill="none" stroke="#000" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="24" cy="24" r="22"></circle>
                    <path d="M28 16 L16 24 L28 32"></path>
                </svg>
            </a>

            <a href="{{ route('pages.noticias') }}" class="receta-volver">
                Volver a Noticias
            </a>

        </div>
    </div>
</div>

@endsection