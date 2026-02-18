<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelicula;

class DashboardController extends Controller
{
    public function index(){
        // Trae todas las películas de la base de datos
        $peliculas = Pelicula::all();

        return view('dashboard', compact('peliculas'));
    }
}
