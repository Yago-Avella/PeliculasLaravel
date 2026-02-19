<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelicula;
use App\Models\Genero;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request){
        // recuperar todos los géneros para el filtro
        $generos = Genero::orderBy('name')->get();

        // construimos la consulta de las películas del usuario
        $query = Auth::user()->peliculas()->with('generos');

        // aplicar filtro si se solicitó un género
        if ($request->filled('genre')) {
            $genreId = $request->input('genre');
            $query->whereHas('generos', function($q) use ($genreId) {
                $q->where('generos.id', $genreId);
            });
        }

        $peliculas = $query->get();

        return view('dashboard', compact('peliculas', 'generos'));
    }

    public function destroy(Pelicula $pelicula)
    {
        Auth::user()->peliculas()->detach($pelicula->id);
        return redirect()->route('dashboard')->with('success', 'Película eliminada de tu catálogo');
    }

    public function toggleWatched(Pelicula $pelicula)
    {
        $user = Auth::user();
        
        // obtener el valor actual de watched desde el pivot
        $watched = $user->peliculas()
            ->find($pelicula->id)
            ->pivot
            ->watched;

        // cambiar el valor de watched
        $user->peliculas()
            ->updateExistingPivot($pelicula->id, ['watched' => !$watched]);

        return redirect()->route('dashboard')->with('success', 'Estado de visualización actualizado');
    }
}
