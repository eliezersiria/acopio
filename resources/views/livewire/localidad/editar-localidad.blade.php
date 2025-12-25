<div>
    {{-- Success is as dangerous as failure. --}}    

    <div class="breadcrumbs text-sm p-4">
        <ul>
            <li>
                <a href="{{ route('localidad.listar') }}" wire:navigate>
                    <x-heroicon-s-arrow-left class="w-4 h-4" />
                    Regresar a lista de comarcas
                </a>
            </li>
            <li>Editando comarca {{ $nombre }}</li>
        </ul>
    </div>

    <div class="border-l-4 border-yellow-500 pl-4 p-4">
        <div class="border border-gray-300 p-4">
            <fieldset class="fieldset">
                <legend>
                    <h2 class="text-xl font-bold">Editar Comarca</h2>
                </legend>
                <form wire:submit="save">

                    @if (session('status'))
                        <div role="alert" class="bg-green-700 alert w-full md:w-3/4 lg:w-1/2">
                            <x-heroicon-o-check-circle class="text-white h-6 w-6 shrink-0 stroke-current" />
                            <span class="text-white font-bold">{{ session('status') }}</span>
                        </div>
                    @endif

                    <div class="flex flex-col">
                        <label class="label">Comarca</label>
                        <input type="text" class="input" wire:model="nombre" placeholder="Comarca" />
                        <div>@error('nombre') <span class="text-red-500">{{ $message }}</span> @enderror</div>
                    </div>

                    <div class="tooltip">
                        <div class="tooltip-content">
                            <div class="animate-bounce text-orange-400 font-black">Campo Obligatorio</div>
                        </div>
                        <span class="link link-hover">Información</span>
                    </div>

                    <div class="divider"></div>

                    <button type="submit" class="btn bg-green-600 text-white mt-4" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="save">Actualizar</span>
                        <span wire:loading wire:target="save" class="loading loading-spinner mr-2"></span>
                    </button>

                    {{--
                    <button type="button" wire:click="sendTrash({{ $localidad_id }})"
                        class="btn bg-red-600 text-white mt-4">
                        <x-heroicon-s-trash class="w-4 h-4" />
                        Enviar a la Papelera
                    </button>
                    --}}
                </form>

            </fieldset>

            <p class="mt-2 text-sm text-gray-500">Registro creado el
                {{ $creado->translatedFormat('l d \d\e F \d\e Y h:i a') }}
            </p>

            <p class="mt-2 text-sm text-gray-500">
                Actualizado {{ $actualizado->diffForHumans() }} el
                {{ $actualizado->translatedFormat('l d \d\e F \d\e Y h:i a') }}
            </p>
        </div>
    </div>

    {{-- Modal de confirmación con DaisyUI --}}
    <div>
        @if($showModalSendTrash)
            <div class="modal modal-open">
                <div class="modal-box">
                    <h3 class="font-bold text-lg">¡Confirmar!</h3>
                    <p class="py-4">¿Desea enviar a la papelera la Comarca {{ $nombre }}?</p>
                    <div class="modal-action">
                        <button wire:click="softDelete" class="btn bg-red-600 text-white">Sí, Eliminar</button>
                        <button wire:click="closeModalSendTrash" class="btn bg-gray-600 text-white">No, Cancelar</button>
                    </div>
                </div>
            </div>
        @endif
    </div>

</div>