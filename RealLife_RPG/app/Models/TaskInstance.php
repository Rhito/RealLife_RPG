<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskInstance extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'generated_by_recurrence_id',
        'user_id',
        'scheduled_date',
        'status',
        'completed_at',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'completed_at' => 'datetime',
    ];

    // Relationships
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function user()
    {
        return $this->belongsTO(User::class);
    }

    public function recurrnce()
    {
        return $this->belongsTo(TaskRecurrence::class, 'generated_by_recurrence_id');
    }

    public function completion()
    {
        return $this->hasOne(TaskCompletion::class);
    }
}
