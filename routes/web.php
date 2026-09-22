<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\FacilityController;
use App\Http\Controllers\Admin\RecapController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\DamageReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CatalogController::class, 'index'])->middleware(['auth'])->name('catalog.index');

Route::get('/dashboard', function () {
    if (auth()->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('catalog.index');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // New user routes
    Route::get('/facilities/{facility}', [CatalogController::class, 'show'])->name('catalog.show');
    Route::get('/facilities/{facility}/slots', [CatalogController::class, 'slots'])->name('catalog.slots');
    
    Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');
    Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
    Route::post('/reservations/{reservation}/cancel', [ReservationController::class, 'cancel'])->name('reservations.cancel');
    
    Route::get('/reports', [DamageReportController::class, 'index'])->name('reports.index');
    Route::post('/reports', [DamageReportController::class, 'store'])->name('reports.store');
    Route::patch('/reports/{report}/status', [DamageReportController::class, 'updateStatus'])->name('reports.update-status');

    // Petugas: kelola & setujui/tolak reservasi
    Route::middleware('role:admin,petugas')->group(function () {
        Route::get('/petugas', [PetugasController::class, 'dashboard'])->name('petugas.dashboard');
        Route::post('/reservations/{reservation}/approve', [ReservationController::class, 'approve'])->name('reservations.approve');
        Route::post('/reservations/{reservation}/reject', [ReservationController::class, 'reject'])->name('reservations.reject');
    });
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // User Management
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/pending', [AdminUserController::class, 'pending'])->name('users.pending');
    Route::post('/users/{user}/approve', [AdminUserController::class, 'approve'])->name('users.approve');
    Route::post('/users/{user}/reject', [AdminUserController::class, 'reject'])->name('users.reject');
    Route::get('/users/create-petugas', [AdminUserController::class, 'createPetugas'])->name('users.create-petugas');
    Route::post('/users/create-petugas', [AdminUserController::class, 'storePetugas'])->name('users.store-petugas');
    Route::get('/users/create-pengguna', [AdminUserController::class, 'createPengguna'])->name('users.create-pengguna');
    Route::post('/users/create-pengguna', [AdminUserController::class, 'storePengguna'])->name('users.store-pengguna');

    // Facility Management
    Route::resource('facilities', FacilityController::class)->except(['show']);
    Route::patch('/facilities/{facility}/status', [FacilityController::class, 'updateStatus'])->name('facilities.update-status');

    // Recap & Export
    Route::get('/recap', [RecapController::class, 'index'])->name('recap.index');
    Route::get('/recap/export.csv', [RecapController::class, 'exportCsv'])->name('recap.export');
});

require __DIR__.'/auth.php';
