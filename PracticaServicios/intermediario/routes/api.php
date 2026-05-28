<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonaController;
use App\Http\Middleware\JwtMiddleware;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TransaccionController;

Route::get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('personas', PersonaController::class)->middleware(JwtMiddleware::class);
Route::post('login', [LoginController::class, 'login']);   
Route::post('transaccion', [TransaccionController::class, 'store'])->middleware(\App\Http\Middleware\JwtMiddleware::class);
// Ruta de prueba pública para consultar saldo (intenta SOAP y luego BNB REST)
Route::get('test-saldo/{cuenta}', [TransaccionController::class, 'testSaldo']);
