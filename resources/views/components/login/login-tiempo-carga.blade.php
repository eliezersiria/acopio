@if(app()->has('request_time'))
<div class="mb-2 text-xs text-gray-500 flex justify-center items-center gap-1">
    Cargado en {{ app('request_time') }} segundos
    <x-heroicon-o-clock class="w-4 h-4" />
</div>
@endif
