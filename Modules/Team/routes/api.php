<?php

use Illuminate\Support\Facades\Route;
use Modules\Team\App\Http\Controllers\TeamController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::get('/teams', [TeamController::class, 'listTeams']);
    Route::get('/teams/info/{team}', [TeamController::class, 'getTeamDetails']);
    Route::post('/teams/create', [TeamController::class, 'createTeam']);
    Route::get('/teams/by-owner/{owner}', [TeamController::class, 'getTeamsByOwner']);
    Route::put('/teams/update/{team}', [TeamController::class, 'updateTeam']);
    Route::delete('/teams/delete/{team}', [TeamController::class, 'deleteTeam']);
    Route::get('/teams/by-user/{user}', [TeamController::class, 'getUserTeams']);
    Route::post('/team/add-user/{team}/{user}', [TeamController::class, 'addUser']);
    Route::post('/team/remove-user/{team}/{user}', [TeamController::class, 'removeUser']);
});
