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
        Schema::create('task_instances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('task_id');
            $table->unsignedBigInteger('user_id'); // tránh join tasks -> user quá nhiều
            $table->date('scheduled_date');
            $table->enum('status', ['pending','completed', 'missed'])->default('pending');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->unique(['task_id', 'scheduled_date']); // 1 task = 1 instance trong ngày
            $table->index(['user_id', 'scheduled_date']);

            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
        Schema::table('task_completions', function (Blueprint $table) {
            $table->unsignedBigInteger('task_instance_id')->nullable()->after('task_id');
            $table->foreign('task_instance_id')->references('id')->on('task_instances')->onDelete('cascade');
            $table->unique('task_instance_id');
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('task_completions', function (Blueprint $table) {
            $table->dropForeign(['task_instance_id']);
            $table->dropColumn(['task_instance_id']);
        });
        Schema::dropIfExists('task_completions');
    }
};
