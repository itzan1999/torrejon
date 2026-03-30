@extends('pages.Dashboard._Layouts.dashboardDefault')

@section('titulo','Detalle Pedido | Leche El Torrejon')

@push('styles')
    <link rel="stylesheet" href="{{ asset('resources/css/pages/Dashboard/Admin/Pedidos/pedidos.css') }}">
@endpush

@push('js')
    <script type="module" src="{{ asset('resources/js/pages/Dashboard/Admin/Pedidos/detallePedido.js') }}"></script>
@endpush

@section('contenidoDashboard')

<h1>Detalle Pedido</h1>

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
        </tr>
    </thead>
    <tbody id="tablaPedido">
        <!-- Se cargará dinámicamente -->
    </tbody>
</table>

<br><br>

<h1>Cliente Asociado</h1>

<table class="table table-hover border text-center">
    <thead class="table-primary">
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Nombre</th>
            <th>Apellidos</th>
            <th>Rol</th>
            <th>Nº Pedidos Totales</th>
        </tr>
    </thead>
    <tbody id="tablaUsuario">
        <!-- Se cargará dinámicamente -->
    </tbody>
</table>

<button type="button" id="btnVolver" class="btn btn-secondary">
    Volver atrás
</button>
@endsection