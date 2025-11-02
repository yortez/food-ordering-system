<style>
      .pos-container {
            display: grid;
            grid-template-columns: 2fr 400px;
            height: 100vh;
            gap: 1rem;
            padding: 1rem;
        }

        .products-section {
            background: white;
            border-radius: 8px;
            padding: 1rem;
            overflow-y: auto;
        }

        .cart-section {
            background: white;
            border-radius: 8px;
            padding: 1rem;
            display: flex;
            flex-direction: column;
        }

        .category-tabs {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1rem;
            flex-wrap: wrap;
        }

        .category-tab {
            padding: 0.5rem 1rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            cursor: pointer;
            background: #f8f9fa;
            transition: all 0.2s;
        }

        .category-tab.active {
            background: #007bff;
            color: white;
            border-color: #007bff;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 1rem;
        }

        .product-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 1rem;
            cursor: pointer;
            transition: all 0.2s;
            text-align: center;
        }

        .product-card:hover {
            border-color: #007bff;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .product-image {
            width: 100%;
            height: 100px;
            /* object-fit: cover; */
            border-radius: 4px;
            margin-bottom: 0.5rem;
        }

        .cart-items {
            flex: 1;
            overflow-y: auto;
            margin-bottom: 1rem;
        }

        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem;
            border-bottom: 1px solid #eee;
        }

        .cart-total {
            border-top: 2px solid #333;
            padding-top: 1rem;
            margin-bottom: 1rem;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }

        .total-row.final {
            font-weight: bold;
            font-size: 1.2rem;
            color: #007bff;
        }

        .btn-checkout {
            width: 100%;
            padding: 1rem;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 1.1rem;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-checkout:hover {
            background: #218838;
        }

        .btn-remove {
            background: #dc3545;
            color: white;
            border: none;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            cursor: pointer;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .quantity-btn {
            background: #6c757d;
            color: white;
            border: none;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            cursor: pointer;
        }

        .modal {
            scale: 0.7;
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100px;
            z-index: 1000;
        }

        .modal-content {
            background: white;
            margin: 5% auto;
            padding: 2rem;
            border-radius: 8px;
            width: 90%;
            max-width: 400px;
            cursor: move;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .modal-buttons {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 2rem;
        }

        .btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-primary {
            background: #007bff;
            color: white;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .order-history {
            margin-top: 1rem;
        }

        .order-item {
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 0.5rem;
            cursor: pointer;
        }

        .order-item:hover {
            background: #f8f9fa;
        }

</style>

<div>
<div class="w-full pos-container">
        <!-- Products Section -->
        <div class="products-section">
            <h2 class="text-2xl font-bold mb-4">Products</h2>

            <!-- Category Tabs -->
            <div class="category-tabs">
                <div class="category-tab active" data-category="all">
                    <i class="fas fa-th"></i> All
                </div>
                @foreach($categories as $category)
                <div class="category-tab" data-category="{{ $category->id }}">
                    {{ $category->name }}
                </div>
                @endforeach
            </div>

            <!-- Product Grid -->
            <div class="product-grid" id="productGrid">
                @foreach($products as $product)
                <div class="product-card" data-category="{{ $product->category_id }}"
                    data-product-id="{{ $product->id }}">
                    @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                        class="product-image">
                    @else
                    <div class="product-image bg-gray-200 flex items-center justify-center">
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
        <div class="cart-section">
            <h2 class="text-2xl font-bold mb-4">
                <i class="fas fa-shopping-cart"></i> Cart
            </h2>

            <div class="cart-items" id="cartItems">
                <p class="text-gray-500 text-center">No items in cart</p>
            </div>

            <div class="cart-total" id="cartTotal" style="display: none;">
                <div class="total-row">
                    <span>Subtotal:</span>
                    <span id="subtotal">₱0.00</span>
                </div>
                <div class="total-row">
                    <span>Tax (12%):</span>
                    <span id="tax">₱0.00</span>
                </div>
                <div class="total-row">
                    <span>Discount:</span>
                    <span id="discount">₱0.00</span>
                </div>
                <div class="total-row final">
                    <span>Total:</span>
                    <span id="total">₱0.00</span>
                </div>
            </div>

            <button class="btn-checkout" id="checkoutBtn" style="display: none;">
                <i class="fas fa-credit-card"></i> Checkout
            </button>

            <!-- Order History -->
            <div class="order-history">
                <h3 class="text-lg font-bold mb-2">Recent Orders</h3>
                <div id="orderHistory"></div>
            </div>
        </div>
    </div>

    <!-- Checkout Modal -->
    <div id="checkoutModal" class="modal" >
        <div class="modal-content">
            <h3 class="text-xl font-bold mb-4">Checkout</h3>

            <form id="checkoutForm">
                <div class="form-group">
                    <label>Customer Name:</label>
                    <div class="form-group mb-3">
                        <label>Select Guest:</label>
                        <select id="existingCustomer" name="existing_customer_id" class="form-control">
                            <option value="">-- Select Guest --</option>
                            @foreach($guests as $guest)
                            <option value="{{ $guest->id }}" data-name="{{ $guest->name }}" data-phone="{{ $guest->phone }}"
                                data-email="{{ $guest->email }}">
                                {{ $guest->name }}
                            </option>
                            @endforeach
                            <option value="new">➕ Add New Guest</option>
                        </select>

                    </div>
                </div>

                <div id="guestFields" style="display: none;">
                    <div class="form-group">
                        <label>Customer Name:</label>
                        <input id="customer_name" type="text" name="customer_name" placeholder="Optional" required>

                    </div>

                    <div class="form-group">
                        <label>Customer Phone:</label>
                        <input id="customer_phone" type="tel" name="customer_phone" placeholder="Optional">
                    </div>
                    <div class="form-group">
                        <label>Customer Email:</label>
                        <input id="customer_email" type="email" name="customer_email" placeholder="Optional">
                    </div>
                </div>


                <div class="form-group">
                    <label>Payment Method:</label>
                    <select name="payment_method" required>
                        <option value="cash">Cash</option>
                        <option value="card">Card</option>
                        <option value="digital_wallet">Digital Wallet</option>
                        <option value="bank_transfer">Bank Transfer</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Discount Amount:</label>
                    <input type="number" name="discount_amount" step="0.01" min="0" value="0">
                </div>

                <div class="form-group">
                    <label>Notes:</label>
                    <input type="text" name="notes" placeholder="Optional">
                </div>

                <div class="modal-buttons">
                    <button type="button" class="btn btn-secondary" id="cancelCheckout">Cancel</button>
                    <button type="submit" class="btn btn-primary">Complete Order</button>
                </div>
            </form>
        </div>
    </div>
</div>
 <script>
        let cart = [];
        let categories = @json($categories);
        let products = @json($products);

       // Initialize
        document.addEventListener('DOMContentLoaded', function() {
        loadOrderHistory();
        setupEventListeners();
        const modal = document.getElementById('checkoutModal');
        const modalContent = modal.querySelector('.modal-content');

        let isDragging = false;
        let offsetX, offsetY;

        // Start dragging when mouse is pressed
        modalContent.addEventListener('mousedown', function(e) {
        isDragging = true;
        offsetX = e.clientX - modalContent.getBoundingClientRect().left;
        offsetY = e.clientY - modalContent.getBoundingClientRect().top;
        modalContent.style.transition = 'none'; // disable smooth transitions while dragging
        document.body.style.userSelect = 'none'; // prevent text highlighting
        });

          // Move modal with mouse
    document.addEventListener('mousemove', function(e) {
        if (isDragging) {
            e.preventDefault();

            // Calculate new position
            const left = e.clientX - offsetX;
            const top = e.clientY - offsetY;

            // Apply absolute position
            modalContent.style.position = 'absolute';
            modalContent.style.left = `${left}px`;
            modalContent.style.top = `${top}px`;
            modalContent.style.margin = 0; // reset margin to allow free movement
        }
    });

         // Stop dragging when mouse is released
    document.addEventListener('mouseup', function() {
        isDragging = false;
        document.body.style.userSelect = 'auto';
    });
        });

        function setupEventListeners() {
            // Category tabs
            document.querySelectorAll('.category-tab').forEach(tab => {
                tab.addEventListener('click', function() {
                    document.querySelectorAll('.category-tab').forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    filterProducts(this.dataset.category);
                });
            });

            // Product cards
            document.querySelectorAll('.product-card').forEach(card => {
                card.addEventListener('click', function() {
                    const productId = this.dataset.productId;
                    const product = products.find(p => p.id == productId);
                    addToCart(product);
                });
            });

            // Select existing guest
            document.getElementById('existingCustomer').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const selectedValue = this.value;

    const guestFields = document.getElementById('guestFields');
    const existingGuest = document.getElementById('existingGuest');

    const nameInput = document.getElementById('customer_name');
    const phoneInput = document.getElementById('customer_phone');
    const emailInput = document.getElementById('customer_email');

    if (selectedValue === 'new' || selectedValue === '') {
        // Show empty form for new guest
        guestFields.style.display = 'block';
        nameInput.value = '';
        phoneInput.value = '';
        emailInput.value = '';

        nameInput.removeAttribute('readonly');
        phoneInput.removeAttribute('readonly');
        emailInput.removeAttribute('readonly');
    } else {
        // Fill with existing guest data
        guestFields.style.display = 'block';
        nameInput.value = selectedOption.getAttribute('data-name');
        phoneInput.value = selectedOption.getAttribute('data-phone');
        emailInput.value = selectedOption.getAttribute('data-email');

        nameInput.setAttribute('readonly', true);
        phoneInput.setAttribute('readonly', true);
        emailInput.setAttribute('readonly', true);
    }
});

            // Checkout button
            document.getElementById('checkoutBtn').addEventListener('click', showCheckoutModal);

            // Modal controls
            document.getElementById('cancelCheckout').addEventListener('click', hideCheckoutModal);
            document.getElementById('checkoutForm').addEventListener('submit', processCheckout);

            // Close modal on outside click
            document.getElementById('checkoutModal').addEventListener('click', function(e) {
                if (e.target === this) hideCheckoutModal();
            });
        }

        function filterProducts(categoryId) {
            const cards = document.querySelectorAll('.product-card');
            cards.forEach(card => {
                if (categoryId === 'all' || card.dataset.category === categoryId) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        function addToCart(product) {
            const existingItem = cart.find(item => item.id === product.id);

            if (existingItem) {
                existingItem.quantity += 1;
            } else {
                cart.push({
                    id: product.id,
                    name: product.name,
                    price: parseFloat(product.price),
                    quantity: 1
                });
            }

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

            let html = '';
            let subtotal = 0;

            cart.forEach(item => {
                const itemTotal = item.price * item.quantity;
                subtotal += itemTotal;

                html += `
                    <div class="cart-item">
                        <div>
                            <div class="font-semibold">${item.name}</div>
                            <div class="text-sm text-gray-600">₱${item.price.toFixed(2)} each</div>
                        </div>
                        <div class="quantity-controls">
                            <button class="quantity-btn" onclick="updateQuantity(${item.id}, -1)">-</button>
                            <span>${item.quantity}</span>
                            <button class="quantity-btn" onclick="updateQuantity(${item.id}, 1)">+</button>
                            <button class="btn-remove" onclick="removeFromCart(${item.id})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                `;
            });

            const tax = subtotal * 0.12;
            const total = subtotal + tax;

            cartItems.innerHTML = html;
            cartTotal.style.display = 'block';
            checkoutBtn.style.display = 'block';

            document.getElementById('subtotal').textContent = `₱${subtotal.toFixed(2)}`;
            document.getElementById('tax').textContent = `₱${tax.toFixed(2)}`;
            document.getElementById('discount').textContent = '₱0.00';
            document.getElementById('total').textContent = `₱${total.toFixed(2)}`;
        }

        function updateQuantity(productId, change) {
            const item = cart.find(item => item.id === productId);
            if (item) {
                item.quantity += change;
                if (item.quantity <= 0) {
                    removeFromCart(productId);
                } else {
                    updateCartDisplay();
                }
            }
        }

        function removeFromCart(productId) {
            cart = cart.filter(item => item.id !== productId);
            updateCartDisplay();
        }

        function showCheckoutModal() {
            document.getElementById('checkoutModal').style.display = 'block';
        }

        function hideCheckoutModal() {
            document.getElementById('checkoutModal').style.display = 'none';
        }

        async function processCheckout(e) {
            e.preventDefault();

            const formData = new FormData(e.target);
            const items = cart.map(item => ({
                product_id: item.id,
                quantity: item.quantity
            }));

            const data = {
                items: items,
                customer_name: formData.get('customer_name'),
                customer_phone: formData.get('customer_phone'),
                customer_email: formData.get('customer_email'),
                payment_method: formData.get('payment_method'),
                discount_amount: parseFloat(formData.get('discount_amount')) || 0,
                notes: formData.get('notes')
            };

            try {
                const response = await fetch('/create-order', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (result.success) {
                    alert('Order created successfully!');
                    cart = [];
                    updateCartDisplay();
                    hideCheckoutModal();
                    loadOrderHistory();

                    // Open receipt in new tab
                    window.open(`/pos/receipt/${result.order.id}`, '_blank');
                } else {
                    alert('Error: ' + result.message);
                }
            } catch (error) {
                alert('Error creating order: ' + error.message);
            }
        }

        async function loadOrderHistory() {
            try {
                const response = await fetch('/order-history');
                const result = await response.json();

                const historyHtml = result.orders.map(order => `
                    <div class="order-item" onclick="viewOrder(${order.id})">
                        <div class="flex justify-between">
                            <span class="font-semibold">${order.order_number}</span>
                            <span>₱${parseFloat(order.total_amount).toFixed(2)}</span>
                        </div>
                        <div class="text-sm text-gray-600">
                            ${new Date(order.created_at).toLocaleString()}
                        </div>
                        <div class="text-sm">
                            ${order.order_items.length} items
                        </div>
                    </div>
                `).join('');

                document.getElementById('orderHistory').innerHTML = historyHtml;
            } catch (error) {
                console.error('Error loading order history:', error);
            }
        }

        function viewOrder(orderId) {
            window.open(`/pos/receipt/${orderId}`, '_blank');
        }
    </script>
