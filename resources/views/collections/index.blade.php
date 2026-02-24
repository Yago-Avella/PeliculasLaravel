<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Mis Colecciones
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Mensaje de éxito --}}
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Sección: Mis colecciones --}}
            <div class="mb-12">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-gray-900">Mis colecciones</h3>
                </div>

                @if($userCollections->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($userCollections as $collection)
                            <div class="bg-white shadow-md rounded-lg overflow-hidden hover:shadow-lg transition">
                                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-24 flex items-center justify-center">
                                    <h4 class="text-white text-lg font-bold text-center px-4">{{ $collection->name }}</h4>
                                </div>

                                <div class="p-4">
                                    @if($collection->description)
                                        <p class="text-sm text-gray-600 mb-3">{{ Str::limit($collection->description, 100) }}</p>
                                    @endif

                                    <div class="flex items-center justify-between mb-4">
                                        <span class="text-sm font-semibold text-gray-700">
                                            {{ $collection->peliculas->count() }} 
                                            {{ $collection->peliculas->count() === 1 ? 'película' : 'películas' }}
                                        </span>
                                        @if($collection->is_public)
                                            <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Pública</span>
                                        @else
                                            <span class="inline-block bg-gray-100 text-gray-800 px-2 py-1 rounded text-xs">Privada</span>
                                        @endif
                                    </div>

                                    <div class="flex gap-2">
                                        <a href="{{ route('collections.show', $collection) }}" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded text-sm font-semibold text-center transition">
                                            Ver
                                        </a>
                                        <form method="POST" action="{{ route('collections.destroy', $collection) }}" style="flex: 1;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded text-sm font-semibold transition"
                                                onclick="return confirm('¿Estás seguro? Esta acción no se puede deshacer.')">
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-white shadow-md rounded-lg p-6 text-center">
                        <p class="text-gray-600 text-lg">No tienes ninguna colección todavía</p>
                        <p class="text-gray-500 text-sm mt-2">Crea una nueva colección desde el dashboard cuando agregues una película</p>
                    </div>
                @endif
            </div>

            {{-- Sección: Colecciones públicas --}}
            @if($publicCollections->count() > 0)
                <div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">Colecciones públicas</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($publicCollections as $collection)
                            <div class="bg-white shadow-md rounded-lg overflow-hidden hover:shadow-lg transition">
                                <div class="bg-gradient-to-r from-purple-500 to-pink-600 h-24 flex items-center justify-center">
                                    <h4 class="text-white text-lg font-bold text-center px-4">{{ $collection->name }}</h4>
                                </div>

                                <div class="p-4">
                                    <p class="text-sm text-gray-600 mb-2">por <strong>{{ $collection->user->name }}</strong></p>

                                    @if($collection->description)
                                        <p class="text-sm text-gray-600 mb-3">{{ Str::limit($collection->description, 100) }}</p>
                                    @endif

                                    <div class="flex items-center justify-between mb-4">
                                        <span class="text-sm font-semibold text-gray-700">
                                            {{ $collection->peliculas->count() }} 
                                            {{ $collection->peliculas->count() === 1 ? 'película' : 'películas' }}
                                        </span>
                                        <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Pública</span>
                                    </div>

                                    <a href="{{ route('collections.show', $collection) }}" class="block w-full bg-purple-600 hover:bg-purple-700 text-white px-3 py-2 rounded text-sm font-semibold text-center transition">
                                        Ver colección
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
