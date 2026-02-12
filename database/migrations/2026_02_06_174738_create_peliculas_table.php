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
        Schema::create('peliculas', function (Blueprint $table) {
            /*
            Título, año, duración, sinopsis y póster. 
o Géneros asociados. 
o Media de valoraciones. 
o Reseñas de usuarios (con control de moderación).
            */
            $table->id(); // id auto-incremental
            $table->string('titulo');
            $table->integer('anyo');
            $table->integer('duracion');
            $table->text('sinopsis');
            $table->string('poster');
            $table->integer('media');
            $table->timestamps();
            //no se si hará falta algo mas
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peliculas');
    }
};
