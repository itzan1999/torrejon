<footer class="footer py-4">
    <div class="container py-4 pb-0">
        <!-- Sección Principal (Contacto, Logo, Redes) -->
        <div class="row align-items-center mb-4">
            <!-- Contacto Izquierda -->
            <div class="col-12 col-md-4 text-center text-md-start order-2 order-md-9 py-md-3 px-3 px-md-4 mt-4 mt-md-1 d-flex flex-column gap-2">
                <br>
                <p class="fw-bold mb-0" style="font-family: AbrilTitlingSb; color: black; font-size: 15px;">EL TORREJÓN, S.L</p>
                <a href="tel:968425069" class="text-dark text-decoration-none mb-0" style="font-family: AbrilTitlingSb; color: black; font-size: 15px;">
                    968 425 069
                </a>
                <a href="mailto:info@lecheeltorrejon.com" class="text-dark text-decoration-none mb-0" style="font-family: AbrilTitlingSb; color: black; font-size: 15px;">
                    info@lecheeltorrejon.com
                </a>
                <br>
            </div>

            <!-- Logo Centro -->
            <div class="col-12 col-md-4 text-center order-first order-md-2">
                <a href="{{ route('pages.index') }}" class="text-decoration-none">
                    <img src="{{ asset('resources/images/logo_main_black.png') }}" height="70" alt="Logo" class="mb-2">
                </a>
            </div>

            <!-- Redes Sociales Derecha -->
            <div class="col-12 col-md-4 d-flex gap-3 justify-content-center justify-content-md-end order-3 order-md-3">
                <!-- Facebook -->
                <a href="https://www.facebook.com/Leche-El-Torrej%C3%B3n-106144608554513" target="_blank" class="social-btn rounded-circle p-2" >
                    <svg fill="#222" width="20" height="20" viewBox="0 0 24 24">
                        <path d="M22 12a10 10 0 1 0-11.5 9.9v-7H8v-3h2.5V9.5A3.5 3.5 0 0 1 14 6h3v3h-3c-.5 0-1 .5-1 1V12H17l-.5 3h-2.5v7A10 10 0 0 0 22 12"/>
                    </svg>
                </a>
                
                <!-- Instagram -->
                <a href="https://www.instagram.com/lecheeltorrejon" target="_blank" class="social-btn rounded-circle p-2">
                    <svg fill="none" stroke="#222" stroke-width="2" width="20" height="20" viewBox="0 0 24 24">
                        <rect x="2" y="2" width="20" height="20" rx="5"/>
                        <circle cx="12" cy="12" r="4"/>
                        <circle cx="17" cy="7" r="1"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
    
    {{-- Contenedor para la sección de legalidad --}}
    <div class="container-fluid mt-23 legal">
        <div class="container pb-0">
            <div class="row pt-4 pb-0">
                <div class="col-12 d-flex flex-column flex-md-row flex-wrap justify-content-center justify-content-md-between align-items-center text-center text-md-start gap-2 gap-md-1">
                    <nav class="d-flex flex-wrap gap-2 gap-md-3 ps-md-3 justify-content-center justify-content-md-start order-2 order-md-1">
                        <a class="text-decoration-none text-dark" href="{{ route('pages.Legal.avisoLegal') }}">Aviso legal</a>
                        <a class="text-decoration-none text-dark" href="{{ route('pages.Legal.politicaPrivacidad') }}">Política de privacidad</a>
                        <a class="text-decoration-none text-dark" href="{{ route('pages.Legal.politicaProteccionDeDatosPersonales') }}" >Política de protección de datos personales</a>
                        <a class="text-decoration-none text-dark" href="{{ route('pages.Legal.politicaCookies') }}">Política de cookies</a>
                    </nav>
                    <p class="copy text-dark mb-0" style="font-size: 12px; order: 2;">Copyright {{ date('Y') }}</p>
                </div>
            </div>
        </div>
    </div>
</footer>