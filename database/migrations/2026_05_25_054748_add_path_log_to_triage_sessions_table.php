<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('triage_sessions', function (Blueprint $table) {
            // Stores the raw answers array (q1 to q10) for descriptive analysis in SPSS/Excel
            $table->json('sus_responses')->nullable()->after('path_log');
            // Stores the final calculated psychometric SUS multiplier score (0-100)
            $table->float('sus_score', 5, 2)->nullable()->after('sus_responses');
        });
    }

    public function down(): void
    {
        Schema::table('triage_sessions', function (Blueprint $table) {
            $table->dropColumn(['sus_responses', 'sus_score']);
        });
    }
};