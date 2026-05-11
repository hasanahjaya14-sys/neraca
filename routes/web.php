<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\PengisianController;
use App\Http\Controllers\RekonsiliasiController;
use App\Http\Controllers\FormulaController;
use App\Http\Controllers\MetadataController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/', fn() => redirect()->route('dashboard'));

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::prefix('monitoring')->name('monitoring.')->group(function () {
    Route::get('/', [MonitoringController::class, 'index'])->name('index');
});

Route::prefix('pengisian')->name('pengisian.')->group(function () {
    Route::get('/', [PengisianController::class, 'index'])->name('index');
});

Route::prefix('rekonsiliasi')->name('rekonsiliasi.')->group(function () {
    Route::get('/', [RekonsiliasiController::class, 'index'])->name('index');
});

Route::prefix('formula')->name('formula.')->group(function () {
    Route::get('/', [FormulaController::class, 'index'])->name('index');
});

Route::prefix('metadata')->name('metadata.')->group(function () {
    Route::get('/', [MetadataController::class, 'index'])->name('index');
});

Route::prefix('data')->name('data.')->group(function () {
    Route::get('/', [DataController::class, 'index'])->name('index');
});

Route::prefix('users')->name('users.')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index');
});