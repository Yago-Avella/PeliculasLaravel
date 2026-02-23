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
     * Casts for attributes that need specific types.
     *
     * We cast media to float so decimal ratings behave consistently
     * and formatting in views can rely on numeric values.
     */
    protected $casts = [
        'media' => 'float',
    ];

    /**
     * Géneros asociados a la película (muchos a muchos)
     */
    public function generos()
    {
        return $this->belongsToMany(Genero::class, 'genero_pelicula');
    }

    /**
     * Todas las valoraciones de esta película
     */
    public function valoraciones()
    {
        return $this->hasMany(Valoraciones::class);
    }
}
