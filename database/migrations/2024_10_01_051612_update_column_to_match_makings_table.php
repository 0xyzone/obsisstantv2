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
            $table->dropColumn('match_winner');
            $table->foreignId('match_winner')
                ->nullable() // Making the column nullable
                ->constrained('tournament_teams')
                ->cascadeOnDelete()
                ->change();
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
