<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CouponResource\Pages;
use App\Models\Coupon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CouponResource extends Resource
{
    protected static ?string $model = Coupon::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static ?string $modelLabel = 'Cupom';
    protected static ?string $pluralModelLabel = 'Cupons';
    protected static ?string $navigationGroup = 'E-commerce';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informações do Cupom')
                    ->schema([
                        Forms\Components\TextInput::make('code')
                            ->label('Código do Cupom')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->placeholder('Ex: DESCONTO10')
                            ->helperText('Código único que será usado para aplicar o desconto'),
                        Forms\Components\Textarea::make('description')
                            ->label('Descrição')
                            ->rows(3)
                            ->maxLength(500)
                            ->columnSpanFull()
                            ->helperText('Descrição opcional sobre o cupom'),
                        Forms\Components\TextInput::make('discount_percentage')
                            ->label('Desconto (%)')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(50)
                            ->step(0.01)
                            ->suffix('%')
                            ->helperText('Desconto em porcentagem (máximo 50%)')
                            ->default(0),
                        Forms\Components\DatePicker::make('valid_until')
                            ->label('Validade')
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->minDate(now())
                            ->helperText('Data de validade do cupom (opcional)'),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Status')
                            ->helperText('Cupom ativo ou inativo')
                            ->default(true),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Código')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Código copiado!')
                    ->copyMessageDuration(1500),
                Tables\Columns\TextColumn::make('description')
                    ->label('Descrição')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->description)
                    ->toggleable(),
                Tables\Columns\TextColumn::make('discount_percentage')
                    ->label('Desconto')
                    ->suffix('%')
                    ->sortable()
                    ->badge()
                    ->color(fn ($state) => match (true) {
                        $state >= 30 => 'success',
                        $state >= 15 => 'warning',
                        default => 'info',
                    }),
                Tables\Columns\TextColumn::make('valid_until')
                    ->label('Validade')
                    ->date('d/m/Y')
                    ->sortable()
                    ->color(fn ($record) => {
                        if (!$record->valid_until) {
                            return null;
                        }
                        return $record->valid_until->isPast() ? 'danger' : ($record->valid_until->isToday() ? 'warning' : null);
                    })
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state ? $state->format('d/m/Y') : 'Sem validade'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status')
                    ->placeholder('Todos')
                    ->trueLabel('Apenas ativos')
                    ->falseLabel('Apenas inativos'),
                Tables\Filters\Filter::make('valid_until')
                    ->form([
                        Forms\Components\DatePicker::make('valid_from')
                            ->label('Válido a partir de'),
                        Forms\Components\DatePicker::make('valid_until')
                            ->label('Válido até'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when(
                                $data['valid_from'],
                                fn ($query, $date) => $query->whereDate('valid_until', '>=', $date)
                            )
                            ->when(
                                $data['valid_until'],
                                fn ($query, $date) => $query->whereDate('valid_until', '<=', $date)
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListCoupons::route('/'),
            'create' => Pages\CreateCoupon::route('/create'),
            'edit' => Pages\EditCoupon::route('/{record}/edit'),
        ];
    }
}
