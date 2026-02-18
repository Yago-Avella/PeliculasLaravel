<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelicula;

class DashboardController extends Controller
{
    public function index(){
        // Trae todas las películas de la base de datos
        $movies = Movie::all();

        return view('dashboard', compact('movies'));
    }
}
