<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Models\Payment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $modelLabel = 'Pagamento';
    protected static ?string $pluralModelLabel = 'Pagamentos';

    public static function form(Form $form): Form
    {
        // Read-only or simple form
        return $form
            ->schema([
                Forms\Components\Section::make('Informações do Pagamento')
                    ->schema([
                        Forms\Components\Select::make('invoice_id')
                            ->relationship('invoice', 'number')
                            ->disabled(),
                        Forms\Components\TextInput::make('amount')
                            ->numeric()
                            ->prefix('R$')
                            ->disabled(),
                        Forms\Components\DateTimePicker::make('paid_at')
                            ->disabled(),
                    ])
                    ->columns(3),
                Forms\Components\Section::make('Detalhes da Transação')
                    ->schema([
                        Forms\Components\TextInput::make('method')
                            ->disabled(),
                        Forms\Components\TextInput::make('transaction_id')
                            ->disabled(),
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pendente',
                                'confirmed' => 'Confirmado',
                                'failed' => 'Falhou'
                            ])
                            ->disabled(),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('invoice.number')->label('Fatura Nº'),
                Tables\Columns\TextColumn::make('amount')->money('BRL')->label('Valor'),
                Tables\Columns\TextColumn::make('paid_at')->dateTime('d/m/Y')->label('Pago em'),
                Tables\Columns\TextColumn::make('method')->label('Método'),
                Tables\Columns\TextColumn::make('status')->badge(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
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
            'index' => Pages\ListPayments::route('/'),
            'view' => Pages\ViewPayment::route('/{record}'),
        ];
    }
}