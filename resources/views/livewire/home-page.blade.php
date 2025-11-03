

    <!-- Main Content -->
    <main class="p-4 sm:ml-64">
           <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700 mt-14">

        <!-- Dashboard Cards -->
        <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-4 mb-8">
            <div class="bg-white rounded-lg p-8 shadow text-center hover:shadow-md transition">
                <div class="text-blue-500 text-5xl mb-4">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="text-3xl font-bold text-blue-600 mb-2">
                    {{ \App\Models\Order::whereDate('created_at', today())->count() }}
                </div>
                <div class="text-gray-600 text-lg">Today's Orders</div>
            </div>

            <div class="bg-white rounded-lg p-8 shadow text-center hover:shadow-md transition">
                <div class="text-green-500 text-5xl mb-4">
                    <i class="fas fa-peso-sign"></i>
                </div>
                <div class="text-3xl font-bold text-green-600 mb-2">
                    ₱{{ number_format(\App\Models\Order::whereDate('created_at', today())->sum('total_amount'), 2) }}
                </div>
                <div class="text-gray-600 text-lg">Today's Revenue</div>
            </div>

            <div class="bg-white rounded-lg p-8 shadow text-center hover:shadow-md transition">
                <div class="text-purple-500 text-5xl mb-4">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="text-3xl font-bold text-purple-600 mb-2">
                    ₱{{ number_format(\App\Models\Order::whereDate('created_at', today())->sum('profit'), 2) }}
                </div>
                <div class="text-gray-600 text-lg">Today's Profit</div>
            </div>

            <div class="bg-white rounded-lg p-8 shadow text-center hover:shadow-md transition">
                <div class="text-orange-500 text-5xl mb-4">
                    <i class="fas fa-users"></i>
                </div>
                <div class="text-3xl font-bold text-orange-600 mb-2">
                    {{ \App\Models\Order::whereDate('created_at', today())->distinct('customer_phone')->count() }}
                </div>
                <div class="text-gray-600 text-lg">Today's Customers</div>
            </div>
        </div>

        <!-- Dashboard Actions -->
        <div class="flex flex-wrap justify-center gap-4 mb-8">
            <a href="/pos"
               class="inline-flex items-center gap-2 bg-blue-600 text-white text-lg px-6 py-3 rounded-lg shadow hover:-translate-y-0.5 hover:shadow-lg transition">
                <i class="fas fa-cash-register"></i> Open POS
            </a>

            <a href="{{ route('filament.admin.resources.orders.index') }}"
               class="inline-flex items-center gap-2 bg-green-600 text-white text-lg px-6 py-3 rounded-lg shadow hover:-translate-y-0.5 hover:shadow-lg transition">
                <i class="fas fa-list"></i> View Orders
            </a>

            <a href="{{ route('filament.admin.resources.products.index') }}"
               class="inline-flex items-center gap-2 bg-cyan-600 text-white text-lg px-6 py-3 rounded-lg shadow hover:-translate-y-0.5 hover:shadow-lg transition">
                <i class="fas fa-box"></i> Manage Products
            </a>
        </div>

        <!-- Recent Orders -->
        <div class="bg-white rounded-lg p-8 shadow">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Recent Orders</h2>
            <div class="flex flex-col gap-4">
                @foreach(\App\Models\Order::with('orderItems.product')->latest()->take(5)->get() as $order)
                    <div class="flex justify-between items-center p-4 border border-gray-200 rounded hover:bg-gray-50 transition">
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

            <div class="text-center mt-6">
                <a href="{{ route('filament.admin.resources.orders.index') }}" class="text-blue-600 hover:underline font-medium">
                    View All Orders →
                </a>
            </div>
        </div>
    </main>
</div>
</div>



