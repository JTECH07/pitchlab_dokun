<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/artisans/{id}', [\App\Http\Controllers\ArtisanPublicController::class, 'show'])->name('artisans.show');
Route::post('/artisans/{artisan_id}/reservations', [\App\Http\Controllers\ReservationController::class, 'store'])->name('reservations.store');
Route::get('/carte', [\App\Http\Controllers\MapController::class, 'index'])->name('carte');

Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/reservations', [\App\Http\Controllers\Admin\ReservationAdminController::class, 'index'])->name('reservations.index');
        Route::patch('/reservations/{reservation}/status', [\App\Http\Controllers\Admin\ReservationAdminController::class, 'updateStatus'])->name('reservations.updateStatus');

        Route::get('/artisans', [\App\Http\Controllers\Admin\ArtisanAdminController::class, 'index'])->name('artisans.index');
        Route::get('/artisans/create', [\App\Http\Controllers\Admin\ArtisanAdminController::class, 'create'])->name('artisans.create');
        Route::post('/artisans', [\App\Http\Controllers\Admin\ArtisanAdminController::class, 'store'])->name('artisans.store');
        Route::patch('/artisans/{artisan}/toggle', [\App\Http\Controllers\Admin\ArtisanAdminController::class, 'toggleStatus'])->name('artisans.toggle');
    });
});

require __DIR__.'/auth.php';
