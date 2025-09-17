<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\TenantController as AdminTenantController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminAuthController;

Route::get('/', function () {
    return redirect()->route('admin.login');
});

// Admin auth routes
Route::get('admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// Protected admin routes
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/password', [AdminAuthController::class, 'showChangePassword'])->name('password');
    Route::put('/password', [AdminAuthController::class, 'changePassword'])->name('password.update');
    Route::get('/tenants', [AdminTenantController::class, 'index'])->name('tenants.index');
    Route::get('/tenants/create', [AdminTenantController::class, 'create'])->name('tenants.create');
    Route::post('/tenants', [AdminTenantController::class, 'store'])->name('tenants.store');
    Route::get('/tenants/{tenant}/edit', [AdminTenantController::class, 'edit'])->name('tenants.edit');
    Route::put('/tenants/{tenant}', [AdminTenantController::class, 'update'])->name('tenants.update');
    Route::delete('/tenants/{tenant}', [AdminTenantController::class, 'destroy'])->name('tenants.destroy');
    // get website test function 
    Route::get('/website', function () {
        $client = new \App\Services\AAPanel\AAPanelClient();
        $website = $client->getWebsite('test.com');
        return response()->json($website);
    })->name('website');
});
