@component('mail::message')

<div style="text-align: center; margin-bottom: 20px;">
    <img src="cid:logo" alt="Logo Leche El Torrejon" style="max-width: 200px;">
</div>

<h2>Factura de tu Pedido</h2>
<p>Estimado {{ $pedido->usuario->cuenta->nombre . ' ' . $pedido->usuario->cuenta->apellidos }},</p>
<p>Adjuntamos la factura de tu pedido #{{ $pedido->codigo }}.</p>
<p>Total: {{ number_format($pedido->precio_total, 2) }}€</p>

<p>Gracias por confiar en nosotros.</p>
<p>Saludos,<br>{{ config('app.name') }}</p>
@endcomponent