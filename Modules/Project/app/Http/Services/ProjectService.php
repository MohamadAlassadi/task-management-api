<?php
namespace Modules\Project\App\Http\Services;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Modules\Project\App\Models\Project;
use Modules\Project\App\Http\Resources\ProjectResource;
use Modules\Team\App\Models\Team;
use Modules\User\App\Models\User;
use App\Support\Api\Resources\WithPagination;
use Exception;
class ProjectService
{  
    public function listProjects()
    {
        try 
        {
        $projects=Project::paginate(10);
        if ($projects->isEmpty()) {
            Log::error('PROJECT-014-404: No projects found');
            return ['success' => false, 'message' => 'No projects found'];
        }
        return  [
                'success' => true,
                'message' => 'Projects fetched successfully',
                'projects' => ProjectResource::collection($projects),
                ];
        } catch (Exception $e) {
            Log::error('PROJECT-015-400: Failed to fetch projects', ['error' => $e->getMessage()]);
                return [
                'success' => false,
                'message' => 'Failed to fetch projects'
            ];
        }
    }
    public function getProjectDetails($project)
    {
        try 
        {
            return  [
                'success' => true,
                'message' => 'Project fetched successfully',
                'project' => new ProjectResource($project),
                ];
        } catch (Exception $e) {
            Log::error('PROJECT-016-400: Failed to fetch project info', ['error' => $e->getMessage()]);
                return [
                'success' => false,
                'message' => 'Failed to fetch project info'
            ];
        }
    }
    public function createProject(array $request)
    {
        try 
        { 
            $created_by = $request['created_by'];
            if (!$created_by)
                {
                    return [
                        'success' => false,
                        'message' => 'Creator not found'
                    ];
                }
            $team_id = $request['team_id'];
            $team = Team::find($team_id);
            if (!$team)
                {
                    return [
                        'success' => false,
                        'message' => 'Team not found'
                    ];
                }
                if ($created_by!=$team->owner_id)
                {
                    return [
                        'success' => false,
                        'message' => 'Unauthorized to create project for this team'
                    ];

                }
            $project=Project::create($request + [
                'created_by'=>$created_by,
                'team_id'=>$team_id,
            ]);
            return  [
                'success' => true,
                'message' => 'Project created successfully',
                'project' => new ProjectResource($project),
                ];
        } catch (Exception $e) {
            Log::error('PROJECT-019-400: Failed to create project', ['error' => $e->getMessage()]);
                return [
                'success' => false,
                'message' => 'Failed to create project'
            ];
        }
    }
    public function updateProject(array $request,$project)
    {
        try 
        { 
            $project->update($request);
            return  [
                'success' => true,
                'message' => 'Project updated successfully',
                'project' => new ProjectResource($project),
                ];
        } catch (Exception $e) {
            Log::error('PROJECT-020-400: Failed to update project', ['error' => $e->getMessage()]);
                return [
                'success' => false,
                'message' => 'Failed to update project'
            ];
        }
    }
    public function deleteProject($project)
    {
        try 
        { 
            $project->delete();
            return  [
                'success' => true,
                'message' => 'Project deleted successfully',
            ];
        } catch (Exception $e) {
            Log::error('PROJECT-021-400: Failed to delete project', ['error' => $e->getMessage()]);
                return [
                'success' => false,
                'message' => 'Failed to delete project'
            ];
        }
    }
}