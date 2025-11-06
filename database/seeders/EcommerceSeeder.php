<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Banner;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EcommerceSeeder extends Seeder
{
    public function run(): void
    {
        // Create Categories
        $electronics = Category::create([
            'name' => 'Eletrônicos',
            'slug' => 'eletronicos',
            'description' => 'Produtos eletrônicos e tecnologia',
        ]);

        $clothing = Category::create([
            'name' => 'Roupas',
            'slug' => 'roupas',
            'description' => 'Roupas e acessórios',
        ]);

        $books = Category::create([
            'name' => 'Livros',
            'slug' => 'livros',
            'description' => 'Livros e materiais de leitura',
        ]);

        // Create Products
        Product::create([
            'category_id' => $electronics->id,
            'name' => 'Smartphone XYZ',
            'slug' => 'smartphone-xyz',
            'description' => 'Smartphone com tela de 6.5 polegadas, 128GB de armazenamento e câmera tripla.',
            'price' => 599.99,
            'stock' => 50,
            'status' => 'active',
        ]);

        Product::create([
            'category_id' => $electronics->id,
            'name' => 'Notebook Pro',
            'slug' => 'notebook-pro',
            'description' => 'Notebook com processador Intel i7, 16GB RAM e SSD de 512GB.',
            'price' => 1299.99,
            'stock' => 25,
            'status' => 'active',
        ]);

        Product::create([
            'category_id' => $clothing->id,
            'name' => 'Camiseta Básica',
            'slug' => 'camiseta-basica',
            'description' => 'Camiseta 100% algodão, disponível em várias cores.',
            'price' => 29.99,
            'stock' => 100,
            'status' => 'active',
        ]);

        Product::create([
            'category_id' => $clothing->id,
            'name' => 'Tênis Esportivo',
            'slug' => 'tenis-esportivo',
            'description' => 'Tênis confortável para corrida e caminhada.',
            'price' => 89.99,
            'stock' => 75,
            'status' => 'active',
        ]);

        Product::create([
            'category_id' => $books->id,
            'name' => 'Livro de Programação',
            'slug' => 'livro-programacao',
            'description' => 'Guia completo de programação em PHP e Laravel.',
            'price' => 49.99,
            'stock' => 30,
            'status' => 'active',
        ]);

        Product::create([
            'category_id' => $books->id,
            'name' => 'Romance Best Seller',
            'slug' => 'romance-best-seller',
            'description' => 'Romance emocionante que está conquistando leitores.',
            'price' => 24.99,
            'stock' => 60,
            'status' => 'active',
        ]);

        // Create Banners
        Banner::create([
            'title' => 'Promoção de Verão',
            'image' => 'banners/summer-sale.jpg',
            'link' => '/products?category_id=' . $clothing->id,
            'active' => true,
        ]);

        Banner::create([
            'title' => 'Novos Eletrônicos',
            'image' => 'banners/new-electronics.jpg',
            'link' => '/products?category_id=' . $electronics->id,
            'active' => true,
        ]);

        Banner::create([
            'title' => 'Ofertas Especiais',
            'image' => 'banners/special-offers.jpg',
            'link' => '/products',
            'active' => true,
        ]);

        // Create Settings
        Setting::create([
            'key' => 'store_name',
            'value' => 'Minha Loja Online',
        ]);

        Setting::create([
            'key' => 'store_email',
            'value' => 'contato@minhaloja.com',
        ]);

        Setting::create([
            'key' => 'store_phone',
            'value' => '+55 (11) 99999-9999',
        ]);

        Setting::create([
            'key' => 'store_address',
            'value' => 'Rua Exemplo, 123 - São Paulo, SP',
        ]);

        Setting::create([
            'key' => 'currency',
            'value' => 'USD',
        ]);

        Setting::create([
            'key' => 'zelle_email',
            'value' => 'pagamentos@minhaloja.com',
        ]);
    }
}
