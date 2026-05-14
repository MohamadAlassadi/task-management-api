<?php

namespace App\Policies;

use Modules\User\App\Models\User;
use Modules\Project\App\Models\Project;
use Modules\Team\App\Models\Team;

class ProjectPolicy
{

    public function update(User $user, Project $project)
    {
        return $user->user_id === $project->created_by;
    }

    public function delete(User $user, Project $project)
    {
        return $user->user_id === $project->created_by;
    }
}