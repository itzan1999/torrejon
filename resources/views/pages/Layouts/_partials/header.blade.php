<header class="header">
    <!-- ================= TOP BAR ================= -->
    <div class="top-bar border-bottom border-dark">
        <div class="container">
            <div class="d-flex justify-content-end align-items-stretch">
                <!-- Teléfono y Email -->
                <div class="d-flex">
                    <span class="socialHeader1">
                        <a href="tel:968425069" class="text-dark text-decoration-none" style="font-family: AbrilTilting; font-size: 14px;">
                            968 425 069
                        </a>
                        <a href="mailto:info@lecheeltorrejon.com" class="text-dark text-decoration-none" style="font-family: AbrilTilting; font-size: 14px;">
                            info@lecheeltorrejon.com
                        </a>
                    </span>
                </div>


                <!-- Redes + Usuario -->
                <div class="d-flex align-items-center gap-3">
                    <span class="socialHeader2">
                        <!-- Facebook -->
                        <a href="https://www.facebook.com/Leche-El-Torrej%C3%B3n-106144608554513" target="_blank" class="social-btnHeader rounded-circle p-1">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="#000">
                                <path d="M22 12a10 10 0 1 0-11.5 9.9v-7H8v-3h2.5V9.5A3.5 3.5 0 0 1 14 6h3v3h-3c-.5 0-1 .5-1 1V12H17l-.5 3h-2.5v7A10 10 0 0 0 22 12"/>
                            </svg>
                        </a>

                        <!-- Instagram -->
                        <a href="https://www.instagram.com/lecheeltorrejon" target="_blank" class="social-btnHeader rounded-circle p-1">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="2">
                                <rect x="2" y="2" width="20" height="20" rx="5"/>
                                <circle cx="12" cy="12" r="4"/>
                                <circle cx="17" cy="7" r="1"/>
                            </svg>
                        </a>
                    </span>
       
                    <!-- ================= MI CUENTA ================= -->
                    <!-- Usuario logueado -->
                    @if(auth()->check())
                        {{-- Para sacar que usuario y si es admin --}}
                        @php
                            $usuario = auth()->user()->usuario; // Cuenta → Usuario
                            $esAdmin = $usuario ? $usuario->roles()->where('nombre_rol', 'admin')->exists() : false;
                        @endphp
                        @if($esAdmin)
                            <!-- Admin -->
                            <span class="text-dark">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" fill="#000">
                                    <path d="M12 12q-1.65 0-2.825-1.175T8 8t1.175-2.825T12 
                                    4t2.825 1.175T16 8t-1.175 2.825T12 12m-8 
                                    8v-2.8q0-.85.438-1.562T5.6 14.55q1.55-.775 
                                    3.15-1.162T12 13t3.25.388t3.15 
                                    1.162q.725.375 1.163 1.088T20 17.2V20z"/>
                                </svg>
                                {{ $usuario->username }} <span>(admin)</span>
                                <a href="{{ route('pages.Dashboard.Admin.panelAdmin') }}" class="text-dark text-decoration-none user-btn">
                                    Dashboard
                                </a>
                            </span>
                        @else
                            <!-- Usuario normal -->
                            <a href="{{ route('pages.Dashboard.Usuarios.panelUsuario') }}" class="text-dark text-decoration-none user-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" fill="#000">
                                    <path d="M12 12q-1.65 0-2.825-1.175T8 8t1.175-2.825T12 
                                    4t2.825 1.175T16 8t-1.175 2.825T12 12m-8 
                                    8v-2.8q0-.85.438-1.562T5.6 14.55q1.55-.775 
                                    3.15-1.162T12 13t3.25.388t3.15 
                                    1.162q.725.375 1.163 1.088T20 17.2V20z"/>
                                </svg>
                                {{ $usuario->username }}
                            </a>
                        @endif

                    @else
                        <!-- Usuario NO logueado -->
                        <a href="{{ route('login') }}" class="text-dark text-decoration-none user-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" fill="#000">
                                <path d="M12 12q-1.65 0-2.825-1.175T8 8t1.175-2.825T12 
                                4t2.825 1.175T16 8t-1.175 2.825T12 12m-8 
                                8v-2.8q0-.85.438-1.562T5.6 14.55q1.55-.775 
                                3.15-1.162T12 13t3.25.388t3.15 
                                1.162q.725.375 1.163 1.088T20 17.2V20z"/>
                            </svg>
                            Mi cuenta
                        </a>
                    @endif

                    <!-- ================= CARRITO ================= -->
                    @if(!auth()->check() || (auth()->check() && !$esAdmin))
                        <div class="position-relative d-flex align-items-center">
                            <a href="#" class="text-dark text-decoration-none d-flex align-items-center gap-1 position-relative cart-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" fill="#000">
                                    <path d="M7 18c-1.1 0-2 .9-2 2s.9 2 
                                    2 2s2-.9 2-2s-.9-2-2-2zm10 
                                    0c-1.1 0-2 .9-2 2s.9 2 
                                    2 2s2-.9 2-2s-.9-2-2-2zM7.2 
                                    14h9.6c.8 0 1.5-.5 
                                    1.8-1.2l3-6.8H5.2L4.3 
                                    4H1v2h2l3.6 
                                    7.6-1.4 2.4c-.1.2-.2.5-.2.8 
                                    0 1.1.9 2 2 2h12v-2H7.4l-.2-.3l.8-1.4z"/>
                                </svg>
                                Ver carrito
                                <span id="contadorCarrito" class="cart-badge">
                                    0
                                </span>
                            </a>
                        </div>
                    @endif

                    <!-- ================= LOGOUT ================= -->

                    @if(auth()->check())
                        <form id="logoutForm" action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="btn btn-danger" type="submit">
                                <i class="bi bi-box-arrow-right"></i> Cerrar sesión
                            </button>
                        </form>
                    @endif
    
                </div>
            </div>
        </div>
    </div>


    <!-- ================= NAVBAR ================= -->
    <nav class="navbar navbar-expand-lg navbar-dark py-4 position-relative bg-black">
        <div class="container">
            <div class="w-100 d-flex align-items-center justify-content-between">
                <!-- ================= IZQUIERDA ================= -->
                <div class="d-none d-lg-flex gap-5 gap-xl-6 ps-lg-3 ps-xl-4 justify-content-center py-4">
                    <a href="{{ route('pages.eltorrejon') }}"
                    class="nav-link-custom fw-bold {{ request()->routeIs('pages.eltorrejon') ? 'active' : '' }}">
                        EL TORREJÓN
                    </a>
                    <a href="{{ route('pages.calidad') }}"
                    class="nav-link-custom fw-bold {{ request()->routeIs('pages.calidad') ? 'active' : '' }}">
                        CALIDAD
                    </a>
                    <a href="{{ route('pages.tienda') }}"
                    class="nav-link-custom fw-bold {{ request()->routeIs('pages.tienda') ? 'active' : '' }}">
                        TIENDA
                    </a>
                </div>
                <!-- ================= LOGO ================= -->
                <div class="logo-container d-none d-lg-flex flex-shrink-1 text-center pl-2 px-2" style="display:block;">
                    <a href="{{ route('pages.index') }}">
                        <img src="{{ asset('resources/images/logo_main_white.png') }}" height="55" alt="Logo">
                    </a>
                </div>

                {{-- APARTADO DE MÓVIL --}}
                <!-- Logo IZQUIERDA (visible en móvil) -->
                <div class="navbar-brand d-lg-none position-absolute start-0 ps-3 ms-n4" style="display:block;">
                    <a href="{{ route('pages.index') }}">
                        <img src="{{ asset('resources/images/logo_main_white.png') }}" height="55" alt="Logo">
                    </a>
                </div>
                <!-- ================= DERECHA ================= -->
                <div class="d-flex align-items-center gap-5 gap-xl-6 flex-fill justify-content-end">
                    <div class="d-none d-lg-flex flex-shrink-0 text-center gap-5 gap-xl-6">
                        <a href="{{ route('pages.noticias') }}"
                        class="nav-link-custom fw-bold {{ request()->routeIs('pages.noticias') ? 'active' : '' }}">NOTICIAS</a>
                        <a href="{{ route('pages.contacto') }}"
                        class="nav-link-custom fw-bold {{ request()->routeIs('pages.contacto') ? 'active' : '' }}">CONTACTO</a>
                    </div>
                    <!-- ================= BARRA DE BÚSQUEDA (modificada para línea recta separada sin bordes redondeados ni sombra) ================= -->
                    <form class="d-flex align-items-center search-form" action="/buscar" method="GET" style="max-width: 260px;">
                        <div class="position-relative w-100">
                            <input
                                class="form-control border-0 text-white placeholder-black ps-0 pe-4 py-1 bg-transparent"
                                type="search"
                                name="s"
                                placeholder="Buscar..."
                                style="font-size: 15px; box-shadow: none; outline: none; border-radius: 2;">
                            <div style="height: 1px; background-color: #ffffff; width: calc(100% - 45px); margin-top: 4px;"></div> <!-- Línea recta separada, acortada para no llegar a la lupa -->
                            <button type="submit"
                                    class="position-absolute end-0 top-50 translate-middle-y border-0 bg-transparent p-0 me-1"
                                    style="color: white;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
                <!-- Hamburguesa MÓVIL -->
                <button class="navbar-toggler border-0 pe-3 ms-2 d-lg-none"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#menuPrincipal">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
        </div>
    </nav>

    <!-- ================= MENÚ DESPLEGABLE VISIBILIDAD MÓVIL ================= -->
    <div class="collapse bg-white w-100 border-bottom border-3" id="menuPrincipal" style="border-color: linear-gradient(to right, #8B5A3C, #CD853F, #8B5A3C) !important;">
        <div class="text-center py-2">
            <ul class="navbar-nav flex-column text-end">
                <li class="nav-item"><a class="nav-link fw-bold" href="{{ route('pages.eltorrejon') }}" style=" font-size: 14px; padding: 10px 20px; font-family: AbrilTitlingSb;">EL TORREJÓN</a></li>
                <hr class="my-1">
                <li class="nav-item"><a class="nav-link fw-bold" href="{{ route('pages.calidad') }}" style=" font-size: 14px; padding: 10px 20px; font-family: AbrilTitlingSb;">CALIDAD</a></li>
                <hr class="my-1">
                <li class="nav-item"><a class="nav-link fw-bold" href="{{ route('pages.tienda') }}" style=" font-size: 14px; padding: 10px 20px; font-family: AbrilTitlingSb;">TIENDA</a></li>
                <hr class="my-1">
                <li class="nav-item"><a class="nav-link fw-bold" href="{{ route('pages.noticias') }}" style=" font-size: 14px; padding: 10px 20px; font-family: AbrilTitlingSb;">NOTICIAS</a></li>
                <hr class="my-1">
                <li class="nav-item"><a class="nav-link fw-bold" href="{{ route('pages.contacto') }}" style=" font-size: 14px; padding: 10px 20px; font-family: AbrilTitlingSb;">CONTACTO</a></li>
            </ul>
        </div>
    </div>

</header>
