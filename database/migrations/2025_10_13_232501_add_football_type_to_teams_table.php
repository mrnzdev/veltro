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
        Schema::table('teams', function (Blueprint $table) {
            $table->enum('football_type', ['football_5', 'football_7', 'football_11', 'futsal'])
                ->default('football_11')
                ->after('description');
        });

        // Update existing teams based on their current max_members
        // Map max_members to appropriate football_type
        DB::table('teams')->where('max_members', 5)->update(['football_type' => 'futsal']);
        DB::table('teams')->where('max_members', 7)->update(['football_type' => 'football_7']);
        DB::table('teams')->where('max_members', 11)->update(['football_type' => 'football_11']);

        // Set max_members based on football_type for consistency
        DB::table('teams')->where('football_type', 'football_5')->update(['max_members' => 5]);
        DB::table('teams')->where('football_type', 'futsal')->update(['max_members' => 5]);
        DB::table('teams')->where('football_type', 'football_7')->update(['max_members' => 7]);
        DB::table('teams')->where('football_type', 'football_11')->update(['max_members' => 11]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->dropColumn('football_type');
        });
    }
};
