<x-mail::message>
# Olá, {{ $clientName }}!

Ótimas notícias! Seu pedido **#{{ $orderId }}** foi enviado e está a caminho!

## Informações de Rastreio

- **Pedido:** #{{ $orderId }}
@if($trackingCode)
- **Código de Rastreio:** {{ $trackingCode }}
@endif

Você pode acompanhar o status do seu pedido através do código de rastreio acima.

Qualquer dúvida, estamos à disposição.

Obrigado,<br>
**Edfy IT**
</x-mail::message>
