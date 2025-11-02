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
        Schema::create('match_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_request_id')->constrained()->onDelete('cascade');
            $table->foreignId('applicant_team_id')->constrained('teams')->onDelete('cascade');
            $table->text('message')->nullable();
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
            $table->timestamps();

            $table->unique(['match_request_id', 'applicant_team_id']);
            $table->index('match_request_id');
            $table->index('applicant_team_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('match_applications');
    }
};
