<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelicula extends Model
{
    // Lista de atributos que se pueden asignar en masa
    protected $fillable = [
        'tmdb_id',
        'titulo',
        'anyo',
        'duracion',
        'sinopsis',
        'poster',
        'media',
    ];

    // si es necesario, puedes definir relaciones aquí, por ejemplo:
    // public function users()
    // {
    //     return $this->belongsToMany(User::class);
    // }
}
