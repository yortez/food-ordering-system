<div class="h-screen grid grid-cols-[220px_1fr] gap-4 bg-gray-100 p-4">
    <!-- Sidebar -->
    <aside class="bg-white rounded-lg shadow p-4 flex flex-col justify-between">
        <div>
            <h2 class="text-xl font-bold mb-6 text-blue-600">POS Menu</h2>
            <nav class="flex flex-col space-y-2">
                <a href="/pos" class="flex items-center gap-2 px-3 py-2 rounded hover:bg-blue-100 text-gray-700">
                    <i class="fas fa-cash-register"></i>
                    <span>POS</span>
                </a>
                <a href="{{ route('filament.admin.resources.orders.index') }}"
                    class="flex items-center gap-2 px-3 py-2 rounded hover:bg-blue-100 text-gray-700">
                    <i class="fas fa-list"></i>
                    <span>Orders</span>
                </a>
                <a href="{{ route('filament.admin.resources.products.index') }}"
                    class="flex items-center gap-2 px-3 py-2 rounded hover:bg-blue-100 text-gray-700">
                    <i class="fas fa-box"></i>
                    <span>Products</span>
                </a>
                <a href="{{ route('filament.admin.resources.guests.index') }}"
                    class="flex items-center gap-2 px-3 py-2 rounded hover:bg-blue-100 text-gray-700">
                    <i class="fas fa-user"></i>
                    <span>Guests</span>
                </a>
            </nav>
        </div>
        <footer class="text-xs text-gray-400 mt-6">
            <p>&copy; {{ date('Y') }} POS System</p>
        </footer>
    </aside>
