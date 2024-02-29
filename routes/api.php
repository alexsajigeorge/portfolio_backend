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
    /////////////////////////////// SKILLS ROUTES ///////////////////////////////////////
    Route::post('/addSkill', [App\Http\Controllers\Admin\SkillsController::class, 'addSkill']);
    Route::post('/updateSkill/{id}', [App\Http\Controllers\Admin\SkillsController::class, 'updateSkill']);
    Route::delete('/deleteSkill/{id}', [App\Http\Controllers\Admin\SkillsController::class, 'deleteSkill']);
    /////////////////////////////// PROFILE ROUTES ///////////////////////////////////////
    Route::post('/addProfile', [App\Http\Controllers\Admin\ProfileController::class, 'AddProfile']);
    Route::post('/updateProfile/{id}', [App\Http\Controllers\Admin\ProfileController::class, 'updateProfile']);
    Route::post('/deleteProfile/{id}', [App\Http\Controllers\Admin\ProfileController::class, 'deleteProfile']);
    /////////////////////////////// PROJECT ROUTES ///////////////////////////////////////
});


/////////////////////////////// ADMIN AUTH ROUTES ///////////////////////////////////////

Route::post('/register', [App\Http\Controllers\Admin\AuthController::class, 'register']);
Route::post('/login', [App\Http\Controllers\Admin\AuthController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\Admin\AuthController::class, 'logout']);



/////////////////////////////// FRONTEND ROUTES ///////////////////////////////////////

Route::get('/getSkills', [App\Http\Controllers\Admin\SkillsController::class, 'getSkills']);
Route::get('/getProfile', [App\Http\Controllers\Admin\ProfileController::class, 'getProfile']);