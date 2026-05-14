<?php
namespace Modules\Project\App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Team\App\Models\Team;
use Modules\User\App\Models\User;

class ProjectResource extends JsonResource
{
 
    public function toArray($request)
    {
        $team = Team::where('team_id', $this->team_id)->first();
        $created_by = User::where('user_id', $this->created_by)->first();
        return [
            'id' => $this->project_id,
            'title' => $this->title,
            'description' => $this->description,
            'team' => $team ? $team->name : null,
            'created_by'=> $created_by ? $created_by->name : null,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}