<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\Pelicula;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class CollectionController extends Controller
{
    /**
     * Display all collections (user's and public ones)
     */
    public function index()
    {
        $user = Auth::user();
        
        $userCollections = $user->collections()->with('peliculas')->orderBy('created_at', 'desc')->get();
        $publicCollections = Collection::where('is_public', true)
            ->where('user_id', '!=', $user->id)
            ->with('user', 'peliculas')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('collections.index', compact('userCollections', 'publicCollections'));
    }

    /**
     * Show a specific collection
     */
    public function show(Collection $collection)
    {
        // Verificar que solo el propietario o público puedan verlo
        if ($collection->user_id !== Auth::id() && !$collection->is_public) {
            abort(403);
        }

        $peliculas = $collection->peliculas()->with('generos', 'valoraciones')->get();
        $isOwner = $collection->user_id === Auth::id();

        return view('collections.show', compact('collection', 'peliculas', 'isOwner'));
    }

    /**
     * Show intermediate modal to add movie to collection
     */
    public function addMovieModal(Pelicula $pelicula)
    {
        $user = Auth::user();
        $collections = $user->collections()->get();

        return view('collections.add-movie-modal', compact('pelicula', 'collections'));
    }

    /**
     * Store movie in a collection or create new collection
     */
    public function storeMovie(Request $request, Pelicula $pelicula)
    {
        $user = Auth::user();

        // Check if adding to existing collection or creating new one
        if ($request->input('collection_id')) {
            // Add to existing collection
            $collection = Collection::findOrFail($request->input('collection_id'));

            // Verify ownership
            if ($collection->user_id !== $user->id) {
                abort(403);
            }

            // Check if movie already exists in collection
            if ($collection->peliculas()->where('pelicula_id', $pelicula->id)->exists()) {
                return Redirect::back()->with('warning', 'Esta película ya está en la colección');
            }

            $collection->peliculas()->attach($pelicula->id);

            return Redirect::back()->with('success', 'Película añadida a la colección correctamente');
        } else {
            // Create new collection and add movie
            $validated = $request->validate([
                'new_collection_name' => ['required', 'string', 'max:255'],
                'new_collection_description' => ['nullable', 'string', 'max:1000'],
                'new_collection_is_public' => ['nullable', 'boolean'],
            ]);

            $collection = $user->collections()->create([
                'name' => $validated['new_collection_name'],
                'description' => $validated['new_collection_description'] ?? null,
                'is_public' => $validated['new_collection_is_public'] ?? false,
            ]);

            $collection->peliculas()->attach($pelicula->id);

            return Redirect::back()->with('success', 'Colección creada y película añadida correctamente');
        }
    }

    /**
     * Remove movie from collection
     */
    public function removeMovie(Collection $collection, Pelicula $pelicula)
    {
        // Verify ownership
        if ($collection->user_id !== Auth::id()) {
            abort(403);
        }

        $collection->peliculas()->detach($pelicula->id);

        return Redirect::back()->with('success', 'Película eliminada de la colección');
    }

    /**
     * Delete a collection
     */
    public function destroy(Collection $collection)
    {
        // Verify ownership
        if ($collection->user_id !== Auth::id()) {
            abort(403);
        }

        $collection->delete();

        return Redirect::route('collections.index')->with('success', 'Colección eliminada correctamente');
    }

    /**
     * Update collection visibility
     */
    public function updateVisibility(Collection $collection, Request $request)
    {
        // Verify ownership
        if ($collection->user_id !== Auth::id()) {
            abort(403);
        }

        $collection->update([
            'is_public' => $request->input('is_public', false),
        ]);

        return Redirect::back()->with('success', 'Visibilidad de la colección actualizada');
    }
}
