<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\SubTask;
use App\Models\Task;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectTaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $project = Project::find(1);

        if ($project) {
            // Create a new task
            $task = Task::create([
                'project_id' => $project->id,
                'title' => 'Sample Task',
            ]);

            // Create subtasks for the task
            $subtasks = [
                ['task_id' => $task->id, 'title' => 'Subtask 1'],
                ['task_id' => $task->id, 'title' => 'Subtask 2'],
                ['task_id' => $task->id, 'title' => 'Subtask 3'],
            ];

            foreach ($subtasks as $subtask) {
                SubTask::create($subtask);
            }
        }
    }
}
