<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Filament\Resources\ProductResource\Widgets\ProductStats;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static ?string $label = 'Məhsullar';
    protected static ?string $pluralLabel = 'Məhsullar';
    protected static ?string $modelLabel = 'Məhsul';
    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';
    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('brand_id')
                    ->relationship('brand', 'name'),
                Forms\Components\TextInput::make('size')
                    ->maxLength(255),
                Forms\Components\TextInput::make('model')
                    ->maxLength(255),
                Forms\Components\TextInput::make('family')
                    ->maxLength(255),
                Forms\Components\TextInput::make('fw')
                    ->maxLength(255),
                Forms\Components\TextInput::make('heads')
                    ->maxLength(255),
                Forms\Components\TextInput::make('stock')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('id_number')
                    ->maxLength(255),
                Forms\Components\DatePicker::make('date'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('brand.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('size')
                    ->searchable(),
                Tables\Columns\TextColumn::make('model')
                    ->searchable(),
                Tables\Columns\TextColumn::make('family')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fw')
                    ->searchable(),
                Tables\Columns\TextColumn::make('heads')
                    ->searchable(),
                Tables\Columns\TextColumn::make('stock')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('id_number')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('brand')
                    ->relationship('brand', 'name')
                    ->label('Brand'),
                Tables\Filters\Filter::make('id_number')
                    ->label('ID Number')
                    ->query(function (Builder $query) {
                        // This will add sorting for id_number by ignoring the first character
                        $query->orderByRaw('CAST(SUBSTRING(id_number, 2) AS UNSIGNED) ASC');
                    })
                    ->form([
                        Forms\Components\Select::make('order')
                            ->options([
                                'asc' => 'Ascending',
                                'desc' => 'Descending',
                            ])
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, Builder $query) {
                                if ($state === 'asc') {
                                    $query->orderByRaw('CAST(SUBSTRING(id_number, 2) AS UNSIGNED) ASC');
                                } else {
                                    $query->orderByRaw('CAST(SUBSTRING(id_number, 2) AS UNSIGNED) DESC');
                                }
                            }),
                    ]),

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getWidgets(): array
    {
        return [
            ProductStats::class,
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->orderBy('created_at', 'DESC');
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    // İstifadəçinin bu resursu görmək icazəsinin olub-olmamasını yoxlayın
    public static function canViewAny(): bool
    {
        return Auth::user()->role === 'admin';
    }

    // Navigasiyada görünmə icazəsini yoxlayın
    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->role === 'admin';
    }

}
