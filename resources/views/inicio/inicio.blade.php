@extends('layouts.base')

@section('title', 'Bienvenido || Acopio')

@section('navbar')
    <x-inicio.navbar-inicio-desktop/>
    <x-inicio.navbar-inicio-movil/>
@endsection

@section('sidebar')
    <x-inicio.sidebar-inicio />
@endsection

@section('content')
    <x-inicio.jumbotron />
@endsection