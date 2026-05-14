<?php

namespace Modules\Task\App\Http\Controllers;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Modules\Task\App\Http\Services\TaskService;
use Modules\Project\App\Models\Project;
use Modules\User\App\Models\User;
use Modules\Task\App\Models\Task;
use Modules\Team\App\Models\Team;
use Modules\Team\App\Models\TeamUser;
use Illuminate\Support\Facades\Log;
use Modules\Task\App\Http\Requests\CreateTaskRequest;
use Modules\Task\App\Http\Requests\UpdateTaskRequest;
use Modules\Task\App\Http\Requests\ChangeTaskStatusRequest;
use Exception;

class TaskController extends ApiController
{
    use AuthorizesRequests;
    protected $taskService;
    public function __Construct(TaskService $taskService)
     {
        $this->taskService = $taskService;
     }
     public function ListTasks()
     {
      try 
      {
        $tasks = $this->taskService->ListTasks();
        if (!$tasks['success']) {
            return $this->errorResponse('TASK-001-404', $tasks['message'], 404);
        }
        return  $this->successResponse($tasks['tasks'], $tasks['message'], 200);
      } catch (Exception $e) {
        Log::error('TASK-001-400: Error fetching tasks', ['error' => $e->getMessage()]);
        return $this->errorResponse('TASK-001-400', 'Error fetching tasks', 400);
      }
   }
   public function getTaskDetails(Task $task)
   {
      $this->authorize('view', $task);
    try 
    {
        $taskInfo = $this->taskService->getTaskDetails($task);
        if (!$taskInfo['success']) {
            return $this->errorResponse('TASK-002-404', $taskInfo['message'], 404);
        }
        return  $this->successResponse($taskInfo['task'], $taskInfo['message'], 200);
    } catch (Exception $e) {
        Log::error('TASK-002-400: Error fetching task details', ['error' => $e->getMessage()]);
        return $this->errorResponse('TASK-002-400', 'Error fetching task details', 400);
    }
   }
   public function createTask(CreateTaskRequest $request,Project $project)
   {

   $this->authorize('create', [Task::class, $project]);   
   try 
      {
         $task = $this->taskService->createTask($request->validated(),$project);
         if (!$task['success']) {
               return $this->errorResponse('TASK-003-400', $task['message'], 400);
         }
         return $this->successResponse($task['task'], $task['message'], 201);
      } catch (Exception $e) {
         Log::error('TASK-003-400: Error creating task', ['error' => $e->getMessage()]);
         return $this->errorResponse('TASK-003-400', 'Error creating task', 400);
      }
   }
   public function updateTask(UpdateTaskRequest $request, Task $task)
   {
      $this->authorize('update', [Task::class, $task]);
      try 
      {
         $updatedTask = $this->taskService->updateTask($request->validated(),$task);
         if (!$updatedTask['success']) {
               return $this->errorResponse('TASK-004-400', $updatedTask['message'], 400);
         }
         return $this->successResponse($updatedTask['task'], $updatedTask['message'], 200);
      } catch (Exception $e) {
         Log::error('TASK-004-400: Error updating task', ['error' => $e->getMessage()]);
         return $this->errorResponse('TASK-004-400', 'Error updating task', 400);
      }
   }
   public function deleteTask(Task $task)
   {
      $this->authorize('delete', $task);
      try 
      {
         $deleted = $this->taskService->deleteTask($task);
         if (!$deleted['success']) {
               return $this->errorResponse('TASK-005-400', $deleted['message'], 400);
         }
         return $this->successResponse(null, 'Task deleted successfully', 200);
      } catch (Exception $e) {
         Log::error('TASK-005-400: Error deleting task', ['error' => $e->getMessage()]);
         return $this->errorResponse('TASK-005-400', 'Error deleting task', 400);
      }
   }
   public function getByTasksByProject(Project $project)
   {
      $this->authorize('viewProjectTasks', [Task::class, $project]);
      try 
      {
         $tasks = $this->taskService->getByTasksByProject($project);
         if (!$tasks['success']) {
               return $this->errorResponse('TASK-006-400', $tasks['message'], 404);
         }
         return $this->successResponse($tasks['tasks'], $tasks['message'], 200);
      } catch (Exception $e) {
         Log::error('TASK-006-400: Error fetching tasks for project', ['error' => $e->getMessage()]);
         return $this->errorResponse('TASK-006-400', 'Error fetching tasks for project', 400);
      }
   }
   public function getTasksByUser(User $user)
   {
      try 
      {
         $tasks = $this->taskService->getTasksByUser($user);
         if (!$tasks['success']) {
               return $this->errorResponse('TASK-007-400', $tasks['message'], 404);
         }
         return $this->successResponse($tasks['tasks'], $tasks['message'], 200);
      } catch (Exception $e) {
         Log::error('TASK-007-400: Error fetching tasks for user', ['error' => $e->getMessage()]);
         return $this->errorResponse('TASK-007-400', 'Error fetching tasks for user', 400);
      }
   }
   public function changeTaskStatus(Task $task, ChangeTaskStatusRequest $request)
   {
      $this->authorize('changeStatus', $task);
      try 
      {
         $updatedTask = $this->taskService->changeTaskStatus($task, $request->status);
         if (!$updatedTask['success']) {
               return $this->errorResponse('TASK-008-400', $updatedTask['message'], 400);
         }
         return $this->successResponse($updatedTask['task'], $updatedTask['message'], 200);
      } catch (Exception $e) {
         Log::error('TASK-008-400: Error changing task status', ['error' => $e->getMessage()]);
         return $this->errorResponse('TASK-008-400', 'Error changing task status', 400);
      }
   }
}
