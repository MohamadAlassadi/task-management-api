<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Modules\Project\App\Models\Project;
use App\Policies\ProjectPolicy;
use Modules\Team\App\Models\Team;
use App\Policies\TeamPolicy;
use App\Policies\TaskPolicy;
use Modules\Task\App\Models\Task;
class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Project::class => ProjectPolicy::class,
        Team::class => TeamPolicy::class,
        Task::class => TaskPolicy::class,

    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}