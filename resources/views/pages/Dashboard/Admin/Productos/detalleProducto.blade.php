@extends('pages.Dashboard._Layouts.dashboardDefault')

@section('titulo','Detalle Producto | Leche El Torrejon')

@push('styles')
    <link rel="stylesheet" href="{{ asset('resources/css/pages/Dashboard/Admin/Productos/productos.css') }}">
@endpush

@push('js')
    <script type="module" src="{{ asset('resources/js/pages/Dashboard/Admin/Productos/detalleProducto.js') }}"></script>
@endpush

@section('contenidoDashboard')

<h1>Detalle Producto</h1>

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
            <th>Oferta</th>
            <th>Stock</th>
            <th>Tamaño</th>
        </tr>
    </thead>
    <tbody id="tablaProducto">
        <!-- Se cargará dinámicamente -->
    </tbody>
</table>

{{-- Imagen del producto --}}
<h1>Imagen del Producto</h1>
<div class="contenedor-imagen">
    <div class="card-imagen-producto" id="imagenProducto">

    </div>
</div>

{{-- Información nutricional --}}
<div class="accordion mt-4" id="accordionNutricional">

    <div class="accordion-item">

        <h2 class="accordion-header">
            <button class="accordion-button collapsed"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#nutricional">
                Información nutricional
            </button>
        </h2>

        <div id="nutricional" class="accordion-collapse collapse">
            <div class="accordion-body" id="informacionNutricional"></div>
        </div>

    </div>

</div>

<br>
<button type="button" id="btnVolver" class="btn btn-secondary">
    Volver atrás
</button>
@endsection