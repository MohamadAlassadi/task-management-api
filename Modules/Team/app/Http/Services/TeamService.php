<?php
namespace Modules\Team\App\Http\Services;
use Modules\Team\App\Models\Team;
use Modules\Team\App\Models\TeamUser;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Modules\User\App\Models\User;
use Modules\Team\App\Http\Resources\TeamResource;
class  TeamService
{
    public function listTeams()
    {
        try 
        {
        $teams=Team::with('team_users.user')->paginate(10);
        if ($teams->isEmpty()) {
            Log::error('TEAM-001-404: No teams found');
            return ['success' => false, 'message' => 'No teams found'];
        }
        return  [
                'success' => true,
                'message' => 'Teams fetched successfully',
                'teams' => TeamResource::collection($teams),
                ];
        } catch (Exception $e) {
            Log::error('TEAM-002-400: Failed to fetch teams', ['error' => $e->getMessage()]);
                return [
                'success' => false,
                'message' => 'Failed to fetch teams'
            ];
        }
    }
    public function getTeamDetails($team)
    {
        try 
        {
            return  [
                'success' => true,
                'message' => 'Team fetched successfully',
                'team' => new TeamResource($team),
                ];
        } catch (Exception $e) {
            Log::error('TEAM-003-400: Failed to fetch team info', ['error' => $e->getMessage()]);
                return [
                'success' => false,
                'message' => 'Failed to fetch team info'
            ];
        }
    }
    public function createTeam(array $request)
    {
        try 
        {
            $team = Team::create([
                'name' => $request['name'],
                'description' => $request['description'] ?? null,
                'owner_id' => auth()->user()->user_id,
            ]);
            $team->team_users()->create([
                'user_id' => auth()->user()->user_id,
                'role' => 'owner',
            ]);
            if (isset($request['users']) && is_array($request['users'])) {
                foreach ($request['users'] as $userId) {
                    TeamUser::create([
                        'team_id' => $team->team_id,
                        'user_id' => $userId,
                    ]);
                }
            }
            return [
                'success' => true,
                'message' => 'Team created successfully',
                'team' => new TeamResource($team),
            ];
        } catch (Exception $e) {
            Log::error('TEAM-004-400: Failed to create team', ['error' => $e->getMessage()]);
                return [
                'success' => false,
                'message' => 'Failed to create team'
            ];
        }
    }
    public function updateTeam(array $request, $team)
    {
        try 
        {
            $team->update([
                'name' => $request['name'] ?? $team->name,
                'description' => $request['description'] ?? $team->description,
            ]);

            if (isset($request['owner_id']) && $request['owner_id'] != $team->owner_id) {

                $team->update(['owner_id' => $request['owner_id']]);

                TeamUser::where('team_id', $team->team_id)
                    ->where('role', 'owner')
                    ->update(['role' => 'member']);

                TeamUser::updateOrCreate(
                    ['team_id' => $team->team_id, 'user_id' => $request['owner_id']],
                    ['role' => 'owner']
                );
            }
            return [
                'success' => true,
                'message' => 'Team updated successfully',
                'team' => new TeamResource($team),
            ];
        } catch (Exception $e) {
            Log::error('TEAM-005-400: Failed to update team', ['error' => $e->getMessage()]);
                return [
                'success' => false,
                'message' => 'Failed to update team'
            ];
        }
    }
    public function deleteTeam($team)
    {
        try 
        {
            $team->delete();
            return [
                'success' => true,
                'message' => 'Team deleted successfully',
            ];
        } catch (Exception $e) {
            Log::error('TEAM-006-400: Failed to delete team', ['error' => $e->getMessage()]);
                return [
                'success' => false,
                'message' => 'Failed to delete team'
            ];
        }
    }
    public function addUser($team, $userId)
    {
        try 
        {
            $user = User::find($userId);
            if (!$user) {
                return [
                    'success' => false,
                    'message' => 'User not found'
                ];
            }
            if ($team->team_users()->where('user_id', $userId)->exists()) {
                return [
                    'success' => false,
                    'message' => 'User already in team'
                ];
            }
            TeamUser::create([
                'team_id' => $team->team_id,
                'user_id' => $userId,
            ]);
            return [
                'success' => true,
                'message' => 'User added to team successfully',
            ];
        } catch (Exception $e) {
            Log::error('TEAM-008-400: Failed to add user to team', ['error' => $e->getMessage()]);
                return [
                'success' => false,
                'message' => 'Failed to add user to team'
            ];
        }
    }
    public function removeUser($team, $userId)
    {
        try 
        {
            $TeamUser = TeamUser::where('team_id', $team->team_id)->where('user_id', $userId)->first();
            if (!$TeamUser) {
                return [
                    'success' => false,
                    'message' => 'User not found in team'
                ];
            }
            if ($TeamUser->role === 'owner') {
                return [
                    'success' => false,
                    'message' => 'Cannot remove team owner'
                ];
            }
            $TeamUser->delete();
            return [
                'success' => true,
                'message' => 'User removed from team successfully',
            ];
        } catch (Exception $e) {
            Log::error('TEAM-007-400: Failed to remove user from team', ['error' => $e->getMessage()]);
                return [
                'success' => false,
                'message' => 'Failed to remove user from team'
            ];
        }
    }
    public function getTeamsByOwner($ownerId)
    {
        try 
        {
            $teams = Team::where('owner_id', $ownerId)->with('team_users.user')->paginate(10);
            if ($teams->isEmpty()) {
                Log::error('TEAM-009-404: No teams found for owner', ['owner_id' => $ownerId]);
                return ['success' => false, 'message' => 'No teams found for owner'];
            }
            return  [
                    'success' => true,
                    'message' => 'Teams fetched successfully',
                    'teams' => TeamResource::collection($teams),
                    ];
        } catch (Exception $e) {
            Log::error('TEAM-010-400: Failed to fetch teams by owner', ['error' => $e->getMessage(), 'owner_id' => $ownerId]);
                return [
                'success' => false,
                'message' => 'Failed to fetch teams by owner'
            ];
        }
    }
    public function getUserTeams($userId)
    {
        try 
        {
            $teams = Team::whereHas('team_users', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })->with('team_users.user')->paginate(10);
            if ($teams->isEmpty()) {
                Log::error('TEAM-011-404: No teams found for user', ['user_id' => $userId]);
                return ['success' => false, 'message' => 'No teams found for user'];
            }
            return  [
                    'success' => true,
                    'message' => 'Teams fetched successfully',
                    'teams' => TeamResource::collection($teams),
                    ];
        } catch (Exception $e) {
            Log::error('TEAM-012-400: Failed to fetch teams by user', ['error' => $e->getMessage(), 'user_id' => $userId]);
                return [
                'success' => false,
                'message' => 'Failed to fetch teams by user'
            ];
        }
    }
}