<?php

namespace Modules\Team\App\Http\Controllers;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Modules\Team\App\Http\Services\TeamService;
use Modules\Team\App\Models\Team;
use Modules\User\App\Models\User;
use Exception;
use Illuminate\Support\Facades\Log;
use Modules\Team\App\Http\Requests\CreateTeamRequest;
use Modules\Team\App\Http\Requests\UpdateTeamRequest;

class TeamController extends ApiController
{
    use AuthorizesRequests;
    protected $teamService;

    public function __construct(TeamService $teamService)
    {

        $this->teamService = $teamService;
    }
    public function listTeams()
    {
        try 
        {
            $teams = $this->teamService->listTeams();

            if (!$teams['success']) {
            return $this->errorResponse('TEAM-001-404', $teams['message'], 404);
            }
            return $this->successResponse($teams['teams'], $teams['message'], 200);
        } catch (Exception $e) {
            Log::error('TEAM-002-400: Error fetching teams', ['error' => $e->getMessage()]);
            return $this->errorResponse('TEAM-002-400', 'Error fetching teams',400);
        }
    }
    public function getTeamDetails(Team $team)
    {
        try 
        {

            $teamInfo = $this->teamService->getTeamDetails($team);
            if (!$teamInfo['success']) {
            return $this->errorResponse('TEAM-004-404', $teamInfo['message'], 404);
            }
            return $this->successResponse($teamInfo['team'], $teamInfo['message'], 200);
        } catch (Exception $e) {
            Log::error('TEAM-005-400: Error fetching team info', ['error' => $e->getMessage()]);
            return $this->errorResponse('TEAM-005-400', 'Error fetching team info',400);
        }
    }
    public function createTeam(CreateTeamRequest $request)
    {
        try 
        {
            $team = $this->teamService->createTeam($request->validated());

            if (!$team['success']) {
            return $this->errorResponse('TEAM-006-404', $team['message'], 404);
            }
            return $this->successResponse($team['team'], $team['message'], 200);
        } catch (Exception $e) {
            Log::error('TEAM-007-400: Error creating team', ['error' => $e->getMessage()]);
            return $this->errorResponse('TEAM-007-400', 'Error creating team',400);
        }
    }
    public function updateTeam(UpdateTeamRequest $request, Team $team)
    {
        $this->authorize('update', $team);
        try 
        {

            $updatedTeam = $this->teamService->updateTeam($request->validated(), $team);
            if (!$updatedTeam['success']) {
            return $this->errorResponse('TEAM-009-404', $updatedTeam['message'], 404);
            }
            return $this->successResponse($updatedTeam['team'], $updatedTeam['message'], 200);
        } catch (Exception $e) {
            Log::error('TEAM-010-400: Error updating team', ['error' => $e->getMessage()]);
            return $this->errorResponse('TEAM-010-400', 'Error updating team',400);
        }
    }
    public function deleteTeam(Team $team)
    {
        
        $this->authorize('delete', $team);
        try 
        {

            $deleted = $this->teamService->deleteTeam($team);
            if (!$deleted['success']) {
            return $this->errorResponse('TEAM-012-404', $deleted['message'], 404);
            }
            return $this->successResponse(null, $deleted['message'], 200);
        } catch (Exception $e) {
            Log::error('TEAM-013-400: Error deleting team', ['error' => $e->getMessage()]);
            return $this->errorResponse('TEAM-013-400', 'Error deleting team',400);
        }
    }
    public function addUser(Team $team, User $user)    {
        
        $this->authorize('addUser', $team);

        try 
        {
            $result = $this->teamService->addUser($team, $user->user_id);
            if (!$result['success']) {
            return $this->errorResponse('TEAM-015-404', $result['message'], 404);
            }
            return $this->successResponse(null, $result['message'], 200);
        } catch (Exception $e) {
            Log::error('TEAM-016-400: Error adding user to team', ['error' => $e->getMessage()]);
            return $this->errorResponse('TEAM-016-400', 'Error adding user to team',400);
        }
    }
    public function removeUser(Team $team, User $user)    {
        
        $this->authorize('removeUser', $team);
        try 
        {
   
            $result = $this->teamService->removeUser($team, $user->user_id);
            if (!$result['success']) {
            return $this->errorResponse('TEAM-018-404', $result['message'], 404);
            }
            return $this->successResponse(null, $result['message'], 200);
            

        } catch (Exception $e) {
            Log::error('TEAM-019-400: Error removing user from team', ['error' => $e->getMessage()]);
            return $this->errorResponse('TEAM-019-400', 'Error removing user from team',400);
        }
    }
    public function getTeamsByOwner(User $owner)
    {
        try 
        {
            if (!$owner) {
                return $this->errorResponse('TEAM-020-404', 'Owner not found', 404);
            }
            $teams = $this->teamService->getTeamsByOwner($owner->user_id);
            if (!$teams['success']) {
            return $this->errorResponse('TEAM-021-404', $teams['message'], 404);
            }
            return $this->successResponse($teams['teams'], $teams['message'], 200);
        } catch (Exception $e) {
            Log::error('TEAM-022-400: Error fetching teams by owner', ['error' => $e->getMessage()]);
            return $this->errorResponse('TEAM-022-400', 'Error fetching teams by owner',400);
        }
    }
    public function getUserTeams(User $user)
    {
        try 
        {
  
            $teams = $this->teamService->getUserTeams($user->user_id);
            if (!$teams['success']) {
            return $this->errorResponse('TEAM-024-404', $teams['message'], 404);
            }
            return $this->successResponse($teams['teams'], $teams['message'], 200);
        } catch (Exception $e) {
            Log::error('TEAM-025-400: Error fetching teams by user', ['error' => $e->getMessage()]);
            return $this->errorResponse('TEAM-025-400', 'Error fetching teams by user',400);
        }
    }
}
