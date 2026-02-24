<?php

namespace App\Http\Controllers;

use App\Models\Pelicula;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AdminController extends Controller
{
    /**
     * Display the admin control panel with all movies
     */
    public function panel()
    {
        $peliculas = Pelicula::with('generos', 'valoraciones')
            ->orderBy('titulo')
            ->get();

        return view('admin.panel-control', compact('peliculas'));
    }

    /**
     * Delete a movie from the database
     */
    public function destroyMovie(Pelicula $pelicula)
    {
        // eliminar relaciones many-to-many con géneros
        $pelicula->generos()->detach();

        // eliminar todas las valoraciones asociadas
        $pelicula->valoraciones()->delete();

        // eliminar todas las relaciones usuario-película
        $pelicula->users()->detach();

        // eliminar la película
        $pelicula->delete();

        return redirect()->route('panel.control')->with('success', 'Película eliminada correctamente de la base de datos');
    }
}
