<div>
    {{-- Success is as dangerous as failure. --}}
    <x-localidad.tiempo-carga />

    <div class="border-l-4 border-primary pl-4 p-4">
        <div class="border border-gray-300 p-4">
            <fieldset class="fieldset">
                <legend>
                    <h2 class="text-xl font-bold">Agregar Comarca</h2>
                </legend>
                <form wire:submit="save">
                    
                    @if (session('status'))
                        <div role="alert" class="bg-green-700 alert w-full md:w-3/4 lg:w-1/2">
                            <x-heroicon-o-check-circle class="text-white h-6 w-6 shrink-0 stroke-current" />
                            <span class="text-white font-bold">{{ session('status') }}</span>
                        </div>
                    @endif

                    <div class="flex flex-col">
                        <label class="label">Nombre de la Comarca</label>
                        <input type="text" class="input" wire:model="nombre" />
                        <div>@error('nombre') <span class="text-red-500">{{ $message }}</span> @enderror</div>
                    </div>

                    <div class="tooltip">
                        <div class="tooltip-content">
                            <div class="animate-bounce text-orange-400 font-black">Campo Obligatorio</div>
                        </div>
                        <span class="link link-hover">Información</span>
                    </div>

                    <div class="divider"></div>

                    <button type="submit" class="btn btn-neutral mt-4">
                        <span wire:loading class="loading loading-spinner mr-2"></span>
                        <span>Guardar</span>
                    </button>
                </form>
            </fieldset>
            
        </div>
    </div>

</div>