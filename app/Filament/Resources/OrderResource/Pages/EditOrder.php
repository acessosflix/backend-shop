<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Mail\OrderApprovedMailable;
use App\Mail\OrderShippedMailable;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected ?string $originalStatus = null;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Armazenar o status original antes de salvar
        $this->originalStatus = $this->record->status;
        
        return $data;
    }

    protected function afterSave(): void
    {
        $order = $this->record->fresh();
        $originalStatus = $this->originalStatus;
        $newStatus = $order->status;

        // Enviar email quando o pedido é aprovado (status muda para 'paid')
        if ($originalStatus !== 'paid' && $newStatus === 'paid') {
            try {
                Mail::to($order->userClient->email)->send(new OrderApprovedMailable($order));
            } catch (\Exception $e) {
                Log::error('Failed to send order approval email', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // Enviar email quando o pedido é despachado (status muda para 'shipped')
        if ($originalStatus !== 'shipped' && $newStatus === 'shipped') {
            try {
                Mail::to($order->userClient->email)->send(new OrderShippedMailable($order));
            } catch (\Exception $e) {
                Log::error('Failed to send order shipped email', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }
}
