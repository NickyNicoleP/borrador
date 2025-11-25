<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\PromocionController;
use App\Http\Controllers\Api\CotizacionController;
use App\Http\Controllers\Api\NewsletterController;
use App\Http\Controllers\Api\UsuarioController;

Route::get('/', function () {
    return view('welcome');
});


Route::prefix('api')->group(function () {

    Route::get('/promociones', [PromocionController::class, 'index']);
    Route::post('/cotizar', [CotizacionController::class, 'store']);

    Route::get('/newsletter', [NewsletterController::class, 'index']);
    Route::post('/newsletter', [NewsletterController::class, 'store']);

    Route::get('/usuarios', [UsuarioController::class, 'index']);
    Route::post('/usuarios', [UsuarioController::class, 'store']);
});

