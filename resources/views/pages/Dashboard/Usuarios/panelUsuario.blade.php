@extends('pages.Dashboard._Layouts.dashboardDefault')

@section('titulo','Cuenta usuario: ' . $nombreUsuario . ' | Leche El Torrejon')

@push('js')
    
@endpush

@push('styles')
    
@endpush

@section('contenidoDashboard')
    <h1>Bienvenid@, {{ $nombreUsuario }}</h1>
    <p>Esta es la página principal del dashboard para el usuario.</p>
@endsection