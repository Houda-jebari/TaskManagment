<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubTask;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class SubTaskController extends Controller
{
   public function index($task_id)
    {
        // Return all subtasks associated with a specific task_id
        return SubTask::where('task_id', $task_id)->get();
    }
    

    public function store(Request $request, Task $task)
    {
        $subtask = new SubTask($request->all());
        $task->subtasks()->save($subtask);
        return response()->json($subtask, 201);
    } 
    public function storeTitle(Request $request,  $taskid)
    {
        Log::info('Task ID: ' . $taskid);
        Log::info('Request data: ' . json_encode($request->all()));
       $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $subtask = new SubTask();
        $subtask->title=$request->input('title');
        $task=Task::findOrfail($taskid);
        $task->subtasks()->save($subtask);
        return response()->json($subtask, 201);
    }


    public function show($id){
        return SubTask::findOrFail($id);
    }
    public function update(Request $request){
        $subtask = SubTask::findOrFail($request->id);
        $subtask->update($request->all());
        return response()->json($subtask,200);
    }

    public function updateStatus (Request $request,$id){
        $subtask=SubTask::findOrFail($id);
        $subtask->status=$request->status;
        $subtask->save();
        return response()->json($subtask);
    }
   public function assignUser(Request $request, $id)
   {
        $subtask = SubTask::findOrFail($id);
        $email = $request->input('email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $subtask->assigned_user_id = $user->id;
        $subtask->save();

        return response()->json($subtask, 200);
}

    public function destroy($id)
    {
        SubTask::destroy($id);
        return response()->json(null,204);
    }
}
