<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\PengisianController;
use App\Http\Controllers\RekonsiliasiController;
use App\Http\Controllers\FormulaController;
use App\Http\Controllers\MetadataController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;

// ── Auth ──────────────────────────────────────────────
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

// ── Protected Routes ──────────────────────────────────
Route::middleware('auth')->group(function () {

    Route::get('/', fn() => redirect()->route('dashboard'));

    // Utama
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Monitoring
    Route::prefix('monitoring')->name('monitoring.')->group(function () {
        Route::get('/', [MonitoringController::class, 'index'])->name('index');
    });

    // Pengisian
    Route::prefix('pengisian')->name('pengisian.')->group(function () {
        Route::get('/', [PengisianController::class, 'index'])->name('index');
        Route::get('/{kode}', [PengisianController::class, 'show'])->name('show');
    });

    // Rekonsiliasi
    Route::prefix('rekonsiliasi')->name('rekonsiliasi.')->group(function () {
        Route::get('/', [RekonsiliasiController::class, 'index'])->name('index');
    });

    // Konfigurasi
    Route::prefix('formula')->name('formula.')->group(function () {
        Route::get('/', [FormulaController::class, 'index'])->name('index');
    });

    // Metadata
    Route::prefix('metadata')->name('metadata.')->group(function () {
        Route::get('/', [MetadataController::class, 'index'])->name('index');
        Route::get('/{kode}', [MetadataController::class, 'show'])->name('show');
    });

    // Data
    Route::prefix('data')->name('data.')->group(function () {
        Route::get('/', [DataController::class, 'index'])->name('index');
        Route::get('/{kode}', [DataController::class, 'show'])->name('show');
    });

    // Admin - Users
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::put('/{user}/password', [UserController::class, 'updatePassword'])->name('password');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
    });

});
