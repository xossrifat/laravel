<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Shortlink API routes
Route::prefix('shortlinks')->group(function () {
    Route::get('/available', [\App\Http\Controllers\ShortlinkController::class, 'getAvailableShortlinks']);
    Route::post('/verify', [\App\Http\Controllers\ShortlinkController::class, 'verifyCompletion']);
});

// Other API routes as needed 