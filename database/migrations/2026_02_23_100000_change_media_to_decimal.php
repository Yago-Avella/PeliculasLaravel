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
        // alter "media" column from integer to decimal with two places
        Schema::table('peliculas', function (Blueprint $table) {
            // use change() method to modify existing column
            $table->decimal('media', 5, 2)->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peliculas', function (Blueprint $table) {
            $table->integer('media')->default(0)->change();
        });
    }
};
