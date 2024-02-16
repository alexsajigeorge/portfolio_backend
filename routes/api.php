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



Route::middleware('auth:sanctum')->group(function () {
    Route::get('/getAuthUser', [App\Http\Controllers\Admin\AuthController::class, 'getAuthUser']);
});


/////////////////////////////// ADMIN AUTH ROUTES   ///////////////////////////////////////

Route::post('/register', [App\Http\Controllers\Admin\AuthController::class, 'register']);
Route::post('/login', [App\Http\Controllers\Admin\AuthController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\Admin\AuthController::class, 'logout']);



/////////////////////////////// FRONTEND ROUTES   ///////////////////////////////////////
