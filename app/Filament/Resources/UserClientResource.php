<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserClientResource\Pages;
use App\Filament\Resources\UserClientResource\RelationManagers;
use App\Models\User;
use App\Models\UserClient;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class UserClientResource extends Resource
{
    protected static ?string $model = UserClient::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $modelLabel = 'Cliente';
    protected static ?string $pluralModelLabel = 'Clientes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Usuário')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Usuário')
                            ->relationship('user', 'name')
                            ->searchable(['name', 'email'])
                            ->preload()
                            ->required()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nome')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('email')
                                    ->label('E-mail')
                                    ->email()
                                    ->required()
                                    ->unique('users', 'email')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('password')
                                    ->label('Senha')
                                    ->password()
                                    ->required()
                                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('phone')
                                    ->label('Telefone')
                                    ->tel()
                                    ->maxLength(20),
                            ])
                            ->columnSpanFull(),
                    ]),
                Forms\Components\Section::make('Endereço')
                    ->schema([
                        Forms\Components\Textarea::make('address')
                            ->label('Endereço')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('city')
                            ->label('Cidade')
                            ->maxLength(100),
                        Forms\Components\TextInput::make('state')
                            ->label('Estado')
                            ->maxLength(100),
                        Forms\Components\TextInput::make('zipcode')
                            ->label('CEP')
                            ->maxLength(20),
                    ])
                    ->columns(3),
                Forms\Components\Section::make('Avatar')
                    ->schema([
                        Forms\Components\FileUpload::make('avatar')
                            ->label('Avatar')
                            ->image()
                            ->directory('avatars')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nome')
                    ->searchable(['user.name', 'user.email'])
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.email')
                    ->label('E-mail')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.phone')
                    ->label('Telefone'),
                Tables\Columns\TextColumn::make('city')
                    ->label('Cidade'),
                Tables\Columns\ImageColumn::make('avatar')
                    ->label('Avatar')
                    ->circular(),
                Tables\Columns\TextColumn::make('orders_count')
                    ->label('Pedidos')
                    ->counts('orders'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            RelationManagers\OrdersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUserClients::route('/'),
            'create' => Pages\CreateUserClient::route('/create'),
            'view' => Pages\ViewUserClient::route('/{record}'),
            'edit' => Pages\EditUserClient::route('/{record}/edit'),
        ];
    }
}
