<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskRecurrence extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'rule',
        'ex_dates',
        'rule_type', // Keep this if we still want to categorize, though rule string has it
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'ex_dates' => 'array',
    ];

    // Relationship
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function generatedInstances()
    {
        return $this->hasMany(TaskInstance::class, 'generated_by_recurrence_id');
    }
}
