 <style>
      .dashboard-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            padding: 2rem;
        }

        .dashboard-card {
            background: white;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
        }

        .dashboard-card .icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .dashboard-card .value {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .dashboard-card .label {
            color: #6c757d;
            font-size: 1.1rem;
        }

        .dashboard-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 2rem;
            flex-wrap: wrap;
        }

        .btn-dashboard {
            padding: 1rem 2rem;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s;
        }

        .btn-primary {
            background: #007bff;
            color: white;
        }

        .btn-success {
            background: #28a745;
            color: white;
        }

        .btn-info {
            background: #17a2b8;
            color: white;
        }

        .btn-dashboard:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        .recent-orders {
            background: white;
            border-radius: 8px;
            padding: 2rem;
            margin: 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .recent-orders h2 {
            margin-bottom: 1rem;
            color: #333;
        }

        .order-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .order-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            border: 1px solid #eee;
            border-radius: 4px;
        }

        .order-item:hover {
            background: #f8f9fa;
        }

 </style>
 <div>
  <div class="w-full dashboard-container">
        <div class="dashboard-card">
            <div class="icon text-blue-500">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <div class="value text-blue-600">
                {{ \App\Models\Order::whereDate('created_at', today())->count() }}
            </div>
            <div class="label">Today's Orders</div>
        </div>

        <div class="dashboard-card">
            <div class="icon text-green-500">
                <i class="fas fa-peso-sign"></i>
            </div>
            <div class="value text-green-600">
                ₱{{ number_format(\App\Models\Order::whereDate('created_at', today())->sum('total_amount'), 2) }}
            </div>
            <div class="label">Today's Revenue</div>
        </div>

        <div class="dashboard-card">
            <div class="icon text-purple-500">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="value text-purple-600">
                ₱{{ number_format(\App\Models\Order::whereDate('created_at', today())->sum('profit'), 2) }}
            </div>
            <div class="label">Today's Profit</div>
        </div>

        <div class="dashboard-card">
            <div class="icon text-orange-500">
                <i class="fas fa-users"></i>
            </div>
            <div class="value text-orange-600">
                {{ \App\Models\Order::whereDate('created_at', today())->distinct('customer_phone')->count() }}
            </div>
            <div class="label">Today's Customers</div>
        </div>
    </div>

    <div class="dashboard-actions">
        <a href="/pos" class="btn-dashboard btn-primary">
            <i class="fas fa-cash-register"></i>
            Open POS
        </a>

        <a href="{{ route('filament.admin.resources.orders.index') }}" class="btn-dashboard btn-success">
            <i class="fas fa-list"></i>
            View Orders
        </a>

        <a href="{{ route('filament.admin.resources.products.index') }}" class="btn-dashboard btn-info">
            <i class="fas fa-box"></i>
            Manage Products
        </a>
    </div>

    <div class="recent-orders">
        <h2 class="text-xl font-bold">Recent Orders</h2>
        <div class="order-list">
            @foreach(\App\Models\Order::with('orderItems.product')->latest()->take(5)->get() as $order)
            <div class="order-item">
                <div>
                    <div class="font-semibold">{{ $order->order_number }}</div>
                    <div class="text-sm text-gray-600">{{ $order->created_at->format('Y-m-d H:i') }}</div>
                    <div class="text-sm">{{ $order->orderItems->count() }} items</div>
                </div>
                <div class="text-right">
                    <div class="font-bold text-lg">₱{{ number_format($order->total_amount, 2) }}</div>
                    <div class="text-sm text-gray-600">{{ ucfirst($order->payment_method) }}</div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('filament.admin.resources.orders.index') }}" class="text-blue-600 hover:underline">
                View All Orders →
            </a>
        </div>
    </div>
</div>
