<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('register', [RegisterController::class,"register"])->name("register");
Route::patch('user/{user:id}', [RegisterController::class,"update"])->name("update");
Route::patch('user/api-update/{user:id}', [RegisterController::class,"updateNotifToken"])->name("updateNotifToken");
Route::post('login', [LoginController::class,"login"])->name("login");