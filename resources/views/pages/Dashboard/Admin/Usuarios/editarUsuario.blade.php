@extends('pages.Dashboard._Layouts.dashboardDefault')

@section('titulo','Editar Usuario | Leche El Torrejon')

@push('styles')
    <link rel="stylesheet" href="{{ asset('resources/css/pages/Dashboard/Admin/Usuarios/usuarios.css') }}">
@endpush

@push('js')
    <script type="module" src="{{ asset('resources/js/pages/Dashboard/Admin/Usuarios/editarUsuario.js') }}"></script>
@endpush

@section('contenidoDashboard')

<h1>Editar Usuario</h1>

<form id="formEditarUsuario">
    @csrf
    @method('PUT')

    <div class="row">

        {{-- Username --}}
        <div class="mb-3 col-md-6">
            <label class="form-label">Nombre de usuario*</label>
            <input type="text" name="username" id="username" class="form-control" placeholder="Nombre de usuario*">
            <div class="error text-danger small hidden"></div>
        </div>

        {{-- Nombre --}}
        <div class="mb-3 col-md-6">
            <label class="form-label">Nombre*</label>
            <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Nombre*">
            <div class="error text-danger small hidden"></div>
        </div>

        {{-- Apellidos --}}
        <div class="mb-3 col-md-6">
            <label class="form-label">Apellidos*</label>
            <input type="text" name="apellidos" id="apellidos" class="form-control" placeholder="Apellidos*">
            <div class="error text-danger small hidden"></div>
        </div>

        {{-- Email --}}
        <div class="mb-3 col-md-6">
            <label class="form-label">Email*</label>
            <input type="email" name="email" id="email" class="form-control" placeholder="Email*">
            <div class="error text-danger small hidden"></div>
        </div>

        {{-- Dirección --}}
        <div class="mb-3 col-12">
            <label class="form-label">Dirección*</label>
            <input type="text" name="direccion" id="direccion" class="form-control" placeholder="Dirección*">
            <div class="error text-danger small hidden"></div>
        </div>

        {{-- Rol y saldo solo para admin --}}
        <div id="adminFields" class="row d-none">
            <div class="mb-3 col-md-6">
                <label class="form-label">Rol*</label>
                <select name="rol" id="rol" class="form-select">
                    <option value="user">Usuario</option>
                    <option value="admin">Administrador</option>
                </select>
                <div class="error text-danger small hidden"></div>
            </div>
            <div class="mb-3 col-md-6">
                <label class="form-label">Saldo</label>
                <input type="number" name="saldo" id="saldo" class="form-control" min="0" step="0.01">
                <div class="error text-danger small hidden"></div>
            </div>
        </div>

    </div>

    <div id="alert" class="alert alert-success alert-dismissible fade" role="alert" style="display: none;">
        <span id="alertMsg"></span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>

    <button type="button" id="btnVolver" class="btn btn-secondary">
        Volver atrás
    </button>
    <button type="button" id="btnActualizarUsuario" class="btn btn-dark">
        Actualizar Usuario
    </button>
</form>

<br>
@endsection