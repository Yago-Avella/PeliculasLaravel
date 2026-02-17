<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PeliculasController extends Controller
{
    public function index(){
        $response = Http::withToken(config('tmdb.token'))
            ->get(config('tmdb.base_url').'movie/popular');

        $movies = $response->json()['results'];

        return view('movies.index', compact('movies'));
    }
}
