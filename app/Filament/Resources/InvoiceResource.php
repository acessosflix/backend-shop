<?php

namespace App\Filament\Resources;

use App\Enums\InvoiceStatus;
use App\Filament\Resources\InvoiceResource\Pages;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Project;
use App\Services\InvoicePdfService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvoiceMailable;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Number;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $modelLabel = 'Fatura';
    protected static ?string $pluralModelLabel = 'Faturas';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('project_id')
                    ->label('Projeto')
                    ->options(Project::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('client_id')
                    ->label('Cliente')
                    ->options(Client::all()->pluck('company_name', 'id'))
                    ->searchable()
                    ->required(),
                Forms\Components\TextInput::make('number')
                    ->label('Número')
                    ->required()
                    ->maxLength(255)
                    ->default('INV-' . date('Ymd') . '-' . random_int(100, 999)),
                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options(InvoiceStatus::class)
                    ->required()
                    ->default(InvoiceStatus::Draft),
                Forms\Components\DatePicker::make('due_date')
                    ->label('Data de Vencimento')
                    ->required(),
                Forms\Components\TextInput::make('total')
                    ->label('Total (BRL)')
                    ->required()
                    ->numeric()
                    ->prefix('R$'),
                Forms\Components\Textarea::make('notes')
                    ->label('Observações')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('pdf_path')
                    ->label('Caminho do PDF')
                    ->readOnly(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('number')->label('Número')->searchable(),
                Tables\Columns\TextColumn::make('client.company_name')->label('Cliente')->sortable(),
                Tables\Columns\TextColumn::make('project.name')->label('Projeto')->sortable(),
                Tables\Columns\BadgeColumn::make('status')->label('Status'),
                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->money('BRL')
                    ->sortable(),
                Tables\Columns\TextColumn::make('due_date')->label('Vencimento')->date('d/m/Y')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options(InvoiceStatus::class),
                Tables\Filters\SelectFilter::make('client_id')
                    ->label('Cliente')
                    ->relationship('client', 'company_name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Action::make('generatePdf')
                    ->label('Gerar PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->action(function (Invoice $record, InvoicePdfService $pdfService) {
                        $path = $pdfService->generate($record);
                        $record->update(['pdf_path' => $path]);
                        Notification::make()->title('PDF gerado com sucesso!')->success()->send();
                        return Storage::disk('public')->download($path);
                    }),
                Action::make('sendEmail')
                    ->label('Enviar E-mail')
                    ->icon('heroicon-o-envelope')
                    ->action(function (Invoice $record) {
                        if (empty($record->pdf_path) || !Storage::disk('public')->exists($record->pdf_path)) {
                            Notification::make()->title('PDF não encontrado. Gere o PDF primeiro.')->danger()->send();
                            return;
                        }
                        Mail::to($record->client->email)->send(new InvoiceMailable($record));
                        $record->update(['status' => InvoiceStatus::Sent]);
                        Notification::make()->title('E-mail enviado para o cliente.')->success()->send();
                    })
                    ->requiresConfirmation(),
                Action::make('markAsPaid')
                    ->label('Marcar como Paga')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(function (Invoice $record) {
                        $record->update(['status' => InvoiceStatus::Paid]);
                        Payment::create([
                            'invoice_id' => $record->id,
                            'amount' => $record->total,
                            'paid_at' => now(),
                            'method' => 'manual', // ou outro método
                            'status' => 'confirmed',
                        ]);
                        Notification::make()->title('Fatura marcada como paga!')->success()->send();
                    })
                    ->requiresConfirmation()
                    ->visible(fn (Invoice $record) => $record->status !== InvoiceStatus::Paid),
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
            'index' => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
        ];
    }
}
