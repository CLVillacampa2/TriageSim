<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
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
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('triage_sessions');
    }
};
