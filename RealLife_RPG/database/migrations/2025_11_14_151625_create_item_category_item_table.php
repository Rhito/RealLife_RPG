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
        Schema::create('item_category_item', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('item_category_id');
            $table->timestamps();

            $table->foreign('item_id')->references('id')->on('items')->ondelete('cascade');
            $table->foreign('item_category_id')->references('id')->on('item_categories')->ondelete('cascade');

            $table->unique(['item_id', 'item_category_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_category_item');
    }
};
