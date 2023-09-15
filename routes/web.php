<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\auth\LoginController;
use App\Http\Controllers\auth\RegisterController;
use App\Http\Controllers\frontend\ClassController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => ['guest']], function() {

    Route::get('/login', [LoginController::class, 'show'])->name("login");

    Route::post('/user/login', [LoginController::class, 'login'])->name("user.login");

    Route::get('/classcode', [RegisterController::class, 'showClassCode'])->name("showClassCode");

    Route::post('/user/classcode', [RegisterController::class, 'checkClassCode'])->name("user.classcode");

    Route::get('/register', [RegisterController::class, 'showRegister'])->name("showRegister");

    Route::post('/user/register', [RegisterController::class, 'register'])->name("user.register");

});

Route::group(['middleware' => ['auth']], function() {

    Route::get('/', [DashboardController::class, 'show']);

    Route::get('/home', [DashboardController::class, 'show']);

    Route::get('/frontend/classes', [ClassController::class, 'index']);

});