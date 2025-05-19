<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteAplicacionController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CooperativaController;
use App\Http\Controllers\AplicacionController;


Route::get('/', function () {
    return view('welcome');
});


Route::middleware(['auth', 'prevent.back.history'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');



    Route::get('/clientes', [ClienteAplicacionController::class, 'index'])->name('clientes.index');
    Route::post('/clientes', [ClienteAplicacionController::class, 'store'])->name('clientes_aplicacion.store');

    // routes/web.php
    Route::get('/clientes/list', [ClienteController::class, 'index'])->name('clientes.list');
    Route::post('/clientes/add', [ClienteController::class, 'store'])->name('clientes.store');
    Route::put('/clientes/{cliente}', [ClienteController::class, 'update'])->name('clientes.update');
    Route::delete('/clientes/{cliente}', [ClienteController::class, 'destroy'])->name('clientes.destroy');
    // routes/web.php o routes/api.php
    Route::get('/clientes/{id}', [ClienteController::class, 'show']);




    Route::get('/cooperativas/list', [CooperativaController::class, 'index'])->name('cooperativas.list');
    Route::post('/cooperativas/add', [CooperativaController::class, 'store'])->name('cooperativas.store');


    // routes/web.php
    Route::get('/clientes/ajax', [ClienteAplicacionController::class, 'ajaxList'])->name('clientes.ajax');


    Route::get('/aplicaciones/list', [AplicacionController::class, 'index'])->name('aplicaciones.list');
    Route::post('/aplicaciones/add', [AplicacionController::class, 'store'])->name('aplicaciones.store');




});


require __DIR__ . '/auth.php';
