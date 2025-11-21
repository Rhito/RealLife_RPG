<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskRecurrence extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'rule_type',
        'weekday',
        'month_day',
        'interval_days',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'weekday' => 'integer',
        'month_day' => 'integer',
        'interval_days' => 'integer'
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
