<?php

namespace App\Services;

use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class InvoicePdfService
{
    /**
     * Gera o PDF de uma fatura e o salva no storage.
     *
     * @param Invoice $invoice
     * @return string O caminho do arquivo salvo.
     */
    public function generate(Invoice $invoice): string
    {
        // Ponto Chave: Geração do PDF usando DOMPDF
        // Carrega a view 'invoices.pdf_template' com os dados da fatura.
        $pdf = Pdf::loadView('invoices.pdf_template', ['invoice' => $invoice]);
        
        // Define o nome do arquivo e o caminho para salvar
        $filename = 'invoices/fatura-' . $invoice->number . '.pdf';

        // Salva o conteúdo do PDF no disco 'public'
        Storage::disk('public')->put($filename, $pdf->output());

        return $filename;
    }
}
