<?php

namespace Modules\Project\App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Modules\Project\App\Http\Services\ProjectService;
use Modules\Project\App\Models\Project;
use Modules\Project\App\Http\Requests\CreateProjectRequest;
use Modules\Project\App\Http\Requests\UpdateProjectRequest;
use Exception;
use Illuminate\Support\Facades\Log;
class ProjectController extends ApiController
{
    use AuthorizesRequests;
    protected $projectService;

    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }
    public function listProjects()
    {
        try 
        {
            $projects = $this->projectService->listProjects();
            if (!$projects['success']) {
                return $this->errorResponse('PROJECT-001-404', $projects['message'], 404);
            }
            return $this->successResponse($projects['projects'], $projects['message'], 200);
        } catch (Exception $e) {
            Log::error('PROJECT-002-400: Error fetching projects', ['error' => $e->getMessage()]);
            return $this->errorResponse('PROJECT-002-400', 'Error fetching projects',400);
        }
    }
    public function getProjectDetails(Project $project)
    {
        try 
        {

            $projectInfo = $this->projectService->getProjectDetails($project);
            if (!$projectInfo['success']) {
            return $this->errorResponse('PROJECT-004-404', $projectInfo['message'], 404);
            }
            return $this->successResponse($projectInfo['project'], $projectInfo['message'], 200);
        } catch (Exception $e) {
            Log::error('PROJECT-005-400: Error fetching project info', ['error' => $e->getMessage()]);
            return $this->errorResponse('PROJECT-005-400', 'Error fetching project info',400);
        }
    }
    public function createProject(CreateProjectRequest $request)
    {
        try 
        {
            $project = $this->projectService->createProject($request->validated());

            if (!$project['success']) {
            return $this->errorResponse('PROJECT-006-404', $project['message'], 404);
            }
            return $this->successResponse($project['project'], $project['message'], 200);
        } catch (Exception $e) {
            Log::error('PROJECT-007-400: Error creating project', ['error' => $e->getMessage()]);
            return $this->errorResponse('PROJECT-007-400', 'Error creating project',400);
        }
    }
    public function updateProject(UpdateProjectRequest $request, Project $project)
    {
        $this->authorize('update', $project);
        try 
        {
            $updatedProject = $this->projectService->updateProject($request->validated(), $project);

            if (!$updatedProject['success']) {
            return $this->errorResponse('PROJECT-009-404', $updatedProject['message'], 404);
            }
            return $this->successResponse($updatedProject['project'], $updatedProject['message'], 200);
        } catch (Exception $e) {
            Log::error('PROJECT-010-400: Error updating project', ['error' => $e->getMessage()]);
            return $this->errorResponse('PROJECT-010-400', 'Error updating project',400);
        }
    }
    public function deleteProject(Project $project)
    {
        $this->authorize('delete', $project);
        try 
        {

            $deleted = $this->projectService->deleteProject($project);

            if (!$deleted['success']) {
            return $this->errorResponse('PROJECT-012-404', $deleted['message'], 404);
            }
            return $this->successResponse(null, $deleted['message'], 200);
         } catch (Exception $e) {
            Log::error('PROJECT-013-400: Error deleting project', ['error' => $e->getMessage()]);
            return $this->errorResponse('PROJECT-013-400', 'Error deleting project',400);
        }
    }
}
