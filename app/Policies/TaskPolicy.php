<?php

namespace App\Policies;

use Modules\User\App\Models\User;
use Modules\Task\App\Models\Task;
use Modules\Project\App\Models\Project;
use Modules\Team\App\Models\TeamUser;
use Illuminate\Auth\Access\HandlesAuthorization;
class TaskPolicy
{

    public function view(User $user, Task $task)
    {
        return
            $user->user_id === $task->assigned_to
            || $user->user_id === $task->created_by
            || $user->user_id === $task->project->created_by;
    }


    public function viewProjectTasks(User $user, Project $project)
    {
        return $user->user_id === $project->created_by;
    }


    public function create(User $user, Project $project)
    {
        return $user->user_id === $project->created_by;
    }


    public function update(User $user, Task $task)
    {
        return $user->user_id === $task->project->created_by;
    }

    public function delete(User $user, Task $task)
    {
        return $user->user_id === $task->project->created_by;
    }


    public function changeStatus(User $user, Task $task)
    {
        return
            $user->user_id === $task->assigned_to
            || $user->user_id === $task->project->created_by;
    }
}