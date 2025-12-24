<div>
    {{-- A good traveler has no fixed plans and is not intent upon arriving. --}}

    <div class="p-4">
        <p class="mb-2 text-sm text-gray-500">
            Tiempo de consulta: {{ $tiempo }} segundos | Registros cargados: {{ $numeroFilas }}
        </p>

        <div class="relative mb-4">
            <button class="btn">
                Productores <div class="badge badge-sm badge-secondary">{{ $numeroFilas }}</div>
            </button>

            <input type="text" wire:model.live="search" placeholder="Buscar productor, cédula, teléfono o localidad"
                class="input input-bordered" />
        </div>

        @if (session('status'))
            <div role="alert" class="bg-green-700 alert w-full md:w-3/4 lg:w-1/2">
                <x-heroicon-o-check-circle class="text-white h-6 w-6 shrink-0 stroke-current" />
                <span class="text-white font-bold">{{ session('status') }}</span>
            </div>
        @endif
    </div>


    <div class="border-l-4 border-primary pl-4 p-4">

        <div class="overflow-x-auto">

            <table class="table table-hover table-zebra">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Teléfono</th>
                        <th>Cédula</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($productores as $productor)
                        <tr class="hover:bg-base-300">
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="avatar">
                                        <div class="mask mask-squircle h-12 w-12">
                                            <a href="{{ route('productor.editar', $productor->id) }}" wire:navigate>
                                                <img src="{{ asset("$productor->foto") }}" />
                                            </a>
                                        </div>
                                    </div>

                                    <div>
                                        <div class="font-bold">
                                            <a href="{{ route('productor.editar', $productor->id) }}" wire:navigate
                                                class="hover:underline active:underline">
                                                {{ $productor->nombre }}
                                            </a>
                                        </div>
                                        <div class="text-sm opacity-50">{{ $productor->localidad->nombre }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                {{ $productor->telefono }}                                
                            </td>
                            <td>
                                <span class="badge badge-ghost badge-sm">{{ $productor->cedula }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- 🔽 Enlaces de paginación --}}
            <div class="mt-4 w-1/2">
                {{ $productores->links() }}
            </div>

        </div>

    </div>

</div>