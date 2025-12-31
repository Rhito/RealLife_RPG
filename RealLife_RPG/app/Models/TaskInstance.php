<?php

namespace App\Models;

use App\Enums\TaskStatus;
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
        'generated_by',
    ];

    protected $casts = [
        'scheduled_date' => 'datetime',
        'completed_at' => 'datetime',
        'status' => TaskStatus::class,
        'priority' => 'string'
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

    public function recurrence()
    {
        return $this->belongsTo(TaskRecurrence::class, 'generated_by_recurrence_id');
    }

    public function completion()
    {
        return $this->hasOne(TaskCompletion::class);
    }
}
