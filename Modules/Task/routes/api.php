<?php

use Illuminate\Support\Facades\Route;
use Modules\Task\App\Http\Controllers\TaskController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::get('/tasks', [TaskController::class, 'ListTasks']);
     Route::post('/tasks/create/{project}', [TaskController::class, 'createTask']);
     Route::get('/tasks/info/{task}', [TaskController::class, 'getTaskDetails']);
     Route::put('/tasks/update/{task}', [TaskController::class, 'updateTask']);
     Route::delete('/tasks/delete/{task}', [TaskController::class, 'deleteTask']);
     Route::get('/projects/{project}/tasks', [TaskController::class, 'getByTasksByProject']);
     Route::get('/users/{user}/tasks', [TaskController::class, 'getTasksByUser']);
     Route::patch('/task/status/{task}', [TaskController::class, 'changeTaskStatus']);
});
