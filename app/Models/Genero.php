<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Pelicula;

class Genero extends Model
{
    // lo unico q se puede asignar es el name
    protected $fillable = ['name'];

    // Películas asociadas a este género (muchos a muchos)
    public function peliculas(){
        return $this->belongsToMany(Pelicula::class, 'genero_pelicula');
    }
}
