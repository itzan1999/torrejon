@extends('pages.Layouts.plantillaDefault')

@push('styles')
    <link rel="stylesheet" href="{{ asset('resources/css/pages/Dashboard/dashboard.css') }}">
@endpush

@section('contenido')

    <div class="container-fluid mt-4">
        <div class="row">

            {{-- SIDEBAR IZQUIERDO --}}
            <div class="col-md-3">
                @include('pages.Dashboard._Layouts._partials.sidebar')
            </div>

            {{-- CONTENIDO DERECHO DINÁMICO --}}
            <div class="col-md-9">
                @yield('contenidoDashboard')
            </div>

        </div>
    </div>

@endsection