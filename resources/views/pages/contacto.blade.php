@extends('pages.Layouts.plantillaDefault')

@section('titulo','Contacto - Leche El Torrejon')

@push('js')
    <script type="module" src="{{ asset('resources/js/pages/validations/Contacto/validacionFormularioContacto.js') }}"></script>
@endpush

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('resources/css/pages/Extras/contacto.css') }}"/>
@endpush

@section("contenido")
{{-- 
    'container-fluid' ocupa el 100% del ancho de la pantalla. 
    'p-0' elimina el relleno (padding) lateral por defecto.
--}}
<div class="container-fluid p-0">
    
{{-- 
    'row' activa el sistema de rejilla Flexbox. 
    'g-0' (gutters 0) elimina el espaciado interno entre columnas. 
    'border-bottom border-dark border-2' aplica un borde inferior negro de 2px.
--}}
    <div class="row g-0 border-bottom border-dark border-2">
        
{{-- 
    'col-12' en móvil y 'col-md-6' en escritorio. 
    'bg-light' fondo gris claro y 'order-1' posiciona el elemento primero.
--}}
        <div class="col-12 col-md-6 bg-light order-1 order-md-1">
            
{{-- 
    'img-fluid' hace la imagen responsiva. 
    'w-100 h-100' obliga a la imagen a ocupar todo el contenedor disponible.
--}}
            <img src="{{ asset('resources/images/image_b.jpg') }}" alt="Vaso de leche" class="img-fluid w-100 h-100" style="object-fit: cover; min-height: 300px;">
        </div>
        
{{-- 
    'd-flex' y 'align-items-center' centran el contenido verticalmente.
    'p-4' (móvil) y 'p-lg-5' (PC) gestionan el padding interno.
--}}
        <div class="col-12 col-md-6 d-flex align-items-center p-4 p-lg-5 order-2 order-md-2">
            <div class="w-100 px-lg-4"> 
{{-- 
    'mb-4' margen inferior y 'fw-bold' fuente en negrita.
    'clamp' ajusta el tamaño del texto dinámicamente según el dispositivo.
--}}
                <h1 class="mb-4 fw-bold" style="font-family: serif; font-size: clamp(2rem, 4vw, 3.5rem);">Estamos para ayudarte</h1>
                
                <form method="POST" id="form">
                    @csrf
{{-- 
    'mb-3' aplica un margen inferior para separar los bloques de entrada.
--}}
                    <div class="mb-3">
{{-- 
    'form-label' estilo de etiqueta, 'text-muted' color gris y 'fs-5' tamaño de fuente.
--}}
                        <label for="nombre" class="form-label text-muted fs-5 mb-2">Nombre</label>

{{-- 
    'form-control' estilo de campo, 'rounded-0' elimina esquinas redondeadas.
    'shadow-none' quita la sombra al seleccionar el campo.
--}}
                        <input type="text" name="nombre" id="nombre" class="form-control fs-5 p-2 border border-dark rounded-0 shadow-none"/>
                    </div>
                    <div class="error" data-error="nombre"></div>


                    <div class="mb-3">
                        <label for="email" class="form-label text-muted fs-5 mb-2">Email</label>
                        <input type="email" name="email" id="email" class="form-control fs-5 p-2 border border-dark rounded-0 shadow-none"/>
                    </div>
                    <div class="error" data-error="email"></div>


                    <div class="mb-3">
                        <label for="telefono" class="form-label text-muted fs-5 mb-2">Teléfono</label>
                        <input type="tel" name="telefono" id="telefono" class="form-control fs-5 p-2 border border-dark rounded-0 shadow-none"/>
                    </div>
                    <div class="error" data-error="telefono"></div>


                    <div class="mb-3">
                        <label for="consulta" class="form-label text-muted fs-5 mb-2">Consulta</label>
                        <textarea rows="5" id="consulta" name="consulta" class="form-control fs-5 p-2 border border-dark rounded-0 shadow-none"></textarea>
                    </div>
                    <div class="error" data-error="consulta"></div>
                    
                    
{{-- 
    'form-check' contenedor para checkbox. 'mb-4' margen inferior grande.
--}}
                    <div class="mb-4 form-check">
{{-- 
    'form-check-input' estiliza el cuadro. 'mt-2' ajusta la posición superior.
--}}
                        <input type="checkbox" name="aceptar" id="aceptar" class="form-check-input border-dark rounded-0 mt-2"/>
                        <label for="aceptar" class="form-check-label text-muted fs-5">
                            He leído y acepto la <a href="{{ route('pages.Legal.politicaPrivacidad') }}" class="text-dark">Política de privacidad</a>
                        </label>
                    </div>
                    <div class="error" data-error="check"></div>
                    
