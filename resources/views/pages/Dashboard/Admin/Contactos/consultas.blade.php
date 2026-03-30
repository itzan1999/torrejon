@extends('pages.Dashboard._Layouts.dashboardDefault')
@section('titulo','Contactos | Leche El Torrejon')

@push('styles')
    <link rel="stylesheet" href="{{ asset('resources/css/pages/Dashboard/Admin/Contactos/consultas.css') }}">
@endpush

@push('js')
    <script type="module" src="{{ asset('resources/js/pages/Dashboard/Admin/Contactos/consultas.js') }}"></script>
@endpush

@section('contenidoDashboard')
<div class="inbox">
    <aside class="sidebar">
        <div id="lista-consultas"></div>
    </aside>

    <div id="detalle-consulta">
        
        <div id="contenido-dinamico-consulta">
            </div>

        <div id="fab-overlay" class="fab-overlay"></div>

        <div class="fab-container" id="fab-container" style="display: none;">
            <div class="fab-menu" id="fab-menu">
                <button class="fab-item" data-estado="pendiente" style="background-color: #f39c12;">Pendiente</button>
                <button class="fab-item" data-estado="en proceso" style="background-color: #2980b9;">En Proceso</button>
                <button class="fab-item" data-estado="resuelta" style="background-color: #27ae60;">Resuelta</button>
            </div>
            <button class="fab-main" id="fab-main">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus-icon lucide-plus"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
            </button>
        </div>
        
    </div>
</div>
@endsection
