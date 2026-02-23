<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Valoraciones;
use App\Models\Pelicula;
use Illuminate\Support\Facades\Auth;

class ValoracionesController extends Controller
{
    /**
     * Show all valoraciones.  Normal users see only visible ones; admins see everything.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            $valoraciones = Valoraciones::with(['pelicula', 'user'])->get();
        } else {
            $valoraciones = Valoraciones::with(['pelicula', 'user'])
                ->where('visible', true)
                ->get();
        }

        return view('valoraciones.index', compact('valoraciones'));
    }

    /**
     * Show form to create a new valoración for a given movie.
     */
    public function create(Pelicula $pelicula)
    {
        $user = Auth::user();

        // ensure the movie belongs to the user list
        if (! $user->peliculas()->where('pelicula_id', $pelicula->id)->exists()) {
            abort(403);
        }

        return view('valoraciones.create', compact('pelicula'));
    }

    /**
     * Store a rating/review submitted by user.
     */
    public function store(Request $request, Pelicula $pelicula)
    {
        $user = Auth::user();

        // check ownership again
        if (! $user->peliculas()->where('pelicula_id', $pelicula->id)->exists()) {
            abort(403);
        }

        $data = $request->validate([
            'rating' => 'required|integer|min:1|max:10',
            'review' => 'nullable|string|max:2000',
        ]);

        // try update existing or create new
        $valoracion = Valoraciones::updateOrCreate(
            [
                'user_id' => $user->id,
                'pelicula_id' => $pelicula->id,
            ],
            [
                'rating' => $data['rating'],
                'review' => $data['review'] ?? null,
            ]
        );

        // recalculate average on pelicula (fall back to 0 if no visible ratings)
        $avg = Valoraciones::where('pelicula_id', $pelicula->id)
                ->where('visible', true)
                ->avg('rating');
        $pelicula->media = $avg ?? 0;
        $pelicula->save();

        return redirect()->route('dashboard')->with('success', 'Tu valoración se ha guardado.');
    }

    /**
     * Toggle visibility (only for admins).
     */
    public function toggleVisibility(Valoraciones $valoracion)
    {
        // controller already behind can:admin-only middleware but double check
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $valoracion->visible = ! $valoracion->visible;
        $valoracion->save();

        // actualizar media de la película afectada
        $pelicula = $valoracion->pelicula;
        if ($pelicula) {
            $avg = Valoraciones::where('pelicula_id', $pelicula->id)
                    ->where('visible', true)
                    ->avg('rating');
            $pelicula->media = $avg ?? 0;
            $pelicula->save();
        }

        return back();
    }
}
