<?php

namespace App\Filament\Resources;

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use App\Filament\Resources\TicketResource\Pages;
use App\Models\Ticket;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static ?string $navigationIcon = 'heroicon-o-lifebuoy';
    protected static ?string $modelLabel = 'Ticket de Suporte';
    protected static ?string $pluralModelLabel = 'Tickets de Suporte';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('project_id')->relationship('project', 'name')->required(),
                Forms\Components\Select::make('client_id')->relationship('client', 'company_name')->required(),
                Forms\Components\TextInput::make('subject')->label('Assunto')->required(),
                Forms\Components\Select::make('status')->options(TicketStatus::class)->required()->default(TicketStatus::Open),
                Forms\Components\Select::make('priority')->options(TicketPriority::class)->required()->default(TicketPriority::Low),
                Forms\Components\RichEditor::make('description')->label('Descrição')->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('subject')->label('Assunto'),
                Tables\Columns\TextColumn::make('project.name')->label('Projeto'),
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\TextColumn::make('priority')->badge(),
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTickets::route('/'),
            'create' => Pages\CreateTicket::route('/create'),
            'edit' => Pages\EditTicket::route('/{record}/edit'),
        ];
    }
}