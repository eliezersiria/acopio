<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Inicio;
use App\Http\Controllers\LocalidadController;
use App\Http\Controllers\ProductorController;



Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');


Route::get('/bienvenido', [Inicio::class, 'index'])->name('inicio')->middleware('auth');

Route::get('/localidad', [LocalidadController::class, 'index'])->name('localidad')->middleware('auth');
Route::get('/localidad-agregar', [LocalidadController::class, 'create'])->name('localidad.agregar')->middleware('auth');
Route::get('/localidad-listar', [LocalidadController::class, 'listar'])->name('localidad.listar')->middleware('auth');
Route::get('/localidad-editar/{id}', [LocalidadController::class, 'editar'])->name('localidad.editar')->middleware('auth');

Route::get('/productor', [ProductorController::class, 'index'])->name('productor')->middleware('auth');
Route::get('/productor-agregar', [ProductorController::class, 'create'])->name('productor.agregar')->middleware('auth');
Route::get('/productor-listar', [ProductorController::class, 'listar'])->name('productor.listar')->middleware('auth');
Route::get('/productor-editar/{id}', [ProductorController::class, 'editar'])->name('productor.editar')->middleware('auth');

