<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SubtaskController;
use App\Http\Controllers\UserController;
use App\Models\Task;
use App\Models\User;

// public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',[AuthController::class,'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

//admin routes
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
Route::get('/project', [ProjectController::class, 'index']);
Route::resource('projects', ProjectController::class);
Route::post('projects/{projectId}/tasks', [ProjectController::class, 'storeTask']);
Route::post('projects/{projectId}/tasks/{taskId}/subtasks', [ProjectController::class, 'storeSubTask']);
Route::get('projects/{projectId}/tasks', [ProjectController::class, 'getTasks']);
Route::get('projects/{projectId}/tasks/{taskId}/subtasks', [ProjectController::class, 'getSubTasks']); 
Route::patch('/tasks/{task}/subtasks/{subtask}', [SubtaskController::class, 'updateStatus']);

Route::resource('/subtask', SubtaskController::class);
Route::put('/subtasks/{id}/assign', [SubTaskController::class, 'assignUser']);

// Route for deleting subtasks
Route::delete('/subtasks/{subtaskId}', [SubtaskController::class, 'destroy']);

Route::post('/task/{task}/assign',[TaskController::class,'assignUser']);


Route::get('/task/getUsersOfProject', [TaskController::class, 'getUsersOfProject']);
Route::get('tasks/{taskId}/assigned-user', [TaskController::class, 'getAssignedUser']);

Route::get('/user/{userId}', [UserController::class, 'show']);
 

Route::get('/users',[UserController::class],'index');

Route::post('/projects', [ProjectController::class, 'store']);
Route::post('/projects/{project}/assign', [ProjectController::class, 'assignUsers']);
Route::get('/users', [UserController::class, 'index']);
Route::put('/users/{user}', [UserController::class, 'update']);
Route::delete('/users/{user}', [UserController::class, 'destroy']);



});


//user routes : 
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/tasks', [TaskController::class, 'index']);
    Route::post('/tasks', [TaskController::class, 'store']);
    Route::get('/tasks/{task}', [TaskController::class, 'show']);
    Route::put('/tasks', [TaskController::class, 'update']);
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);


    Route::post('/tasks/{task}/subtasks/title',[SubtaskController::class,'storeTitle']);
    Route::delete('/subtasks/{subtaskId}', [SubtaskController::class, 'destroy']);


    Route::get('/tasks/{task}/subtasks', [SubtaskController::class, 'index']);
    Route::post('/tasks/{task}/subtasks', [SubtaskController::class, 'store']);
    Route::put('/tasks/{task}/subtasks/{subtask}', [SubtaskController::class, 'update']);
    Route::put('/subtask', [SubtaskController::class, 'update']);

    Route::delete('/tasks/{task}/subtasks/{subtask}', [SubtaskController::class, 'destroy']);
    Route::patch('/subtasks/{subtask}', [SubtaskController::class, 'updateStatus']);

});
// 
// Public routes accessible without authentication
/*Route::get('/user', function (Request $request) {
    return $request->user();
});//->middleware('auth:sanctum');

*/
// Additional public routes as needed
