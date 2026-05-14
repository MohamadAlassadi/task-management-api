<?php
namespace Modules\Team\App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Team\App\Models\TeamUser;
class TeamResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->team_id,
            'name' => $this->name,
            'description' => $this->description,
            'users' => $this->team_users->map(function ($TeamUser) {
                return [
                    'id' => $TeamUser->user->user_id,
                    'name' => $TeamUser->user->name,
                    'email' => $TeamUser->user->email,
                    'role' => $TeamUser->role,
                ];
            }),
        ];
    }
}