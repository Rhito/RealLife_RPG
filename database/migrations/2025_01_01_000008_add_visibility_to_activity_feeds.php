<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('activity_feeds', function (Blueprint $table) {
            $table->string('visibility')->default('public')->after('activity_type');
        });
    }

    public function down(): void
    {
        Schema::table('activity_feeds', function (Blueprint $table) {
            $table->dropColumn('visibility');
        });
    }
};
