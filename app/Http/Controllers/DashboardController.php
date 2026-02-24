<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelicula;
use App\Models\Genero;
use App\Models\Valoraciones;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request){
        // recuperar todos los géneros para el filtro
        $generos = Genero::orderBy('name')->get();

        // construimos la consulta de las películas del usuario
        // también traemos las valoraciones relacionadas para evitar N+1
        $query = Auth::user()->peliculas()->with(['generos', 'valoraciones']);

        // aplicar filtro si se solicitó un género
        if ($request->filled('genre')) {
            $genreId = $request->input('genre');
            $query->whereHas('generos', function($q) use ($genreId) {
                $q->where('generos.id', $genreId);
            });
        }

        // aplicar filtro por año si se solicitó
        if ($request->filled('year')) {
            $year = $request->input('year');
            $query->where('anyo', $year);
        }

        // aplicar filtro por duración si se solicitó
        if ($request->filled('duration')) {
            $dur = $request->input('duration');
            $query->where('duracion', $dur);
        }

        // aplicar orden si se solicitó
        if ($request->filled('sort')) {
            $sort = $request->input('sort');
            // mapping safe column names
            $map = [
                'title' => 'titulo',
                'year' => 'anyo',
                'rating' => 'media',
            ];
            if (isset($map[$sort])) {
                $query->orderBy($map[$sort]);
            }
        }

        $peliculas = $query->get();

        return view('dashboard', compact('peliculas', 'generos'));
    }

    public function destroy(Pelicula $pelicula)
    {
        $user = Auth::user();

        // eliminar cualquier valoración personal que el usuario hubiera puesto
        $user->valoraciones()->where('pelicula_id', $pelicula->id)->delete();

        $user->peliculas()->detach($pelicula->id);

        // recalcular media para la película en caso de que existan otras valoraciones visibles
        $avg = Valoraciones::where('pelicula_id', $pelicula->id)
                    ->where('visible', true)
                    ->avg('rating');
        $pelicula->media = $avg ?? 0;
        $pelicula->save();

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