@extends('pages.Layouts.plantillaDefault')

@section('titulo','Noticias - Leche El Torrejon')

@push('styles')
    <link rel="stylesheet" href="{{ asset('resources/css/pages/Noticias/noticias.css') }}">
@endpush

@section('contenido')

<div class="container my-5">
    <div class="news-grid-wrapper">
        <div class="news-grid">
            <!-- Tarjeta 1 -->
            <div class="card">
                {{-- Imagen --}}
                <a href="{{ route('pages.Noticias.recetaChocolate') }}" class="image-hover">
                    <img src="{{ asset('resources/images/torrejon-cabecera-recetas-500x500.jpg') }}" 
                    class="card-img-top">
                </a>
                {{-- Cuerpo --}}
                <div class="card-body">
                    <div>
                        <h5 class="card-title fw-bold">Receta Chocolate a la taza</h5>
                        <p class="card-text">
                            Cómo hacer chocolate a la taza. En invierno, cuando las temperaturas bajan,
                            una taza de chocolate caliente nos ayuda a calmar el cuerpo y entrar en calor.
                            Hoy os explicamos cómo prepararlo de forma fácil y rápida.
                        </p>
                    </div>

                    <div class="card-arrow-border">
                        <a href="{{ route('pages.Noticias.recetaChocolate') }}">
                            <img src="{{ asset('resources/icons/icon_arrow_a.svg') }}" width="60">
                        </a>
                    </div>
                </div>
            </div>

            <!-- Tarjeta 2 -->
            <div class="card">
                {{-- Imagen --}}
                <a href="{{ route('pages.Noticias.aumentoConsumoLeche') }}" class="image-hover">
                    <img src="{{ asset('resources/images/article-500x500.jpg') }}" 
                        class="card-img-top">
                </a>
                {{-- Cuerpo --}}
                <div class="card-body">
                    <div>
                        <h5 class="card-title fw-bold">Aumenta el consumo de leche fresca en España</h5>
                        <p class="card-text">
                            El mercado de la leche experimenta la demanda de productos naturales por parte
                            de los consumidores, que apuestan por la leche fresca.
                        </p>
                    </div>

                    <div class="card-arrow-border">
                        <a href="{{ route('pages.Noticias.aumentoConsumoLeche') }}">
                            <img src="{{ asset('resources/icons/icon_arrow_a.svg') }}" width="60">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
