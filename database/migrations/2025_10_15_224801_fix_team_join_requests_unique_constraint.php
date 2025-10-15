<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop the existing unique constraint that prevents multiple accepted/rejected records
        Schema::table('team_join_requests', function (Blueprint $table) {
            $table->dropUnique(['team_id', 'user_id', 'status']);
        });

        // We'll rely on application logic to prevent duplicate pending requests
        // This allows multiple historical records (accepted/rejected) for the same team/user
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore the unique constraint if rolling back
        Schema::table('team_join_requests', function (Blueprint $table) {
            $table->unique(['team_id', 'user_id', 'status']);
        });
    }
};
