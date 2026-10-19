<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_profesor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->foreignId('profesor_id')->constrained('profesors')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['course_id', 'profesor_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_profesor');
    }
};
