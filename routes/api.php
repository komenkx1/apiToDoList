<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\UserController;
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
Route::patch('user/{token}', [UserController::class, "update"])->name("update");
Route::patch('user/notif-api-update/{token}', [UserController::class, "updateNotifToken"])->name("updateNotifToken");

Route::post('login', [LoginController::class, "login"])->name("login");

Route::middleware('authApi:api')->group(function () {
    Route::delete('logout', [LoginController::class, "logout"])->name("logout");
    Route::get('task-active', [TaskController::class, "indexActive"])->name("index_task_active");
    Route::get('task-completed', [TaskController::class, "indexCompleted"])->name("index_task_completed");
    Route::post('task', [TaskController::class, "create"])->name("create_task");
    Route::put('task', [TaskController::class, "update"])->name("update_task");
    Route::delete('/deletetask', [TaskController::class, "delete"])->name("delete_task");
    Route::put('/task/lastseen/{id}', [TaskController::class, "last_seen"])->name("last_seen_task");

    Route::post('task-completed', [TaskController::class, "complete_task"])->name("complete_task");
    Route::post('task-uncompleted', [TaskController::class, "uncomplete_task"])->name("uncomplete_task");
});
