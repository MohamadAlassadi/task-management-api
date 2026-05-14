<?php
namespace App\Policies;
use Modules\User\App\Models\User;
use Modules\Team\App\Models\Team;   
class TeamPolicy
{
    public function update(User $user, Team $team)
    {
        return $user->user_id === $team->owner_id;
    }

    public function delete(User $user, Team $team)
    {
        return $user->user_id === $team->owner_id;
    }
    public function addUser(User $user, Team $team)
    {
        return $user->user_id === $team->owner_id;
    }
    public function removeUser(User $user, Team $team)
    {
        return $user->user_id === $team->owner_id;
    }

}