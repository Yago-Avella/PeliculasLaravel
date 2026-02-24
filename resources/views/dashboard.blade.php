<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('TUS PELICULAS') }}
        </h2>
    </x-slot>

    <div class="p-6 space-y-4">

        {{-- filtros (género, año, duración) --}}
        <div class="mb-6">
            <form method="GET" action="{{ route('dashboard') }}" class="flex items-center space-x-4">
                <div class="flex items-center space-x-2">
                    <label for="genre" class="font-semibold">Filtrar por género:</label>
                    <select name="genre" id="genre" onchange="this.form.submit()" class="border rounded p-1">
                        <option value="">Todos</option>
                        @foreach($generos as $genero)
                            <option value="{{ $genero->id }}" {{ request('genre') == $genero->id ? 'selected' : '' }}>{{ $genero->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center space-x-2">
                    <label for="year" class="font-semibold">Filtrar por año:</label>
                    <input type="number" name="year" id="year" min="1888" placeholder="Ej. 2023"
                        value="{{ request('year') }}" class="border rounded p-1 w-20" onchange="this.form.submit()">
                </div>

                <div class="flex items-center space-x-2">
                    <label for="duration" class="font-semibold">Filtrar por duración:</label>
                    <input type="number" name="duration" id="duration" min="1" placeholder="min"
                        value="{{ request('duration') }}" class="border rounded p-1 w-20" onchange="this.form.submit()">
                </div>

                <div class="flex items-center space-x-2">
                    <label for="sort" class="font-semibold">Ordenar por:</label>
                    <select name="sort" id="sort" onchange="this.form.submit()" class="border rounded p-1">
                        <option value="">Ninguno</option>
                        <option value="title" {{ request('sort')=='title' ? 'selected' : '' }}>Título</option>
                        <option value="year" {{ request('sort')=='year' ? 'selected' : '' }}>Año</option>
                        <option value="rating" {{ request('sort')=='rating' ? 'selected' : '' }}>Valoración</option>
                    </select>
                </div>

                @if(request()->filled('genre') || request()->filled('year') || request()->filled('duration') || request()->filled('sort'))
                    <a href="{{ route('dashboard') }}" class="text-sm text-blue-600 hover:underline">limpiar filtros</a>
                @endif
            </form>
        </div>

        {{-- Películas NO VISTAS --}}
        <h3 class="text-2xl font-bold text-gray-800 mt-8">No Vistas</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse ($peliculas->where('pivot.watched', false) as $pelicula)
                <div class="p-4 bg-white shadow rounded">
                    <h4 class="font-bold text-lg">{{ $pelicula->titulo }}</h4>
                    <p><strong>Año:</strong> {{ $pelicula->anyo }}</p>
                    <p><strong>Duración:</strong> {{ $pelicula->duracion }} min</p>
                    <p><strong>Media:</strong> {{ $pelicula->media !== null ? number_format($pelicula->media, 2) : 'Sin valoraciones' }}</p>
                    <p class="mt-2">{{ $pelicula->sinopsis }}</p>

                    {{-- mostrar géneros asociados --}}
                    @if($pelicula->generos->isNotEmpty())
                        <p class="mt-2"><strong>Géneros:</strong> {{ $pelicula->generos->pluck('name')->join(', ') }}</p>
                    @endif

                    @if ($pelicula->poster)
                        <img src="{{ $pelicula->poster }}" alt="{{ $pelicula->titulo }}" class="mt-2 w-full h-auto rounded">
                    @endif

                    @php
                        $userRating = $pelicula->valoraciones->where('user_id', auth()->id())->first();
                    @endphp
                    <div class="flex gap-2 mt-2">
                        @if($userRating)
                            <span class="text-sm font-medium">Tu valoración: {{ $userRating->rating }} / 10</span>
                        @endif
                    </div>
                    <div class="flex gap-2 mt-2">
                        <a href="{{ route('valoraciones.create', $pelicula) }}" class="flex-1 px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 w-full text-center">
                            {{ $userRating ? 'Editar valoración' : 'Valorar película' }}
                        </a>
                    </div>
                    <div class="flex gap-2 mt-2">
                        <a href="{{ route('collections.add-movie-modal', $pelicula) }}" class="flex-1 px-3 py-1 bg-purple-500 text-white rounded hover:bg-purple-600 w-full text-center">
                            Agregar a colección
                        </a>
                    </div>
                    <div class="flex gap-2 mt-2">
                        <form action="{{ route('dashboard.peliculas.toggleWatched', $pelicula) }}" method="POST" class="flex-1">
                            @csrf
                            <button type="submit" class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600 w-full">
                                Marcar como vista
                            </button>
                        </form>
                        <form action="{{ route('dashboard.peliculas.destroy', $pelicula) }}" method="POST" class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                            onclick="return confirm('¿Estás seguro de que quieres eliminar esta película?')"
                            class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 w-full">
                                Eliminar
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="col-span-full text-gray-500">No hay películas sin ver.</p>
            @endforelse
        </div>

        <hr class="my-8 border-t-2 border-gray-300">

        {{-- Películas VISTAS --}}
        <h3 class="text-2xl font-bold text-gray-800 mt-8">Vistas</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse ($peliculas->where('pivot.watched', true) as $pelicula)
                <div class="p-4 bg-white shadow rounded opacity-75">
                    <h4 class="font-bold text-lg">{{ $pelicula->titulo }}</h4>
                    <p><strong>Año:</strong> {{ $pelicula->anyo }}</p>
                    <p><strong>Duración:</strong> {{ $pelicula->duracion }} min</p>
                    <p><strong>Media:</strong> {{ $pelicula->media !== null ? number_format($pelicula->media, 2) : 'Sin valoraciones' }}</p>
                    <p class="mt-2">{{ $pelicula->sinopsis }}</p>

                    {{-- mostrar géneros asociados --}}
                    @if($pelicula->generos->isNotEmpty())
                        <p class="mt-2"><strong>Géneros:</strong> {{ $pelicula->generos->pluck('name')->join(', ') }}</p>
                    @endif

                    @if ($pelicula->poster)
                        <img src="{{ $pelicula->poster }}" alt="{{ $pelicula->titulo }}" class="mt-2 w-full h-auto rounded">
                    @endif

                    @php
                        $userRating = $pelicula->valoraciones->where('user_id', auth()->id())->first();
                    @endphp
                    <div class="flex gap-2 mt-2">
                        @if($userRating)
                            <span class="text-sm font-medium">Tu valoración: {{ $userRating->rating }} / 10</span>
                        @endif
                    </div>
                    <div class="flex gap-2 mt-2">
                        <a href="{{ route('valoraciones.create', $pelicula) }}" class="flex-1 px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 w-full text-center">
                            {{ $userRating ? 'Editar valoración' : 'Valorar película' }}
                        </a>
                    </div>
                    <div class="flex gap-2 mt-2">
                        <a href="{{ route('collections.add-movie-modal', $pelicula) }}" class="flex-1 px-3 py-1 bg-purple-500 text-white rounded hover:bg-purple-600 w-full text-center">
                            Agregar a colección
                        </a>
                    </div>
                    <div class="flex gap-2 mt-2">
                        <form action="{{ route('dashboard.peliculas.toggleWatched', $pelicula) }}" method="POST" class="flex-1">
                            @csrf
                            <button type="submit" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 w-full">
                                Marcar como no vista
                            </button>
                        </form>
                        <form action="{{ route('dashboard.peliculas.destroy', $pelicula) }}" method="POST" class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                            onclick="return confirm('¿Estás seguro de que quieres eliminar esta película?')"
                            class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 w-full">
                                Eliminar
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="col-span-full text-gray-500">No hay películas vistas.</p>
            @endforelse
        </div>

    </div>
</x-app-layout>
