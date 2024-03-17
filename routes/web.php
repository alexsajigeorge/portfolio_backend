<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('/getSkills', [App\Http\Controllers\Admin\SkillsController::class, 'getSkills']);
Route::get('/getProjects', [App\Http\Controllers\Admin\ProjectController::class, 'getProjects']);
Route::get('/getProfile', [App\Http\Controllers\Admin\ProfileController::class, 'getProfile']);

Route::get('/getContacts', [App\Http\Controllers\Frontend\ContactController::class, 'getContacts']);
Route::post('/contactMe', [App\Http\Controllers\Frontend\ContactController::class, 'contactMe']);
Route::delete('/deleteContact/{id}', [App\Http\Controllers\Frontend\ContactController::class, 'deleteContact']);

Route::get('/getSocialLinks', [App\Http\Controllers\Admin\SocialLinkController::class, 'getSocialLinks']);
