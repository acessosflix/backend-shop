<?php

namespace App\Filament\Resources\UserClientResource\Pages;

use App\Filament\Resources\UserClientResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class ViewUserClient extends ViewRecord
{
    protected static string $resource = UserClientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Dados Básicos')
                    ->schema([
                        Infolists\Components\TextEntry::make('user.name')
                            ->label('Nome'),
                        Infolists\Components\TextEntry::make('user.email')
                            ->label('E-mail'),
                        Infolists\Components\TextEntry::make('user.phone')
                            ->label('Telefone'),
                    ])
                    ->columns(3),
                Infolists\Components\Section::make('Endereço')
                    ->schema([
                        Infolists\Components\TextEntry::make('address')
                            ->label('Endereço')
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('city')
                            ->label('Cidade'),
                        Infolists\Components\TextEntry::make('state')
                            ->label('Estado'),
                        Infolists\Components\TextEntry::make('zipcode')
                            ->label('CEP'),
                    ])
                    ->columns(3),
                Infolists\Components\Section::make('Avatar')
                    ->schema([
                        Infolists\Components\ImageEntry::make('avatar')
                            ->label('Avatar')
                            ->circular(),
                    ]),
                Infolists\Components\Section::make('Informações Adicionais')
                    ->schema([
                        Infolists\Components\TextEntry::make('orders_count')
                            ->label('Total de Pedidos')
                            ->state(fn ($record) => $record->orders()->count()),
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Cadastrado em')
                            ->dateTime('d/m/Y H:i'),
                        Infolists\Components\TextEntry::make('updated_at')
                            ->label('Atualizado em')
                            ->dateTime('d/m/Y H:i'),
                    ])
                    ->columns(3),
            ]);
    }
}
