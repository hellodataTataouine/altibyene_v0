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
        Schema::table('book_requests', function (Blueprint $table) {
            //
            $table->string('school')->nullable()->after('name');
            $table->text('address')->nullable()->after('school');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('book_requests', function (Blueprint $table) {
            //
            $table->dropColumn(['school','address']);
        });
    }
};
