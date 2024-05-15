<?php

use App\Http\Controllers\ObraController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('app');
});

Route::get('/obras', ObraController::class . '@index')->name('obras');

Route::post('/obras', ObraController::class . '@store');

Route::delete('/obras/{id}', [ObraController::class , 'destroy'])->name('obras-destroy');

Route::get('/obras/{id}', [ObraController::class , 'show'])->name('obras-edit');

Route::patch('/obras/{id}', [ObraController::class , 'update'])->name('obras-update');