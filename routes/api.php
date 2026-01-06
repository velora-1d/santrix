<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\KbmController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public Routes
Route::post('/kbm/login', [KbmController::class, 'login']);

// Protected Routes (Required Token)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // KBM Feature
    Route::get('/kbm/jadwal', [KbmController::class, 'getJadwal']);
    Route::get('/kbm/santri/{kelasId}', [KbmController::class, 'getSantriByKelas']);
    Route::post('/kbm/jurnal', [KbmController::class, 'storeJurnal']);
});
