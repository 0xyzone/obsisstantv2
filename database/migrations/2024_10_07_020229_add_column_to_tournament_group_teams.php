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
        Schema::table('tournament_group_teams', function (Blueprint $table) {
            $table->foreignId('tournament_group_id')->constrained()->cascadeOnDelete()->after('tournament_team_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tournament_group_teams', function (Blueprint $table) {
            //
        });
    }
};
