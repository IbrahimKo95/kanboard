<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'due_date',
        'completed_at',
        'project_id',
        'priority_id',
        'column_id',
        'user_id',
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignedUsers()
    {
        return $this->belongsToMany(User::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
