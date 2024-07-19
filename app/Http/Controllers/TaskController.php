<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\SubTask;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class TaskController extends Controller /*implements HasMiddleware*/
{

  /*  public static function middleware()
    {
        return[
            new Middleware('auth:sanctum',except:['index','show'])
        ];
    }*/
    

    public function index()
    {
        //tasks of the current user

        $tasks = Task::with('subtasks')->where('assigned_user_id', Auth::id())->get();
        return response()->json($tasks);
    }

    public function getUsersAssignedToTask($taskId){
        try{
           $task=Task::findOrFail($taskId);
            $userAssigned = User::find($task->assigned_user_id);
             if (!$userAssigned) {
            throw new \Exception('Assigned user not found.');
        }
           return response()->json($userAssigned);
        }catch (\Exception $e) {
            return response()->json(['error' => 'Task not found or error retrieving users assigned.'], 404);
        }
    }
    public function findTasksOf()
    {
            $tasks=Task::with('subtasks')->where('assigned_user_id', auth()->id())->get();
        
            return response()->json($tasks);
    }
    
    public function getAssignedUser($taskId)
    {
        try {
            $task = Task::findOrFail($taskId);
            $assignedUser = $task->user()->first(['name', 'email']); // Assuming 'name' and 'email' are fields in your users table
            if($assignedUser){
                 return response()->json($assignedUser);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Task not found.'], 404);
        }
    }


    public function store(Request $request)
    {
        // $fields=$request->validate([
        //     'title'=>'required|max:191',
        //     'status'=>'required|max:191',
        // ]);
        
        try {
            $task = new Task($request->all());
            $task->assigned_user_id= Auth::id(); // Assign the authenticated user's ID
            $task->save();
            return response()->json($task, 201);
        } catch (\Exception $e) {
            Log::error('Error creating task: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to create task'], 500);
        }
    }
   public function getUsersOfProject(Request $request)
{
    try {
        $project = Project::findOrFail($request->id);
        $tasks = $project->tasks;
        
        $userIds = $tasks->pluck('assigned_user_id')->unique();

        $users = [];
        foreach ($userIds as $userId) {
            $user = User::find($userId);
            if ($user) {
                $users[] = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ];
            }
        }

        return response()->json($users);
    } catch (\Exception $e) {
        Log::error('Error fetching users assigned to project: ' . $e->getMessage());
        return response()->json(['error' => 'Failed to fetch users assigned to project'], 500);
    }
}

    public function show($id)
    {
        return Task::findOrFail($id);
    }

    public function update(Request $request)
    {
        $task = Task::findOrFail($request->id);
        $task->update($request->all());
        
        return response()->json($task, 200);
    }
    public function assignUser(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $user = User::where('email', $request->input('email'))->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $task->assigned_user_id=$user->id;
        $task->save();

        return response()->json(['task' => $task->load('assigneduser')], 200);
    }

    public function destroy($id)
    {
        Task::destroy($id);
        return response()->json(null, 204);
    }
}
