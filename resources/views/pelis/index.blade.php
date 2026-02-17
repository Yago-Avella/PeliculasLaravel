<x-app-layout>

    <x-slot name="header">
        <h2 class="text-xl font-semibold">
            Películas populares
        </h2>
    </x-slot>

    <div class="p-6">

        @foreach ($movies as $movie)
            <div class="mb-4 p-4 bg-white shadow rounded">

                <h3 class="text-lg font-bold">
                    {{ $movie['title'] }}
                </h3>

                <p>
                    {{ $movie['overview'] }}
                </p>

                <img src="https://image.tmdb.org/t/p/w500{{ $movie['poster_path'] }}" width="150">

            </div>
        @endforeach

    </div>

</x-app-layout>