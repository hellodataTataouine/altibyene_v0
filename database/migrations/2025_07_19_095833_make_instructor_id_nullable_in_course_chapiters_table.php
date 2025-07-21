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
        Schema::table('course_chapters', function (Blueprint $table) {
            //
            $table->foreignId('instructor_id')->nullable()->change();

            // Et ajouter la contrainte si tu veux la mettre maintenant
            $table->foreign('instructor_id')
                  ->references('id')->on('users')
                  ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_chapters', function (Blueprint $table) {
            //
            $table->dropForeign(['instructor_id']);

            // Remet la colonne comme NOT NULL
            $table->foreignId('instructor_id')->nullable(false)->change();
        });
    }
};
