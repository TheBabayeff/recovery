<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\TicketResource;
use App\Models\Ticket;
use Carbon\Carbon;
use Filament\Widgets\Widget;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class CalendarWidget extends FullCalendarWidget
{

    public function fetchEvents(array $fetchInfo): array
    {
        // Fetch the events within the specified date range
        return Ticket::query()
            ->whereDate('created_at', '>=', Carbon::parse($fetchInfo['start'])->toDateString())
            ->whereDate('created_at', '<=', Carbon::parse($fetchInfo['end'])->toDateString())
            ->get()
            ->map(
                fn (Ticket $ticket) => [
                    'title' => 'Sifariş #' . $ticket->number . ' - ' . $ticket->customer->name,
                    'start' => $ticket->created_at->toIso8601String(),
                    'url' => TicketResource::getUrl(name: 'edit', parameters: ['record' => $ticket]),
                    'shouldOpenUrlInNewTab' => true,
                ]
            )
            ->all();
    }

    public static function canView(): bool
    {
        return auth()->user()->role === 'xxxx';
    }

}
