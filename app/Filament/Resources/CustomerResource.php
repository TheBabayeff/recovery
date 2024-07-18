<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $label = 'Müştərilər';
    protected static ?string $pluralLabel = 'Müştərilər';
    protected static ?string $modelLabel = 'Müştəri';
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Müştəri adı')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->label('Telefon nömrəsi')
                    ->tel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->maxLength(255),
                Forms\Components\TextInput::make('passport')
                    ->label('Ş\V seriyası və ya Fin')
                    ->maxLength(255),
                Forms\Components\DatePicker::make('birthday')
                    ->label('Doğum tarixi'),
                Forms\Components\TextInput::make('address')
                    ->label('Qeydiyyat ünvanı')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Müştəri adı')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Telefon nömrəsi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('passport')
                    ->label('Ş\V seriyası və ya Fin')
                    ->searchable(),
                Tables\Columns\TextColumn::make('birthday')
                    ->label('Doğum tarixi')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('address')
                    ->label('Qeydiyyat ünvanı')
                    ->searchable(),
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
                //
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
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
