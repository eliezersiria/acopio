@extends('layouts.base')

@section('title', 'Resumen Semanal || Acopio')

@section('navbar')
<x-acopio.navbar-acopio-desktop />
<x-acopio.navbar-acopio-movil />
@endsection

@section('sidebar')
<x-acopio.sidebar-acopio />
@endsection

@section('content')
<livewire:acopio.resumen-semanal />
@endsection