<?php

namespace App\Services\Payments\Gateways;

use App\Models\Order;
use App\Services\Payments\PaymentServiceInterface;

class CardGatewayPlaceholder implements PaymentServiceInterface
{
    public function createPayment(Order $order, float $amount, string $currency, string $callbackUrl): array
    {
        // Placeholder for future card payment implementation
        return [
            'success' => false,
            'error' => 'Card payment gateway not yet implemented',
            'message' => 'This payment method is not available yet.',
        ];
    }

    public function checkStatus(string $paymentId): array
    {
        return [
            'success' => false,
            'error' => 'Card payment gateway not yet implemented',
        ];
    }
}
