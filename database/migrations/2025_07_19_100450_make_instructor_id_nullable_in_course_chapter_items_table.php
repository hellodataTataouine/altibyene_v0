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
        Schema::table('course_chapter_items', function (Blueprint $table) {
            // Supprime la contrainte existante si elle existe
            $table->dropForeign(['instructor_id']);
        });

        Schema::table('course_chapter_items', function (Blueprint $table) {
            // Modifie la colonne pour la rendre nullable
            $table->foreignId('instructor_id')->nullable()->change();

            // Recrée la contrainte de clé étrangère
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
        Schema::table('course_chapter_items', function (Blueprint $table) {
            //
            $table->dropForeign(['instructor_id']);

            // Remet la colonne comme NOT NULL
            $table->foreignId('instructor_id')->nullable(false)->change();
        });
    }
};
