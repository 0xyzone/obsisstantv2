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
        Schema::create('tournament_group_teams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tournament_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tournament_team_id')->constrained()->cascadeOnDelete();
            $table->integer('p')->unsigned()->nullable();
            $table->integer('w')->unsigned()->nullable();
            $table->integer('d')->unsigned()->nullable();
            $table->integer('l')->unsigned()->nullable();
            $table->integer('f')->unsigned()->nullable();
            $table->integer('pts')->unsigned()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tournament_group_teams');
    }
};
