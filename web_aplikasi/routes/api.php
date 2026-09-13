<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FileController;
use App\Http\Controllers\Api\VendorReportController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::prefix('perencanaan')->group(function () {
        Route::get('/files', [FileController::class, 'getFiles']);
        Route::post('/upload', [FileController::class, 'uploadFile']);
    });

    Route::prefix('konstruksi')->group(function () {
        Route::get('/files', [FileController::class, 'getFiles']);
        Route::post('/upload', [FileController::class, 'uploadFile']);
    });

    Route::post('/vendor/laporan', [VendorReportController::class, 'store']);
    Route::get('/vendor/laporan', [VendorReportController::class, 'index']);
    Route::post('/vendor/laporan/{report}/update', [VendorReportController::class, 'update']);
    Route::delete('/vendor/laporan/{report}', [VendorReportController::class, 'destroy']);
});
