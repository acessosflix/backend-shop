<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\InvoiceResource;
use App\Models\Invoice;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestInvoices extends BaseWidget
{
    protected static ?string $heading = 'Últimas Faturas';
    
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(InvoiceResource::getEloquentQuery()->latest()->limit(5))
            ->columns([
                Tables\Columns\TextColumn::make('number')->label('Número'),
                Tables\Columns\TextColumn::make('client.company_name')->label('Cliente'),
                Tables\Columns\BadgeColumn::make('status')->label('Status'),
                Tables\Columns\TextColumn::make('total')->money('BRL'),
            ]);
    }
}
