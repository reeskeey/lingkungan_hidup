<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebGisController;

// 1. WebGIS Interaktif Nasional
Route::get('/', [WebGisController::class, 'index'])->name('home');
Route::get('/webgis', [WebGisController::class, 'index'])->name('webgis.index');
Route::get('/webgis/data', [WebGisController::class, 'apiGeoData'])->name('webgis.data');
