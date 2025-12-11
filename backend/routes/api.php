<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CotizacionAdminController;
use App\Http\Controllers\Api\CompraController;
use App\Http\Controllers\Api\ContactoController;
use App\Http\Controllers\Api\CotizacionController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\NewsletterController;
use App\Http\Controllers\Api\PlanController;
use App\Http\Controllers\Api\PromocionController;
use App\Http\Controllers\Api\SolicitudPortabilidadController;
use App\Http\Controllers\Api\UsuarioController;
use App\Http\Controllers\Api\UsuarioAdminController;

Route::post('/register', [AuthController::class, 'register']);

Route::get('/promociones', [PromocionController::class, 'index']);
Route::get('/planes', [PlanController::class, 'index']);
Route::get('/dashboard', DashboardController::class);

Route::get('/cotizaciones', [CotizacionController::class, 'index']);
Route::post('/cotizar', [CotizacionController::class, 'store']);
Route::get('/compras', [CompraController::class, 'index']);
Route::post('/compras', [CompraController::class, 'store']);
Route::post('/solicitudes-portabilidad', [SolicitudPortabilidadController::class, 'store']);
Route::post('/contactos', [ContactoController::class, 'store']);

Route::get('/newsletter', [NewsletterController::class, 'index']);
Route::post('/newsletter', [NewsletterController::class, 'store']);

Route::get('/usuarios', [UsuarioController::class, 'index']);
Route::post('/usuarios', [UsuarioController::class, 'store']);

Route::middleware('admin.token')->prefix('admin')->group(function () {
    Route::get('/usuarios', [UsuarioAdminController::class, 'index']);
    Route::post('/usuarios', [UsuarioAdminController::class, 'store']);
    Route::put('/usuarios/{id}', [UsuarioAdminController::class, 'update']);
    Route::delete('/usuarios/{id}', [UsuarioAdminController::class, 'destroy']);
    Route::put('/cotizaciones/{id}', [CotizacionAdminController::class, 'update']);
    Route::delete('/cotizaciones/{id}', [CotizacionAdminController::class, 'destroy']);
});
