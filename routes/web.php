<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebGisController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GapAnalysisController;
use App\Http\Controllers\RoadmapController;
use App\Http\Controllers\FasyankesController;
use App\Http\Controllers\TreatmentFacilityController;
use App\Http\Controllers\TransferLocationController;

// 1. WebGIS Interaktif Nasional
Route::get('/', [WebGisController::class, 'index'])->name('home');
Route::get('/webgis', [WebGisController::class, 'index'])->name('webgis.index');
Route::get('/webgis/data', [WebGisController::class, 'apiGeoData'])->name('webgis.data');

// 2. Dashboard Eksekutif
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// 3. Analisis Kesenjangan Kapasitas (Gap Analysis)
Route::get('/gap-analysis', [GapAnalysisController::class, 'index'])->name('gap-analysis');

// 4. Matriks Peta Jalan 10 Tahun (Roadmap 2026 - 2036)
Route::get('/roadmap', [RoadmapController::class, 'index'])->name('roadmap.index');
Route::get('/roadmap/export-csv', [RoadmapController::class, 'exportCsv'])->name('roadmap.export');

// 5. Data Master CRUD
Route::resource('fasyankes', FasyankesController::class);
Route::resource('treatment-facilities', TreatmentFacilityController::class);
Route::resource('transfer-locations', TransferLocationController::class);
