<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            
            $table->string('type'); 
            $table->string('difficulty'); 
            
            $table->json('repeat_days')->nullable(); 
            
            $table->integer('reward_exp')->default(10);
            $table->integer('reward_coins')->default(10);
            
            $table->boolean('is_active')->default(true);
            $table->timestamp('due_date')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
