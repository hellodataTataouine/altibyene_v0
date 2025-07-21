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
            // Supprime la contrainte existante
            $table->dropForeign(['instructor_id']);
        });

        // Pour pouvoir utiliser `change()`, il faut que le package doctrine/dbal soit installé
        Schema::table('course_chapter_items', function (Blueprint $table) {
            // Rendre la colonne nullable
            $table->unsignedBigInteger('instructor_id')->nullable()->change();
        });

        Schema::table('course_chapter_items', function (Blueprint $table) {
            // Recréer la contrainte de clé étrangère
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
            $table->dropForeign(['instructor_id']);
        });

        Schema::table('course_chapter_items', function (Blueprint $table) {
            $table->unsignedBigInteger('instructor_id')->nullable(false)->change();
        });

        Schema::table('course_chapter_items', function (Blueprint $table) {
            $table->foreign('instructor_id')
                ->references('id')->on('users')
                ->onDelete('cascade'); // selon le comportement initial
        });
    }
};
