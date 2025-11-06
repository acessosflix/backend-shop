<?php

namespace App\Services\Payments\Gateways;

use App\Models\Order;
use App\Services\Payments\PaymentServiceInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CryptoNowPaymentsService implements PaymentServiceInterface
{
    private string $apiKey;
    private string $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.nowpayments.api_key');
        $this->baseUrl = config('services.nowpayments.base_url', 'https://api.nowpayments.io/v1');
    }

    public function createPayment(Order $order, float $amount, string $currency, string $callbackUrl): array
    {
        try {
            $response = Http::withHeaders([
                'x-api-key' => $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}/payment", [
                'price_amount' => $amount,
                'price_currency' => $currency,
                'pay_currency' => $currency,
                'order_id' => $order->id,
                'order_description' => "Order #{$order->id}",
                'ipn_callback_url' => $callbackUrl,
                'success_url' => $callbackUrl . '?status=success',
                'cancel_url' => $callbackUrl . '?status=cancel',
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                $order->update([
                    'transaction_id' => $data['payment_id'] ?? null,
                    'status' => 'pending',
                ]);

                return [
                    'success' => true,
                    'payment_id' => $data['payment_id'] ?? null,
                    'payment_url' => $data['invoice_url'] ?? null,
                    'pay_address' => $data['pay_address'] ?? null,
                    'pay_amount' => $data['pay_amount'] ?? null,
                    'pay_currency' => $data['pay_currency'] ?? null,
                ];
            }

            Log::error('NowPayments API Error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return [
                'success' => false,
                'error' => 'Failed to create payment',
                'message' => $response->json('message', 'Unknown error'),
            ];
        } catch (\Exception $e) {
            Log::error('NowPayments Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'error' => 'Payment service error',
                'message' => $e->getMessage(),
            ];
        }
    }

    public function checkStatus(string $paymentId): array
    {
        try {
            $response = Http::withHeaders([
                'x-api-key' => $this->apiKey,
            ])->get("{$this->baseUrl}/payment/{$paymentId}");

            if ($response->successful()) {
                $data = $response->json();
                
                return [
                    'success' => true,
                    'status' => $data['payment_status'] ?? 'unknown',
                    'data' => $data,
                ];
            }

            return [
                'success' => false,
                'error' => 'Failed to check payment status',
                'message' => $response->json('message', 'Unknown error'),
            ];
        } catch (\Exception $e) {
            Log::error('NowPayments Status Check Exception', [
                'message' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => 'Payment status check error',
                'message' => $e->getMessage(),
            ];
        }
    }
}
