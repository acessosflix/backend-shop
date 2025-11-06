<?php

namespace App\Filament\Resources;

use App\Enums\LeadStatus;
use App\Filament\Resources\LeadResource\Pages;
use App\Models\Client;
use App\Models\Lead;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;

class LeadResource extends Resource
{
    protected static ?string $model = Lead::class;

    protected static ?string $navigationIcon = 'heroicon-o-phone-arrow-down-left';
    protected static ?string $modelLabel = 'Lead';
    protected static ?string $pluralModelLabel = 'Leads';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->label('Nome')->required(),
                Forms\Components\TextInput::make('email')->label('E-mail')->email(),
                Forms\Components\TextInput::make('phone')->label('Telefone'),
                Forms\Components\TextInput::make('source')->label('Origem'),
                Forms\Components\Select::make('status')->label('Status')->options(LeadStatus::class)->required()->default(LeadStatus::New),
                Forms\Components\TextInput::make('value_estimate')->label('Valor Estimado')->numeric()->prefix('R$'),
                Forms\Components\Textarea::make('notes')->label('Notas')->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nome')->searchable(),
                Tables\Columns\TextColumn::make('email')->label('E-mail'),
                Tables\Columns\TextColumn::make('status')->label('Status')->badge(),
                Tables\Columns\TextColumn::make('value_estimate')->label('Valor Estimado')->money('BRL'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Action::make('convertToClient')
                    ->label('Converter em Cliente')
                    ->icon('heroicon-o-user-plus')
                    ->action(function(Lead $record) {
                        $client = Client::create([
                            'company_name' => $record->name,
                            'email' => $record->email,
                            'phone' => $record->phone,
                        ]);
                        $record->status = LeadStatus::Converted;
                        $record->save();

                        Notification::make()
                            ->title('Lead convertido!')
                            ->body("Cliente '{$client->company_name}' criado com sucesso.")
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation()
                    ->visible(fn(Lead $record) => $record->status !== LeadStatus::Converted)
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
            'index' => Pages\ListLeads::route('/'),
            'create' => Pages\CreateLead::route('/create'),
            'edit' => Pages\EditLead::route('/{record}/edit'),
        ];
    }
}