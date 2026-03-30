@extends('pages.Dashboard._Layouts.dashboardDefault')

@section('titulo','Detalle Usuario | Leche El Torrejon')

@push('styles')
    <link rel="stylesheet" href="{{ asset('resources/css/pages/Dashboard/Admin/Usuarios/usuarios.css') }}">
@endpush

@push('js')
    <script type="module" src="{{ asset('resources/js/pages/Dashboard/Admin/Usuarios/detalleUsuario.js') }}"></script>
@endpush

@section('contenidoDashboard')

<h1>Detalle Usuario</h1>

<div id="alert" class="alert alert-success alert-dismissible fade" role="alert" style="display: none;">
    <span id="alertMsg"></span>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
</div>


<table class="table table-hover border text-center">
    <thead class="table-primary">
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Nombre</th>
            <th>Apellidos</th>
            <th>Rol</th>
            <th>Nº Pedidos</th>
        </tr>
    </thead>
    <tbody id="tablaUsuarios">
        <!-- Se cargará dinámicamente -->
    </tbody>
</table>

<button type="button" id="btnVolver" class="btn btn-secondary">
    Volver atrás
</button>
@endsection