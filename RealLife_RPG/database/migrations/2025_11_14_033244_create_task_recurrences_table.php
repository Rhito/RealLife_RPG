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
        Schema::create('task_recurrences', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('task_id');
            $table->enum('rule_type', [
                'weekly', // repeat days(Mon -> Sun)
                'monthly', //repeat monthly(1-31)
                'interval', // repeat every x day
                'custom', // reserved (in case we need to scale advenced rules )
            ]);

            // WEEKLY: weekday = 0 (mon) -> 6 (sun)
            $table->tinyInteger('week_day')->nullable();

            // Monthly: 1->31
            $table->tinyInteger('month_day')->nullable();

            // Interval: repeat after x days
            $table->integer('interval_day')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            //FK
            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade');

            // index to make CRUD fast
            $table->index(['task_id', 'rule_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_recurrences');
    }
};
