<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $collection->name }}
            </h2>
            @if($isOwner)
                <form method="POST" action="{{ route('collections.destroy', $collection) }}" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm font-semibold"
                        onclick="return confirm('¿Estás seguro? Esta acción no se puede deshacer.')">
                        Eliminar colección
                    </button>
                </form>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Información de la colección --}}
            <div class="bg-white shadow-md rounded-lg p-6 mb-8">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="text-lg text-gray-600 mb-2">
                            @if($isOwner)
                                Colección personal
                            @else
                                Colección de <strong>{{ $collection->user->name }}</strong>
                            @endif
                        </h3>
                        
                        @if($collection->description)
                            <p class="text-gray-700">{{ $collection->description }}</p>
                        @endif
                    </div>

                    @if($isOwner)
                        <div class="text-right">
                            @if($collection->is_public)
                                <span class="inline-block bg-green-100 text-green-800 px-3 py-1 rounded text-sm mb-2">Pública</span>
                            @else
                                <span class="inline-block bg-gray-100 text-gray-800 px-3 py-1 rounded text-sm mb-2">Privada</span>
                            @endif

                            <form method="POST" action="{{ route('collections.update-visibility', $collection) }}" style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="is_public" value="{{ $collection->is_public ? '0' : '1' }}">
                                <button type="submit" class="block text-blue-600 hover:text-blue-800 text-sm">
                                    Cambiar a {{ $collection->is_public ? 'privada' : 'pública' }}
                                </button>
                            </form>
                        </div>
                    @endif
                </div>

                <p class="text-sm text-gray-600">
                    {{ $peliculas->count() }} 
                    {{ $peliculas->count() === 1 ? 'película' : 'películas' }}
                </p>
            </div>

            {{-- Películas --}}
            @if($peliculas->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($peliculas as $pelicula)
                        <div class="bg-white shadow-md rounded-lg overflow-hidden hover:shadow-lg transition">
                            {{-- Poster --}}
                            <div class="relative h-64 bg-gray-200 overflow-hidden">
                                @if($pelicula->poster)
                                    <img src="{{ $pelicula->poster }}" alt="{{ $pelicula->titulo }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                        Sin poster
                                    </div>
                                @endif
                                
                                {{-- Badge de rating --}}
                                @if($pelicula->media > 0)
                                    <div class="absolute top-2 right-2 bg-yellow-500 text-white px-2 py-1 rounded-full text-sm font-bold">
                                        {{ number_format($pelicula->media, 1) }}
                                    </div>
                                @endif
                            </div>

                            {{-- Info --}}
                            <div class="p-4">
                                <h3 class="font-bold text-gray-900 line-clamp-2 mb-2">{{ $pelicula->titulo }}</h3>
                                
                                <p class="text-xs text-gray-600 mb-2">{{ $pelicula->anyo }} • {{ $pelicula->duracion }} min</p>

                                @if($pelicula->generos->count() > 0)
                                    <div class="mb-3 flex flex-wrap gap-1">
                                        @foreach($pelicula->generos as $genero)
                                            <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">
                                                {{ $genero->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                @if($isOwner)
                                    <form method="POST" action="{{ route('collections.remove-movie', [$collection, $pelicula]) }}" style="display: block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full bg-red-100 hover:bg-red-200 text-red-800 px-3 py-2 rounded text-sm font-semibold transition"
                                            onclick="return confirm('¿Eliminar esta película de la colección?')">
                                            Eliminar
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white shadow-md rounded-lg p-6 text-center">
                    <p class="text-gray-600 text-lg">Esta colección está vacía</p>
                    @if($isOwner)
                        <p class="text-gray-500 text-sm mt-2">Ve al dashboard y agrupa tus películas favoritas</p>
                    @endif
                </div>
            @endif

            <div class="mt-8">
                <a href="{{ route('collections.index') }}" class="text-blue-600 hover:text-blue-800">← Volver a colecciones</a>
            </div>

        </div>
    </div>
</x-app-layout>
