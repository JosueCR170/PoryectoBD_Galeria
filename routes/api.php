<?php

use Illuminate\Http\Request;
use App\Http\Controllers\ObraController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(
    function(){
        // Route::resource('/obra',ObraController::class,['except'=>['create','edit']]);
    }
);
