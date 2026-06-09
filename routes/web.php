<?php

use App\Http\Controllers\WisataController;
use Illuminate\Support\Facades\Route;

Route::get('/', [WisataController::class, 'dashboard'])->name('dashboard');
Route::get('/dashboard', [WisataController::class, 'dashboard']);

// Resource route otomatis membuat: index, create, store, show, edit, update, destroy
Route::resource('wisata', WisataController::class);

// Route tambahan
Route::get('/wisata-map', [WisataController::class, 'map'])->name('wisata.map');
