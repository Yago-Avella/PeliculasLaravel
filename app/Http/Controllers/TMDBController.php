<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Pelicula;
use Illuminate\Support\Facades\Auth;



class TMDBController extends Controller
{
    public function index(){
        return view('tmdb.search');
    }

    public function search(Request $request)
    {
        $query = $request->input('titulo');

        if (!$query) {
            return redirect()->back()->with('error', 'Debes ingresar un título');
        }

        $response = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/search/movie', [
                'query' => $query,
                'language' => 'es-ES'
            ]);

        $peliculas = $response->json()['results'] ?? [];

        return view('tmdb.search', compact('peliculas', 'query'));
    }

    public function store($tmdb_id)
    {
        $response = Http::withToken(config('services.tmdb.token'))
            ->get("https://api.themoviedb.org/3/movie/$tmdb_id", [
                'language' => 'es-ES'
            ]);

        $data = $response->json();

        if (!$data || !isset($data['id'])) {
            return redirect()->back()->with('error', 'No se pudo obtener la información de la película');
        }

        // Evitar duplicados por tmdb_id
        $pelicula = Pelicula::firstOrCreate(
            ['tmdb_id' => $tmdb_id],
            [
                'titulo' => $data['title'] ?? 'Sin título',
                'anyo' => substr($data['release_date'] ?? '', 0, 4),
                'duracion' => $data['runtime'] ?? 0,
                'sinopsis' => $data['overview'] ?? '',
                'poster' => isset($data['poster_path']) ? 'https://image.tmdb.org/t/p/w500' . $data['poster_path'] : null,
                'media' => $data['vote_average'] ?? 0,
            ]
        );

        // Relacionar con el usuario logueado (tabla pivot)
        Auth::user()->peliculas()->syncWithoutDetaching([$pelicula->id]);

        return redirect()->back()->with('success', 'Película añadida a tu catálogo');
    }
}
