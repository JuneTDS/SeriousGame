<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\frontend\auth\LoginController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => ['guest']], function() {

    Route::get('/frontend/auth/login', [LoginController::class, 'show']);

    Route::post('/login', [LoginController::class, 'login'])->name("user.login");

});

Route::get('/dashboard', [DashboardController::class, 'show']);