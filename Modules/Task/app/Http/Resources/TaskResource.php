<?php
namespace Modules\Task\App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Project\App\Models\Project;
use Modules\Team\App\Models\Team;
use Modules\User\App\Models\User;
class TaskResource extends JsonResource
{
    public function toArray($request)
    {
        $user = User::find($this->assigned_to);
        $project = Project::find($this->project_id);
         return [
            'task_id' => $this->task_id,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'assigned_to' => $this->assigned_to,
            'project_id' => $this->project_id,
            'project' => $project ? $project->title : null,
            'assignedUser' => $user ? $user->name : null,
        ];
    }
}