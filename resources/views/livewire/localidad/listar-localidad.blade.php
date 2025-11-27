<div>
    {{-- A good traveler has no fixed plans and is not intent upon arriving. --}}

    <div class="p-4">
        <p class="mb-2 text-sm text-gray-500">
            Tiempo de consulta: {{ $tiempo }} segundos | Registros cargados: {{ $numeroFilas }}
        </p>

        <p class="mb-3">
            <button class="btn">
                Comarcas <div class="badge badge-sm badge-secondary">{{ $numeroFilas }}</div>
            </button>
        </p>

        @if (session('status'))
            <div role="alert" class="bg-green-700 alert w-full md:w-3/4 lg:w-1/2">
                <x-heroicon-o-check-circle class="text-white h-6 w-6 shrink-0 stroke-current" />
                <span class="text-white font-bold">{{ session('status') }}</span>
            </div>
        @endif
    </div>


    <div class="border-l-4 border-primary pl-4 p-4">
        <table class="table table-zebra">
            <thead>
                <tr>
                    <th></th>
                    <th>Comarca</th>
                    <th>Editar</th>
                </tr>
            </thead>
            <tbody>
                @foreach($localidades as $localidad)
                    <tr class="hover:bg-base-300">
                        <th>{{ $localidad->id }}</th>
                        <td>{{ $localidad->nombre }}</td>
                        <td>
                            <a href="{{ route('localidad.editar', $localidad->id) }}" wire:navigate class="btn btn-sm">
                                <img src="{{ asset('images/icons/lapiz.png') }}" />
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>

</div>