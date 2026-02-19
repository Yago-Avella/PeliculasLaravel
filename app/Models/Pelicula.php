<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Genero;

class Pelicula extends Model
{
    protected $fillable = [
        'tmdb_id',
        'titulo',
        'anyo',
        'duracion',
        'sinopsis',
        'poster',
        'media',
    ];

    /**
     * Géneros asociados a la película (muchos a muchos)
     */
    public function generos()
    {
        return $this->belongsToMany(Genero::class, 'genero_pelicula');
    }
}
