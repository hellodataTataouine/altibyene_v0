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
        Schema::create('cours_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->integer('max_enrollments');
            $table->integer('enrolled_students');
            $table->unsignedBigInteger('cours_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cours_sessions');
    }
};
