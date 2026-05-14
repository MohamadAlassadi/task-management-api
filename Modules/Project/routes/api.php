<?php

use Illuminate\Support\Facades\Route;
use Modules\Project\App\Http\Controllers\ProjectController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::get('projects', [ProjectController::class, 'listProjects']);
    Route::post('projects/create', [ProjectController::class, 'createProject']);
    Route::patch('projects/update/{project}', [ProjectController::class, 'updateProject']);
    Route::get('projects/info/{project}', [ProjectController::class, 'getProjectDetails']);
    Route::delete('projects/delete/{project}', [ProjectController::class, 'deleteProject']);
});
