<?php

namespace App\Filament\Imports\Shop;

use App\Models\Product;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class ProductImporter extends Importer
{
    protected static ?string $model = Product::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('brand_id')
                ->relationship(resolveUsing: ['name', 'id'])
                ->example('Brand A'),
            ImportColumn::make('size')
                ->requiredMapping()
                ->rules(['required', 'max:255'])
                ->example('Large'),
            ImportColumn::make('model')
                ->requiredMapping()
                ->rules(['required', 'max:255'])
                ->example('Model X'),
            ImportColumn::make('family')
                ->example('Family Y'),
            ImportColumn::make('fw')
                ->example('1.0.0'),
            ImportColumn::make('heads')
                ->example('Heads Info'),
            ImportColumn::make('stock')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer'])
                ->example('100'),
            ImportColumn::make('id_number')
                ->example('12345'),
            ImportColumn::make('date')
                ->date()
                ->example('2024-01-01'),
        ];
    }

    public function resolveRecord(): ?Product
    {
        return Product::firstOrNew([
            'id_number' => $this->data['id_number'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your product import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
