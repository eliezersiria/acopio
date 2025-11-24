@extends('layouts.base')

@section('title', 'Comarcas || Acopio')

@section('navbar')
    <x-localidad.navbar-localidad-desktop/>
    <x-localidad.navbar-localidad-movil/>
@endsection

@section('sidebar')
    <x-localidad.sidebar-localidad/>
@endsection

@section('content')
    <livewire:localidad.crear-localidad/>
@endsection