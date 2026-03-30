@extends('pages.Dashboard._Layouts.dashboardDefault')

@section('titulo','Gestión de Suscripciones | Leche El Torrejon')

@push('styles')
    <link rel="stylesheet" href="{{ asset('resources/css/pages/Dashboard/Admin/Suscripciones/suscripciones.css') }}">
@endpush

@push('js')
    <script type="module" src="{{ asset('resources/js/pages/Dashboard/Admin/Suscripciones/cargarSuscripciones.js') }}"></script>
@endpush

@section('contenidoDashboard')

<h1>Gestión de Suscripciones</h1>

<div class="d-flex justify-content-start mb-3 gap-2">
    <input type="text" id="buscador" class="form-control w-50" placeholder="Buscar por tipo, fecha (por año) y usuario…">
    {{-- <button id="btnNuevoUsuario" class="btn btn-success">Nuevo Usuario</button> --}}
</div>

<div id="alert" class="alert alert-success alert-dismissible fade" role="alert" style="display: none;">
    <span id="alertMsg"></span>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
</div>

<table class="table table-hover border text-center">
    <thead class="table-primary">
        <tr>
            <th>ID</th>
            <th>Tipo</th>
            <th>Fecha Inicio</th>
            <th>Usuario</th>
            <th colspan="3">Acciones</th>
        </tr>
    </thead>
    <tbody id="tablaSuscripciones">
        <!-- Se cargará dinámicamente -->
    </tbody>
</table>

<x-confirm-modal 
    id="confirmModal" 
    title="Eliminar Suscripción" 
    message="¿Seguro que quieres eliminar esta suscripción?" 
    confirm-text="Sí, eliminar" 
    cancel-text="Cancelar" 
/>
@endsection