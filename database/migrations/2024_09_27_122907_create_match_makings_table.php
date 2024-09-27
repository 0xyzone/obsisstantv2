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
        Schema::create('match_makings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tournament_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->longText('title')->nullable();
            $table->foreignId('team_a')->constrained('tournament_teams')->cascadeOnDelete();
            $table->foreignId('team_b')->constrained('tournament_teams')->cascadeOnDelete();
            $table->foreignId('match_winner')->constrained('tournament_teams')->cascadeOnDelete();
            $table->integer('team_a_mp')->nullable();
            $table->integer('team_b_mp')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('match_makings');
    }
};
