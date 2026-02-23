<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Valoraciones') }}
        </h2>
    </x-slot>

    <div class="p-6 space-y-4">
        @foreach($valoraciones as $val)
            @php
                $isHidden = !$val->visible;
            @endphp
            <div class="p-4 rounded shadow {{ $isHidden && auth()->user()->role === 'admin' ? 'bg-gray-200' : 'bg-white' }}">
                <div class="flex items-start gap-4">
                    @if($val->pelicula && $val->pelicula->poster)
                        <img src="{{ $val->pelicula->poster }}" alt="{{ $val->pelicula->titulo }}" class="w-24 h-auto rounded flex-shrink-0">
                    @endif
                    <div class="flex-1">
                        <p><strong>Pelicula:</strong> {{ $val->pelicula->titulo ?? '---' }}</p>
                        <p><strong>Usuario:</strong> {{ $val->user->name }}</p>
                        <p><strong>Nota:</strong> {{ $val->rating }} / 10</p>
                        @if($val->review)
                            <p class="mt-2"><strong>Reseña:</strong> {{ $val->review }}</p>
                        @endif
                        @if(auth()->user()->role === 'admin')
                            <form action="{{ route('valoraciones.toggle', $val) }}" method="POST" class="mt-2 inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="px-2 py-1 text-sm rounded {{ $val->visible ? 'bg-red-500 text-white' : 'bg-green-500 text-white' }}">
                                    {{ $val->visible ? 'Ocultar' : 'Mostrar' }}
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach

        @if($valoraciones->isEmpty())
            <p class="text-gray-600">No hay valoraciones disponibles.</p>
        @endif
    </div>
</x-app-layout>
