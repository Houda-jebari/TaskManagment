<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Task;
use App\Models\Subtask;

class ProjectController extends Controller
{
    public function index()
    {
        //projects with their tasks and subtasks
        $projects = Project::with('tasks.subtasks')->get();
        return response()->json($projects);
    }

    public function store(Request $request)
    {
        $project = Project::create($request->all());

        //creating projects with their tasks and subtasks
        foreach ($request->tasks as $taskData) {
            $task = $project->tasks()->create($taskData);
            foreach ($taskData['subtasks'] as $subtaskData) {
                $task->subtasks()->create($subtaskData);
            }
        }

        return response()->json($project->load('tasks.subtasks'));
    }

    public function update(Request $request, Project $project)
    {
        $project->update($request->all());
        
        // Remove existing tasks and subtasks
        $project->tasks()->each(function ($task) {
            $task->subtasks()->delete();
            $task->delete();
        });
        // Add new tasks and subtasks
        foreach ($request->tasks as $taskData) {
            $task = $project->tasks()->create($taskData);
            foreach ($taskData['subtasks'] as $subtaskData) {
                $task->subtasks()->create($subtaskData);
            }
        }
        return response()->json($project->load('tasks.subtasks'));
    }
    
    public function assignUsersProject(Request $request,$projectId){

    }

    public function destroy(Project $project)
    {
        $project->delete();
        return response()->json(['message' => 'Project deleted successfully']);
    }
}
