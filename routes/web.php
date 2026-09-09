<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebGisController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GapAnalysisController;

// 1. WebGIS Interaktif Nasional
Route::get('/', [WebGisController::class, 'index'])->name('home');
Route::get('/webgis', [WebGisController::class, 'index'])->name('webgis.index');
Route::get('/webgis/data', [WebGisController::class, 'apiGeoData'])->name('webgis.data');

// 2. Dashboard Eksekutif
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// 3. Analisis Kesenjangan Kapasitas (Gap Analysis)
Route::get('/gap-analysis', [GapAnalysisController::class, 'index'])->name('gap-analysis');
