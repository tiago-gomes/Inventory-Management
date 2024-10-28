<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('public/test', function (Request $request) {
    return response()->json(['data'=> 'ok'], 200);
});

// Routes that require authentication
Route::middleware('auth:sanctum')->group(function () {
    Route::get('protected/test', function (Request $request) {
        return response()->json(['data'=> 'ok'], 200);
    });
});
