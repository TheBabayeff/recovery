<?php

namespace App\Filament\Pages;

use App\Enums\TicketStatus;
use App\Filament\Resources\TicketResource;
use App\Models\Ticket;
use Mokhosh\FilamentKanban\Pages\KanbanBoard;

class TicketsKanbanBoard extends KanbanBoard
{
    protected static ?string $label = 'Sifariş izlənməsi';

    protected static ?string $navigationLabel = 'Sifarişlərin izlənməsi';

    protected static string $recordTitleAttribute = 'customer_name';
    protected static string $model = Ticket::class;
    protected static string $statusEnum = TicketStatus::class;
    protected static ?int $navigationSort = 1;
    protected static string $recordView = 'filament-kanban::kanban-record'; // Xüsusi view faylını göstəririk
    public bool $disableEditModal = true; // Edit modalını deaktiv edirik



    public static function getNavigationBadge(): ?string
    {

        $modelClass = static::$model;

        return (string) $modelClass::where('status', 'new')->count();
    }
    protected function getStatuses(): array
    {
        return TicketStatus::statuses()->toArray();
    }
    public function redirectToResource($recordId)
    {
        $url = route('filament.admin.resources.tickets.edit', $recordId);
        return redirect()->to($url);
    }




}
