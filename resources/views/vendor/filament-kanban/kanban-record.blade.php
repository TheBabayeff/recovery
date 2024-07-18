<div
    id="{{ $record->getKey() }}"
    wire:click="redirectToResource('{{ $record->getKey() }}')"
    class="record bg-white dark:bg-gray-700 rounded-lg px-4 py-2 cursor-pointer font-medium text-gray-600 dark:text-gray-200"
    @if($record->timestamps && now()->diffInSeconds($record->{$record::UPDATED_AT}) < 3)
        x-data
    x-init="
            $el.classList.add('animate-pulse-twice', 'bg-primary-100', 'dark:bg-primary-800')
            $el.classList.remove('bg-white', 'dark:bg-gray-700')
            setTimeout(() => {
                $el.classList.remove('bg-primary-100', 'dark:bg-primary-800')
                $el.classList.add('bg-white', 'dark:bg-gray-700')
            }, 3000)
        "
    @endif
>
    <strong>Sifariş:</strong> <span style="color: #28a745;">{{ $record->number }}</span> <br>
    <strong>Müştəri:</strong> {{ optional($record->customer)->name ?? 'Məlumat yoxdur' }} <br>
    <strong>Mühəndis:</strong> {{ optional($record->engineer)->name ?? 'Məlumat yoxdur' }} <br>
    <strong>Mühəndis Qeydi:</strong> {{ $record->engineer_note }} <br>

</div>
