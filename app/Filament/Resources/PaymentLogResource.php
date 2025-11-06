<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentLogResource\Pages;
use App\Models\PaymentLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PaymentLogResource extends Resource
{
    protected static ?string $model = PaymentLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $modelLabel = 'Log de Pagamento';
    protected static ?string $pluralModelLabel = 'Logs de Pagamento';
    protected static ?string $navigationGroup = 'E-commerce';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informações do Log')
                    ->schema([
                        Forms\Components\Select::make('order_id')
                            ->label('Pedido')
                            ->relationship('order', 'id')
                            ->searchable()
                            ->preload()
                            ->disabled(),
                        Forms\Components\TextInput::make('gateway')
                            ->label('Gateway')
                            ->required()
                            ->maxLength(255)
                            ->disabled(),
                        Forms\Components\TextInput::make('ip_address')
                            ->label('Endereço IP')
                            ->maxLength(45)
                            ->disabled(),
                        Forms\Components\Textarea::make('payload')
                            ->label('Payload do Webhook')
                            ->disabled()
                            ->formatStateUsing(fn ($state) => $state ? json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : '')
                            ->rows(10)
                            ->columnSpanFull(),
                        Forms\Components\Placeholder::make('created_at')
                            ->label('Criado em')
                            ->content(fn ($record) => $record?->created_at?->format('d/m/Y H:i:s') ?? '-'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('order_id')
                    ->label('Pedido ID')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('gateway')
                    ->label('Gateway')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'c6' => 'success',
                        'crypto' => 'info',
                        'zelle' => 'warning',
                        default => 'gray',
                    })
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ip_address')
                    ->label('IP')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Data/Hora')
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('gateway')
                    ->label('Gateway')
                    ->options([
                        'c6' => 'C6',
                        'crypto' => 'Crypto',
                        'zelle' => 'Zelle',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPaymentLogs::route('/'),
            'view' => Pages\ViewPaymentLog::route('/{record}'),
        ];
    }
}
