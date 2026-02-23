<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Valorar película') }}
        </h2>
    </x-slot>

    <div class="p-6">
        <div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
            <h3 class="text-lg font-bold mb-4">{{ $pelicula->titulo }} ({{ $pelicula->anyo }})</h3>
            @if($pelicula->poster)
                <img src="{{ $pelicula->poster }}" alt="{{ $pelicula->titulo }}" class="mb-4 w-32 h-auto rounded">
            @endif

            <form action="{{ route('valoraciones.store', $pelicula) }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="rating" class="block font-medium">Puntuación (1-10)</label>
                    <input type="number" name="rating" id="rating" min="1" max="10"
                           value="{{ old('rating', optional($pelicula->valoraciones->where('user_id', auth()->id())->first())->rating) }}"
                           class="border rounded w-full p-2">
                    @error('rating')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
                </div>

                <div class="mb-4">
                    <label for="review" class="block font-medium">Descripción (opcional)</label>
                    <textarea name="review" id="review" rows="4"
                              class="border rounded w-full p-2">{{ old('review', optional($pelicula->valoraciones->where('user_id', auth()->id())->first())->review) }}</textarea>
                    @error('review')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
                </div>

                <div class="flex justify-end space-x-2">
                    <a href="{{ url()->previous() }}" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancelar</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Guardar valoración
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