{{-- 
    'btn-link' elimina el aspecto de botón sólido. 'p-0' y 'border-0' quitan rellenos y bordes.
--}}
                    <button type="button" id="send" class="btn btn-link p-0 border-0 shadow-none text-start">
                        <img src="{{ asset('resources/icons/icon_arrow_a.svg') }}" alt="Enviar"/>
                    </button>
                </form>
                <div class="resolucion"></div>
            </div>
        </div>
    </div>

{{-- 
    'flex-column' apila los elementos. 'justify-content-center' y 'align-items-center' centran todo.
    'text-center' alinea el texto al medio. 'p-5' da padding uniforme.
--}}
    <div class="row g-0 border-bottom border-dark border-2">
        <div class="col-12 col-md-6 d-flex flex-column justify-content-center align-items-center text-center p-5">
            <div class="px-lg-5">
                <h2 class="fw-bold mb-4" style="font-family: serif; font-size: clamp(2rem, 3.5vw, 3rem);">Planta y oficina</h2>
                <p class="fs-5 mb-0" style="line-height: 1.6;">
                    Polígono Industrial “El Saladar”<br>
                    C/ Balsón de Guillén, 33<br>
                    0850 Totana (Murcia)
                </p>
            </div>
        </div>
        
{{-- 
    'min-height: 300px' asegura visibilidad en móvil. 
    'bg-light' y centrado flex para el contenedor del iframe.
--}}
        <div class="col-12 col-md-6 bg-light d-flex align-items-center justify-content-center" style="min-height: 300px;">
            <iframe title="Planta y oficina" src="https://www.google.com/maps?q=El+Torrej%C3%B3n,+Pol%C3%ADgono+Industrial+%22El+Saladar%22+C,+C.+Balson+de+Guill%C3%A9n+-Sa,+3,+30850+Totana,+Murcia,+Espa%C3%B1a&z=16&output=embed" style="border:0; width:100%; height:100%; min-height:300px;" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>

{{-- 
    'border-bottom: 1px solid black' para cerrar la estructura visual de la última fila.
--}}
    <div class="row g-0"> 
        <div class="col-12 col-md-6 d-flex flex-column justify-content-center align-items-center text-center p-5">
            <div class="px-lg-5">
                <h2 class="fw-bold mb-4" style="font-family: serif; font-size: clamp(2rem, 3.5vw, 3rem);">Planta de nuevos desarrollos</h2>
                <p class="fs-5 mb-0" style="line-height: 1.6;">
                    Polígono Industrial El Saladar<br>
                    Calle El Torrejón<br>
                    Granja El Torrejon<br>
                    Diputación La Ñorica<br>
                    30850 Totana (Murcia)
                </p>
            </div>
        </div>
        
        <div class="col-12 col-md-6 bg-light d-flex align-items-center justify-content-center" style="min-height: 300px; border-bottom: 1px solid black;">
            <iframe title="Planta nuevos desarrollos" src="https://www.google.com/maps?ll=37.779195,-1.458281&z=16&t=m&hl=es-ES&gl=US&mapclient=embed&q=C.+el+Torrej%C3%B3n+30850+Murcia&output=embed" style="border:0; width:100%; height:100%; min-height:300px;" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>

</div>
@endsection