<?php

// app/Http/Controllers/Api/ProductController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource; // Vamos criar este resource
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Listar todos os produtos
    public function index()
    {
        return ProductResource::collection(Product::paginate(10));
    }

    // Mostrar um produto específico
    public function show(Product $product)
    {
        return new ProductResource($product);
    }
}