<div>
    {{-- Knowing others is intelligence; knowing yourself is true wisdom. --}}
    <x-productor.tiempo-carga />

    <div class="border-l-4 border-primary pl-4 flex">

        <fieldset class="fieldset bg-base-200 border-base-300 rounded-box border p-4">

            @if (session('status'))
                <div role="alert" class="bg-green-700 alert w-full md:w-3/4 lg:w-1/2">
                    <x-heroicon-o-check-circle class="text-white h-6 w-6 shrink-0 stroke-current" />
                    <span class="text-white font-bold">{{ session('status') }}</span>
                </div>
            @endif

            <legend>
                <h2 class="text-xl font-bold">Agregar Productor</h2>
            </legend>

            <form wire:submit.prevent="saveProductor">

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                    <div>
                        <label class="label">Nombre del productor</label>
                        <input type="text" class="input" placeholder="nombre" wire:model="nombre" />
                        @error('nombre') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="label">Cédula</label>
                        <input type="text" class="input" placeholder="cedula" wire:model="cedula" />
                        @error('cedula') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="label">Teléfono</label>
                        <input type="number" class="input" placeholder="telefono" wire:model="telefono" />
                        @error('telefono') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="label">Comarca</label>
                        <select class="select appearance-none" wire:model="localidad_id">
                            <option value="">Seleccionar Comarca</option>
                            @foreach ($localidades as $localidad)
                                <option value="{{ $localidad->id }}">{{ $localidad->nombre }}</option>
                            @endforeach
                        </select>
                        @error('localidad_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="label">Seleccione semana de entrega</label>
                        <select class="select appearance-none" wire:model="semana">
                            <option value="">Seleccione semana</option>
                            <option value="A">Domingo a Sábado</option>
                            <option value="B">Viernes a Jueves</option>
                        </select>
                        @error('semana') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="label">Dirección</label>
                        <textarea class="textarea" placeholder="direccion" wire:model="direccion" rows="4"></textarea>
                        @error('direccion') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>                    

                    <div>
                        <input type="file" wire:model="foto" accept="image/*">

                        @if ($foto)
                            <img src="{{ $foto->temporaryUrl() }}" class="h-40 rounded-lg" />
                        @else
                            <img src="{{ asset('images/icons/imagen.png') }}" class="rounded-lg" />
                        @endif
                    </div>
                    @error('foto') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <div class="divider"></div>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </fieldset>
    </div>


</div>