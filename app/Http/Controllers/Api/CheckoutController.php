<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\UserClient;
use App\Services\Payments\Gateways\CryptoNowPaymentsService;
use App\Services\Payments\Gateways\ZellePaymentService;
use App\Services\Payments\Gateways\CardGatewayPlaceholder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|in:crypto,zelle,card',
            'zelle_reference' => 'nullable|string|required_if:payment_method,zelle',
            'proof_image_url' => 'nullable|url|required_if:payment_method,zelle',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = auth('api')->user();
            
            // Buscar ou criar UserClient para o usuário autenticado
            $userClient = $user->userClient;
            if (!$userClient) {
                // Criar UserClient se não existir
                $userClient = UserClient::create([
                    'user_id' => $user->id,
                ]);
            }

            DB::beginTransaction();

            $totalAmount = 0;
            $orderItems = [];

            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);
                
                if ($product->stock < $item['quantity']) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => "Insufficient stock for product: {$product->name}"
                    ], 400);
                }

                $itemTotal = $product->price * $item['quantity'];
                $totalAmount += $itemTotal;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                ];

                // Update stock
                $product->decrement('stock', $item['quantity']);
            }

            $order = Order::create([
                'user_client_id' => $userClient->id,
                'total_amount' => $totalAmount,
                'payment_method' => $request->payment_method,
                'status' => 'pending',
                'zelle_reference' => $request->zelle_reference,
                'proof_image_url' => $request->proof_image_url,
            ]);

            foreach ($orderItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }

            // Process payment based on method
            $paymentService = $this->getPaymentService($request->payment_method);
            $callbackUrl = url('/api/v1/payment/callback');
            
            $paymentResult = $paymentService->createPayment(
                $order,
                $totalAmount,
                'USD', // Default currency, can be made configurable
                $callbackUrl
            );

            if (!$paymentResult['success']) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => $paymentResult['message'] ?? 'Payment processing failed',
                    'error' => $paymentResult['error'] ?? null,
                ], 400);
            }

            DB::commit();

            $order->load(['orderItems.product']);

            return response()->json([
                'success' => true,
                'message' => 'Order created successfully',
                'data' => [
                    'order' => $order,
                    'payment' => $paymentResult,
                ],
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to process checkout',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function getPaymentService(string $method)
    {
        return match($method) {
            'crypto' => new CryptoNowPaymentsService(),
            'zelle' => new ZellePaymentService(),
            'card' => new CardGatewayPlaceholder(),
            default => new ZellePaymentService(),
        };
    }
}