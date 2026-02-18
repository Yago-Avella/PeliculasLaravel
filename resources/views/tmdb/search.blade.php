<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Importar películas desde TMDB</h2>
    </x-slot>

    <div class="p-6">

        {{-- Formulario búsqueda --}}
        <form method="GET" action="{{ route('tmdb.search') }}">
            <input type="text" name="titulo" placeholder="Buscar película..." class="border p-2 rounded w-full">
            <button class="mt-2 bg-blue-500 text-white px-4 py-2 rounded">
                Buscar
            </button>
        </form>

        {{-- Resultados --}}
        dd($peliculas);
        @isset($peliculas)
            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($peliculas as $pelicula)
                    <div class="bg-white p-4 shadow rounded">
                        <h3 class="font-bold">{{ $pelicula['title'] }}</h3>
                        <p>Año: {{ substr($pelicula['release_date'] ?? '', 0, 4) }}</p>

                        @if($pelicula['poster_path'])
                            <img src="https://image.tmdb.org/t/p/w200{{ $pelicula['poster_path'] }}"
                                 class="mt-2 rounded">
                        @endif

                        <form method="POST" action="{{ route('tmdb.store', $pelicula['id']) }}">
                            @csrf
                            <button class="mt-3 bg-green-500 text-white px-3 py-1 rounded">
                                Añadir a mi catálogo
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        @endisset

    </div>
</x-app-layout>
