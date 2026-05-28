<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CuentaController;

// Servicio BNB
Route::get('cuenta/{cuenta}', [CuentaController::class, 'show']);
Route::put('cuenta/{cuenta}', [CuentaController::class, 'update']);
    
