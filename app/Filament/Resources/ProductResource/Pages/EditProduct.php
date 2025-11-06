<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $this->updateImageOrder();
    }

    protected function updateImageOrder(): void
    {
        $product = $this->record;
        $images = $product->images()->orderBy('id')->get();
        
        foreach ($images as $index => $image) {
            $image->update(['order' => $index]);
        }
    }
}
