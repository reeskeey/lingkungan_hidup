<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\DashboardApiController;
use App\Http\Controllers\Api\WebGisApiController;
use App\Http\Controllers\Api\FasyankesApiController;
use App\Http\Controllers\Api\RoadmapApiController;
use App\Http\Middleware\AuthenticateApiToken;

/*
|--------------------------------------------------------------------------
| API Routes untuk Mobile & Integrasi Layanan Eksternal
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {
    // 1. Rute Publik
    Route::post('/auth/login', [AuthApiController::class, 'login']);
    Route::get('/dashboard/summary', [DashboardApiController::class, 'summary']);
    Route::get('/webgis/data', [WebGisApiController::class, 'data']);
    Route::get('/provinces', [FasyankesApiController::class, 'provinces']);
    Route::get('/provinces/{province}/regencies', [FasyankesApiController::class, 'regencies']);
    Route::get('/roadmap', [RoadmapApiController::class, 'index']);

    // 2. Rute Terproteksi (Wajib Bearer Token Petugas)
    Route::middleware(AuthenticateApiToken::class)->group(function () {
        Route::get('/user', [AuthApiController::class, 'user']);
        Route::post('/auth/logout', [AuthApiController::class, 'logout']);

        // Katalog & Input Lapangan Fasyankes
        Route::get('/fasyankes', [FasyankesApiController::class, 'index']);
        Route::post('/fasyankes', [FasyankesApiController::class, 'store']);

        // Pembaruan Progres Aksi Roadmap
        Route::patch('/roadmap/{action}/progress', [RoadmapApiController::class, 'updateProgress']);
    });
});
