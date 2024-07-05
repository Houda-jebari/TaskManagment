<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubTask extends Model
{
    use HasFactory;
     protected $attribute = [
        'title',
        'description',
        'status',
        'assigned_user_id',
    ];

    public function task():BelongsTo{
        return $this->belongsTo(Task::class ,);
    }
     // Relationship: Subtask belongs to a user (assigned user)
    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }
}
