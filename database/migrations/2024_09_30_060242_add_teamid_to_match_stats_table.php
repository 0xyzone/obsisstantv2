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
        Schema::table('match_stats', function (Blueprint $table) {
            $table->foreignId('tournament_team_id')->constrained()->cascadeOnDelete()->after('match_making_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('match_stats', function (Blueprint $table) {
            //
        });
    }
};
