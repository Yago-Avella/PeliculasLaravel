<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Agregar película a colección
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Mensaje de éxito o error --}}
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('warning'))
                <div class="mb-4 p-4 bg-yellow-100 border border-yellow-400 text-yellow-700 rounded">
                    {{ session('warning') }}
                </div>
            @endif

            <div class="bg-white shadow-md rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Película seleccionada</h3>
                
                <div class="flex gap-6">
                    @if($pelicula->poster)
                        <img src="{{ $pelicula->poster }}" alt="{{ $pelicula->titulo }}" class="h-48 w-auto rounded-lg shadow">
                    @else
                        <div class="h-48 w-32 bg-gray-200 rounded-lg flex items-center justify-center">
                            <span class="text-gray-500">Sin poster</span>
                        </div>
                    @endif

                    <div class="flex-1">
                        <h4 class="text-xl font-bold text-gray-900">{{ $pelicula->titulo }}</h4>
                        <p class="text-sm text-gray-600 mt-1">Año: {{ $pelicula->anyo }}</p>
                        <p class="text-sm text-gray-600">Duración: {{ $pelicula->duracion }} min</p>
                        
                        @if($pelicula->media > 0)
                            <p class="text-sm text-gray-600 mt-2">
                                Valoración: <span class="font-semibold text-yellow-600">{{ number_format($pelicula->media, 1) }}/10</span>
                            </p>
                        @endif
                        @if($pelicula->generos->count() > 0)
                            <div class="mt-3">
                                @foreach($pelicula->generos as $genero)
                                    <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs mr-1">
                                        {{ $genero->name }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Opción 1: Agregar a colección existente --}}
            @if($collections->count() > 0)
                <div class="bg-white shadow-md rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Mis colecciones existentes</h3>
                    
                    <ul class="space-y-2">
                        @foreach($collections as $collection)
                            <li>
                                <form method="POST" action="{{ route('collections.store-movie', $pelicula) }}">
                                    @csrf
                                    <input type="hidden" name="collection_id" value="{{ $collection->id }}">
                                    <button type="submit" class="w-full text-left p-3 border border-gray-300 rounded-lg hover:bg-blue-50 hover:border-blue-500 transition">
                                        <div class="font-semibold text-gray-900">{{ $collection->name }}</div>
                                        <div class="text-sm text-gray-600">{{ $collection->peliculas->count() }} películas</div>
                                        @if($collection->is_public)
                                            <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded text-xs mt-1">Pública</span>
                                        @else
                                            <span class="inline-block bg-gray-100 text-gray-800 px-2 py-1 rounded text-xs mt-1">Privada</span>
                                        @endif
                                    </button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Opción 2: Crear nueva colección --}}
            <div class="bg-white shadow-md rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Crear nueva colección</h3>
                
                <form method="POST" action="{{ route('collections.store-movie', $pelicula) }}">
                    @csrf

                    <div class="mb-4">
                        <x-input-label for="new_collection_name" :value="__('Nombre de la colección')" />
                        <x-text-input 
                            id="new_collection_name" 
                            name="new_collection_name" 
                            type="text" 
                            class="mt-1 block w-full" 
                            placeholder="p. ej., Películas de terror"
                        />
                        <x-input-error class="mt-2" :messages="$errors->get('new_collection_name')" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="new_collection_description" :value="__('Descripción (opcional)')" />
                        <textarea 
                            id="new_collection_description" 
                            name="new_collection_description" 
                            class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm"
                            rows="3"
                            placeholder="Describe tu colección..."
                        ></textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('new_collection_description')" />
                    </div>

                    <div class="mb-6">
                        <label class="flex items-center">
                            <input type="checkbox" name="new_collection_is_public" value="1" class="rounded border-gray-300">
                            <span class="ms-2 text-sm text-gray-600">Hacer esta colección pública</span>
                        </label>
                    </div>

                    <x-primary-button>Crear colección y agregar película</x-primary-button>
                </form>
            </div>

            <div class="mt-6">
                <a href="{{ route('dashboard') }}" class="text-blue-600 hover:text-blue-800">← Volver al dashboard</a>
            </div>

        </div>
    </div>
</x-app-layout>
