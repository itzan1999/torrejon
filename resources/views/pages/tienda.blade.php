@extends('pages.Layouts.plantillaDefault')

@push('js')
    <script  src="{{ asset('resources/js/pages/tienda.js') }}"></script>
@endpush

@push("styles")
    <link rel="stylesheet" href="{{ asset('resources/css/pages/tienda.css') }}"/>
@endpush

@section('contenido')

<div class="container-fluid contenedor-fluido mt-4 px-5">
    <div class="row justify-content-end mb-4">
        <div class="col-md-4">
            <div class="posicion-relativa">
                <div class="input-group border border-dark redondeo-0">
                    <span class="input-group-text bg-white border-0"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control border-0 buscarProductos redondeo-0" placeholder="Buscar producto">
                </div>
                <div id="resultadosBusqueda" class="resBusqueda"></div>
            </div>
        </div>
    </div>

    <div class="row">
        <aside class="col-md-3 col-lg-2 border-end border-dark pe-4">
            <form id="formularioFiltros">
                <div class="mb-4">
                    <label class="fw-bold d-block mb-2 small">Filtrado de resultados</label>
                    <select id="categoria" class="form-select border-dark redondeo-0">
                        <option value="">Todos los productos</option>
                        <option value="leche">Leche</option>
                        <option value="queso">Queso</option>
                        <option value="yogur">Yogur</option>
                        <option value="mantequilla">Mantequilla</option>
                        <option value="nata">Nata</option>
                    </select>
                </div>
                <div class="mb-4">
                    <input type="number" id="precio" class="form-control border-dark redondeo-0" placeholder="Precio máximo">
                </div>
            </form>
        </aside>

        <div class="col-md-9 col-lg-10 ps-4">
            {{-- Div para el alert del mensaje de añadido producto --}}
            <div id="alert" class="alert alert-success alert-dismissible fade" role="alert" style="display: none;">
                <span id="alertMsg"></span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
            
            <div class="row tienda g-4">
                {{-- Aquí inyecta el JS las tarjetas --}}
            </div>
        </div>
    </div>
</div>

@endsection