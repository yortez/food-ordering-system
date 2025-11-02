<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - {{ $order->order_number }}</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            max-width: 300px;
            margin: 0 auto;
            padding: 20px;
        }

        .receipt-header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px dashed #333;
            padding-bottom: 10px;
        }

        .receipt-header h1 {
            margin: 0;
            font-size: 18px;
        }

        .receipt-info {
            margin-bottom: 20px;
        }

        .receipt-info div {
            margin-bottom: 5px;
        }

        .items-table {
            width: 100%;
            margin-bottom: 20px;
        }

        .items-table th {
            text-align: left;
            border-bottom: 1px solid #333;
            padding-bottom: 5px;
        }

        .items-table td {
            padding: 3px 0;
        }

        .items-table .quantity {
            text-align: center;
        }

        .items-table .price {
            text-align: right;
        }

        .totals {
            border-top: 2px dashed #333;
            padding-top: 10px;
        }

        .totals div {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .totals .total-final {
            font-weight: bold;
            font-size: 16px;
            border-top: 1px solid #333;
            padding-top: 5px;
        }

        .thank-you {
            text-align: center;
            margin-top: 20px;
            font-weight: bold;
        }

        .barcode {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
        }

        @media print {
            body {
                margin: 0;
                padding: 10px;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="receipt-header">
        <h1>{{ config('app.name', 'Food Ordering System') }}</h1>
        <p>Point of Sale Receipt</p>
        <p>{{ now()->format('Y-m-d H:i:s') }}</p>
    </div>

    <div class="receipt-info">
        <div><strong>Order #:</strong> {{ $order->order_number }}</div>
        <div><strong>Cashier:</strong> {{ $order->user->name ?? 'N/A' }}</div>
        @if($order->customer_name)
        <div><strong>Customer:</strong> {{ $order->customer_name }}</div>
        @endif
        @if($order->customer_phone)
        <div><strong>Phone:</strong> {{ $order->customer_phone }}</div>
        @endif
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th>Item</th>
                <th class="quantity">Qty</th>
                <th class="price">Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderItems as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td class="quantity">{{ $item->quantity }}</td>
                <td class="price">₱{{ number_format($item->total_price, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <div>
            <span>Subtotal:</span>
            <span>₱{{ number_format($order->subtotal, 2) }}</span>
        </div>
        <div>
            <span>Tax (10%):</span>
            <span>₱{{ number_format($order->tax_amount, 2) }}</span>
        </div>
        @if($order->discount_amount > 0)
        <div>
            <span>Discount:</span>
            <span>-₱{{ number_format($order->discount_amount, 2) }}</span>
        </div>
        @endif
        <div class="total-final">
            <span>Total:</span>
            <span>₱{{ number_format($order->total_amount, 2) }}</span>
        </div>
        <div>
            <span>Payment:</span>
            <span>{{ ucfirst($order->payment_method) }}</span>
        </div>
    </div>

    <div class="barcode">
        Order: {{ $order->order_number }}
    </div>

    <div class="thank-you">
        Thank you for your business!
    </div>

    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">
            Print Receipt
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 4px; cursor: pointer; margin-left: 10px;">
            Close
        </button>
    </div>
</body>
</html>
