<?php

use App\Http\Controllers\User\DashboardController;
use Illuminate\Support\Facades\Route;

// User Customer Routes
Route::get('/', [DashboardController::class, 'index'])->name('user.dashboard');
Route::get('/dashboard', [DashboardController::class, 'index']);

Route::get('/konsultasi', function () {
    return redirect()->route('user.dashboard')->with('info', 'Halaman Konsultasi akan segera dimuat.');
})->name('user.consultation');

Route::get('/produk-saya', function () {
    return redirect()->route('user.dashboard')->with('info', 'Halaman Produk Saya akan segera dimuat.');
})->name('user.products');

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
