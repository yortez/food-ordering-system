<div>

    <!-- Main POS Content -->
    <div class="w-full grid grid-cols-[2fr_400px] gap-4">
        <!-- Products Section -->
        <div class="bg-white rounded-lg p-4 overflow-y-auto">
            <h2 class="text-2xl font-bold mb-4">Products</h2>

            <!-- Category Tabs -->
            <div class="flex flex-wrap gap-2 mb-4">
                <div class="px-4 py-2 border border-gray-300 rounded cursor-pointer bg-gray-100 hover:bg-gray-200 transition active:bg-blue-500 active:text-white"
                    data-category="all">
                    <i class="fas fa-th"></i> All
                </div>
                @foreach($categories as $category)
                <div class="px-4 py-2 border border-gray-300 rounded cursor-pointer bg-gray-100 hover:bg-gray-200 transition"
                    data-category="{{ $category->id }}">
                    {{ $category->name }}
                </div>
                @endforeach
            </div>

            <!-- Product Grid -->
            <div class="grid grid-cols-[repeat(auto-fill,minmax(150px,1fr))] gap-4" id="productGrid">
                @foreach($products as $product)
                <div class="product-card border border-gray-300 rounded-lg p-4 cursor-pointer text-center transition transform hover:-translate-y-1 hover:shadow-lg hover:border-blue-500"
                    data-category="{{ $product->category_id }}" data-product-id="{{ $product->id }}">
                    @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                        class="w-full h-24 rounded mb-2 object-cover">
                    @else
                    <div class="w-full h-24 bg-gray-200 flex items-center justify-center rounded mb-2">
                        <i class="fas fa-image text-gray-400 text-2xl"></i>
                    </div>
                    @endif
                    <h3 class="font-semibold text-sm">{{ $product->name }}</h3>
                    <p class="text-gray-600 text-xs mt-1">{{ $product->category->name }}</p>
                    <p class="font-bold text-lg text-blue-600 mt-2">₱{{ number_format($product->price, 2) }}</p>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Cart Section -->
        <div class="bg-white rounded-lg p-4 flex flex-col">
            <h2 class="text-2xl font-bold mb-4">
                <i class="fas fa-shopping-cart"></i> Cart
            </h2>

            <div class="flex-1 overflow-y-auto mb-4" id="cartItems">
                <p class="text-gray-500 text-center">No items in cart</p>
            </div>

            <div class="border-t-2 border-gray-800 pt-4 mb-4 hidden" id="cartTotal">
                <div class="flex justify-between mb-1">
                    <span>Subtotal:</span>
                    <span id="subtotal">₱0.00</span>
                </div>
                <div class="flex justify-between mb-1">
                    <span>Tax (12%):</span>
                    <span id="tax">₱0.00</span>
                </div>
                <div class="flex justify-between mb-1">
                    <span>Discount:</span>
                    <span id="discount">₱0.00</span>
                </div>
                <div class="flex justify-between font-bold text-xl text-blue-600 mt-2">
                    <span>Total:</span>
                    <span id="total">₱0.00</span>
                </div>
            </div>

            <button id="checkoutBtn"
                class="hidden w-full py-3 bg-green-600 hover:bg-green-700 text-white rounded text-lg font-medium transition">
                <i class="fas fa-credit-card"></i> Checkout
            </button>

            <!-- Order History -->
            <div class="mt-4">
                <h3 class="text-lg font-bold mb-2">Recent Orders</h3>
                <div id="orderHistory"></div>
            </div>
        </div>
    </div>

    <!-- Checkout Modal -->
    <div id="checkoutModal"
        class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md shadow-lg cursor-move relative transition-transform">
            <h3 class="text-xl font-bold mb-4">Checkout</h3>

            <form id="checkoutForm">
                <div class="mb-3">
                    <label class="block mb-1 font-bold">Select Guest:</label>
                    <select id="existingCustomer" name="existing_customer_id"
                        class="w-full border border-gray-300 rounded p-2">
                        <option value="">-- Select Guest --</option>
                        @foreach($guests as $guest)
                        <option value="{{ $guest->id }}" data-name="{{ $guest->name }}" data-phone="{{ $guest->phone }}" data-email="{{ $guest->email }}">
                            {{ $guest->name }}
                        </option>
                        @endforeach
                        <option value="new">➕ Add New Guest</option>
                    </select>
                </div>

                <div id="guestFields" class="hidden">
                    <div class="mb-3">
                        <label class="block mb-1 font-bold">Customer Name:</label>
                        <input id="customer_name" type="text" name="customer_name" placeholder="Optional"
                            class="w-full border border-gray-300 rounded p-2" required>
                    </div>
                    <div class="mb-3">
                        <label class="block mb-1 font-bold">Customer Phone:</label>
                        <input id="customer_phone" type="tel" name="customer_phone" placeholder="Optional"
                            class="w-full border border-gray-300 rounded p-2">
                    </div>
                    <div class="mb-3">
                        <label class="block mb-1 font-bold">Customer Email:</label>
                        <input id="customer_email" type="email" name="customer_email" placeholder="Optional"
                            class="w-full border border-gray-300 rounded p-2">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="block mb-1 font-bold">Payment Method:</label>
                    <select name="payment_method" required class="w-full border border-gray-300 rounded p-2">
                        <option value="cash">Cash</option>
                        <option value="card">Card</option>
                        <option value="digital_wallet">Digital Wallet</option>
                        <option value="bank_transfer">Bank Transfer</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="block mb-1 font-bold">Discount Amount:</label>
                    <input type="number" name="discount_amount" step="0.01" min="0" value="0"
                        class="w-full border border-gray-300 rounded p-2">
                </div>

                <div class="mb-3">
                    <label class="block mb-1 font-bold">Notes:</label>
                    <input type="text" name="notes" placeholder="Optional"
                        class="w-full border border-gray-300 rounded p-2">
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" id="cancelCheckout"
                        class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700 transition">Cancel</button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Complete
                        Order</button>
                </div>
            </form>
        </div>
    </div>
