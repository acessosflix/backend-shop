<?php

namespace App\Services\Payments\Gateways;

use App\Models\Order;
use App\Services\Payments\PaymentServiceInterface;

class ZellePaymentService implements PaymentServiceInterface
{
    public function createPayment(Order $order, float $amount, string $currency, string $callbackUrl): array
    {
        // Zelle is a manual payment method
        // The order is marked as pending and admin will confirm manually via Filament panel
        
        $order->update([
            'status' => 'pending',
            'payment_method' => 'zelle',
        ]);

        return [
            'success' => true,
            'payment_method' => 'zelle',
            'message' => 'Order created. Please complete Zelle payment and upload proof. Admin will confirm manually.',
            'order_id' => $order->id,
            'amount' => $amount,
            'currency' => $currency,
        ];
    }

    public function checkStatus(string $paymentId): array
    {
        // For Zelle, we check the order status directly
        $order = Order::find($paymentId);
        
        if (!$order) {
            return [
                'success' => false,
                'error' => 'Order not found',
            ];
        }

        return [
            'success' => true,
            'status' => $order->status,
            'order_id' => $order->id,
        ];
    }
}
