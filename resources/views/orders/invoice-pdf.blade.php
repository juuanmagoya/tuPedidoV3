<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura Pedido #{{ $order->id }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .empresa-info {
            float: left;
            width: 50%;
        }
        .factura-info {
            float: right;
            width: 40%;
            text-align: right;
        }
        .clear {
            clear: both;
        }
        .cliente-info {
            margin-bottom: 30px;
            padding: 15px;
            background: #f9f9f9;
            border-radius: 5px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .table th {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 10px;
            text-align: left;
            font-weight: bold;
        }
        .table td {
            border: 1px solid #dee2e6;
            padding: 10px;
        }
        .table-totals {
            width: 300px;
            margin-left: auto;
            margin-bottom: 30px;
        }
        .table-totals td {
            border: none;
            padding: 5px 10px;
        }
        .table-totals tr.total td {
            font-weight: bold;
            font-size: 14px;
            border-top: 2px solid #333;
        }
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .mb-4 {
            margin-bottom: 20px;
        }
        .mb-3 {
            margin-bottom: 15px;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
        }
        .status-pagado { background: #d4edda; color: #155724; }
        .status-pendiente { background: #fff3cd; color: #856404; }
        .status-cancelado { background: #f8d7da; color: #721c24; }
        .status-procesando { background: #ffeaa7; color: #5d4a00; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Encabezado -->
        <div class="header">
            <div class="empresa-info">
                <h1 style="margin: 0; color: #2c3e50;">{{ config('app.name', 'Mi Tienda') }}</h1>
                <p style="margin: 5px 0;">
                    Dirección de tu negocio<br>
                    Teléfono: (123) 456-7890<br>
                    Email: info@mitienda.com<br>
                    NIT: 123456789-0
                </p>
            </div>
            
            <div class="factura-info">
                <h2 style="margin: 0; color: #2c3e50;">FACTURA</h2>
                <p style="margin: 5px 0;">
                    <strong>N° Factura:</strong> {{ $order->id }}<br>
                    <strong>Fecha:</strong> {{ $order->created_at->format('d/m/Y') }}<br>
                    <strong>Hora:</strong> {{ $order->created_at->format('H:i') }}
                </p>
            </div>
            <div class="clear"></div>
        </div>

        <!-- Información del Cliente -->
        <div class="cliente-info">
            <h3 style="margin: 0 0 10px 0; color: #2c3e50;">INFORMACIÓN DEL CLIENTE</h3>
            <p style="margin: 5px 0;">
                <strong>Nombre:</strong> {{ $order->customer_name }}<br>
                @if($order->address)
                    <strong>Dirección:</strong> {{ $order->address }}<br>
                @endif
                <strong>Estado del Pedido:</strong> 
                <span class="status-badge status-{{ strtolower($order->status ?? 'pendiente') }}">
                    {{ ucfirst($order->status ?? 'Pendiente') }}
                </span>
            </p>
        </div>

        <!-- Detalles de Productos -->
        <h3 style="color: #2c3e50; margin-bottom: 15px;">DETALLES DEL PEDIDO</h3>
        <table class="table">
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th width="45%">PRODUCTO</th>
                    <th width="15%" class="text-right">CANTIDAD</th>
                    <th width="15%" class="text-right">PRECIO UNIT.</th>
                    <th width="20%" class="text-right">SUBTOTAL</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->products as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->product->name ?? 'Producto' }}</td>
                    <td class="text-right">{{ $item->quantity }}</td>
                    <td class="text-right">${{ number_format($item->unit_price, 2) }}</td>
                    <td class="text-right">${{ number_format($item->quantity * $item->unit_price, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totales -->
        <table class="table-totals">
            <tr>
                <td><strong>SUBTOTAL:</strong></td>
                <td class="text-right">${{ number_format($order->total, 2) }}</td>
            </tr>
            <tr>
                <td><strong>IVA (0%):</strong></td>
                <td class="text-right">$0.00</td>
            </tr>
            <tr class="total">
                <td><strong>TOTAL:</strong></td>
                <td class="text-right">${{ number_format($order->total, 2) }}</td>
            </tr>
        </table>

        <!-- Información Adicional -->
        <div class="mb-4">
            <p><strong>Observaciones:</strong> Pedido generado el {{ $order->created_at->format('d/m/Y \a \l\a\s H:i') }}</p>
            @if($order->notes)
                <p><strong>Notas adicionales:</strong> {{ $order->notes }}</p>
            @endif
        </div>

        <!-- Pie de página -->
        <div class="footer">
            <p>
                <strong>{{ config('app.name', 'Mi Tienda') }}</strong><br>
                Este documento es una factura generada automáticamente.<br>
                Fecha de generación: {{ now()->format('d/m/Y H:i') }}
            </p>
        </div>
    </div>
</body>
</html>