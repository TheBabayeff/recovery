<?php

namespace App\Filament\Widgets;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Ticket;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    use InteractsWithPageFilters;
    protected static ?int $sort = 0;

    protected function getFilters(): array
    {
        return [
            DatePicker::make('startDate')
                ->label('Başlangıç Tarihi')
                ->default(now()->startOfMonth())
                ->required(),
            DatePicker::make('endDate')
                ->label('Bitiş Tarihi')
                ->default(now())
                ->required(),
        ];
    }

    protected function getStats(): array
    {
        $startDate = $this->filters['startDate'] ?? null ? Carbon::parse($this->filters['startDate']) : now()->startOfMonth();
        $endDate = $this->filters['endDate'] ?? null ? Carbon::parse($this->filters['endDate']) : now();

        // Filtrelenmiş tarihlere göre biletleri alın
        $tickets = Ticket::whereBetween('created_at', [$startDate, $endDate])->get();

        // Toplam gelir
        $totalRevenue = $tickets->sum('total_price');

        // Yeni müşteriler
        $newCustomers = $tickets->groupBy('customer_id')->count();

        // Yeni siparişler (biletler)
        $newOrders = $tickets->count();
        $brands = Brand::withCount('products')->get();

        return [
            Stat::make('Revenue', '₼' . number_format($totalRevenue, 2))
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),
            Stat::make('Yeni müştərilər', $newCustomers)
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->chart([17, 16, 14, 15, 14, 13, 12])
                ->color('success'),
            Stat::make('Yeni Sifarişlər', $newOrders)
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([15, 4, 10, 2, 12, 4, 12])
                ->color('success'),

        ];
    }

    public static function canView(): bool
    {
        return auth()->user()->role === 'admin';
    }
}
