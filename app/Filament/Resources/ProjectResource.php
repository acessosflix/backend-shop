<?php

namespace App\Filament\Resources;

use App\Enums\ProjectModel;
use App\Enums\ProjectStatus;
use App\Filament\Resources\ProjectResource\Pages;
use App\Models\Project;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $modelLabel = 'Projeto';
    protected static ?string $pluralModelLabel = 'Projetos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('client_id')
                    ->relationship('client', 'company_name')
                    ->label('Cliente')
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->label('Nome do Projeto')
                    ->required(),
                Forms\Components\RichEditor::make('description')
                    ->label('Descrição')
                    ->columnSpanFull(),
                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options(ProjectStatus::class)
                    ->required(),
                Forms\Components\Select::make('model')
                    ->label('Modelo')
                    ->options([
                        'time_and_material' => 'Time & Material',
                        'escopo_fechado' => 'Escopo Fechado',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('hourly_rate')
                    ->label('Valor/Hora (T&M)')
                    ->numeric()
                    ->prefix('R$'),
                Forms\Components\TextInput::make('fixed_price')
                    ->label('Preço Fixo (Escopo Fechado)')
                    ->numeric()
                    ->prefix('R$'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nome')->searchable(),
                Tables\Columns\TextColumn::make('client.company_name')->label('Cliente')->sortable(),
                Tables\Columns\BadgeColumn::make('status')->label('Status'),
                Tables\Columns\TextColumn::make('model')->label('Modelo')->formatStateUsing(fn (string $state): string => $state === 'time_and_material' ? 'Time & Material' : 'Escopo Fechado'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options(ProjectStatus::class),
                Tables\Filters\SelectFilter::make('client_id')->label('Cliente')->relationship('client', 'company_name'),
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
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}