</div>

 <script>
    let cart = [];
    const categories = @json($categories);
    const products = @json($products);

    document.addEventListener('DOMContentLoaded', function () {
        loadOrderHistory();
        setupEventListeners();
        setupModalDrag();
    });

    function setupEventListeners() {
        // Category tabs
        document.querySelectorAll('.category-tab').forEach(tab => {
            tab.addEventListener('click', function () {
                document.querySelectorAll('.category-tab').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                filterProducts(this.dataset.category);
            });
        });

        // Product cards
        document.querySelectorAll('.product-card').forEach(card => {
            card.addEventListener('click', function () {
                const product = products.find(p => p.id == this.dataset.productId);
                addToCart(product);
            });
        });

        // Select existing guest
        const existingCustomer = document.getElementById('existingCustomer');
        const guestFields = document.getElementById('guestFields');
        const nameInput = document.getElementById('customer_name');
        const phoneInput = document.getElementById('customer_phone');
        const emailInput = document.getElementById('customer_email');

        existingCustomer.addEventListener('change', function () {
            const selected = this.options[this.selectedIndex];
            const value = this.value;

            if (value === 'new' || value === '') {
                guestFields.style.display = 'block';
                nameInput.value = '';
                phoneInput.value = '';
                emailInput.value = '';
                nameInput.removeAttribute('readonly');
                phoneInput.removeAttribute('readonly');
                emailInput.removeAttribute('readonly');
            } else {
                guestFields.style.display = 'block';
                nameInput.value = selected.dataset.name;
                phoneInput.value = selected.dataset.phone;
                emailInput.value = selected.dataset.email;
                nameInput.setAttribute('readonly', true);
                phoneInput.setAttribute('readonly', true);
                emailInput.setAttribute('readonly', true);
            }
        });

        // Checkout
        document.getElementById('checkoutBtn').addEventListener('click', showCheckoutModal);
        document.getElementById('cancelCheckout').addEventListener('click', hideCheckoutModal);
        document.getElementById('checkoutForm').addEventListener('submit', processCheckout);
        document.getElementById('checkoutModal').addEventListener('click', e => {
            if (e.target === e.currentTarget) hideCheckoutModal();
        });
    }

    function setupModalDrag() {
        const modal = document.getElementById('checkoutModal');
        const modalContent = modal.querySelector('.modal-content');
    let isDragging = false;
        let offsetX, offsetY;
        modalContent.addEventListener('mousedown', e => {
            isDragging = true;
            offsetX = e.clientX - modalContent.getBoundingClientRect().left;
            offsetY = e.clientY - modalContent.getBoundingClientRect().top;
            document.body.style.userSelect = 'none';
        });

        document.addEventListener('mousemove', e => {
            if (!isDragging) return;
            modalContent.style.position = 'absolute';
            modalContent.style.left = `${e.clientX - offsetX}px`;
            modalContent.style.top = `${e.clientY - offsetY}px`;
            modalContent.style.margin = 0;
        });

        document.addEventListener('mouseup', () => {
            isDragging = false;
            document.body.style.userSelect = 'auto';
        });
    }

    function filterProducts(categoryId) {
        document.querySelectorAll('.product-card').forEach(card => {
            card.style.display = categoryId === 'all' || card.dataset.category === categoryId ? 'block' : 'none';
        });
    }

    function addToCart(product) {
        const item = cart.find(i => i.id === product.id);
        if (item) item.quantity++;
        else cart.push({ ...product, quantity: 1 });
        updateCartDisplay();
    }

    function updateCartDisplay() {
        const cartItems = document.getElementById('cartItems');
        const cartTotal = document.getElementById('cartTotal');
        const checkoutBtn = document.getElementById('checkoutBtn');

        if (cart.length === 0) {
            cartItems.innerHTML = '<p class="text-gray-500 text-center">No items in cart</p>';
            cartTotal.style.display = 'none';
            checkoutBtn.style.display = 'none';
            return;
        }

        let subtotal = 0;
        cartItems.innerHTML = cart.map(item => {
            const itemTotal = item.price * item.quantity;
            subtotal += itemTotal;
            return `
                <div class="cart-item">
                    <div>
                        <div class="font-semibold">${item.name}</div>
                        <div class="text-sm text-gray-600">₱${item.price.toFixed(2)} each</div>
                    </div>
                    <div class="quantity-controls" data-id="${item.id}">
                        <button class="quantity-btn decrease">-</button>
                        <span>${item.quantity}</span>
                        <button class="quantity-btn increase">+</button>
                        <button class="btn-remove delete"><i class="fas fa-trash"></i></button>
                    </div>
                </div>
            `;
        }).join('');

        const tax = subtotal * 0.12;
        const total = subtotal + tax;

        document.getElementById('subtotal').textContent = `₱${subtotal.toFixed(2)}`;
        document.getElementById('tax').textContent = `₱${tax.toFixed(2)}`;
        document.getElementById('discount').textContent = '₱0.00';
        document.getElementById('total').textContent = `₱${total.toFixed(2)}`;

        cartTotal.style.display = 'block';
        checkoutBtn.style.display = 'block';

        // Rebind button events
        document.querySelectorAll('.quantity-controls').forEach(control => {
            const id = parseInt(control.dataset.id);
            control.querySelector('.decrease').onclick = () => updateQuantity(id, -1);
            control.querySelector('.increase').onclick = () => updateQuantity(id, 1);
            control.querySelector('.delete').onclick = () => removeFromCart(id);
        });
    }

    function updateQuantity(id, change) {
        const item = cart.find(i => i.id === id);
        if (!item) return;
        item.quantity += change;
        if (item.quantity <= 0) removeFromCart(id);
        else updateCartDisplay();
    }

    function removeFromCart(id) {
        cart = cart.filter(i => i.id !== id);
        updateCartDisplay();
    }

    function showCheckoutModal() {
        const modal = document.getElementById('checkoutModal');
        modal.style.display = 'block';
        modal.querySelector('.modal-content').style.left = '';
        modal.querySelector('.modal-content').style.top = '';
    }

    function hideCheckoutModal() {
        document.getElementById('checkoutModal').style.display = 'none';
    }

    async function processCheckout(e) {
        e.preventDefault();

        const formData = new FormData(e.target);
        const data = {
            items: cart.map(i => ({ product_id: i.id, quantity: i.quantity })),
            customer_name: formData.get('customer_name'),
            customer_phone: formData.get('customer_phone'),
            customer_email: formData.get('customer_email'),
            payment_method: formData.get('payment_method'),
            discount_amount: parseFloat(formData.get('discount_amount')) || 0,
            notes: formData.get('notes')
        };

        try {
            const res = await fetch('/create-order', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            });

            const result = await res.json();
            if (!res.ok || !result.success) throw new Error(result.message || 'Failed to create order');

            alert('✅ Order created successfully!');
            cart = [];
            updateCartDisplay();
            hideCheckoutModal();
            await loadOrderHistory();
            window.open(`/pos/receipt/${result.order.id}`, '_blank');
        } catch (err) {
            alert('Error: ' + err.message);
        }
    }

    async function loadOrderHistory() {
        try {
            const res = await fetch('/order-history');
            const result = await res.json();
            document.getElementById('orderHistory').innerHTML = result.orders.map(order => `
                <div class="order-item" onclick="viewOrder(${order.id})">
                    <div class="flex justify-between">
                        <span class="font-semibold">${order.order_number}</span>
                        <span>₱${parseFloat(order.total_amount).toFixed(2)}</span>
                    </div>
                    <div class="text-sm text-gray-600">${new Date(order.created_at).toLocaleString()}</div>
                    <div class="text-sm">${order.order_items.length} items</div>
                </div>
            `).join('');
        } catch (err) {
            console.error('Error loading order history:', err);
        }
    }

    function viewOrder(id) {
        window.open(`/pos/receipt/${id}`, '_blank');
    }
</script>

