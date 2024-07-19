<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    protected $fillable = [
    'title',
    'description',
    'status',
    'assigned_user_id',
    'project_id',
    'due_date',
    ];

    use HasFactory;
    public function assigneduser():BelongsTo
    {
        //'assigned_user_id' parameter specifies the name of the foreign key column (assigned_user_id) in the tasks table 
        return $this->belongsTo(User::class, 'assigned_user_id');

    }
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
    public function  subtasks():HasMany
    {
        return $this->hasMany(SubTask::class);
    }
    

}
