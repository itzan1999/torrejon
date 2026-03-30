@extends('pages.Dashboard._Layouts.dashboardDefault')

@section('titulo','Nuevo Producto | Leche El Torrejon')

@push('styles')
    <link rel="stylesheet" href="{{ asset('resources/css/pages/Dashboard/Admin/Productos/productos.css') }}">
@endpush

@push('js')
    <script type="module" src="{{ asset('resources/js/pages/Dashboard/Admin/Productos/nuevoProducto.js') }}"></script>
@endpush

@section('contenidoDashboard')

<h1>Nuevo Producto</h1>

<form id="formNuevoProducto" enctype="multipart/form-data">
    @csrf

    <div class="row">

        {{-- Nombre --}}
        <div class="mb-3 col-md-6">
            <label class="form-label">Nombre*</label>
            <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Nombre del producto*">
            <div class="error text-danger small hidden"></div>
        </div>

        {{-- Precio --}}
        <div class="mb-3 col-md-6">
            <label class="form-label">Precio*</label>
            <input type="number" step="0.01" min="0" name="precio" id="precio" class="form-control" placeholder="Precio*">
            <div class="error text-danger small hidden"></div>
        </div>

        {{-- Stock --}}
        <div class="mb-3 col-md-6">
            <label class="form-label">Stock*</label>
            <input type="number" min="0" name="stock" id="stock" class="form-control" placeholder="Cantidad en stock*">
            <div class="error text-danger small hidden"></div>
        </div>

        {{-- Oferta --}}
        <div class="mb-3 col-md-6">
            <label class="form-label">Oferta (%)</label>
            <input type="number" step="0.01" min="0" name="oferta" id="oferta" class="form-control"  placeholder="Oferta">
            <div class="error text-danger small hidden"></div>
        </div>

        {{-- Tamaño --}}
        <div class="mb-3 col-md-6">
            <label class="form-label">Tamaño*</label>
            <input type="number" step="0.01" min="0" name="tamanyo" id="tamanyo" class="form-control" placeholder="Tamaño del producto*">
            <div class="error text-danger small hidden"></div>
        </div>

        {{-- Unidad de medida --}}
        <div class="mb-3 col-md-6">
            <label class="form-label">Unidad de medida*</label>
            <select name="unidad_medida" id="unidad_medida" class="form-select">
                <option value="" disabled selected>Seleccionar unidad</option>
                <option value="mg">mg</option>
                <option value="g">g</option>
                <option value="kg">kg</option>
                <option value="mL">mL</option>
                <option value="L">L</option>
            </select>
            <div class="error text-danger small hidden"></div>
        </div>

        {{-- Descripción --}}
        <div class="mb-3 col-12">
            <label class="form-label">Descripción</label>
            <textarea name="descripcion" id="descripcion" class="form-control" rows="3" placeholder="Descripción del producto*"></textarea>
            <div class="error text-danger small hidden"></div>
        </div>

        {{-- Imagen --}}
        <div class="mb-3 col-md-6">
            <label class="form-label">Imagen del producto*</label>
            <input type="file" name="path_imagen" id="path_imagen" class="form-control" accept="image/*">
            <div class="error text-danger small hidden"></div>
        </div>

        {{-- Información nutricional --}}
        <div class="mb-3 col-12">
            <label class="form-label">Información nutricional*</label>
            <div class="row">
                <div class="col-md-2 mb-2">
                    <input type="number" step="0.01" min="0" id="calorias" name="informacion_nutricional[calorias]" class="form-control" placeholder="Calorías*">
                </div>
                <div class="col-md-2 mb-2">
                    <input type="number" step="0.01" min="0" id="grasas" name="informacion_nutricional[grasas]" class="form-control" placeholder="Grasas*">
                </div>
                <div class="col-md-2 mb-2">
                    <input type="number" step="0.01" min="0" id="grasas_saturadas" name="informacion_nutricional[grasas_saturadas]" class="form-control" placeholder="Grasas saturadas*">
                </div>
                <div class="col-md-2 mb-2">
                    <input type="number" step="0.01" min="0" id="hidratos" name="informacion_nutricional[hidratos]" class="form-control" placeholder="Hidratos*">
                </div>
                <div class="col-md-2 mb-2">
                    <input type="number" step="0.01" min="0" id="azucares" name="informacion_nutricional[azucares]" class="form-control" placeholder="Azúcares*">
                </div>
                <div class="col-md-2 mb-2">
                    <input type="number" step="0.01" min="0" id="proteinas" name="informacion_nutricional[proteinas]" class="form-control" placeholder="Proteínas*">
                </div>
                <div class="col-md-2 mb-2">
                    <input type="number" step="0.01" min="0" id="sal" name="informacion_nutricional[sal]" class="form-control" placeholder="Sal*">
                </div>
            </div>
            <div class="error text-danger small hidden" id="errorNutricional"></div>
        </div>

    </div>

    <div id="alert" class="alert alert-success alert-dismissible fade" role="alert" style="display: none;">
        <span id="alertMsg"></span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>

    <button type="button" id="btnVolver" class="btn btn-secondary">Volver atrás</button>
    <button type="button" id="btnCrearProducto" class="btn btn-dark">Crear Producto</button>

</form>

@endsection