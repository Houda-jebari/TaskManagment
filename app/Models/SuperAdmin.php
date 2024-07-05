<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SuperAdmin extends Authenticatable
{
    use HasFactory;

    public function projects():HasMany{
        return $this->hasMany(Project::class);
    }
    public function users():HasMany{
        return $this->hasMany(User::class);
    }
}
