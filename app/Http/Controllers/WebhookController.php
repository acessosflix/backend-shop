<?php

namespace App\Http\Controllers;

use App\Enums\InvoiceStatus;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        Log::info('C6 Webhook Received:', $payload);

        // Simulação: o payload contém o ID da fatura e o status
        $invoiceId = $payload['invoice_id'] ?? null;
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

        return response()->json(['status' => 'success']);
    }
}