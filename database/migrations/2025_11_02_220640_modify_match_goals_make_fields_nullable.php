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
        Schema::table('match_goals', function (Blueprint $table) {
            $table->integer('minute')->nullable()->change();
            $table->foreignId('scorer_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('match_goals', function (Blueprint $table) {
            $table->integer('minute')->nullable(false)->change();
            $table->foreignId('scorer_id')->nullable(false)->change();
        });
    }
};
