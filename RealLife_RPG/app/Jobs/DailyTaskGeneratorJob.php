<?php

namespace App\Jobs;

use App\Models\TaskInstance;
use App\Models\TaskRecurrence;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use RRule\RRule;

class DailyTaskGeneratorJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $today = Carbon::today();

        // Get all active recurrences with their tasks (EAGER LOAD the task and user)
        $recurrences = TaskRecurrence::where('is_active', true)
            ->with(['task.user'])
            ->get();

        foreach ($recurrences as $recurrence) {
            if (empty($recurrence->rule)) {
                continue;
            }

            try {
                // Parse the rule to extract DTSTART if present
                $ruleString = $recurrence->rule;
                $dtstart = null;
                
                // Check if DTSTART is in the rule
                if (preg_match('/DTSTART=(\d{8}T\d{6})/', $ruleString, $matches)) {
                    $dtstart = $matches[1];
                    // Remove DTSTART from the rule string as it needs to be passed separately
                    $ruleString = preg_replace('/;?DTSTART=[^;]+/', '', $ruleString);
                }
                
                // Create RRule instance - pass DTSTART as array parameter if it exists
                if ($dtstart) {
                    // Parse DTSTART: format is YYYYMMDDTHHMMSS
                    $year = substr($dtstart, 0, 4);
                    $month = substr($dtstart, 4, 2);
                    $day = substr($dtstart, 6, 2);
                    $hour = substr($dtstart, 9, 2);
                    $minute = substr($dtstart, 11, 2);
                    $second = substr($dtstart, 13, 2);
                    $dtstartFormatted = "$year-$month-$day $hour:$minute:$second";
                    
                    // Build array config
                    $config = ['DTSTART' => $dtstartFormatted];
                    
                    // Parse rule string parts
                    foreach (explode(';', $ruleString) as $part) {
                        if (!empty($part) && strpos($part, '=') !== false) {
                            list($key, $value) = explode('=', $part, 2);
                            $config[$key] = $value;
                        }
                    }
                    
                    $rrule = new RRule($config);
                } else {
                    $rrule = new RRule($ruleString);
                }

                // Check if the rule occurs today (any time during the day)
                $occurrences = $rrule->getOccurrencesBetween($today->copy()->startOfDay(), $today->copy()->endOfDay());

                if (count($occurrences) > 0) {
                    // Check if instance already exists to prevent duplicates
                    // Use datetime format to match what's stored in the database
                    $exists = TaskInstance::where('task_id', $recurrence->task_id)
                        ->whereDate('scheduled_date', $today->toDateString())
                        ->exists();

                    if (!$exists && $recurrence->task) {
                        TaskInstance::create([
                            'task_id' => $recurrence->task_id,
                            'user_id' => $recurrence->task->user_id,
                            'scheduled_date' => $today,
                            'generated_by_recurrence_id' => $recurrence->id,
                            'status' => 'pending', // Default status
                        ]);
                    }
                }
            } catch (\Exception $e) {
                Log::error("Failed to generate task instance for Recurrence ID {$recurrence->id}: " . $e->getMessage());
            }
        }
    }
}
