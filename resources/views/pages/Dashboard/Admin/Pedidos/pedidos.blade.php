@extends('pages.Dashboard._Layouts.dashboardDefault')

@section('titulo','Gestión de Pedidos | Leche El Torrejon')

@push('styles')
    <link rel="stylesheet" href="{{ asset('resources/css/pages/Dashboard/Admin/Pedidos/pedidos.css') }}">
@endpush

@push('js')
    <script type="module" src="{{ asset('resources/js/pages/Dashboard/Admin/Pedidos/cargarPedidos.js') }}"></script>
@endpush

@section('contenidoDashboard')

<h1>Gestión de Pedidos</h1>

<div class="d-flex justify-content-start mb-3 gap-2">
    <input type="text" id="buscador" class="form-control w-50" placeholder="Buscar por nombre, apellidos, username, estado, email y suscripcion.">
</div>

<div id="alert" class="alert alert-success alert-dismissible fade" role="alert" style="display: none;">
    <span id="alertMsg"></span>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
</div>

<table class="table table-hover border text-center">
    <thead class="table-primary">
        <tr>
            <th>ID Pedido</th>
            <th>Codigo</th>
            <th>Cliente</th>
            <th>Email</th>
            <th>Estado</th>
            <th>Generado por Suscripción</th>
            <th colspan="3">Acciones</th>
        </tr>
    </thead>
    <tbody id="tablaPedidos">
        <!-- Se cargará dinámicamente -->
    </tbody>
</table>

<x-confirm-modal 
    id="confirmModal" 
    title="Eliminar Pedido" 
    message="¿Seguro que quieres eliminar este pedido?" 
    confirm-text="Sí, eliminar" 
    cancel-text="Cancelar" 
/>
@endsection