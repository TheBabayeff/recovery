<?php

namespace App\Enums;

use App\Filament\Resources\TicketResource;
use App\Models\Ticket;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Collection;

enum TicketStatus: string implements HasColor, HasIcon, HasLabel
{
    case New = 'new';
    case technical_examination = 'technical_examination';
    case in_agreement = 'in_agreement';
    case Processing = 'processing';

    case Ready = 'ready';
    case Cancelled = 'cancelled';
    case Done = 'done';

    public function getLabel(): string
    {
        return match ($this) {
            self::New => 'Yeni',
            self::technical_examination => 'Texniki Müayinədə',

            self::in_agreement => 'Razılaşmada',
            self::Processing => 'Təmirdə',
            self::Ready => 'Hazırdır',
            self::Cancelled => 'İmtina Edildi',
            self::Done => 'Təhvil verildi',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::New => 'info',
            self::in_agreement => 'warning',
            self::Processing => 'warning',
            self::technical_examination,
            self::Ready => 'success',
            self::Cancelled => 'danger',
            self::Done => 'success',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::New => 'heroicon-o-sparkles',
            self::in_agreement => 'heroicon-o-arrow-path',
            self::Processing => 'heroicon-o-arrow-path',
            self::technical_examination => 'heroicon-o-truck',
            self::Ready => 'heroicon-o-check-badge',
            self::Cancelled => 'heroicon-o-x-circle',
            self::Done => 'heroicon-o-x-circle',
        };
    }



    public static function statuses(): Collection
    {
        return collect(self::cases())->map(fn ($case) => [
            'id' => $case->value,
            'title' => $case->getLabel()
        ]);
    }

    public function records(): Collection
    {
        return Ticket::where('status', $this->value)->latest('updated_at')->get();
    }
    protected function getRecordUrlUsing(): ?callable
    {
        return fn (Ticket $record): string => TicketResource::getUrl('edit', ['record' => $record]);
    }

}
