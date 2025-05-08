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
        Schema::table('users', function (Blueprint $table) {
            //
            $table->string('last_name')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('birth_country')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('phone_fix')->nullable();
            $table->boolean('honor_statement')->nullable();
            $table->boolean('rules_acknowledgment')->nullable();
            $table->boolean('data_processing_acknowledgment')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->dropColumn(('last_name'));
            $table->dropColumn('birth_date');
            $table->dropColumn('birth_country');
            $table->dropColumn('zip_code');
            $table->dropColumn('phone_fix');
            $table->dropColumn('honor_statement');
            $table->dropColumn('rules_acknowledgment');
            $table->dropColumn('data_processing_acknowledgment');
        });
    }
};
