<?php


namespace App\Filament\Resources;

use App\Enums\TicketStatus;
use App\Filament\Resources\TicketResource\Pages\ManageTicketComments;
use App\Filament\Resources\TicketResource\Pages;
use App\Models\Diagnostic;
use App\Models\Service;
use App\Models\Ticket;
use Filament\Forms\Components\Split;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Pages\Page;
use Filament\Pages\SubNavigationPosition;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static ?string $label = 'Sifarişlər';
    protected static ?string $pluralLabel = 'Sifarişlər';
    protected static ?string $modelLabel = 'Sifariş';
    protected static ?int $navigationSort = 2;

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function getNavigationBadge(): ?string
    {

        $modelClass = static::$model;

        return (string) $modelClass::where('status', 'new')->count();
    }

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make()
                    ->schema([
                        // İlk mərhələ: Əsas məlumatlar və mühəndis seçimi
                        Wizard\Step::make('Sifariş haqqında')
                            ->schema(static::getDetailsFormSchema())
                            ->columns(2),

                        // İkinci mərhələ: Device məlumatları
                        Wizard\Step::make('Cihaz Haqqında')
                            ->schema([
                                TextInput::make('device_model')
                                    ->label('Cihazın Modeli')
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('device_serial_number')
                                    ->label('Cihazın Seriya Nömrəsi')
                                    ->required()
                                    ->maxLength(255),

                                MarkdownEditor::make('device_appearance')
                                    ->label('Cihazın Görünüşü')
                                    ->columnSpan('full')
                                    ->required(),
                            ])
                            ->columns(2),

                        // Üçüncü mərhələ: Repeater ilə itemlar
                        Wizard\Step::make('Göstəriləcək Xidmətlər')
                            ->schema([
                                Forms\Components\Section::make('Hansı xidmətlər istifadə olunacaq?')
                                    ->headerActions([
                                        Forms\Components\Actions\Action::make('reset')
                                            ->modalHeading('Are you sure?')
                                            ->modalDescription('All existing items will be removed from the Ticket.')
                                            ->requiresConfirmation()
                                            ->color('danger')
                                            ->action(fn (Forms\Set $set) => $set('items', [])),
                                    ])
                                    ->schema([
                                        static::getItemsRepeater(),
                                        TextInput::make('total_price')
                                            ->label('Ümumi Qiymət')
                                            ->disabled()
                                            ->numeric()
                                            ->dehydrated(),
                                    ]),
                            ])
                            ->columns(2),
                    ])
                    ->columnSpan('full')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table

            ->columns([
                Tables\Columns\TextColumn::make('number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Müştəri adı')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('engineer.name')
                    ->label('Mühəndis')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->sortable(),

                Tables\Columns\TextColumn::make('device_model')
                    ->label('Cihaz Modeli')
                    ->searchable(),
                Tables\Columns\TextColumn::make('device_serial_number')
                    ->label('Cihazın Seriyası')
                    ->searchable(),
                Tables\Columns\TextColumn::make('finished_at')
                    ->label('Təhvil Verilmə Tarixi')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_price')
                    ->label('Qiymət')
                    ->numeric()
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
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),

                Tables\Actions\Action::make('pdf')
                    ->color('success')
//                ->icon('heroicon-o-document-download')
                    ->url(fn(Ticket $record)=>route('ticket.pdf.download', $record))
                    ->openUrlInNewTab(),
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
            'index' => Pages\ListTickets::route('/'),
            'create' => Pages\CreateTicket::route('/create'),
            'edit' => Pages\EditTicket::route('/{record}/edit'),
            'view' => Pages\ViewTicket::route('/{record}'),

            'manageComments' => Pages\ManageTicketComments::route('/{record}/comments'),

        ];
    }

    public static function getDetailsFormSchema(): array
    {
        return [
            TextInput::make('number')
                ->label('Sifariş nömrəsi')
                ->default('#' . random_int(1000, 9999))
                ->disabled()
                ->dehydrated()
                ->required()
                ->maxLength(32)
                ->unique(Ticket::class, 'number', ignoreRecord: true),

            // Display the operator's name
            TextInput::make('operator_id')

                ->label('Operator')
                ->default(fn () => auth()->user()->id)
                ->disabled()
                ->dehydrated(),



            Select::make('customer_id')
                ->label('Müştəri adı')
                ->relationship('customer', 'name')
                ->searchable()
                ->required()
                ->createOptionForm([
                    Forms\Components\TextInput::make('name')
                        ->label('Müştəri adı')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('phone')
                        ->label('Telefon nömrəsi')
                        ->tel()
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('email')
                        ->email()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('passport')
                        ->label('Ş\V seriyası və ya Fin')
                        ->maxLength(255)
                        ->required(),
                    Forms\Components\DatePicker::make('birthday')
                        ->label('Doğum tarixi'),
                    Forms\Components\TextInput::make('address')
                        ->label('Qeydiyyat ünvanı')
                        ->maxLength(255),

                ])
                ->createOptionAction(function (Forms\Components\Actions\Action $action) {
                    return $action
                        ->modalHeading('Müştəri yarat')
                        ->modalSubmitActionLabel('Müştəri yarat')
                        ->modalWidth('lg');
                }),
            Select::make('engineer_id')
                ->label('Mühəndisi seç')
                ->options(User::engineers())
                ->searchable()
                ->required()
                ->relationship('engineer', 'name'),

            Forms\Components\ToggleButtons::make('status')
                ->inline()
                ->default('new')
                ->options(TicketStatus::class)
                ->required(),
            Forms\Components\DatePicker::make('finished_at')
                ->label('Təhvil verilmə tarixi'),
            Forms\Components\Textarea::make('note')
                ->label('Qeyd'),
            Forms\Components\Textarea::make('engineer_note')
                ->label('Mühəndis qeydi'),
        ];
    }

    public static function getItemsRepeater(): Repeater
    {
        return Repeater::make('items')
            ->relationship('items') // Əlaqəni 'items' olaraq təyin etdik
            ->schema([
                Select::make('service_id')
                    ->label('Məhsul')
                    ->options(Service::all()->pluck('name', 'id'))
                    ->reactive()
                    ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('price', Service::find($state)?->price ?? 0))
                    ->distinct()
                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                    ->columnSpan([
                        'md' => 5,
                    ])
                    ->searchable(),

                TextInput::make('qty')
                    ->label('Say')
                    ->numeric()
                    ->default(1)
                    ->columnSpan([
                        'md' => 2,
                    ])
                    ->required(),

                TextInput::make('price')
                    ->label('Ədəd Qiyməti')
                    ->disabled()
                    ->dehydrated()
                    ->numeric()
                    ->required()
                    ->columnSpan([
                        'md' => 3,
                    ]),
            ])
            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                $items = $get('items');
                $totalPrice = collect($items)->sum(fn ($item) => ($item['price'] ?? 0) * ($item['qty'] ?? 1));
                $set('total_price', $totalPrice);
            })

            ->extraItemActions([
                Forms\Components\Actions\Action::make('openProduct')
                    ->tooltip('Open product')
                    ->icon('heroicon-m-arrow-top-right-on-square')
                    ->url(function (array $arguments, Repeater $component): ?string {
                        $itemData = $component->getRawItemState($arguments['item']);

                        $product = Service::find($itemData['service_id']);

                        if (! $product) {
                            return null;
                        }

                        return ServiceResource::getUrl('edit', ['record' => $product]);
                    }, shouldOpenInNewTab: true)
                    ->hidden(fn (array $arguments, Repeater $component): bool => blank($component->getRawItemState($arguments['item'])['service_id'])),
            ])
            ->live(true)
            ->defaultItems(1)
            ->hiddenLabel()
            ->columns([
                'md' => 10,
            ])


            ->required();
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->orderBy('created_at', 'DESC');
    }


    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Pages\ViewTicket::class,
            Pages\EditTicket::class,
            ManageTicketComments::class,
        ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make()
                    ->schema([
                        \Filament\Infolists\Components\Split::make([
                            Grid::make(2)
                                ->schema([
                                    Group::make([
                                        TextEntry::make('number')
                                            ->label('Sifariş nömrəsi'),
                                        TextEntry::make('customer.name')
                                            ->label('Müştəri adı'),
                                        TextEntry::make('device_model')
                                            ->label('Cihazın Modeli'),
                                        TextEntry::make('device_serial_number')
                                            ->label('Cihazın Seriya Nömrəsi'),
                                        TextEntry::make('status')
                                            ->label('Status')
                                            ->badge()
                                            ->color('success'),
                                    ]),
                                    Group::make([
                                        TextEntry::make('engineer.name')
                                            ->label('Mühəndis'),
                                        TextEntry::make('operator.name')
                                            ->label('Operator'),
                                        TextEntry::make('total_price')
                                            ->label('Toplam Qiymət')
                                            ->prefix('₼'),
                                        TextEntry::make('finished_at')
                                            ->label('Təhvil verilmə Tarixi')
                                            ->date(),
                                        TextEntry::make('updated_at')
                                            ->label('Yenilənmə Tarixi')
                                            ->date(),
                                    ]),
                                ]),
                            ImageEntry::make('image')
                                ->hiddenLabel()
                                ->grow(false),
                        ])->from('lg'),
                    ]),
                Section::make('Qeyd')
                    ->schema([
                        TextEntry::make('note')
                            ->label('Qeyd')
                            ->prose()
                            ->markdown()
                            ->hiddenLabel(),
                    ])
                    ->collapsible(),

            ]);
    }




}
