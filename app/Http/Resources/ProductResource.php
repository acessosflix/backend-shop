<?php

// app/Http/Resources/ProductResource.php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // Retorna os dados exatamente como você precisa
        return [
            'id' => $this->id,
            'title' => $this->title,
            'shortDescription' => $this->short_description,
            'longDescription' => $this->long_description,
            'price' => $this->price,
            'originalPrice' => $this->original_price,
            'features' => $this->features,
            'image' => $this->image ? url('storage/' . $this->image) : null, // Link completo da imagem
            'demoUrl' => $this->demo_url,
            'fullDescription' => $this->full_description,
            'galleryImages' => collect($this->gallery_images)->map(fn ($img) => url('storage/' . $img))->all(),
            'videoUrl' => $this->video_url,
            'techDetails' => $this->tech_details,
            'softwareFramework' => $this->software_framework,
            'developedWith' => $this->developed_with,
            'total_sold' => $this->total_sold,
        ];
    }
}