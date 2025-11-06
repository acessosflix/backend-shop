<?php

namespace App\Services;

use App\Models\Invoice;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class C6Service
{
    protected string $apiKey;
    protected string $apiUrl;

    public function __construct()
    {
        $this->apiKey = config('services.c6.api_key');
        // $this->apiUrl = config('services.c6.api_url');
    }

    /**
     * Simula a criação de uma cobrança no C6 Bank.
     * Em um cenário real, faria uma requisição HTTP para a API do C6.
     */
    public function createCharge(Invoice $invoice): array
    {
        Log::info("Simulando criação de cobrança no C6 para a fatura #{$invoice->number}");
        
        // Simulação de chamada HTTP
        // $response = Http::withToken($this->apiKey)->post($this->apiUrl . '/charges', [
        //     'amount' => $invoice->total * 100, // em centavos
        //     'due_date' => $invoice->due_date->format('Y-m-d'),
        //     'customer_name' => $invoice->client->company_name,
        //     'customer_document' => $invoice->client->document,
        //     'reference_id' => $invoice->id,
        // ]);
        
        // if ($response->failed()) {
        //     Log::error("Falha ao criar cobrança no C6 para a fatura #{$invoice->number}");
        //     return ['success' => false, 'message' => 'Erro na API do C6'];
        // }

        $fakeTransactionId = 'c6_trx_' . uniqid();
        Log::info("Cobrança simulada com ID: {$fakeTransactionId}");

        return [
            'success' => true,
            'transaction_id' => $fakeTransactionId,
            'message' => 'Cobrança simulada com sucesso',
        ];
    }
}