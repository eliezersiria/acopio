<div>
    {{-- To attain knowledge, add things every day; To attain wisdom, subtract things every day. --}}
    <x-acopio.tiempo-carga />

    <div class="border-l-4 border-primary pl-4 flex">

        <fieldset class="fieldset bg-base-200 border-base-300 rounded-box border p-4">

            @if (session('status'))
                <div role="alert" class="bg-green-700 alert w-full md:w-3/4 lg:w-1/2">
                    <x-heroicon-o-check-circle class="text-white h-6 w-6 shrink-0 stroke-current" />
                    <span class="text-white font-bold">{{ session('status') }}</span>
                </div>
            @endif

            <legend>
                <h2 class="text-xl font-bold">Registrar Acopio</h2>
            </legend>

            <form wire:submit.prevent="SaveAcopio">

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                    {{-- PRIMER CAMPO BUSQUEDA --}}
                    <div>

                        <label class="label">Seleccione Productor</label>
                        <label class="input">
                            <input type="text" class="grow" placeholder="nombre"
                                oninput="this.value = this.value.replace(/^\s+/g, '')"
                                wire:model.live.300ms="productorquery"
                                wire:keydown.arrow-down.prevent="incrementHighlight"
                                wire:keydown.arrow-up.prevent="decrementHighlight"
                                wire:keydown.enter.prevent="selectHighlighted" />

                            <!-- Spinner -->
                            <div wire:loading wire:target="productorquery" class="ml-2">
                                <svg class="animate-spin h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z">
                                    </path>
                                </svg>
                            </div>
                        </label>

                        @if ($productores)
                            @if ($productores->isEmpty())
                                <div class="p-2 text-red-500">
                                    No se encontraron registros.
                                </div>
                            @else
                                <ul class="z-10 w-full border rounded mt-1">
                                    @foreach($productores as $index => $prod)
                                        <li class="px-3 py-2 cursor-pointer @if($highlightIndex === $index) bg-primary font-bold text-white @endif"
                                            wire:click="selectProductor({{ $prod->id }})">
                                            {{ $prod->nombre }}
                                            <span class="text-gray-500 text-sm block">
                                                {{ $prod->localidad?->nombre ?? 'Sin localidad' }}
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        @endif

                        <input type="hidden" class="input" wire:model="productor_id">
                        @error('productor_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                    </div>
                    {{-- FIN PRIMER CAMPO BUSQUEDA --}}

                    <div>
                        <label class="label">Comunidad</label>
                        <input type="text" class="input" wire:model="localidad" readonly />
                        <input type="hidden" class="input" wire:model="localidad_id" />
                        @error('localidad_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="label">Fecha</label>
                        <input type="date" class="input" wire:model="fecha" />
                    </div>

                    <div>
                        <label class="label">Hora</label>
                        <input type="time" class="input" wire:model="hora" step="1" />
                    </div>

                    <div>
                        <label class="label">Litros recibidos</label>
                        <input type="number" class="input" wire:model.live="litros" />
                        @error('litros') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="label">Precio por litro</label>
                        <input type="number" class="input" wire:model.live="precio_litro" />
                        @error('precio_litro') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="label">Total pagado</label>
                        <input type="number" class="input" wire:model="total_pagado" readonly />
                        @error('total_pagado') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                </div>

                <div>
                    <div class="divider"></div>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>

            </form>


        </fieldset>

    </div>

</div>