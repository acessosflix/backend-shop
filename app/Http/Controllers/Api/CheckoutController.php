<?php

// app/Http/Controllers/Api/CheckoutController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CheckoutRequest;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function store(CheckoutRequest $request)
    {
        DB::beginTransaction();

        try {
            // Encontra ou cria o usuário
            $user = User::firstOrCreate(
                ['email' => $request->email],
                ['name' => $request->name, 'password' => bcrypt(Str::random(16))]
            );

            // Calcula o total
            $totalAmount = 0;
            $productIds = collect($request->products)->pluck('id');
            $productsInDb = Product::findMany($productIds);

            foreach ($request->products as $productData) {
                $product = $productsInDb->find($productData['id']);
                $totalAmount += $product->price * $productData['quantity'];
            }

            // Cria o pedido
            $order = Order::create([
                'user_id' => $user->id,
                'customer_name' => $request->name,
                'customer_email' => $request->email,
                'total_amount' => $totalAmount,
                'status' => 'pending', // Será 'paid' após pagamento
            ]);
            
            // Anexa os produtos ao pedido
            foreach ($request->products as $productData) {
                $product = $productsInDb->find($productData['id']);
                $order->products()->attach($product->id, [
                    'quantity' => $productData['quantity'],
                    'price' => $product->price,
                ]);
            }

            DB::commit();

            // Aqui você integraria com um gateway de pagamento (Stripe, PagSeguro, etc.)
            // e retornaria a URL de pagamento ou os dados do PIX.

            return response()->json([
                'message' => 'Pedido criado com sucesso!',
                'order_id' => $order->id,
                // 'payment_url' => '...url_do_gateway...'
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Ocorreu um erro ao processar o pedido.', 'error' => $e->getMessage()], 500);
        }
    }
}