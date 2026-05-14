<?php
namespace Modules\Task\App\Http\Services;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Modules\Task\App\Models\Task;
use Modules\Task\App\Http\Resources\TaskResource;
use Modules\Project\App\Models\Project;
use Modules\User\App\Models\User;
use Modules\Team\App\Models\Team;
use Modules\Team\App\Models\TeamUser;
use App\Support\Api\Resources\WithPagination;

use Exception;
class TaskService
{
     public function ListTasks()
     {
        try 
        {
            $tasks = Task::with(['project', 'assignedUser'])->paginate(10);
            if ($tasks->isEmpty()) {
                Log::error('TASK-000-404: No tasks found');
                return [
                    'success' => false,
                    'message' => 'No tasks found'
                ];
            }
            return [
                'success' => true,
                'tasks' => TaskResource::collection($tasks)->response()->getData(true),
                'message' => 'Tasks retrieved successfully'
            ];
        } catch (Exception $e) {
            Log::error('TASK-001-400: Error fetching tasks', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => 'Error fetching tasks'
            ];
        }
     }
     public function getTaskDetails($task)
     {
        try 
        {
            $task->load(['project', 'assignedUser']);
            return [
                'success' => true,
                'task' => new TaskResource($task),
                'message' => 'Task details retrieved successfully'
            ];
        } catch (Exception $e) {
            Log::error('TASK-002-400: Error fetching task details', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => 'Error fetching task details'
            ];
        }
     }
     public function createTask($request,$project)
     {
        try 
        {
            
            $memberExists = TeamUser::where('team_id', $project->team_id)
                ->where('user_id', $request['assigned_to'])
                ->exists();

            if (!$memberExists) {
                return [
                    'success' => false,
                    'message' => 'The assigned user is not a member of the project team.'
                ];
            }
            
            $task = Task::create([
                'title' => $request['title'],
                'description' => $request['description'] ?? null,
                'status' => $request['status'],
                'assigned_to' => $request['assigned_to'],
                'project_id' => $project->project_id,
                'created_by' => auth()->user()->user_id,
            ]);
            return [
                'success' => true,
                'task' => new TaskResource($task),
                'message' => 'Task created successfully'
            ];
        } catch (Exception $e) {
            Log::error('TASK-003-400: Error creating task', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => 'Error creating task'
            ];
        }
     }
     public function updateTask($request, $task)
     {
        try 
        {
            $project = $task->project;
            $memberExists = TeamUser::where('team_id', $project->team_id)
                ->where('user_id', $request['assigned_to'])
                ->exists();

            if (!$memberExists) {
                return [
                    'success' => false,
                    'message' => 'The assigned user is not a member of the project team.'
                ];
            }           
            $task->update([
                'title' => $request['title'],
                'description' => $request['description'] ?? null,
                'status' => $request['status'],
                'assigned_to' => $request['assigned_to'],
            ]);
            return [
                'success' => true,
                'task' => new TaskResource($task),
                'message' => 'Task updated successfully'
            ];
        } catch (Exception $e) {
            Log::error('TASK-004-400: Error updating task', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => 'Error updating task'
            ];
        }
     }
     public function deleteTask($task)
     {
        try 
        {
            $task->delete();
            return [
                'success' => true,
                'message' => 'Task deleted successfully'
            ];
        } catch (Exception $e) {
            Log::error('TASK-005-400: Error deleting task', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => 'Error deleting task'
            ];
        }
     }
     public function getByTasksByProject($project)
     {
        try 
        {
            $tasks = Task::with(['assignedUser'])->where('project_id', $project->project_id)->paginate(10);
            return [
                'success' => true,
                'tasks' => TaskResource::collection($tasks)->response()->getData(true),
                'message' => 'Tasks retrieved successfully'
            ];
        } catch (Exception $e) {
            Log::error('TASK-006-400: Error fetching tasks by project', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => 'Error fetching tasks by project'
            ];
        }
     }
     public function getTasksByUser($user)
     {
        try 
        {
            $tasks = Task::with(['project'])->where('assigned_to', $user->user_id)->paginate(10);
            return [
                'success' => true,
                'tasks' => TaskResource::collection($tasks)->response()->getData(true),
                'message' => 'Tasks retrieved successfully'
            ];
        } catch (Exception $e) {
            Log::error('TASK-007-400: Error fetching tasks by user', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => 'Error fetching tasks by user'
            ];
        }
     }
     public function changeTaskStatus($task, $status)
     {
        try 
        {
            $task->update(['status' => $status]);
            return [
                'success' => true,
                'task' => new TaskResource($task),
                'message' => 'Task status updated successfully'
            ];
        } catch (Exception $e) {
            Log::error('TASK-008-400: Error changing task status', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => 'Error changing task status'
            ];
        }
     }
     
}