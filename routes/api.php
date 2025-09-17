<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\TenantController;

Route::get('/tenants', [TenantController::class, 'index']);
Route::post('/tenants', [TenantController::class, 'store']);
Route::get('/tenants/{id}', [TenantController::class, 'show']);
Route::put('/tenants/{id}', [TenantController::class, 'update']);
Route::delete('/tenants/{id}', [TenantController::class, 'destroy']);

Route::get('/statistics/tenants', [TenantController::class, 'stats']);


