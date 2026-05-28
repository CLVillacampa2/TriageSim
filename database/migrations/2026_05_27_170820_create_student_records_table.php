<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_records', function (Blueprint $table) {
            $table->id();
            $table->string('student_id');
            $table->string('student_name');
            $table->string('cohort');
            $table->string('scenario');
            $table->decimal('latency', 8, 2); // Tracks decision latency (e.g., 12.45s)
            $table->integer('efficiency');    // Percentage integer (e.g., 85)
            $table->integer('accuracy');      // Percentage integer (e.g., 100)
            $table->timestamps();             // Automatically tracks created_at / updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_records');
    }
};