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
        Schema::table('matches', function (Blueprint $table) {
            $table->dateTime('team_result_submitted_at')->nullable()->after('status');
            $table->foreignId('team_result_submitted_by')->nullable()->constrained('users')->onDelete('set null')->after('team_result_submitted_at');
            $table->dateTime('opponent_result_submitted_at')->nullable()->after('team_result_submitted_by');
            $table->foreignId('opponent_result_submitted_by')->nullable()->constrained('users')->onDelete('set null')->after('opponent_result_submitted_at');
            $table->dateTime('result_confirmed_at')->nullable()->after('opponent_result_submitted_by');
            $table->integer('team_score')->default(0)->after('result_confirmed_at');
            $table->integer('opponent_score')->default(0)->after('team_score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->dropForeign(['team_result_submitted_by']);
            $table->dropForeign(['opponent_result_submitted_by']);
            $table->dropColumn([
                'team_result_submitted_at',
                'team_result_submitted_by',
                'opponent_result_submitted_at',
                'opponent_result_submitted_by',
                'result_confirmed_at',
                'team_score',
                'opponent_score',
            ]);
        });
    }
};
