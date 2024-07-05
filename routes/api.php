<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SubtaskController;
use App\Http\Controllers\UserController;

// this is my routes file 
Route::post('/register', [RegisterController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    // Protected routes that require authentication
    Route::resource('tasks', TaskController::class);
    Route::resource('subtasks', SubtaskController::class);
    Route::resource('projects', ProjectController::class);
    Route::resource('users', UserController::class);
});

// Public routes accessible without authentication
Route::get('/user', function (Request $request) {
    return $request->user();
});

// Additional public routes as needed
