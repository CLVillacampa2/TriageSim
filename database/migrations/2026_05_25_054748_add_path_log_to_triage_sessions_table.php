<?php



use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('triage_sessions', function (Blueprint $table) {
            // Adds a JSON column to store the history of answers
            $table->json('path_log')->nullable()->after('accuracy');
        });
    }

    public function down()
    {
        Schema::table('triage_sessions', function (Blueprint $table) {
            $table->dropColumn('path_log');
        });
    }
};