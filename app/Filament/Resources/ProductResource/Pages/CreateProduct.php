<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function afterCreate(): void
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
