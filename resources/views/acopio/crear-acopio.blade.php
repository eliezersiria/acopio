@extends('layouts.base')

@section('title', 'Registrar acopio || Acopio')

@section('navbar')
    <x-acopio.navbar-acopio-desktop />
    <x-acopio.navbar-acopio-movil />
@endsection

@section('sidebar')
    <x-acopio.sidebar-acopio />
@endsection

@section('content')
    <livewire:acopio.agregar-acopio />
@endsection