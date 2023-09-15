<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FormController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('/v1')->group(function() {
    // Authentication
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/register', [AuthController::class, 'register']);

    Route::middleware('login')->prefix(function() {
        Route::get('/forms', [FormController::class, 'index']);
        Route::post('/forms/create', [FormController::class, 'store']);
        Route::get('/forms/{slug}', [FormController::class, 'show']);
    });
    
});