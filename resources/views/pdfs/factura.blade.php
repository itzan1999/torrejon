<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <style>
    body { font-family: Arial; }
    .header { text-align: center; margin-bottom: 30px; }
    .datos { margin-bottom: 20px; }
    table { width: 100%; border-collapse: collapse; margin: 20px 0; }
    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
    th { background-color: #f2f2f2; }
  </style>
</head>
<body>
  <div class="header">
    <h1>FACTURA</h1>
    <p>Número: {{ $pedido->codigo }}</p>
    <p>Fecha: {{ $pedido->created_at->format('d/m/Y') }}</p>
  </div>

  <div class="datos">
    <h3>Datos del Cliente</h3>
    <p><strong>Nombre:</strong> {{ $pedido->usuario->cuenta->nombre }} {{ $pedido->usuario->cuenta->apellidos }}</p>
    <p><strong>Email:</strong> {{ $pedido->usuario->email }}</p>
    <p><strong>Dirección:</strong> {{ $pedido->usuario->direccion }}</p>
  </div>

  <table>
    <thead>
      <tr>
        <th>Producto</th>
        <th>Cantidad</th>
        <th>Precio Unitario</th>
        <th>Descuento</th>
        <th>Subtotal</th>
      </tr>
    </thead>
    <tbody>
      @foreach($pedido->pedidoProductos as $item)
      <tr>
        <td>{{ $item->producto->nombre }}</td>
        <td>{{ $item->cantidad }}</td>
        <td>{{ number_format($item->precio, 2) }}€</td>
        <td>{{ $item->producto->oferta }}%</td>
        <td>{{ number_format((1 - ($item->producto->oferta / 100)) * $item->precio * $item->cantidad, 2) }}€</td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <div style="text-align: right; font-size: 16px; font-weight: bold;">
    <p>TOTAL: {{ number_format($pedido->precio_total, 2) }}€</p>
  </div>
</body>
</html>