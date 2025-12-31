<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('task_instances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->date('scheduled_date');
            $table->string('status')->default('pending'); 
            
            $table->timestamp('completed_at')->nullable();
            
            $table->string('generated_by')->nullable(); 
            $table->unsignedBigInteger('generated_by_recurrence_id')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_instances');
    }
};
