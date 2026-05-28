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
        Schema::create('triage_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('student_id');
            $table->string('student_name');
            $table->string('cohort');
            $table->string('scenario');
            $table->decimal('latency', 8, 2);
            $table->integer('efficiency');
            $table->integer('accuracy');
            
            // Added to store the full interactive survey datasets permanently:
            $table->text('path_log')->nullable();          // Holds JSON string of student decisions
            $table->text('sus_responses')->nullable();     // Holds JSON string of the 1-5 answers
            $table->decimal('sus_score', 5, 2)->default(0); // Holds the final score out of 100
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('triage_sessions');
    }
};