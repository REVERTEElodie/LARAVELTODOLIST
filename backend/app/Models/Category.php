<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    public function Tasks(): HasMany
    {
        return $this->hasMany(Task::class);

    }

    public function Tags(): HasMany
    {
        return $this->hasMany(Task::class);

    }
}
