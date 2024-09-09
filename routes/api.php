<?php

use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');




// Route::group(['prefix' => 'admin', 'middleware' => ['auth:sanctum', 'role:admin']], function () {
Route::post('/register', RegisterController::class);



Route::middleware('domain')->group(function () {
    Route::controller(LoginController::class)->group(function () {
        Route::post('/login', 'login');
        Route::post('/logout', 'logout')->middleware('auth:api');
    });
    Route::middleware('auth:api')->group(function (){
        Route::patch('tasks/change-task-status/{task}', [TaskController::class,'changeStatus']);
        Route::apiResources([
            'users' => UserController::class,
            'tasks' => TaskController::class,
        ]);
    });
});