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
        Schema::table('task_recurrences', function (Blueprint $table) {
            if(Schema::hasColumn('task_recurrences', 'week_day')){
                $table->renameColumn('week_day', 'weekday');
            }
            if(Schema::hasColumn('task_recurrences', 'interval_day')){
                $table->renameColumn('interval_day', 'interval_days');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('task_recurrences', function (Blueprint $table) {
            if(Schema::hasColumn('task_recurrences', 'week_day')){
                $table->renameColumn('weekday', 'week_day');
            }
            if(Schema::hasColumn('task_recurrences', 'interval_day')){
                $table->renameColumn('interval_days', 'interval_day');
            }
        });
    }
};
