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
        Schema::table('match_makings', function (Blueprint $table) {
            $table->unsignedBigInteger('match_winner')->nullable()->change();
            // $table->foreignId('match_winner')->nullable()->constrained('tournament_teams')->cascadeOnDelete()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('match_makings', function (Blueprint $table) {
            //
        });
    }
};
