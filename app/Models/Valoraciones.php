<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Pelicula;

class Valoraciones extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'pelicula_id',
        'rating',
        'review',
        'visible',
    ];

    /**
     * Relaciones
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pelicula()
    {
        return $this->belongsTo(Pelicula::class);
    }
}
