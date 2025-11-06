<?php

namespace App\Http\Controllers;

use App\Enums\InvoiceStatus;
use App\Mail\OrderApprovedMailable;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class WebhookController extends Controller
{
    public function handleC6(Request $request)
    {
        // Ponto Chave: Validar o webhook
        // Em um cenário real, você validaria a assinatura do webhook aqui
        // usando o `webhook_secret` para garantir que a requisição é legítima.
        $webhookSecret = config('services.c6.webhook_secret');
        // $signature = $request->header('X-C6-Signature');
        // if (!hash_equals($signature, ...)) {
        //     return response()->json(['error' => 'Invalid signature'], 403);
        // }

        $payload = $request->all();
        $ipAddress = $request->ip();
        
        Log::info('C6 Webhook Received:', $payload);

        // Salvar log do webhook
        PaymentLog::create([
            'order_id' => $payload['order_id'] ?? null,
            'gateway' => 'c6',
            'ip_address' => $ipAddress,
            'payload' => $payload,
        ]);

        // Simulação: o payload contém o ID da fatura e o status
        $invoiceId = $payload['invoice_id'] ?? null;
        $orderId = $payload['order_id'] ?? null;
        $status = $payload['status'] ?? null;

        if ($invoiceId && $status === 'paid') {
            $invoice = Invoice::find($invoiceId);

            if ($invoice && $invoice->status !== InvoiceStatus::Paid) {
                // Ponto Chave: Atualizar fatura e criar pagamento
                $invoice->status = InvoiceStatus::Paid;
                $invoice->save();

                Payment::create([
                    'invoice_id' => $invoice->id,
                    'amount' => $payload['amount'] ?? $invoice->total,
                    'paid_at' => now(),
                    'method' => 'c6_webhook',
                    'transaction_id' => $payload['transaction_id'] ?? null,
                    'status' => 'confirmed',
                ]);
                
                Log::info("Invoice #{$invoiceId} marked as paid via C6 webhook.");
            }
        }

        // Processar webhook para pedidos (orders)
        if ($orderId && $status === 'paid') {
            $order = Order::find($orderId);

            if ($order && $order->status !== 'paid') {
                $order->status = 'paid';
                $order->save();

                // Enviar email de aprovação
                try {
                    Mail::to($order->userClient->email)->send(new OrderApprovedMailable($order));
                } catch (\Exception $e) {
                    Log::error('Failed to send order approval email', [
                        'order_id' => $orderId,
                        'error' => $e->getMessage(),
                    ]);
                }

                Log::info("Order #{$orderId} marked as paid via C6 webhook.");
            }
        }

        return response()->json(['status' => 'success']);
    }

    public function handleCrypto(Request $request)
    {
        $payload = $request->all();
        $ipAddress = $request->ip();
        
        Log::info('Crypto Webhook Received:', $payload);

        // Salvar log do webhook
        $orderId = $payload['order_id'] ?? null;
        
        PaymentLog::create([
            'order_id' => $orderId,
            'gateway' => 'crypto',
            'ip_address' => $ipAddress,
            'payload' => $payload,
        ]);

        // Processar webhook do CryptoNowPayments
        if ($orderId) {
            $order = Order::find($orderId);
            $paymentStatus = $payload['payment_status'] ?? null;

            if ($order && $paymentStatus === 'finished' && $order->status !== 'paid') {
                $order->status = 'paid';
                $order->save();

                // Enviar email de aprovação
                try {
                    Mail::to($order->userClient->email)->send(new OrderApprovedMailable($order));
                } catch (\Exception $e) {
                    Log::error('Failed to send order approval email', [
                        'order_id' => $orderId,
                        'error' => $e->getMessage(),
                    ]);
                }

                Log::info("Order #{$orderId} marked as paid via Crypto webhook.");
            }
        }

        return response()->json(['status' => 'success']);
    }
}