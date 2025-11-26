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
            $table->text('rule')->nullable()->after('task_id');
            $table->json('ex_dates')->nullable()->after('rule');
        });

        Schema::table('task_recurrences', function (Blueprint $table) {
            $table->dropColumn(['weekday', 'month_day', 'interval_days']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('task_recurrences', function (Blueprint $table) {
            $table->tinyInteger('weekday')->nullable();
            $table->tinyInteger('month_day')->nullable();
            $table->integer('interval_days')->nullable();
            $table->dropColumn(['rule', 'ex_dates']);
        });
    }
};
