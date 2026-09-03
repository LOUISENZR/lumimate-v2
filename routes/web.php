<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\User\ConsultationController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\ProductCollectionController;
use Illuminate\Support\Facades\Route;

// Public: Landing page
Route::get('/', [LandingController::class, 'index'])->name('landing');

// Public: Authentication
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// User Customer Routes (must be logged in)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('user.dashboard');

    Route::get('/konsultasi', [ConsultationController::class, 'index'])->name('user.consultation');
    Route::post('/konsultasi', [ConsultationController::class, 'store'])->name('user.consultation.store');
    Route::get('/konsultasi/hasil', [ConsultationController::class, 'result'])->name('user.consultation.result');

    Route::get('/produk-saya', [ProductCollectionController::class, 'index'])->name('user.products');
    Route::post('/produk-saya', [ProductCollectionController::class, 'store'])->name('user.products.store');
    Route::delete('/produk-saya/{userProduct}', [ProductCollectionController::class, 'destroy'])->name('user.products.destroy');
    Route::patch('/produk-saya/{userProduct}/toggle', [ProductCollectionController::class, 'toggle'])->name('user.products.toggle');

    Route::get('/analisis-kandungan', function () {
        return redirect()->route('user.dashboard')->with('info', 'Halaman Analisis Kandungan akan segera dimuat.');
    })->name('user.ingredient.analysis');

    Route::get('/pemeriksa-konflik', function () {
        return redirect()->route('user.dashboard')->with('info', 'Halaman Pemeriksa Konflik akan segera dimuat.');
    })->name('user.conflicts');

    Route::get('/perencana-rutinitas', function () {
        return redirect()->route('user.dashboard')->with('info', 'Halaman Perencana Rutinitas akan segera dimuat.');
    })->name('user.routine');

    Route::get('/pelacak-harian', function () {
        return redirect()->route('user.dashboard')->with('info', 'Halaman Pelacak Harian akan segera dimuat.');
    })->name('user.tracker');

    Route::get('/pemantauan-kemajuan', function () {
        return redirect()->route('user.dashboard')->with('info', 'Halaman Pemantauan Kemajuan akan segera dimuat.');
    })->name('user.progress');
});