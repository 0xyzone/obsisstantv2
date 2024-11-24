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
            $table->foreignId('tournament_admin_id')->nullable()->constrained()->cascadeOnDelete()->after('team_b_mp');
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
