
<aside class="sidebar-user-panel bg-light col-12 col-md-6 order-1">
    {{-- Para sacar que usuario y si es admin --}}
    @php
        $usuario = auth()->user()->usuario;
        $esAdmin = $usuario ? $usuario->roles()->where('nombre_rol', 'admin')->exists() : false;
    @endphp
    @if (!$esAdmin)
    
    <div class="sidebar-group">
        <h6 class="group-title">
            <i class=" bi bi-box-seam"></i> Pedidos y devoluciones
        </h6>
        <nav class="nav-links">
            <a href="#"><i class="fas fa-box-open"></i> Pedidos, devoluciones y facturas</a>
            <a href="#"><i class="fas fa-calendar-times"></i> Pedidos cancelados</a>
            <a href="#"><i class="fas fa-history"></i> Historial de devoluciones</a>
            <a href="#"><i class="fas fa-download"></i> Productos digitales</a>
            <a href="#"><i class="fas fa-shield-alt"></i> Área de seguros</a>
        </nav>
    </div>

    <hr class="sidebar-line">

    <div class="sidebar-group">
        <h6 class="group-title">
            <i class="bi bi-person-circle"></i> Mi cuenta
        </h6>
        <nav class="nav-links">
            <a href="{{ route('pages.Dashboard.Usuarios.Perfil.perfilUsuario') }}"><i class="fas fa-user-cog"></i> Mi perfil</a>
            <a href="#"><i class="fas fa-map-marker-alt"></i> Mis direcciones</a>
            <a href="#"><i class="fas fa-sync-alt"></i> Mis suscripciones</a>
            <a href="#"><i class="fas fa-file-alt"></i> Mis documentos</a>
            <a href="#"><i class="fas fa-heart"></i> Lista de deseos</a>
            <a href="#"><i class="fas fa-envelope"></i> Mensajes</a>
            <a href="#"><i class="fas fa-star"></i> Opiniones</a>
        </nav>
    </div>

    <hr class="sidebar-line">

    <div class="sidebar-group">
        <h6 class="group-title">
            <i class="bi bi-credit-card-2-back-fill"></i> Pago
        </h6>
        <nav class="nav-links">
            <a href="#"><i class="fas fa-id-card"></i> Tarjetas vinculadas</a>
            <a href="#"><i class="fas fa-ticket-alt"></i> Cupones descuento</a>
            <a href="#"><i class="fas fa-wallet"></i> Saldos a favor</a>
            <a href="#"><i class="fas fa-gift"></i> Tarjetas regalo</a>
        </nav>
    </div>

    <hr class="sidebar-line">

    <div class="sidebar-group">
        <h6 class="group-title">
           <i class="bi bi-question-lg"></i> ¿Necesitas ayuda?
        </h6>
        <nav class="nav-links">
            <a href="#"><i class="fas fa-tools"></i> Devoluciones y Garantías</a>
            <a href="#"><i class="fas fa-gift"></i> Devolver un regalo</a>
            <a href="#"><i class="fas fa-headset"></i> Centro de soporte</a>
        </nav>
    </div>

    <hr class="sidebar-line">

    <div class="sidebar-footer">
        <a href="#" class="footer-link">
            <i class="bi bi-person-slash"></i> Borrar cuenta
        </a>
    </div>
    @else
<aside class="sidebar-admin-panel"> {{-- Clase raíz única --}}
    <div class="admin-sidebar-group">
        <nav class="admin-nav-links">
            <a href="{{ route("pages.Dashboard.Admin.Usuarios.usuariosAdmin") }}" class="admin-sidebar-item">
                <i class="bi bi-people-fill admin-group-icon"></i>
                <span class="admin-item-text">Usuarios</span>
            </a>
            <a href="#" class="admin-sidebar-item">
                <i class="bi bi-pencil-square admin-group-icon"></i>
                <span class="admin-item-text">Permisos</span>
            </a>
            <a href="#" class="admin-sidebar-item">
                <i class="bi bi-hammer admin-group-icon"></i>
                <span class="admin-item-text">Roles</span>
            </a>
            <a href="{{ route('pages.Dashboard.Admin.Pedidos.pedidosAdmin') }}" class="admin-sidebar-item">
                <i class="bi bi-box2 admin-group-icon"></i>
                <span class="admin-item-text">Pedidos</span>
            </a>
            <a href="{{ route('pages.Dashboard.Admin.Productos.productosAdmin') }}" class="admin-sidebar-item">
                <i class="bi bi-cup-straw admin-group-icon"></i>
                <span class="admin-item-text">Productos</span>
            </a>
            <a href="{{ route('pages.Dashboard.Admin.Suscripciones.suscripcionesAdmin') }}" class="admin-sidebar-item">
                <i class="bi bi-card-checklist text admin-group-icon"></i>
                <span class="admin-item-text">Suscripciones</span>
            </a>
            <a href="{{ route('pages.Dashboard.Admin.Contactos.contactos') }}" class="admin-sidebar-item">
                <i class="bi bi-envelope text admin-group-icon"></i>
                <span class="admin-item-text">Contactos</span>
            </a>
        </nav>
    </div>

    <hr class="admin-sidebar-line">

    <div class="admin-sidebar-group">
        <nav class="admin-nav-links">
            <a href="#" class="admin-sidebar-item">
                <i class="bi bi-percent admin-group-icon"></i>
                <span class="admin-item-text">Estadísticas</span>
            </a>
            <a href="#" class="admin-sidebar-item">
                <i class="bi bi-filetype-csv admin-group-icon"></i>
                <span class="admin-item-text">Informes CVS</span>
            </a>
        </nav>
    </div>
    <hr class="admin-sidebar-line">
    <div class="admin-sidebar-group mt-3">
        <nav class="admin-nav-links">
            <a href="#" class="admin-sidebar-item">
                <i class="bi bi-eye-fill admin-group-icon"></i>
                <span class="admin-item-text">Cambiar vista (Admin / Usuario)</span>
            </a>
        </nav>
    </div>
</aside>          
    @endif
</aside>