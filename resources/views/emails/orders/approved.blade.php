<x-mail::message>
# Olá, {{ $clientName }}!

Seu pedido **#{{ $orderId }}** foi aprovado e está sendo processado!

## Detalhes do Pedido

- **Pedido:** #{{ $orderId }}
- **Valor Total:** ${{ number_format($totalAmount, 2, ',', '.') }}
- **Status:** Pago e em processamento

Em breve você receberá mais informações sobre o envio do seu pedido.

Qualquer dúvida, estamos à disposição.

Obrigado,<br>
**Edfy IT**
</x-mail::message>
