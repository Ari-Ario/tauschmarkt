<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rechnung</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { width: 100%; max-width: 800px; margin: 0 auto; padding: 20px; }
        .header, .footer { text-align: center; margin-bottom: 20px; }
        .footer { margin-top: 20px; }
        .content { margin-bottom: 20px; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table th, .table td { border: 1px solid #000; padding: 8px; text-align: left; }
        .table th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Rechnung</h1>
        </div>
        <div class="content">
            <p><strong>Bestellungsnummer:</strong> {{ $order->id }}</p>
            <p><strong>Datum:</strong> {{ $order->created_at->format('Y-m-d') }}</p>
            <p><strong>Gesamt Preis:</strong> CHF {{ number_format($order->total_price, 2) }}</p>
            <table class="table">
                <thead>
                    <tr>
                        <th>Produkt</th>
                        <th>Quantität</th>
                        <th>Preis pro Einheit</th>
                        <th>Gesamt</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                    <tr>
                        <td>{{ $item['product_id'] }}</td>
                        <td>{{ $item['quantity'] }}</td>
                        <td>CHF {{ number_format($item['price'], 2) }}</td>
                        <td>CHF {{ number_format($item['quantity'] * $item['price'], 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="footer">
            <p>Danke für Ihre Bestellung!</p>
        </div>
    </div>
</body>
</html>
