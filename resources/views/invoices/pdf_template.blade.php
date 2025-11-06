<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Fatura #{{ $invoice->number }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #333; }
        .container { width: 100%; margin: 0 auto; padding: 20px; }
        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 40px; }
        .header h1 { font-size: 24px; color: #000; margin: 0; }
        .header .company-logo { font-size: 36px; font-weight: bold; color: #f59e0b; }
        .invoice-details, .client-details { width: 48%; }
        .invoice-details { text-align: right; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .total { text-align: right; margin-top: 20px; font-size: 1.2em; font-weight: bold; }
        .footer { margin-top: 40px; text-align: center; font-size: 0.9em; color: #777; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="company-logo">
                Edfy IT
            </div>
            <div class="invoice-details">
                <h2>Fatura #{{ $invoice->number }}</h2>
                <p>Data de Emissão: {{ now()->format('d/m/Y') }}</p>
                <p>Data de Vencimento: {{ $invoice->due_date->format('d/m/Y') }}</p>
            </div>
        </div>

        <div class="client-details">
            <h3>Para:</h3>
            <p><strong>{{ $invoice->client->company_name }}</strong></p>
            <p>{{ $invoice->client->document }}</p>
            <p>{{ $invoice->client->email }}</p>
            <p>{{ $invoice->client->phone }}</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Descrição</th>
                    <th>Valor</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        Serviços prestados referentes ao projeto "{{ $invoice->project->name }}".
                        @if($invoice->notes)
                            <br><br><strong>Observações:</strong><br>
                            {!! nl2br(e($invoice->notes)) !!}
                        @endif
                    </td>
                    <td>R$ {{ number_format($invoice->total, 2, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <div class="total">
            Total a Pagar: R$ {{ number_format($invoice->total, 2, ',', '.') }}
        </div>

        <div class="footer">
            <p>Obrigado por sua parceria.</p>
            <p>Edfy IT - Soluções em Software</p>
        </div>
    </div>
</body>
</html>