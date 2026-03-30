@extends('pages.Layouts.plantillaDefault')

@section('titulo', 'Mi Cesta - El Torrejón')

@push('styles')
    <link rel="stylesheet" href="{{ asset('resources/css/pages/Carrito/verCarrito.css') }}"/>
    <link rel="stylesheet" href="{{ asset('resources/css/pages/Carrito/realizarPedido.css') }}"/>
@endpush

@push('js')
    <script src="{{ asset('resources/js/pages/Carrito/verCarrito.js') }}"></script>
    <script src="{{ asset('resources/js/pages/Carrito/realizarPedido.js') }}"></script>
@endpush

@section('contenido')
<div class="contenedor-principal mt-5 container-fluid px-lg-5">
    <div class="row g-4">
        {{-- LISTADO DE PRODUCTOS --}}
        <div class="col-lg-8">
            <div class="tarjeta-blanca-productos p-4 shadow-sm">
                <h5 class="fw-bold mb-4 border-bottom pb-2">Mi Carrito</h5>
                <div id="listaProductosCarrito">
                    <div class="text-center py-5">
                        <div class="spinner-border" role="status"></div>
                        <p class="mt-2 text-muted small">Cargando tus productos...</p>
                    </div>
                </div>
                <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                    <button class="btn-accion-cesta btn-vaciar-carrito px-3">Vaciar cesta</button>
                    <a href="{{ route('pages.tienda') }}" class="btn-accion-cesta btn-seguir-comprando px-4 rounded-pill text-decoration-none">Seguir comprando</a>
                </div>
            </div>
        </div>

        {{-- PANEL RESUMEN --}}
        <div class="col-lg-4">
            <div class="panel-resumen-compra p-4 shadow-sm sticky-top">
                <h5 class="fw-bold mb-4">Resumen</h5>
                <div id="desgloseArticulos" class="mb-3 border-bottom pb-2"></div>
                
                <div class="d-flex justify-content-between mb-2 small">
                    <span class="text-muted fw-bold">Subtotal</span>
                    <span id="subtotalBase" class="fw-bold">0.00€</span>
                </div>
                <div class="d-flex justify-content-between mb-3 small">
                    <span class="text-muted fw-bold">IVA (21%)</span>
                    <span id="totalIVA" class="fw-bold">0.00€</span>
                </div>
                
                {{-- SWITCH DE SUSCRIPCIÓN --}}
                <div class="py-3 mb-4 border-bottom border-top">
                    <div class="d-flex align-items-center justify-content-between">
                        <label class="small fw-bold text-uppercase">Suscripción mensual</label>
                        <div class="switch-container">
                            <input type="checkbox" id="checkSuscripcion" class="switch-input">
                            <label for="checkSuscripcion" class="switch-label">
                                <span class="switch-botton"></span>
                            </label>
                        </div>
                    </div>
                    {{-- ESTADO: Estrictamente necesario para que tu JS funcione --}}
                    <div id="infoSuscripcion" class="mt-2 d-none">
                        <div class="d-flex justify-content-between small">
                            <span class="text-muted">Estado:</span>
                            <span>No activa</span>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <span class="fw-bold fs-5">Total</span>
                    <span id="totalFinal" class="fw-bold fs-3">0.00€</span>
                </div>
                
                <button class="btn-negro-completo btn-realizar-pedido w-100 py-3">REALIZAR PEDIDO</button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL DE CONFIRMACIÓN --}}
<div id="modalConfirmacion" style="display: none;">
    <div class="modal-contenido shadow-lg">
        <i class="fa-solid fa-circle-check text-success mb-3" style="font-size: 4rem;"></i>
        <h3 class="fw-bold mb-3">¡Pedido Realizado!</h3>
        <div id="resumenPedidoModal" class="p-3 mb-4 small text-start bg-light rounded"></div>
        <a href="{{ route('pages.index') }}" id="btnVolverInicio" class="btn-negro-completo w-100 py-3 d-block text-decoration-none text-white rounded-pill text-center">
            VOLVER AL INICIO
        </a>
    </div>
</div>
@endsection