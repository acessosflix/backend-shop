<?php

namespace App\Services\Payments;

use App\Models\Order;

interface PaymentServiceInterface
{
    /**
     * Create a payment for an order
     *
     * @param Order $order
     * @param float $amount
     * @param string $currency
     * @param string $callbackUrl
     * @return array
     */
    public function createPayment(Order $order, float $amount, string $currency, string $callbackUrl): array;

    /**
     * Check the status of a payment
     *
     * @param string $paymentId
     * @return array
     */
    public function checkStatus(string $paymentId): array;
}
