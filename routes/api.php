<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\TaskController;
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

Route::post('register', [RegisterController::class, "register"])->name("register");
Route::patch('user/{user:id}', [RegisterController::class, "update"])->name("update");
Route::post('login', [LoginController::class, "login"])->name("login");

Route::get('task', [TaskController::class, "index"])->name("index_task");
Route::post('task', [TaskController::class, "create"])->name("create_task");
Route::put('/task/{id}', [TaskController::class, "update"])->name("update_task");
Route::delete('/task/{id}', [TaskController::class, "delete"])->name("delete_task");
Route::put('/task/lastseen/{id}', [TaskController::class, "last_seen"])->name("last_seen_task");
