<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController; 
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// ==========================================
// RUTE AUTENTIKASI 
// ==========================================
Route::get('/', [AuthController::class, 'showLogin'])->name('auth.login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('auth.authenticate');

// ==========================================
// RUTE TERPROTEKSI (Wajib Login)
// ==========================================
Route::middleware(['auth'])->group(function () {
    
    // Rute Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');

    // Rute Default / Fallback (jika diakses tanpa prefix)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Rute Profile (Tersedia untuk semua role)
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // ------------------------------------------
    // RUTE DINAMIS BERDASARKAN ROLE
    // ------------------------------------------
    $roles = [
        'ulp'         => 'managerULP',
        'up3'         => 'managerUP3',
        'pelayanan'   => 'pelayanan',
        'admin'       => 'administrator',
        'konstruksi'  => 'konstruksi',
        'jaringan'    => 'jaringan',
        'perencanaan' => 'perencanaan',
        'transaksi'   => 'transaksi',
    ];

    foreach ($roles as $prefix => $roleMiddleware) {
        Route::middleware(['role:' . $roleMiddleware])->prefix($prefix)->name($prefix . '.')->group(function () {
            Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
            Route::get('/data-pbpd', [DashboardController::class, 'dataPbpd'])->name('data_pbpd');
            Route::get('/tanpa-perluasan', [DashboardController::class, 'tanpaPerluasan'])->name('tanpa_perluasan');
            Route::get('/perluasan-jtm', [DashboardController::class, 'perluasanJtm'])->name('perluasan_jtm');
            Route::get('/perluasan-jtr', [DashboardController::class, 'perluasanJtr'])->name('perluasan_jtr');
            Route::get('/pengoperasian', [DashboardController::class, 'pengoperasian'])->name('pengoperasian');
            Route::get('/pencarian', [DashboardController::class, 'pencarian'])->name('pencarian');
            Route::get('/proses-perluasan', [DashboardController::class, 'prosesPerluasan'])->name('proses_perluasan');
            Route::get('/restitusi', [DashboardController::class, 'restitusi'])->name('restitusi');
            Route::get('/laporan', [DashboardController::class, 'laporan'])->name('laporan');
            Route::get('/notifikasi', [DashboardController::class, 'notifikasi'])->name('notifikasi');
            Route::get('/ba-operasi', [DashboardController::class, 'baOperasi'])->name('ba_operasi');
            Route::get('/survey', [DashboardController::class, 'survey'])->name('survey');
            Route::get('/checklist', [DashboardController::class, 'checklist'])->name('checklist');
            Route::get('/upload-data', [DashboardController::class, 'uploadData'])->name('upload_data');
            Route::post('/upload-data', [DashboardController::class, 'storeUploadData'])->name('upload_data.store');
        });
    }
});