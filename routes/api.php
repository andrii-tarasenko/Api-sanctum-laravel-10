<?php

use App\Http\Controllers\API\Comment;
use App\Http\Controllers\API\Task;
use App\Http\Controllers\API\Team;
use App\Http\Controllers\API\User;
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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

/*Registraton and authorisation */
Route::controller(User::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
});

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('logout', [User::class, 'logout']);
    Route::controller(Task::class)->group(function () {
        Route::get('tasks', 'index');
        Route::get('tasks/{id}', 'show');
        Route::post('tasks', 'store');
        Route::put('tasks/{id}', 'update');
        Route::delete('tasks/{id}', 'destroy');
    });
    Route::controller(Comment::class)->group(function () {
        Route::post('tasks/{task_id}/comments', 'store');
        Route::get('tasks/{task_id}/comments', 'index');
        Route::delete('comments/{id}', 'destroy');
    });

    Route::controller(Team::class)->group(function () {
        Route::post('teams', 'store');
        Route::get('teams', 'index');
        Route::post('teams/{teamId}/users', 'addUserToTeam');
        Route::delete('teams/{teamId}/users/{userId}', 'removeUserFromTeam');
    });
});
