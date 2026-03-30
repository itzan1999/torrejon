@extends('pages.Dashboard._Layouts.dashboardDefault')

@section('titulo','Gestión de Usuarios | Leche El Torrejon')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endpush

@section('contenidoDashboard')

    <h1>Gestión de Usuarios</h1>
    <p>Aquí puedes gestionar los usuarios de la aplicación.</p>

@endsection