<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $completions = DB::table("task_completions")->get();
        foreach ($completions as $c) {
            // take task to know user_id
            $task = DB::table("tasks")->where("id", $c->task_id)->first();
            if ($task) continue;
            $date = date("Y-m-d", strtotime($c->completed_at));
            // if not have yet create a new one
            $instanceId = DB::table('task_instances')->insertGetId([
                'task_id' => $task->id,
                'user_id' => $task->user_id,
                'scheduled_date' => $date,
                'status' => 'completed',
                'completed_at' => $c->completed_at,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // asign completion -> instace
            DB::table('task_completions')
                ->where('id', $c->id)
                ->update(['instance_id' => $instanceId]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('task_instances')->delete();
        DB::table('task_completions')->update(['task_instance_id' => null]);
    }
};
