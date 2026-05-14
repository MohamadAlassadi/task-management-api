<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApiExceptionHandler
{
    public function handleModel(ModelNotFoundException $e, Request $request): \Illuminate\Http\JsonResponse
    {
        $ip = $request->ip();
        $model = $e->getModel();

        $map = [
            \Modules\Project\App\Models\Project::class => [
                'code' => 'PROJECT-101-404',
                'name' => 'Project',
            ],
            \Modules\User\App\Models\User::class => [
                'code' => 'USER-102-404',
                'name' => 'User',
            ],
                \Modules\Team\App\Models\Team::class => [
                    'code' => 'TEAM-103-404',
                    'name' => 'Team',
                ],
                \Modules\Team\App\Models\TeamUser::class => [
                    'code' => 'TeamUser-104-404',
                    'name' => 'TeamUser',
                ],
                \Modules\Task\App\Models\Task::class => [
                    'code' => 'TASK-105-404',
                    'name' => 'Task',
                ],
                \Modules\Team\App\Models\Team::class => [
                    'code' => 'TEAM-106-404',
                    'name' => 'Team',
                ],
                \Modules\Team\App\Models\TeamUser::class => [
                    'code' => 'TeamUser-107-404',
                    'name' => 'TeamUser',
                ],
        ];

        $data = $map[$model] ?? [
            'code' => '000-000-404',
            'name' => class_basename($model),
        ];

        Log::error("ModelNotFound: {$model} | IP: {$ip} | Code: {$data['code']}");

        return response()->json([
            'response_code' => $data['code'],
            'message' => $data['name'] . ' not found',
        ], 404);
    }
    public function handleAuthorization(AuthorizationException $e, Request $request): \Illuminate\Http\JsonResponse
{
    $ip = $request->ip();

    $route = $request->route();
    $parameters = $route?->parameters() ?? [];

    $model = null;

    foreach ($parameters as $param) {
        if (is_object($param)) {
            $model = get_class($param);
            break;
        }
    }

        $map = [
            \Modules\Project\App\Models\Project::class => [
                'code' => 'PROJECT-101-404',
                'name' => 'Project',
            ],
            \Modules\User\App\Models\User::class => [
                'code' => 'USER-102-404',
                'name' => 'User',
            ],
                \Modules\Team\App\Models\Team::class => [
                    'code' => 'TEAM-103-404',
                    'name' => 'Team',
                ],
                \Modules\Team\App\Models\TeamUser::class => [
                    'code' => 'TeamUser-104-404',
                    'name' => 'TeamUser',
                ],
                \Modules\Task\App\Models\Task::class => [
                    'code' => 'TASK-105-404',
                    'name' => 'Task',
                ],
                \Modules\Team\App\Models\Team::class => [
                    'code' => 'TEAM-106-404',
                    'name' => 'Team',
                ],
                \Modules\Team\App\Models\TeamUser::class => [
                    'code' => 'TeamUser-107-404',
                    'name' => 'TeamUser',
                ],
        ];

    $data = $map[$model] ?? [
        'code' => '000-000-403',
        'name' => $model ? class_basename($model) : 'Resource',
    ];

    Log::warning("Unauthorized {$data['name']} | IP: {$ip} | Code: {$data['code']}");

    return response()->json([
        'response_code' => $data['code'],
        'message' => "You are not authorized to access this {$data['name']}",
    ], 403);
}
    public function handleFallback(Request $request): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'response_code' => '000-000-404',
            'message' => 'The requested resource was not found',
        ], 404);
    }
}