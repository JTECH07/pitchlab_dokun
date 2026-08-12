<?php

use App\Http\Controllers\Admin\ArtisanAdminController;
use App\Http\Controllers\Admin\CategoryAdminController;
use App\Http\Controllers\Admin\ReservationAdminController;
use App\Http\Controllers\Admin\SavoirFaireAdminController;
use App\Http\Controllers\ArtisanPublicController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\SavoirFairePublicController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Routes Publiques
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/artisans', [ArtisanPublicController::class, 'index'])->name('artisans.index');
Route::get('/artisans/{id}', [ArtisanPublicController::class, 'show'])->name('artisans.show');
Route::post('/artisans/{artisan_id}/reservations', [ReservationController::class, 'store'])->name('reservations.store');

Route::get('/savoir-faire', [SavoirFairePublicController::class, 'index'])->name('savoir-faire.index');
Route::get('/savoir-faire/{slug}', [SavoirFairePublicController::class, 'show'])->name('savoir-faire.show');

Route::get('/carte', [MapController::class, 'index'])->name('carte');

/*
|--------------------------------------------------------------------------
| Routes Authentifiées & Dashboard
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin Panel Routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/reservations', [ReservationAdminController::class, 'index'])->name('reservations.index');
        Route::patch('/reservations/{reservation}/status', [ReservationAdminController::class, 'updateStatus'])->name('reservations.updateStatus');

        Route::patch('/artisans/{artisan}/toggle', [ArtisanAdminController::class, 'toggleStatus'])->name('artisans.toggle');
        Route::resource('artisans', ArtisanAdminController::class);
        Route::resource('categories', CategoryAdminController::class);
        Route::resource('savoir-faires', SavoirFaireAdminController::class);
    });
});

require __DIR__.'/auth.php';
