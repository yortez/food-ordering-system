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
                const response = await fetch('{{ route("pos.create-order") }}', {
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
                    window.open(`{{ url('pos/receipt') }}/${result.order.id}`, '_blank');
                } else {
                    alert('Error: ' + result.message);
                }
            } catch (error) {
                alert('Error creating order: ' + error.message);
            }
        }

        async function loadOrderHistory() {
            try {
                const response = await fetch('{{ route("pos.order-history") }}');
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
            window.open(`{{ url('pos/receipt') }}/${orderId}`, '_blank');
        }
