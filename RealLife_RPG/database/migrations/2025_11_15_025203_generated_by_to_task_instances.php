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
        Schema::table('task_instances', function (Blueprint $table) {
            $table->unsignedBigInteger('generated_by_recurrence_id')
                ->nullable()
                ->after('task_id');
            $table->foreign('generated_by_recurrence_id')
                ->references('id')
                ->on('task_recurrences')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('task_instances', function (Blueprint $table) {
            $table->dropForeign(['generated_by_recurrence_id']);
            $table->dropColumn('generated_by_recurrence_id');
        });
    }
};
