<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\PeliculasController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TMDBController;
use App\Http\Controllers\ValoracionesController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/import-tmdb', [TMDBController::class, 'importPopular'])->name('import.tmdb');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::delete('/dashboard/peliculas/{pelicula}', [DashboardController::class, 'destroy'])->name('dashboard.peliculas.destroy');
    Route::post('/dashboard/peliculas/{pelicula}/toggle-watched', [DashboardController::class, 'toggleWatched'])->name('dashboard.peliculas.toggleWatched');

    // valoraciones: creación y listado
    Route::get('/dashboard/peliculas/{pelicula}/valoracion', [ValoracionesController::class, 'create'])->name('valoraciones.create');
    Route::post('/dashboard/peliculas/{pelicula}/valoracion', [ValoracionesController::class, 'store'])->name('valoraciones.store');

    // vista pública de valoraciones
    Route::get('/valoraciones', [ValoracionesController::class, 'index'])->name('valoraciones.index');
    // admin toggle visibilidad
    Route::patch('/valoraciones/{valoracion}/toggle', [ValoracionesController::class, 'toggleVisibility'])
        ->name('valoraciones.toggle')
        ->middleware('can:admin-only');

    Route::get('/tmdb', [TMDBController::class, 'index'])->name('tmdb.index');
    Route::get('/tmdb/search', [TMDBController::class, 'search'])->name('tmdb.search');
    Route::post('/tmdb/store/{tmdb_id}', [TMDBController::class, 'store'])->name('tmdb.store');
});

Route::get('/panel-control', function () {

    if (Gate::denies('admin-only')) {
        abort(403);
    }

    return view('admin.panel-control');

})->name('panel.control');

Route::get('/peliculas', [PeliculasController::class, 'index'])->name('movies.index');

require __DIR__.'/auth.php';
