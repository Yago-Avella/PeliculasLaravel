<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Panel de control - Administración de películas
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

            {{-- Tabla de películas --}}
            @if ($peliculas->count() > 0)
                <div class="bg-white shadow-md rounded-lg overflow-hidden">
                    <table class="min-w-full">
                        <thead class="bg-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Título</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Año</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Duración</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Géneros</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Valoración</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($peliculas as $pelicula)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm text-gray-900 font-medium">{{ $pelicula->titulo }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $pelicula->anyo }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $pelicula->duracion }} min</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        @forelse ($pelicula->generos as $genero)
                                            <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs mr-1">
                                                {{ $genero->name }}
                                            </span>
                                        @empty
                                            <span class="text-gray-400">Sin género</span>
                                        @endforelse
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        @if ($pelicula->media > 0)
                                            <span class="font-semibold text-yellow-600">{{ number_format($pelicula->media, 1) }}/10</span>
                                        @else
                                            <span class="text-gray-400">Sin valorar</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <form method="POST" action="{{ route('admin.movie.destroy', $pelicula) }}" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm font-semibold"
                                                onclick="return confirm('¿Estás seguro de que deseas eliminar esta película permanentemente? Se borrarán todas las valoraciones asociadas.')">
                                                Eliminar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <p class="mt-4 text-sm text-gray-600">
                    Total de películas en la base de datos: <strong>{{ $peliculas->count() }}</strong>
                </p>
            @else
                <div class="bg-white shadow-md rounded-lg p-6 text-center">
                    <p class="text-gray-600 text-lg">No hay películas en la base de datos</p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>