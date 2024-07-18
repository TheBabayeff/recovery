<div class="ticket-card">
    <div><strong>Number:</strong> {{ $record->number }}</div>
    <div><strong>Customer:</strong> {{ $record->customer->name }}</div>
    <div><strong>Engineer:</strong> {{ $record->engineer->name }}</div>
    <div><strong>Device Model:</strong> {{ $record->device_model }}</div>
    <div><strong>Device Serial Number:</strong> {{ $record->device_serial_number }}</div>
    <div><strong>Status:</strong> {{ $record->status }}</div>
</div>
