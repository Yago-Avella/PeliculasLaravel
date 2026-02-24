<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Pelicula;


// deberia ser singular pero no me pegues kiko porfa
class Valoraciones extends Model{
    protected $fillable = [
        'user_id',
        'pelicula_id',
        'rating',
        'review',
        'visible',
    ];

    // Relaciones----------------------------
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function pelicula(){
        return $this->belongsTo(Pelicula::class);
    }
}
