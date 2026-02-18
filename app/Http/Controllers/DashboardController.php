<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelicula;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(){
        // Trae solo las películas asociadas al usuario logueado
        $peliculas = Auth::user()->peliculas()->get();

        return view('dashboard', compact('peliculas'));
    }

    public function destroy(Pelicula $pelicula)
    {
        Auth::user()->peliculas()->detach($pelicula->id);
        return redirect()->route('dashboard')->with('success', 'Película eliminada de tu catálogo');
    }
}
