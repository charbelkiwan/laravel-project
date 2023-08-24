<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\API\SessionController;


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

Route::apiResource('users', UserController::class);
Route::apiResource('projects', ProjectController::class);
Route::apiResource('tasks', TaskController::class);

Route::post('login', [SessionController::class, 'store']);
Route::middleware('auth:sanctum')->post('logout', [SessionController::class, 'destroy']);

Route::get('export', [ProjectController::class, 'export']);
Route::post('import-projects', [ProjectController::class, 'import']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
