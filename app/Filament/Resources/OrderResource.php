<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Mail\OrderShippedMailable;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $modelLabel = 'Pedido';
    protected static ?string $pluralModelLabel = 'Pedidos';
    protected static ?string $navigationGroup = 'E-commerce';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informações do Pedido')
                    ->schema([
                        Forms\Components\Select::make('user_client_id')
                            ->label('Cliente')
                            ->relationship('userClient', 'id', fn ($query) => $query->with('user'))
                            ->getOptionLabelFromRecordUsing(fn ($record) => {
                                $record->loadMissing('user');
                                return $record->user->name ?? "Cliente #{$record->id}";
                            })
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\TextInput::make('total_amount')
                            ->label('Valor Total')
                            ->numeric()
                            ->required()
                            ->prefix('$')
                            ->disabled(),
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'pending' => 'Pendente',
                                'paid' => 'Pago',
                                'processing' => 'Processando',
                                'shipped' => 'Enviado',
                                'delivered' => 'Entregue',
                                'cancelled' => 'Cancelado',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('tracking_code')
                            ->label('Código de Rastreio')
                            ->maxLength(255)
                            ->visible(fn ($record) => $record?->status === 'shipped' || $record?->status === 'delivered'),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('Informações de Pagamento')
                    ->schema([
                        Forms\Components\Select::make('payment_method')
                            ->label('Método de Pagamento')
                            ->options([
                                'crypto' => 'Crypto',
                                'zelle' => 'Zelle',
                                'card' => 'Cartão',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('transaction_id')
                            ->label('ID da Transação')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('zelle_reference')
                            ->label('Referência Zelle')
                            ->maxLength(255)
                            ->visible(fn ($record) => $record?->payment_method === 'zelle'),
                        Forms\Components\TextInput::make('proof_image_url')
                            ->label('URL da Imagem de Comprovante')
                            ->url()
                            ->maxLength(255)
                            ->visible(fn ($record) => $record?->payment_method === 'zelle'),
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
                Tables\Columns\TextColumn::make('userClient.user.name')
                    ->label('Cliente')
                    ->searchable(['userClient.user.name', 'userClient.user.email'])
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Valor Total')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Método')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'crypto' => 'info',
                        'zelle' => 'warning',
                        'card' => 'success',
                    }),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'paid' => 'success',
                        'processing' => 'info',
                        'shipped' => 'info',
                        'delivered' => 'success',
                        'cancelled' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('tracking_code')
                    ->label('Código de Rastreio')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('orderItems_count')
                    ->label('Itens')
                    ->counts('orderItems'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pendente',
                        'paid' => 'Pago',
                        'processing' => 'Processando',
                        'shipped' => 'Enviado',
                        'delivered' => 'Entregue',
                        'cancelled' => 'Cancelado',
                    ]),
                Tables\Filters\SelectFilter::make('payment_method')
                    ->label('Método de Pagamento')
                    ->options([
                        'crypto' => 'Crypto',
                        'zelle' => 'Zelle',
                        'card' => 'Cartão',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('mark_as_shipped')
                    ->label('Pedido Enviado')
                    ->icon('heroicon-o-truck')
                    ->color('info')
                    ->visible(fn ($record) => $record->status !== 'shipped' && $record->status !== 'delivered' && $record->status !== 'cancelled')
                    ->form([
                        Forms\Components\TextInput::make('tracking_code')
                            ->label('Código de Rastreio')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Digite o código de rastreio'),
                    ])
                    ->action(function ($record, array $data) {
                        $originalStatus = $record->status;
                        
                        $record->update([
                            'tracking_code' => $data['tracking_code'],
                            'status' => 'shipped',
                        ]);

                        // Enviar email quando o pedido é despachado
                        if ($originalStatus !== 'shipped') {
                            try {
                                $userClient = $record->userClient;
                                if ($userClient && $userClient->user) {
                                    Mail::to($userClient->user->email)->send(new OrderShippedMailable($record));
                                }
                            } catch (\Exception $e) {
                                Log::error('Failed to send order shipped email', [
                                    'order_id' => $record->id,
                                    'error' => $e->getMessage(),
                                ]);
                            }
                        }
                    })
                    ->successNotificationTitle('Pedido marcado como enviado com sucesso!'),
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
            'view' => Pages\ViewOrder::route('/{record}'),
        ];
    }
}
