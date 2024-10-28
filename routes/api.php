<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Public Routes
Route::post('/login', [AuthController::class, 'login'])
    ->name('auth.login');

// Public test routes
Route::get('public/test', function (Request $request) {
    return response()->json(['data'=> 'ok'], 200);
});

// Routes that require authentication
Route::middleware('auth:sanctum')->group(function () {

    Route::get('protected/test', function (Request $request) {
        return response()->json(['data'=> 'ok'], 200);
    });

    // user logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
});
