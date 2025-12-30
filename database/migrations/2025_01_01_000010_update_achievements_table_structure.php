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
        Schema::table('achievements', function (Blueprint $table) {
            // Check if columns exist before strictly acting, or just trust the previous state?
            // Safer to just renaming/dropping knowing the state from reading the file.
            
            // Rename title -> name if name doesn't exist
            if (!Schema::hasColumn('achievements', 'name')) {
                // If title exists, rename it. If not (weird), create name.
                 if (Schema::hasColumn('achievements', 'title')) {
                     $table->renameColumn('title', 'name');
                 } else {
                     $table->string('name');
                 }
            }

            // Drop old condition columns
            if (Schema::hasColumn('achievements', 'condition_type')) {
                $table->dropColumn('condition_type');
            }
            if (Schema::hasColumn('achievements', 'condition_value')) {
                $table->dropColumn('condition_value');
            }
            if (Schema::hasColumn('achievements', 'icon')) {
                // Keep icon, it's useful.
            } else {
                 $table->string('icon')->nullable();
            }


            // Add new condition column
            if (!Schema::hasColumn('achievements', 'condition')) {
                $table->json('condition')->nullable();
            }

            // Add other fields
            if (!Schema::hasColumn('achievements', 'is_active')) {
                $table->boolean('is_active')->default(true);
            }
            if (!Schema::hasColumn('achievements', 'item_reward_id')) {
                $table->foreignId('item_reward_id')->nullable()->constrained('items')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('achievements', function (Blueprint $table) {
             // Reverse is hard with conditional logic, but generally:
             $table->renameColumn('name', 'title');
             $table->string('condition_type')->nullable();
             $table->integer('condition_value')->nullable();
             $table->dropColumn('condition');
             $table->dropColumn('is_active');
             $table->dropForeign(['item_reward_id']);
             $table->dropColumn('item_reward_id');
        });
    }
};
