<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Pelicula;

class Genero extends Model
{
    // allow mass assignment on name field
    protected $fillable = ['name'];

    /**
     * Películas asociadas a este género (muchos a muchos)
     */
    public function peliculas()
    {
        return $this->belongsToMany(Pelicula::class, 'genero_pelicula');
    }
}
