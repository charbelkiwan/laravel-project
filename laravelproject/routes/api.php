<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;

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

Route::resource('users', UserController::class);
Route::resource('projects', ProjectController::class);
Route::resource('tasks', TaskController::class);

Route::post('projects/{projectId}/users/{userId}', [ProjectController::class, 'assignUser']);
Route::post('users/{userId}/projects/{projectId}', [UserController::class, 'assignProject']);
Route::post('tasks/{taskId}/projects/{projectId}', [TaskController::class, 'assignToProject']);
Route::post('tasks/{taskId}/users/{userId}', [TaskController::class, 'assignToUser']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
