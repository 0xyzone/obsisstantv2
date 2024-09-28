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
        Schema::create('match_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_making_id')->constrained()->cascadeOnDelete();
            $table->foreignId('team_player_id')->constrained()->cascadeOnDelete();
            $table->foreignId('game_hero_id')->constrained()->cascadeOnDelete();
            $table->integer('kills')->nullable();
            $table->integer('deaths')->nullable();
            $table->integer('assists')->nullable();
            $table->integer('net_worth')->nullable();
            $table->integer('hero_damage')->nullable();
            $table->integer('turret_damage')->nullable();
            $table->integer('damage_taken')->nullable();
            $table->integer('fight_participation')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('match_stats');
    }
};
