<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');

// Rotas de API para os contatos (consumidas pelo Alpine.js)
Route::apiResource('api/contacts', \App\Http\Controllers\ContactController::class);
