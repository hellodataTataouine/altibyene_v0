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
        Schema::table('course_chapter_lessons', function (Blueprint $table) {
            // Supprimer d'abord la contrainte si elle existe
            $table->dropForeign(['instructor_id']);
        });

        Schema::table('course_chapter_lessons', function (Blueprint $table) {
            // Rendre la colonne nullable
            $table->foreignId('instructor_id')->nullable()->change();

            // Recréer la contrainte
            $table->foreign('instructor_id')
                  ->references('id')->on('users')
                  ->nullOnDelete(); // ou ->onDelete('set null')
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_chapter_lessons', function (Blueprint $table) {
            //
            $table->dropForeign(['instructor_id']);

            // Remettre la colonne en NOT NULL
            $table->foreignId('instructor_id')->nullable(false)->change();
        });
    }
};
