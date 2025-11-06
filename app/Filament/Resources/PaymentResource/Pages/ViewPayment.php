<?php

namespace App\Filament\Resources\PaymentResource\Pages;

use App\Filament\Resources\PaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPayment extends ViewRecord
{
    protected static string $resource = PaymentResource::class;

    // Como é um recurso de visualização, não são necessárias ações de edição no header.
    protected function getHeaderActions(): array
    {
        return [];
    }
}
