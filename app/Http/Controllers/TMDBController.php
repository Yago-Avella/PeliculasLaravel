<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TMDBController extends Controller
{
    public function importPopular(){
        $url = config('tmdb.base_url') . 'movie/popular';

        // Petición GET con Bearer token
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('tmdb.token'),
            'Accept' => 'application/json',
        ])->get($url);

        if (!$response->successful()) {
            return back()->with('error', 'No se pudo conectar con TMDB. Código: ' . $response->status());
        }

        $movies = $response->json()['results'] ?? [];

        dd($movies);

        foreach ($movies as $m) {
            Movie::updateOrCreate(
                ['tmdb_id' => $m['id']],
                [
                    'title' => $m['title'] ?? 'Sin título',
                    'year' => isset($m['release_date']) ? substr($m['release_date'], 0, 4) : null,
                    'overview' => $m['overview'] ?? null,
                    'poster_path' => $m['poster_path'] ?? null,
                    'runtime' => null,
                ]
            );
        }

        return back()->with('success', count($movies) . ' películas importadas correctamente');
    }
}
