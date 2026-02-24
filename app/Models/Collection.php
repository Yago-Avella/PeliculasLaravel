<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Collection extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'is_public',
    ];

    //pasa is_public a boolean (se me va a olvidar)

    protected $casts = [
        'is_public' => 'boolean',
    ];

    public function user(): BelongsTo{
        return $this->belongsTo(User::class);
    }

    public function peliculas(): BelongsToMany{
        return $this->belongsToMany(Pelicula::class, 'collection_pelicula');
    }
}
