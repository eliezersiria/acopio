@extends('layouts.base')

@section('title', 'Editar Productor || Acopio')

@section('navbar')
    <x-productor.navbar-productor-desktop />
    <x-productor.navbar-productor-movil />
@endsection

@section('sidebar')
    <x-productor.sidebar-productor />
@endsection

@section('content')
    <livewire:productor.editar-productor :id="$id"/>
@endsection