@extends('pages.Dashboard._Layouts.dashboardDefault')

@section('titulo','Gestión de Productos | Leche El Torrejon')

@push('styles')
    <link rel="stylesheet" href="{{ asset('resources/css/pages/Dashboard/Admin/Productos/productos.css') }}">
@endpush

@push('js')
    <script type="module" src="{{ asset('resources/js/pages/Dashboard/Admin/Productos/cargarProductos.js') }}"></script>
@endpush

@section('contenidoDashboard')

<h1>Gestión de Productos</h1>

<div class="d-flex justify-content-start mb-3 gap-2">
    <input type="text" id="buscador" class="form-control w-50" placeholder="Buscar por nombre, descripcion o precio">
    <button id="btnNuevoProducto" class="btn btn-success">Nuevo Producto</button>
</div>

<div id="alert" class="alert alert-success alert-dismissible fade" role="alert" style="display: none;">
    <span id="alertMsg"></span>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
</div>


<table class="table table-hover border text-center">
    <thead class="table-primary">
        <tr>
            <th>ID Producto</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Precio</th>
            <th>Stock</th>
            <th>Tamaño</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody id="tablaProductos">
        <!-- Se carga dinámicamente -->
    </tbody>
</table>


<x-confirm-modal 
    id="confirmModal" 
    title="Eliminar Producto" 
    message="¿Seguro que quieres eliminar este producto?" 
    confirm-text="Sí, eliminar" 
    cancel-text="Cancelar" 
/>
@endsection