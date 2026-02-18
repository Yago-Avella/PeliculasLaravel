<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelicula extends Model{
    
    protected $table = 'peliculas'; // Nombre exacto de la tabla
    protected $fillable = [
        'titulo',
        'anyo',
        'duracion',
        'sinopsis',
        'poster',
        'media',
        'tmdb_id',
    ];
}
