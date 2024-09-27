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
        Schema::create('tournament_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tournament_id')->constrained()->cascadeOnDelete();
            $table->foreignId('team_a')->constrained('tournament_teams')->cascadeOnDelete();
            $table->bigInteger('team_a_mp')->nullable();
            $table->foreignId('team_b')->constrained('tournament_teams')->cascadeOnDelete();
            $table->bigInteger('team_b_mp')->nullable();
            $table->foreignId('match_winner')->constrained('tournament_teams')->cascadeOnDelete();
            $table->string('title')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tournament_matches');
    }
};
