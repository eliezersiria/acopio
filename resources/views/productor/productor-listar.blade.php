@extends('layouts.base')

@section('title', 'Productores || Acopio')

@section('navbar')
    <x-productor.navbar-productor-desktop />
    <x-productor.navbar-productor-movil />
@endsection

@section('sidebar')
    <x-productor.sidebar-productor />
@endsection

@section('content')
    <livewire:productor.listar-productor />
@endsection