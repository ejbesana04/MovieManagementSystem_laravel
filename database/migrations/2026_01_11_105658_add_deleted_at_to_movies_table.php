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
        // Changed from 'students' to 'movies'
        Schema::table('movies', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Changed from 'students' to 'movies'
        Schema::table('movies', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};