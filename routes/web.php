<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\FacilityController;
use App\Http\Controllers\Admin\RecapController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/pending', [AdminUserController::class, 'pending'])->name('users.pending');
    Route::post('/users/{user}/approve', [AdminUserController::class, 'approve'])->name('users.approve');
    Route::post('/users/{user}/reject', [AdminUserController::class, 'reject'])->name('users.reject');
    Route::get('/users/create-petugas', [AdminUserController::class, 'createPetugas'])->name('users.create-petugas');
    Route::post('/users/create-petugas', [AdminUserController::class, 'storePetugas'])->name('users.store-petugas');
    Route::get('/users/create-pengguna', [AdminUserController::class, 'createPengguna'])->name('users.create-pengguna');
    Route::post('/users/create-pengguna', [AdminUserController::class, 'storePengguna'])->name('users.store-pengguna');

    Route::resource('facilities', FacilityController::class)->except(['show']);
    Route::patch('/facilities/{facility}/status', [FacilityController::class, 'updateStatus'])->name('facilities.update-status');

    Route::get('/recap', [RecapController::class, 'index'])->name('recap.index');
    Route::get('/recap/export.csv', [RecapController::class, 'exportCsv'])->name('recap.export');
});

require __DIR__.'/auth.php';
