<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('TUS PELICULAS') }}
        </h2>
    </x-slot>

    <div class="p-6 space-y-4">

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($peliculas as $pelicula)
                <div class="p-4 bg-white shadow rounded">
                    <h4 class="font-bold text-lg">{{ $pelicula->titulo }}</h4>
                    <p><strong>Año:</strong> {{ $pelicula->anyo }}</p>
                    <p><strong>Duración:</strong> {{ $pelicula->duracion }} min</p>
                    <p><strong>Media:</strong> {{ $pelicula->media ?? 'Sin valoraciones' }}</p>
                    <p class="mt-2">{{ $pelicula->sinopsis }}</p>
                    @if ($pelicula->poster)
                        <img src="{{ $pelicula->poster }}" alt="{{ $pelicula->titulo }}" class="mt-2 w-full h-auto rounded">
                    @endif

                    <form action="{{ route('dashboard.peliculas.destroy', $pelicula) }}" method="POST" class="mt-2">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                        onclick="return confirm('¿Estás seguro de que quieres eliminar esta película?')"
                        class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">
                            Eliminar
                        </button>
                    </form>
                </div>
            @endforeach
        </div>

    </div>
</x-app-layout>
