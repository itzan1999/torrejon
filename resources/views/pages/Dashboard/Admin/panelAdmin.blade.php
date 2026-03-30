@extends('pages.Dashboard._Layouts.dashboardDefault')

@section('titulo','Dashboard del Administrador | Leche El Torrejon')

@push('js')
    
@endpush

@push('styles')
    
@endpush

@section('contenidoDashboard')
    <h1>Bienvenido al Dashboard del Administrador</h1>

    <p>
        Bienvenido al panel de administración de <strong>Leche El Torrejón</strong>. 
        Desde aquí podrás gestionar de forma centralizada todos los aspectos de la aplicación.
    </p>

    <p>
        Utiliza el menú lateral para acceder a las diferentes secciones disponibles:
    </p>

    <ul>
        <li>Gestión de usuarios</li>
        <li>Gestión de permisos</li>
        <li>Gestión de roles</li>
        <li>Gestión de pedidos</li>
        <li>Gestión de productos</li>
        <li>Gestión de contactos</li>
        <li>Gestión de suscripciones</li>
    </ul>

    <p>
        Asegúrate de revisar periódicamente la información para mantener el sistema actualizado 
        y funcionando correctamente.
    </p>

    <p>
        Si necesitas realizar cambios importantes, hazlo con precaución, ya que pueden afectar al funcionamiento general de la plataforma.
    </p>
@endsection