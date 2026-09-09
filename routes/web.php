<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebGisController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GapAnalysisController;
use App\Http\Controllers\RoadmapController;
use App\Http\Controllers\FasyankesController;
use App\Http\Controllers\TreatmentFacilityController;
use App\Http\Controllers\TransferLocationController;
use App\Http\Controllers\AuthController;

// ================= 1. RUTE PUBLIK (DAPAT DIAKSES SEMUA PENGUNJUNG) =================
Route::get('/', [WebGisController::class, 'index'])->name('home');
Route::get('/webgis', [WebGisController::class, 'index'])->name('webgis.index');
Route::get('/webgis/data', [WebGisController::class, 'apiGeoData'])->name('webgis.data');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Autentikasi
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ================= 2. RUTE TERPROTEKSI (WAJIB LOGIN PETUGAS) =================
Route::middleware('auth')->group(function () {
    // Analisis Kesenjangan Kapasitas (Gap Analysis)
    Route::get('/gap-analysis', [GapAnalysisController::class, 'index'])->name('gap-analysis');

    // Matriks Peta Jalan 10 Tahun (Roadmap 2026 - 2036)
    Route::get('/roadmap', [RoadmapController::class, 'index'])->name('roadmap.index');
    Route::get('/roadmap/export-csv', [RoadmapController::class, 'exportCsv'])->name('roadmap.export');
    Route::patch('/roadmap/{action}/progress', [RoadmapController::class, 'updateProgress'])->name('roadmap.progress');
    Route::post('/roadmap', [RoadmapController::class, 'store'])->name('roadmap.store');
    Route::delete('/roadmap/{action}', [RoadmapController::class, 'destroy'])->name('roadmap.destroy');

    // Data Master CRUD
    Route::resource('fasyankes', FasyankesController::class);
    Route::resource('treatment-facilities', TreatmentFacilityController::class);
    Route::resource('transfer-locations', TransferLocationController::class);
});
